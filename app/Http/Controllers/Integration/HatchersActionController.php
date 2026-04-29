<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Helpers\helper;
use App\Models\About;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Coupons;
use App\Models\CustomStatus;
use App\Models\Extra;
use App\Models\Faq;
use App\Models\Features;
use App\Models\Item;
use App\Models\LandingSettings;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Privacypolicy;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\SocialLinks;
use App\Models\StorefrontAlias;
use App\Models\Terms;
use App\Models\Tax;
use App\Models\Testimonials;
use App\Models\User;
use App\Models\Banner;
use App\Models\Variants;
use App\Models\WhoWeAre;
use App\Services\HatchersOsSnapshotService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class HatchersActionController extends Controller
{
    public function __construct(private HatchersOsSnapshotService $snapshotService)
    {
    }

    public function __invoke(Request $request)
    {
        $sharedSecret = trim((string) config('services.os.shared_secret', ''));
        if ($sharedSecret === '') {
            return response()->json(['success' => false, 'error' => 'WEBSITE_PLATFORM_SHARED_SECRET is not configured.'], 500);
        }

        $rawBody = $request->getContent();
        $signature = trim((string) $request->header('X-Hatchers-Signature', ''));
        $expected = hash_hmac('sha256', $rawBody, $sharedSecret);
        if ($signature === '' || !hash_equals($expected, $signature)) {
            return response()->json(['success' => false, 'error' => 'Invalid action signature.'], 403);
        }

        $payload = $request->json()->all();
        $user = $this->findUser($payload);
        if (empty($user)) {
            return response()->json(['success' => false, 'error' => 'Founder vendor account was not found in Bazaar.'], 404);
        }

        $category = trim((string) ($payload['category'] ?? ''));
        if (!in_array($category, ['product', 'blog', 'page', 'website', 'coupon', 'shipping', 'order', 'catalog'], true)) {
            return response()->json(['success' => false, 'error' => 'Unsupported Bazaar action category.'], 422);
        }

        $vendorId = (int) ($user->type == 4 ? $user->vendor_id : $user->id);
        $operation = trim((string) ($payload['operation'] ?? 'create'));

        if ($category === 'website') {
            return match ($operation) {
                'update' => $this->updateWebsite($user, $vendorId, $payload),
                'publish' => $this->publishWebsite($user),
                default => response()->json(['success' => false, 'error' => 'Unsupported Bazaar website action.'], 422),
            };
        }

        if ($operation === 'update') {
            return match ($category) {
                'product' => $this->updateProduct($user, $vendorId, $payload),
                'blog' => $this->updateBlog($user, $vendorId, $payload),
                'page' => $this->updatePage($user, $vendorId, $payload),
                'coupon' => $this->updateCoupon($user, $vendorId, $payload),
                'shipping' => $this->updateShipping($user, $vendorId, $payload),
                'order' => $this->updateOrder($user, $vendorId, $payload),
                'catalog' => $this->updateCatalogEntry($user, $vendorId, $payload),
                default => response()->json(['success' => false, 'error' => 'Unsupported Bazaar update action.'], 422),
            };
        }

        if ($category === 'blog') {
            return $this->createBlog($user, $vendorId, $payload);
        }

        if ($category === 'page') {
            return response()->json(['success' => false, 'error' => 'Pages in Bazaar are updated, not created.'], 422);
        }

        if ($category === 'coupon') {
            return $this->createCoupon($user, $vendorId, $payload);
        }

        if ($category === 'shipping') {
            return $this->createShipping($user, $vendorId, $payload);
        }

        if ($category === 'order') {
            return $this->createOrder($user, $vendorId, $payload);
        }

        if ($category === 'catalog') {
            return $this->createCatalogEntry($user, $vendorId, $payload);
        }

        $title = trim((string) ($payload['title'] ?? ''));
        if ($title === '') {
            $title = 'New product draft';
        }

        $slug = $this->uniqueSlug($title);
        $categoryId = $this->ensureCategory($vendorId);

        $item = new Item();
        $item->vendor_id = $vendorId;
        $item->cat_id = (string) $categoryId;
        $item->item_name = $title;
        $item->slug = $slug;
        $item->item_price = (float) ($payload['price'] ?? 0);
        $item->item_original_price = (float) ($payload['original_price'] ?? ($payload['price'] ?? 0));
        $item->sku = trim((string) ($payload['sku'] ?? ('HAT-' . strtoupper(Str::random(8)))));
        $item->description = trim((string) ($payload['description'] ?? 'Created from Hatchers OS by Atlas.'));
        $item->has_variants = 2;
        $item->variants_json = '';
        $item->stock_management = 2;
        $item->qty = '';
        $item->min_order = '';
        $item->max_order = '';
        $item->low_qty = '';
        $item->tax = '';
        $item->is_available = 1;
        $item->is_deleted = 2;
        $item->is_imported = 2;
        $item->save();

        $this->snapshotService->syncFounder($user, 'os_product_created');

        return response()->json([
            'success' => true,
            'record_id' => $item->id,
            'slug' => $item->slug,
            'edit_url' => url('/admin/products/edit-' . $item->slug),
            'title' => $item->item_name,
        ]);
    }

    private function updateWebsite(User $user, int $vendorId, array $payload)
    {
        $websiteTitle = trim((string) ($payload['website_title'] ?? ''));
        $websitePath = trim((string) ($payload['website_path'] ?? ''));
        $themeTemplate = trim((string) ($payload['theme_template'] ?? ''));
        $customDomain = trim((string) ($payload['custom_domain'] ?? ''));
        $description = trim((string) ($payload['description'] ?? ''));
        $metaTitle = trim((string) ($payload['meta_title'] ?? ''));
        $metaDescription = trim((string) ($payload['meta_description'] ?? ''));
        $contactEmail = trim((string) ($payload['contact_email'] ?? ''));
        $contactPhone = trim((string) ($payload['contact_phone'] ?? ''));
        $businessAddress = trim((string) ($payload['business_address'] ?? ''));
        $whatsappNumber = trim((string) ($payload['whatsapp_number'] ?? ''));
        $aboutContent = trim((string) ($payload['about_content'] ?? ''));
        $faqItems = collect((array) ($payload['faq_items'] ?? []))->filter(fn ($item): bool => is_array($item))->values();
        $socialLinks = collect((array) ($payload['social_links'] ?? []))->filter(fn ($item): bool => is_array($item))->values();
        $featureItems = collect((array) ($payload['feature_items'] ?? []))->filter(fn ($item): bool => is_array($item))->values();
        $testimonials = collect((array) ($payload['testimonials'] ?? []))->filter(fn ($item): bool => is_array($item))->values();
        $storyItems = collect((array) ($payload['story_items'] ?? []))->filter(fn ($item): bool => is_array($item))->values();
        $storyTitle = trim((string) ($payload['story_title'] ?? ''));
        $storySubtitle = trim((string) ($payload['story_subtitle'] ?? ''));
        $storyDescription = trim((string) ($payload['story_description'] ?? ''));
        $mediaAssets = collect((array) ($payload['media_assets'] ?? []))->filter(fn ($item): bool => is_array($item))->values();
        if (
            $websiteTitle === '' &&
            $websitePath === '' &&
            $themeTemplate === '' &&
            $customDomain === '' &&
            $description === '' &&
            $metaTitle === '' &&
            $metaDescription === '' &&
            $contactEmail === '' &&
            $contactPhone === '' &&
            $businessAddress === '' &&
            $whatsappNumber === '' &&
            $aboutContent === '' &&
            $faqItems->isEmpty() &&
            $socialLinks->isEmpty() &&
            $featureItems->isEmpty() &&
            $testimonials->isEmpty() &&
            $storyItems->isEmpty() &&
            $storyTitle === '' &&
            $storySubtitle === '' &&
            $storyDescription === '' &&
            $mediaAssets->isEmpty()
        ) {
            return response()->json(['success' => false, 'error' => 'Website update needs a title, theme, or custom domain.'], 422);
        }

        $settings = Settings::firstOrNew(['vendor_id' => $vendorId]);
        $settings->vendor_id = $vendorId;
        if ($websitePath !== '') {
            $previousSlug = trim((string) $user->slug);
            $user->slug = $this->uniqueStorefrontSlug($websitePath, (int) $user->id);
            $user->save();
            $this->storeLegacyStorefrontAlias($vendorId, $previousSlug, $user->slug);
        }
        if ($websiteTitle !== '') {
            $settings->website_title = $websiteTitle;
        }
        if ($themeTemplate !== '') {
            $settings->template = $themeTemplate;
        }
        if ($description !== '') {
            $settings->description = $description;
        }
        if ($metaTitle !== '') {
            $settings->meta_title = $metaTitle;
        }
        if ($metaDescription !== '') {
            $settings->meta_description = $metaDescription;
        }
        if ($contactEmail !== '') {
            $settings->email = $contactEmail;
        }
        if ($contactPhone !== '') {
            $settings->contact = $contactPhone;
        }
        if ($businessAddress !== '') {
            $settings->address = $businessAddress;
        }
        if ($whatsappNumber !== '') {
            $settings->whatsapp_number = $whatsappNumber;
        }
        if ($storyTitle !== '') {
            $settings->whoweare_title = $storyTitle;
        }
        if ($storySubtitle !== '') {
            $settings->whoweare_subtitle = $storySubtitle;
        }
        if ($storyDescription !== '') {
            $settings->whoweare_description = $storyDescription;
        }
        $settings->save();

        if ($customDomain !== '') {
            $user->custom_domain = $customDomain;
            $user->save();
        }

        if ($aboutContent !== '') {
            $about = About::firstOrNew(['vendor_id' => $vendorId]);
            $about->vendor_id = $vendorId;
            $about->about_content = $aboutContent;
            $about->save();
        }

        foreach ($faqItems as $index => $faqItem) {
            $question = trim((string) ($faqItem['question'] ?? ''));
            $answer = trim((string) ($faqItem['answer'] ?? ''));
            if ($question === '' || $answer === '') {
                continue;
            }

            $faq = Faq::where('vendor_id', $vendorId)
                ->whereRaw('LOWER(question) = ?', [Str::lower($question)])
                ->first();

            if (empty($faq)) {
                $faq = new Faq();
                $faq->vendor_id = $vendorId;
            }

            $faq->question = $question;
            $faq->answer = $answer;
            $faq->reorder_id = $index + 1;
            $faq->save();
        }

        foreach ($socialLinks as $socialItem) {
            $network = trim((string) ($socialItem['network'] ?? ''));
            $url = trim((string) ($socialItem['url'] ?? ''));
            if ($url === '') {
                continue;
            }

            $icon = $this->socialIconMarkup($network, $url);
            $social = SocialLinks::where('vendor_id', $vendorId)
                ->where('link', $url)
                ->first();

            if (empty($social)) {
                $social = new SocialLinks();
                $social->vendor_id = $vendorId;
            }

            $social->icon = $icon;
            $social->link = $url;
            $social->save();
        }

        foreach ($featureItems as $featureItem) {
            $title = trim((string) ($featureItem['title'] ?? ''));
            $description = trim((string) ($featureItem['description'] ?? ''));
            if ($title === '' || $description === '') {
                continue;
            }

            $feature = Features::where('vendor_id', $vendorId)
                ->whereRaw('LOWER(title) = ?', [Str::lower($title)])
                ->first();

            if (empty($feature)) {
                $feature = new Features();
                $feature->vendor_id = $vendorId;
            }

            $feature->title = $title;
            $feature->description = $description;
            $feature->icon = $feature->icon ?: '<i class="fa-solid fa-check"></i>';
            $feature->save();
        }

        foreach ($testimonials as $testimonialItem) {
            $name = trim((string) ($testimonialItem['name'] ?? ''));
            $description = trim((string) ($testimonialItem['description'] ?? ''));
            if ($name === '' || $description === '') {
                continue;
            }

            $testimonial = Testimonials::where('vendor_id', $vendorId)
                ->whereNull('item_id')
                ->whereNull('user_id')
                ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
                ->first();

            if (empty($testimonial)) {
                $testimonial = new Testimonials();
                $testimonial->vendor_id = $vendorId;
            }

            $testimonial->name = $name;
            $testimonial->position = trim((string) ($testimonialItem['position'] ?? 'Customer'));
            $testimonial->description = $description;
            $testimonial->star = max(1, min(5, (int) ($testimonialItem['star'] ?? 5)));
            $testimonial->save();
        }

        foreach ($storyItems as $storyItem) {
            $title = trim((string) ($storyItem['title'] ?? ''));
            $description = trim((string) ($storyItem['description'] ?? ''));
            if ($title === '' || $description === '') {
                continue;
            }

            $who = WhoWeAre::where('vendor_id', $vendorId)
                ->whereRaw('LOWER(title) = ?', [Str::lower($title)])
                ->first();

            if (empty($who)) {
                $who = new WhoWeAre();
                $who->vendor_id = $vendorId;
            }

            $who->title = $title;
            $who->sub_title = $description;
            $who->save();
        }

        if ($mediaAssets->isNotEmpty()) {
            $this->applyWebsiteMedia($vendorId, $settings, $mediaAssets->all());
        }

        $this->snapshotService->syncFounder($user, 'store_setup');

        return response()->json([
            'success' => true,
            'title' => $websiteTitle !== '' ? $websiteTitle : (string) ($settings->website_title ?? ''),
            'theme_template' => $themeTemplate !== '' ? $themeTemplate : (string) ($settings->template ?? ''),
            'custom_domain' => $customDomain,
            'public_url' => helper::storefront_url($user->fresh()),
            'edit_url' => url('/admin/basic_settings'),
        ]);
    }

    private function publishWebsite(User $user)
    {
        $this->snapshotService->syncFounder($user, 'store_dashboard');

        return response()->json([
            'success' => true,
            'public_url' => helper::storefront_url($user->fresh()),
            'edit_url' => url('/admin/dashboard'),
            'title' => 'Bazaar website published',
        ]);
    }

    private function createBlog(User $user, int $vendorId, array $payload)
    {
        $title = trim((string) ($payload['title'] ?? ''));
        if ($title === '') {
            $title = 'New blog draft';
        }

        $blog = new Blog();
        $blog->vendor_id = $vendorId;
        $blog->title = $title;
        $blog->slug = $this->uniqueBlogSlug($title);
        $blog->description = trim((string) ($payload['description'] ?? 'Created from Hatchers OS by Atlas.'));
        $blog->image = '';
        $blog->save();

        $this->snapshotService->syncFounder($user, 'os_blog_created');

        return response()->json([
            'success' => true,
            'record_id' => $blog->id,
            'slug' => $blog->slug,
            'edit_url' => url('/admin/blogs/edit-' . $blog->slug),
            'title' => $blog->title,
        ]);
    }

    private function updateProduct(User $user, int $vendorId, array $payload)
    {
        $item = $this->findTargetProduct($vendorId, $payload);
        if (empty($item)) {
            return response()->json(['success' => false, 'error' => 'The requested product was not found in Bazaar for this founder.'], 404);
        }

        $field = trim((string) ($payload['field'] ?? ''));
        $value = trim((string) ($payload['value'] ?? ''));
        if ($field === '' || $value === '') {
            return response()->json(['success' => false, 'error' => 'Update field and value are required.'], 422);
        }

        if ($field === 'title') {
            $item->item_name = $value;
            $item->slug = $this->uniqueSlug($value, (int) $item->id);
        } elseif ($field === 'description') {
            $item->description = $value;
        } elseif ($field === 'price') {
            $item->item_price = (float) $value;
            if ((float) $item->item_original_price < (float) $item->item_price) {
                $item->item_original_price = (float) $item->item_price;
            }
        } elseif (in_array($field, ['status', 'is_available'], true)) {
            $item->is_available = $this->normalizeEnabledFlag($value) === 1 ? 1 : 2;
        } elseif ($field === 'sku') {
            $item->sku = $value;
        } elseif (in_array($field, ['category_name', 'category'], true)) {
            $item->cat_id = (string) $this->ensureCategory($vendorId, $value);
        } elseif (in_array($field, ['tax_rules', 'tax'], true)) {
            $item->tax = $this->ensureTaxRules($vendorId, $value);
        } elseif ($field === 'variants') {
            $this->syncVariants((int) $item->id, $value, (float) ($item->item_price ?? 0));
            $item->has_variants = 1;
            $item->variants_json = $value;
        } elseif ($field === 'extras') {
            $this->syncExtras((int) $item->id, $value);
        } elseif (in_array($field, ['stock', 'qty'], true)) {
            $item->stock_management = 1;
            $item->qty = max(0, (int) $value);
        } elseif (in_array($field, ['low_stock', 'low_qty'], true)) {
            $item->stock_management = 1;
            $item->low_qty = max(0, (int) $value);
        } else {
            return response()->json(['success' => false, 'error' => 'Unsupported product field update.'], 422);
        }

        $item->save();
        $this->snapshotService->syncFounder($user, 'os_product_updated');

        return response()->json([
            'success' => true,
            'record_id' => $item->id,
            'slug' => $item->slug,
            'edit_url' => url('/admin/products/edit-' . $item->slug),
            'title' => $item->item_name,
        ]);
    }

    private function updateBlog(User $user, int $vendorId, array $payload)
    {
        $blog = $this->findTargetBlog($vendorId, $payload);
        if (empty($blog)) {
            return response()->json(['success' => false, 'error' => 'The requested blog was not found in Bazaar for this founder.'], 404);
        }

        $field = trim((string) ($payload['field'] ?? ''));
        $value = trim((string) ($payload['value'] ?? ''));
        if ($field === '' || $value === '') {
            return response()->json(['success' => false, 'error' => 'Update field and value are required.'], 422);
        }

        if ($field === 'title') {
            $blog->title = $value;
            $blog->slug = $this->uniqueBlogSlug($value, (int) $blog->id);
        } elseif (in_array($field, ['description', 'content'], true)) {
            $blog->description = $value;
        } else {
            return response()->json(['success' => false, 'error' => 'Unsupported blog field update.'], 422);
        }

        $blog->save();
        $this->snapshotService->syncFounder($user, 'os_blog_updated');

        return response()->json([
            'success' => true,
            'record_id' => $blog->id,
            'slug' => $blog->slug,
            'edit_url' => url('/admin/blogs/edit-' . $blog->slug),
            'title' => $blog->title,
        ]);
    }

    private function updatePage(User $user, int $vendorId, array $payload)
    {
        $field = trim((string) ($payload['field'] ?? ''));
        $value = trim((string) ($payload['value'] ?? ''));
        $targetName = trim((string) ($payload['target_name'] ?? ''));
        if (!in_array($field, ['content', 'description'], true) || $value === '' || $targetName === '') {
            return response()->json(['success' => false, 'error' => 'Page name and content are required.'], 422);
        }

        $page = $this->normalizePageName($targetName);
        if ($page === '') {
            return response()->json(['success' => false, 'error' => 'Unsupported Bazaar page target.'], 422);
        }

        if ($page === 'about') {
            $record = About::firstOrNew(['vendor_id' => $vendorId]);
            $record->vendor_id = $vendorId;
            $record->about_content = $value;
            $record->save();
            $title = 'About Us';
            $editUrl = url('/admin/aboutus');
        } elseif ($page === 'privacy') {
            $record = Privacypolicy::firstOrNew(['vendor_id' => $vendorId]);
            $record->vendor_id = $vendorId;
            $record->privacypolicy_content = $value;
            $record->save();
            $title = 'Privacy Policy';
            $editUrl = url('/admin/privacy-policy');
        } elseif ($page === 'terms') {
            $record = Terms::firstOrNew(['vendor_id' => $vendorId]);
            $record->vendor_id = $vendorId;
            $record->terms_content = $value;
            $record->save();
            $title = 'Terms & Conditions';
            $editUrl = url('/admin/terms-conditions');
        } else {
            $record = Settings::firstOrNew(['vendor_id' => $vendorId]);
            $record->vendor_id = $vendorId;
            $record->refund_policy = $value;
            $record->save();
            $title = 'Refund Policy';
            $editUrl = url('/admin/refund_policy');
        }

        $this->snapshotService->syncFounder($user, 'os_page_updated');

        return response()->json([
            'success' => true,
            'record_id' => $record->id ?? 0,
            'edit_url' => $editUrl,
            'title' => $title,
        ]);
    }

    private function createCoupon(User $user, int $vendorId, array $payload)
    {
        $title = trim((string) ($payload['offer_name'] ?? $payload['title'] ?? ''));
        $code = trim((string) ($payload['offer_code'] ?? strtoupper(Str::slug($title, ''))));
        if ($title === '' || $code === '') {
            return response()->json(['success' => false, 'error' => 'Coupon name and code are required.'], 422);
        }

        $coupon = new Coupons();
        $coupon->vendor_id = $vendorId;
        $coupon->offer_name = $title;
        $coupon->offer_code = $code;
        $coupon->offer_type = $this->normalizeDiscountType((string) ($payload['offer_type'] ?? ''));
        $coupon->usage_type = $this->normalizeUsageType((string) ($payload['usage_type'] ?? ''), (int) ($payload['usage_limit'] ?? 0));
        $coupon->usage_limit = $coupon->usage_type == 1 ? max(1, (int) ($payload['usage_limit'] ?? 1)) : 0;
        $coupon->start_date = (string) ($payload['start_date'] ?? now()->toDateString());
        $coupon->exp_date = (string) ($payload['exp_date'] ?? now()->addDays(30)->toDateString());
        $coupon->offer_amount = (float) ($payload['offer_amount'] ?? 0);
        $coupon->min_amount = (float) ($payload['min_amount'] ?? 0);
        $coupon->description = trim((string) ($payload['description'] ?? 'Created from Hatchers OS.'));
        $coupon->is_available = $this->normalizeEnabledFlag($payload['is_available'] ?? 1);
        $coupon->save();

        $this->snapshotService->syncFounder($user, 'os_coupon_created');

        return response()->json([
            'success' => true,
            'record_id' => $coupon->id,
            'title' => $coupon->offer_name,
            'edit_url' => url('/admin/coupons'),
        ]);
    }

    private function updateCoupon(User $user, int $vendorId, array $payload)
    {
        $coupon = $this->findTargetCoupon($vendorId, $payload);
        if (empty($coupon)) {
            return response()->json(['success' => false, 'error' => 'The requested coupon was not found in Bazaar for this founder.'], 404);
        }

        $field = trim((string) ($payload['field'] ?? ''));
        if ($field === 'config') {
            $coupon->offer_name = trim((string) ($payload['offer_name'] ?? $coupon->offer_name));
            $coupon->offer_code = trim((string) ($payload['offer_code'] ?? $coupon->offer_code));
            $coupon->offer_type = $this->normalizeDiscountType((string) ($payload['offer_type'] ?? $coupon->offer_type));
            $coupon->usage_type = $this->normalizeUsageType((string) ($payload['usage_type'] ?? $coupon->usage_type), (int) ($payload['usage_limit'] ?? $coupon->usage_limit));
            $coupon->usage_limit = $coupon->usage_type == 1 ? max(1, (int) ($payload['usage_limit'] ?? $coupon->usage_limit)) : 0;
            $coupon->start_date = (string) ($payload['start_date'] ?? $coupon->start_date);
            $coupon->exp_date = (string) ($payload['exp_date'] ?? $coupon->exp_date);
            $coupon->offer_amount = (float) ($payload['offer_amount'] ?? $coupon->offer_amount);
            $coupon->min_amount = (float) ($payload['min_amount'] ?? $coupon->min_amount);
            $coupon->description = trim((string) ($payload['description'] ?? $coupon->description));
            if (array_key_exists('is_available', $payload)) {
                $coupon->is_available = $this->normalizeEnabledFlag($payload['is_available']);
            }
        } else {
            $value = trim((string) ($payload['value'] ?? ''));
            if ($field === '' || $value === '') {
                return response()->json(['success' => false, 'error' => 'Coupon update field and value are required.'], 422);
            }

            if (in_array($field, ['title', 'offer_name'], true)) {
                $coupon->offer_name = $value;
            } elseif (in_array($field, ['code', 'offer_code'], true)) {
                $coupon->offer_code = $value;
            } elseif (in_array($field, ['description'], true)) {
                $coupon->description = $value;
            } elseif (in_array($field, ['discount_value', 'offer_amount'], true)) {
                $coupon->offer_amount = (float) $value;
            } elseif (in_array($field, ['min_amount'], true)) {
                $coupon->min_amount = (float) $value;
            } elseif (in_array($field, ['usage_limit'], true)) {
                $coupon->usage_limit = (int) $value;
                $coupon->usage_type = $coupon->usage_limit > 0 ? 1 : 2;
            } elseif (in_array($field, ['status', 'is_available'], true)) {
                $coupon->is_available = $this->normalizeEnabledFlag($value);
            } else {
                return response()->json(['success' => false, 'error' => 'Unsupported Bazaar coupon update.'], 422);
            }
        }

        $coupon->save();
        $this->snapshotService->syncFounder($user, 'os_coupon_updated');

        return response()->json([
            'success' => true,
            'record_id' => $coupon->id,
            'title' => $coupon->offer_name,
            'edit_url' => url('/admin/coupons'),
        ]);
    }

    private function createShipping(User $user, int $vendorId, array $payload)
    {
        $areaName = trim((string) ($payload['area_name'] ?? $payload['region'] ?? $payload['title'] ?? ''));
        if ($areaName === '') {
            return response()->json(['success' => false, 'error' => 'Shipping area name is required.'], 422);
        }

        $shipping = new Shipping();
        $shipping->vendor_id = $vendorId;
        $shipping->area_name = $areaName;
        $shipping->delivery_charge = (float) ($payload['delivery_charge'] ?? $payload['fee'] ?? 0);
        $shipping->is_available = $this->normalizeEnabledFlag($payload['is_available'] ?? 1);
        $shipping->save();

        $this->snapshotService->syncFounder($user, 'os_shipping_created');

        return response()->json([
            'success' => true,
            'record_id' => $shipping->id,
            'title' => $shipping->area_name,
            'edit_url' => url('/admin/shipping'),
        ]);
    }

    private function createOrder(User $user, int $vendorId, array $payload)
    {
        $product = $this->findTargetProduct($vendorId, $payload);
        if (empty($product)) {
            return response()->json(['success' => false, 'error' => 'The requested product was not found in Bazaar for this founder.'], 404);
        }

        $customerName = trim((string) ($payload['customer_name'] ?? ''));
        $customerEmail = trim((string) ($payload['customer_email'] ?? ''));
        $customerMobile = trim((string) ($payload['customer_mobile'] ?? ''));
        $address = trim((string) ($payload['address'] ?? ''));
        $building = trim((string) ($payload['building'] ?? ''));
        $landmark = trim((string) ($payload['landmark'] ?? ''));
        $postalCode = trim((string) ($payload['postal_code'] ?? ''));
        $deliveryArea = trim((string) ($payload['delivery_area'] ?? ''));

        if ($customerName === '' || $customerEmail === '' || $customerMobile === '' || $address === '' || $building === '' || $landmark === '' || $postalCode === '' || $deliveryArea === '') {
            return response()->json(['success' => false, 'error' => 'Public order requests need complete customer and delivery details.'], 422);
        }

        $appData = helper::appdata($vendorId);
        $lastOrder = Order::select('order_number_digit', 'order_number_start')->where('vendor_id', $vendorId)->orderByDesc('id')->first();
        if (empty($lastOrder?->order_number_digit)) {
            $nextNumber = (int) ($appData->order_number_start ?? 1001);
        } elseif ((int) ($lastOrder->order_number_start ?? 0) === (int) ($appData->order_number_start ?? 1001)) {
            $nextNumber = (int) $lastOrder->order_number_digit + 1;
        } else {
            $nextNumber = (int) ($appData->order_number_start ?? 1001);
        }

        $orderNumberDigit = str_pad((string) $nextNumber, 0, STR_PAD_LEFT);
        $orderNumber = (string) ($appData->order_prefix ?? 'ORD') . $orderNumberDigit;
        $quantity = max(1, (int) ($payload['quantity'] ?? 1));
        $selectedVariantName = trim((string) ($payload['selected_variant'] ?? ''));
        $selectedVariant = $selectedVariantName !== ''
            ? Variants::where('item_id', $product->id)->whereRaw('LOWER(name) = ?', [Str::lower($selectedVariantName)])->first()
            : null;
        $selectedExtras = collect((array) ($payload['selected_extras'] ?? []))
            ->map(fn ($value) => Str::lower(trim((string) $value)))
            ->filter()
            ->values();
        $extras = $selectedExtras->isEmpty()
            ? collect()
            : Extra::where('item_id', $product->id)->get()->filter(function (Extra $extra) use ($selectedExtras): bool {
                return $selectedExtras->contains(Str::lower(trim((string) $extra->name)));
            });

        $itemUnitPrice = (float) ($selectedVariant?->price ?? $product->item_price ?? 0);
        $extrasUnitPrice = (float) $extras->sum(fn (Extra $extra): float => (float) ($extra->price ?? 0));
        $subTotal = round(($itemUnitPrice + $extrasUnitPrice) * $quantity, 2);

        $shipping = Shipping::where('vendor_id', $vendorId)
            ->whereRaw('LOWER(area_name) = ?', [Str::lower($deliveryArea)])
            ->latest('id')
            ->first();
        $deliveryCharge = (float) ($shipping->delivery_charge ?? 0);
        $grandTotal = $subTotal + $deliveryCharge;
        $paymentType = trim((string) ($payload['payment_type'] ?? '1'));
        $paymentStatus = trim((string) ($payload['payment_status'] ?? 'unpaid')) === 'paid' ? 2 : 1;
        $paymentId = trim((string) ($payload['payment_id'] ?? ''));

        $defaultStatus = CustomStatus::query()
            ->where('vendor_id', $vendorId)
            ->where('order_type', 1)
            ->where('type', 1)
            ->where('is_available', 1)
            ->where('is_deleted', 2)
            ->orderBy('id')
            ->first();

        $order = new Order();
        $order->order_number = $orderNumber;
        $order->order_number_digit = $orderNumberDigit;
        $order->order_number_start = (int) ($appData->order_number_start ?? 1001);
        $order->vendor_id = $vendorId;
        $order->user_id = null;
        $order->payment_type = $paymentType !== '' ? $paymentType : 1;
        $order->payment_status = $paymentStatus;
        $order->sub_total = $subTotal;
        $order->tax = '';
        $order->tax_name = '';
        $order->grand_total = $grandTotal;
        $order->tips = 0;
        $order->status = $defaultStatus?->id;
        $order->status_type = (int) ($defaultStatus?->type ?? 1);
        $order->address = $address;
        $order->delivery_time = trim((string) ($payload['delivery_time'] ?? ''));
        $order->delivery_date = trim((string) ($payload['delivery_date'] ?? ''));
        $order->delivery_area = $deliveryArea;
        $order->delivery_charge = $deliveryCharge;
        $order->discount_amount = 0;
        $order->couponcode = '';
        $order->order_type = 1;
        $order->building = $building;
        $order->landmark = $landmark;
        $order->pincode = $postalCode;
        $order->customer_name = $customerName;
        $order->customer_email = $customerEmail;
        $order->mobile = $customerMobile;
        $order->order_notes = trim((string) ($payload['notes'] ?? $payload['description'] ?? 'Public website order request from Hatchers OS.'));
        if ($paymentId !== '') {
            $order->transaction_id = $paymentId;
        }
        $order->save();

        $orderDetail = new OrderDetails();
        $orderDetail->order_id = $order->id;
        $orderDetail->item_id = $product->id;
        $orderDetail->item_name = $product->item_name;
        $orderDetail->item_image = '';
        $orderDetail->extras_id = $extras->pluck('id')->implode('|');
        $orderDetail->extras_name = $extras->pluck('name')->implode('|');
        $orderDetail->extras_price = $extrasUnitPrice;
        $orderDetail->price = $itemUnitPrice;
        $orderDetail->variants_id = $selectedVariant?->id ? (string) $selectedVariant->id : '';
        $orderDetail->variants_name = (string) ($selectedVariant?->name ?? '');
        $orderDetail->variants_price = (float) ($selectedVariant?->price ?? 0);
        $orderDetail->attribute = '';
        $orderDetail->qty = $quantity;
        $orderDetail->save();

        if ((int) ($selectedVariant?->stock_management ?? 0) === 1 && $selectedVariant) {
            $selectedVariant->qty = max(0, (int) ($selectedVariant->qty ?? 0) - $quantity);
            $selectedVariant->save();
        } elseif ((int) ($product->stock_management ?? 0) === 1) {
            $product->qty = max(0, (int) ($product->qty ?? 0) - $quantity);
            $product->save();
        }

        $emaildata = helper::emailconfigration(helper::appdata($vendorId)->id ?? $vendorId);
        Config::set('mail', $emaildata);
        helper::create_order_invoice(
            $customerEmail,
            $customerName,
            (string) ($user->email ?? ''),
            (string) ($user->name ?? ''),
            $vendorId,
            $orderNumber,
            1,
            (string) $order->delivery_date,
            (string) $order->delivery_time,
            helper::currency_formate($grandTotal, $vendorId),
            helper::storefront_url($user, '/find-order?order=' . $orderNumber)
        );

        $this->snapshotService->syncFounder($user, 'os_public_order_created');

        return response()->json([
            'success' => true,
            'record_id' => $order->id,
            'title' => $orderNumber,
            'edit_url' => url('/admin/orders'),
        ]);
    }

    private function createCatalogEntry(User $user, int $vendorId, array $payload)
    {
        $resource = trim((string) ($payload['resource'] ?? ''));
        $title = trim((string) ($payload['title'] ?? ''));

        if ($resource === 'category') {
            if ($title === '') {
                return response()->json(['success' => false, 'error' => 'Category title is required.'], 422);
            }

            $categoryId = $this->ensureCategory($vendorId, $title);
            $category = Category::find($categoryId);
            $this->snapshotService->syncFounder($user, 'os_catalog_updated');

            return response()->json([
                'success' => true,
                'record_id' => $categoryId,
                'title' => (string) ($category?->name ?? $title),
                'edit_url' => url('/admin/categories'),
            ]);
        }

        if ($resource === 'tax') {
            $value = trim((string) ($payload['value'] ?? ''));
            if ($title === '' || $value === '') {
                return response()->json(['success' => false, 'error' => 'Tax name and value are required.'], 422);
            }

            $taxIds = $this->ensureTaxRules($vendorId, (string) json_encode([[
                'name' => $title,
                'value' => $value,
                'type' => (string) ($payload['type'] ?? 'percent'),
            ]]));
            $taxId = (int) collect(explode('|', $taxIds))->filter()->first();
            $tax = $taxId > 0 ? Tax::find($taxId) : null;
            $this->snapshotService->syncFounder($user, 'os_catalog_updated');

            return response()->json([
                'success' => true,
                'record_id' => $taxId,
                'title' => (string) ($tax?->name ?? $title),
                'edit_url' => url('/admin/taxes'),
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Unsupported Bazaar catalog resource.'], 422);
    }

    private function updateCatalogEntry(User $user, int $vendorId, array $payload)
    {
        $resource = trim((string) ($payload['resource'] ?? ''));
        $field = trim((string) ($payload['field'] ?? ''));
        $value = trim((string) ($payload['value'] ?? ''));

        if ($resource === 'category') {
            $category = $this->findTargetCategory($vendorId, $payload);
            if (empty($category)) {
                return response()->json(['success' => false, 'error' => 'The requested Bazaar category was not found for this founder.'], 404);
            }

            if (in_array($field, ['title', 'name'], true)) {
                $category->name = $value;
                $category->slug = $this->uniqueCategorySlug($value);
            } elseif (in_array($field, ['status', 'is_available'], true)) {
                $category->is_available = $this->normalizeEnabledFlag($value);
            } else {
                return response()->json(['success' => false, 'error' => 'Unsupported Bazaar category update.'], 422);
            }

            $category->save();
            $this->snapshotService->syncFounder($user, 'os_catalog_updated');

            return response()->json([
                'success' => true,
                'record_id' => $category->id,
                'title' => (string) $category->name,
                'edit_url' => url('/admin/categories'),
            ]);
        }

        if ($resource === 'tax') {
            $tax = $this->findTargetTax($vendorId, $payload);
            if (empty($tax)) {
                return response()->json(['success' => false, 'error' => 'The requested Bazaar tax was not found for this founder.'], 404);
            }

            if (in_array($field, ['title', 'name'], true)) {
                $tax->name = $value;
            } elseif (in_array($field, ['value', 'tax'], true)) {
                $tax->tax = (float) $value;
            } elseif ($field === 'type') {
                $tax->type = in_array(strtolower($value), ['fixed', 'flat', 'amount', '1'], true) ? 1 : 2;
            } elseif (in_array($field, ['status', 'is_available'], true)) {
                $tax->is_available = $this->normalizeEnabledFlag($value);
            } else {
                return response()->json(['success' => false, 'error' => 'Unsupported Bazaar tax update.'], 422);
            }

            $tax->save();
            $this->snapshotService->syncFounder($user, 'os_catalog_updated');

            return response()->json([
                'success' => true,
                'record_id' => $tax->id,
                'title' => (string) $tax->name,
                'edit_url' => url('/admin/taxes'),
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Unsupported Bazaar catalog resource.'], 422);
    }

    private function updateShipping(User $user, int $vendorId, array $payload)
    {
        $shipping = $this->findTargetShipping($vendorId, $payload);
        if (empty($shipping)) {
            return response()->json(['success' => false, 'error' => 'The requested shipping plan was not found in Bazaar for this founder.'], 404);
        }

        $field = trim((string) ($payload['field'] ?? ''));
        if ($field === 'config') {
            $shipping->area_name = trim((string) ($payload['area_name'] ?? $payload['region'] ?? $payload['title'] ?? $shipping->area_name));
            $shipping->delivery_charge = (float) ($payload['delivery_charge'] ?? $payload['fee'] ?? $shipping->delivery_charge);
            if (array_key_exists('is_available', $payload)) {
                $shipping->is_available = $this->normalizeEnabledFlag($payload['is_available']);
            }
        } else {
            $value = trim((string) ($payload['value'] ?? ''));
            if ($field === '' || $value === '') {
                return response()->json(['success' => false, 'error' => 'Shipping update field and value are required.'], 422);
            }

            if (in_array($field, ['title', 'area_name', 'region'], true)) {
                $shipping->area_name = $value;
            } elseif (in_array($field, ['fee', 'delivery_charge'], true)) {
                $shipping->delivery_charge = (float) $value;
            } elseif (in_array($field, ['status', 'is_available'], true)) {
                $shipping->is_available = $this->normalizeEnabledFlag($value);
            } else {
                return response()->json(['success' => false, 'error' => 'Unsupported Bazaar shipping update.'], 422);
            }
        }

        $shipping->save();
        $this->snapshotService->syncFounder($user, 'os_shipping_updated');

        return response()->json([
            'success' => true,
            'record_id' => $shipping->id,
            'title' => $shipping->area_name,
            'edit_url' => url('/admin/shipping'),
        ]);
    }

    private function updateOrder(User $user, int $vendorId, array $payload)
    {
        $order = $this->findTargetOrder($vendorId, $payload);
        if (empty($order)) {
            return response()->json(['success' => false, 'error' => 'The requested order was not found in Bazaar for this founder.'], 404);
        }

        $field = trim((string) ($payload['field'] ?? ''));
        $value = trim((string) ($payload['value'] ?? ''));
        if ($field === '' || $value === '') {
            return response()->json(['success' => false, 'error' => 'Order update field and value are required.'], 422);
        }

        if ($field === 'status') {
            $statusType = $this->normalizeWorkflowStatus($value);
            $customStatus = CustomStatus::query()
                ->where('vendor_id', $vendorId)
                ->where('order_type', $order->order_type)
                ->where('type', $statusType)
                ->where('is_available', 1)
                ->where('is_deleted', 2)
                ->orderBy('id')
                ->first();

            if ($customStatus) {
                $order->status = $customStatus->id;
            }
            $order->status_type = $statusType;
        } elseif ($field === 'payment_status') {
            $order->payment_status = $this->normalizePaymentStatus($value);
        } elseif ($field === 'vendor_note') {
            $order->vendor_note = $value;
        } elseif ($field === 'customer_name') {
            $order->customer_name = $value;
        } elseif (in_array($field, ['customer_email', 'email'], true)) {
            $order->customer_email = $value;
        } elseif (in_array($field, ['customer_mobile', 'mobile'], true)) {
            $order->mobile = $value;
        } elseif ($field === 'address') {
            $order->address = $value;
        } elseif ($field === 'building') {
            $order->building = $value;
        } elseif ($field === 'landmark') {
            $order->landmark = $value;
        } elseif (in_array($field, ['postal_code', 'pincode'], true)) {
            $order->pincode = $value;
        } elseif ($field === 'delivery_area') {
            $order->delivery_area = $value;
        } elseif ($field === 'delivery_date') {
            $order->delivery_date = $value;
        } elseif ($field === 'delivery_time') {
            $order->delivery_time = $value;
        } elseif ($field === 'order_notes') {
            $order->order_notes = $value;
        } elseif ($field === 'customer_message') {
            $existing = trim((string) ($order->order_notes ?? ''));
            $channel = trim((string) ($payload['message_channel'] ?? 'manual'));
            $message = '[' . now()->format('Y-m-d H:i') . '][' . ($channel !== '' ? $channel : 'manual') . '] ' . $value;
            $order->order_notes = trim($existing . ($existing !== '' ? "\n" : '') . $message);
        } else {
            return response()->json(['success' => false, 'error' => 'Unsupported Bazaar order update.'], 422);
        }

        $order->save();
        $emailFollowupSent = $this->sendOrderFollowupEmail($vendorId, $order, $field, $value, (string) ($payload['message_channel'] ?? 'manual'));
        $this->snapshotService->syncFounder($user, 'os_order_updated');

        return response()->json([
            'success' => true,
            'record_id' => $order->id,
            'title' => (string) $order->order_number,
            'edit_url' => url('/admin/orders'),
            'email_followup_sent' => $emailFollowupSent,
        ]);
    }

    private function sendOrderFollowupEmail(int $vendorId, Order $order, string $field, string $value, string $channel): bool
    {
        if ($field !== 'status' && !($field === 'customer_message' && strtolower($channel) === 'email')) {
            return false;
        }

        if (trim((string) $order->customer_email) === '') {
            return false;
        }

        $vendor = User::query()->select('id', 'name', 'email')->find($vendorId);
        if (!$vendor) {
            return false;
        }

        $appData = helper::appdata($vendorId);
        if (!$appData || empty($appData->id)) {
            return false;
        }

        $emaildata = helper::emailconfigration($appData->id);
        Config::set('mail', $emaildata);

        $title = $field === 'status'
            ? 'Order ' . ucfirst(str_replace('_', ' ', $order->status_type ?: $value))
            : 'Order update';

        $messageText = $field === 'status'
            ? 'Your order ' . $order->order_number . ' has been updated to ' . ucfirst(str_replace('_', ' ', $order->status_type ?: $value)) . '.'
            : $value;

        return (bool) helper::order_status_email(
            (string) $order->customer_email,
            (string) ($order->customer_name ?? 'Customer'),
            $title,
            $messageText,
            $vendor
        );
    }

    private function findTargetProduct(int $vendorId, array $payload): ?Item
    {
        $query = Item::where('vendor_id', $vendorId)->where('is_deleted', 2);
        $targetName = trim((string) ($payload['target_name'] ?? ''));

        if ($targetName !== '') {
            return (clone $query)
                ->whereRaw('LOWER(item_name) = ?', [Str::lower($targetName)])
                ->latest('id')
                ->first();
        }

        return $query->latest('id')->first();
    }

    private function findTargetCoupon(int $vendorId, array $payload): ?Coupons
    {
        $query = Coupons::where('vendor_id', $vendorId);
        $targetName = trim((string) ($payload['target_name'] ?? ''));

        if ($targetName !== '') {
            return (clone $query)
                ->where(function ($builder) use ($targetName) {
                    $builder->whereRaw('LOWER(offer_name) = ?', [Str::lower($targetName)])
                        ->orWhereRaw('LOWER(offer_code) = ?', [Str::lower($targetName)]);
                })
                ->latest('id')
                ->first();
        }

        return $query->latest('id')->first();
    }

    private function findTargetShipping(int $vendorId, array $payload): ?Shipping
    {
        $query = Shipping::where('vendor_id', $vendorId);
        $targetName = trim((string) ($payload['target_name'] ?? ''));

        if ($targetName !== '') {
            return (clone $query)
                ->whereRaw('LOWER(area_name) = ?', [Str::lower($targetName)])
                ->latest('id')
                ->first();
        }

        return $query->latest('id')->first();
    }

    private function findTargetCategory(int $vendorId, array $payload): ?Category
    {
        $query = Category::where('vendor_id', $vendorId)->where('is_deleted', 2);
        $targetName = trim((string) ($payload['target_name'] ?? ''));

        if ($targetName !== '') {
            return (clone $query)
                ->whereRaw('LOWER(name) = ?', [Str::lower($targetName)])
                ->latest('id')
                ->first();
        }

        return $query->latest('id')->first();
    }

    private function findTargetTax(int $vendorId, array $payload): ?Tax
    {
        $query = Tax::where('vendor_id', $vendorId)->where('is_deleted', 2);
        $targetName = trim((string) ($payload['target_name'] ?? ''));

        if ($targetName !== '') {
            return (clone $query)
                ->whereRaw('LOWER(name) = ?', [Str::lower($targetName)])
                ->latest('id')
                ->first();
        }

        return $query->latest('id')->first();
    }

    private function findTargetOrder(int $vendorId, array $payload): ?Order
    {
        $query = Order::where('vendor_id', $vendorId);
        $targetName = trim((string) ($payload['target_name'] ?? ''));

        if ($targetName !== '') {
            return (clone $query)
                ->whereRaw('LOWER(order_number) = ?', [Str::lower($targetName)])
                ->latest('id')
                ->first();
        }

        return $query->latest('id')->first();
    }

    private function findTargetBlog(int $vendorId, array $payload): ?Blog
    {
        $query = Blog::where('vendor_id', $vendorId);
        $targetName = trim((string) ($payload['target_name'] ?? ''));

        if ($targetName !== '') {
            return (clone $query)
                ->whereRaw('LOWER(title) = ?', [Str::lower($targetName)])
                ->latest('id')
                ->first();
        }

        return $query->latest('id')->first();
    }

    private function normalizePageName(string $page): string
    {
        $normalized = Str::of($page)->lower()->replace(['&', '-'], ['and', ' '])->squish()->value();

        return match ($normalized) {
            'about', 'about us', 'aboutus' => 'about',
            'privacy', 'privacy policy', 'privacypolicy' => 'privacy',
            'terms', 'terms and conditions', 'terms conditions', 'terms condition' => 'terms',
            'refund', 'refund policy', 'refundpolicy' => 'refund',
            default => '',
        };
    }

    private function normalizeDiscountType(string $value): int
    {
        $value = Str::lower(trim($value));
        return in_array($value, ['2', 'percent', 'percentage'], true) ? 2 : 1;
    }

    private function normalizeUsageType(string $value, int $usageLimit): int
    {
        $value = Str::lower(trim($value));
        if (in_array($value, ['1', 'limited'], true)) {
            return 1;
        }

        if (in_array($value, ['2', 'unlimited'], true)) {
            return 2;
        }

        return $usageLimit > 0 ? 1 : 2;
    }

    private function normalizeEnabledFlag(mixed $value): int
    {
        $normalized = Str::lower(trim((string) $value));
        return in_array($normalized, ['1', 'true', 'active', 'enabled', 'on', 'yes'], true) ? 1 : 2;
    }

    private function normalizeWorkflowStatus(string $value): int
    {
        return match (Str::lower(trim($value))) {
            'pending', 'new', 'open' => 1,
            'processing', 'accepted', 'confirmed', 'in_progress', 'in progress' => 2,
            'completed', 'complete', 'delivered', 'done' => 3,
            'cancelled', 'canceled' => 4,
            default => 1,
        };
    }

    private function normalizePaymentStatus(string $value): int
    {
        return in_array(Str::lower(trim($value)), ['2', 'paid', 'complete', 'completed'], true) ? 2 : 1;
    }

    private function findUser(array $payload): ?User
    {
        foreach (['username', 'email'] as $field) {
            $value = trim((string) ($payload[$field] ?? ''));
            if ($value === '') {
                continue;
            }

            $query = User::query()->where($field, $value)->whereIn('type', [2, 4])->where('is_deleted', 2);
            $user = $query->first();
            if (!empty($user)) {
                return $user;
            }
        }

        return null;
    }

    private function ensureCategory(int $vendorId, ?string $name = null): int
    {
        $name = trim((string) $name);
        $category = Category::where('vendor_id', $vendorId)
            ->where('is_deleted', 2)
            ->where('is_available', 1)
            ->when($name !== '', fn ($query) => $query->where('name', $name))
            ->orderBy('reorder_id')
            ->first();

        if (!empty($category)) {
            return (int) $category->id;
        }

        $name = $name !== '' ? $name : 'Hatchers Drafts';
        $newCategory = new Category();
        $newCategory->vendor_id = $vendorId;
        $newCategory->name = $name;
        $newCategory->slug = $this->uniqueCategorySlug($name);
        $newCategory->is_available = 1;
        $newCategory->is_deleted = 2;
        $newCategory->save();

        return (int) $newCategory->id;
    }

    private function ensureTaxRules(int $vendorId, string $rawValue): string
    {
        $rules = json_decode($rawValue, true);
        if (!is_array($rules)) {
            return '';
        }

        $taxIds = collect($rules)
            ->filter(fn ($item) => is_array($item) && trim((string) ($item['name'] ?? '')) !== '' && trim((string) ($item['value'] ?? '')) !== '')
            ->map(function (array $rule) use ($vendorId): string {
                $name = trim((string) ($rule['name'] ?? ''));
                $value = (float) trim((string) ($rule['value'] ?? '0'));
                $type = in_array(strtolower(trim((string) ($rule['type'] ?? 'percent'))), ['fixed', 'flat', 'amount'], true) ? 1 : 2;

                $tax = Tax::where('vendor_id', $vendorId)->where('name', $name)->first();
                if (empty($tax)) {
                    $tax = new Tax();
                    $tax->vendor_id = $vendorId;
                    $tax->name = $name;
                    $tax->is_available = 1;
                    $tax->is_deleted = 2;
                }

                $tax->type = $type;
                $tax->tax = $value;
                $tax->save();

                return (string) $tax->id;
            })
            ->filter()
            ->values()
            ->all();

        return implode('|', $taxIds);
    }

    private function syncVariants(int $itemId, string $rawValue, float $basePrice): void
    {
        $variants = json_decode($rawValue, true);
        if (!is_array($variants)) {
            return;
        }

        Variants::where('item_id', $itemId)->delete();

        foreach ($variants as $variant) {
            if (!is_array($variant)) {
                continue;
            }

            $name = trim((string) ($variant['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            Variants::create([
                'item_id' => $itemId,
                'name' => $name,
                'price' => (float) trim((string) ($variant['price'] ?? (string) $basePrice)),
                'original_price' => (float) trim((string) ($variant['price'] ?? (string) $basePrice)),
                'qty' => max(0, (int) trim((string) ($variant['qty'] ?? '0'))),
                'low_qty' => max(0, (int) trim((string) ($variant['low_stock'] ?? '0'))),
                'min_order' => 0,
                'max_order' => 0,
                'stock_management' => 1,
                'is_available' => 1,
            ]);
        }
    }

    private function syncExtras(int $itemId, string $rawValue): void
    {
        $extras = json_decode($rawValue, true);
        if (!is_array($extras)) {
            return;
        }

        Extra::where('item_id', $itemId)->delete();

        foreach ($extras as $extra) {
            if (!is_array($extra)) {
                continue;
            }

            $name = trim((string) ($extra['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            Extra::create([
                'item_id' => $itemId,
                'name' => $name,
                'price' => (float) trim((string) ($extra['price'] ?? '0')),
            ]);
        }
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title, '-');
        $slug = $base !== '' ? $base : 'product-draft';
        $tries = 1;

        while (
            Item::where('slug', $slug)
                ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'product-draft') . '-' . $tries;
            $tries++;
        }

        return $slug;
    }

    private function uniqueBlogSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title, '-');
        $slug = $base !== '' ? $base : 'blog-draft';
        $tries = 1;

        while (
            Blog::where('slug', $slug)
                ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'blog-draft') . '-' . $tries;
            $tries++;
        }

        return $slug;
    }

    private function uniqueCategorySlug(string $title): string
    {
        $base = Str::slug($title, '-');
        $slug = $base !== '' ? $base : 'hatchers-drafts';
        $tries = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = ($base !== '' ? $base : 'hatchers-drafts') . '-' . $tries;
            $tries++;
        }

        return $slug;
    }

    private function uniqueStorefrontSlug(string $value, ?int $ignoreUserId = null): string
    {
        $base = Str::slug($value, '-');
        $slug = $base !== '' ? $base : 'your-business';
        $tries = 1;

        while (
            User::where('slug', $slug)
                ->when($ignoreUserId !== null, fn ($query) => $query->where('id', '!=', $ignoreUserId))
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'your-business') . '-' . $tries;
            $tries++;
        }

        return $slug;
    }

    private function storeLegacyStorefrontAlias(int $vendorId, string $legacySlug, string $currentSlug): void
    {
        $legacySlug = trim(strtolower($legacySlug), '/');
        $currentSlug = trim(strtolower($currentSlug), '/');

        if ($legacySlug === '' || $legacySlug === $currentSlug) {
            return;
        }

        StorefrontAlias::updateOrCreate(
            ['slug' => $legacySlug],
            ['vendor_id' => $vendorId]
        );
    }

    private function socialIconMarkup(string $network, string $url): string
    {
        $value = Str::lower(trim($network));
        $host = Str::lower((string) (parse_url($url, PHP_URL_HOST) ?? ''));
        $signal = $value . ' ' . $host;

        return match (true) {
            str_contains($signal, 'instagram') => '<i class="fa-brands fa-instagram"></i>',
            str_contains($signal, 'facebook') => '<i class="fa-brands fa-facebook-f"></i>',
            str_contains($signal, 'linkedin') => '<i class="fa-brands fa-linkedin-in"></i>',
            str_contains($signal, 'twitter'), str_contains($signal, 'x.com') => '<i class="fa-brands fa-x-twitter"></i>',
            str_contains($signal, 'youtube') => '<i class="fa-brands fa-youtube"></i>',
            str_contains($signal, 'tiktok') => '<i class="fa-brands fa-tiktok"></i>',
            str_contains($signal, 'whatsapp') => '<i class="fa-brands fa-whatsapp"></i>',
            default => '<i class="fa-solid fa-globe"></i>',
        };
    }

    private function applyWebsiteMedia(int $vendorId, Settings $settings, array $mediaAssets): void
    {
        $landing = LandingSettings::firstOrNew(['vendor_id' => $vendorId]);
        $landing->vendor_id = $vendorId;

        $targets = collect($mediaAssets)
            ->filter(fn ($asset): bool => is_array($asset))
            ->keyBy(fn (array $asset): string => trim((string) ($asset['target'] ?? '')));

        if ($hero = $targets->get('hero')) {
            $filename = $this->storeRemoteImage(
                trim((string) ($hero['source_url'] ?? '')),
                storage_path('app/public/admin-assets/images/banners/'),
                'hero'
            );
            if ($filename) {
                $this->replaceFileIfExists(storage_path('app/public/admin-assets/images/banners/' . (string) ($settings->home_banner ?? '')));
                $settings->home_banner = $filename;
            }
        }

        if ($landingHero = $targets->get('landing')) {
            $filename = $this->storeRemoteImage(
                trim((string) ($landingHero['source_url'] ?? '')),
                storage_path('app/public/admin-assets/images/banners/'),
                'landing'
            );
            if ($filename) {
                $this->replaceFileIfExists(storage_path('app/public/admin-assets/images/banners/' . (string) ($landing->landing_home_banner ?? '')));
                $landing->landing_home_banner = $filename;
            }
        }

        if ($faq = $targets->get('faq')) {
            $filename = $this->storeRemoteImage(
                trim((string) ($faq['source_url'] ?? '')),
                storage_path('app/public/admin-assets/images/index/'),
                'faq'
            );
            if ($filename) {
                $this->replaceFileIfExists(storage_path('app/public/admin-assets/images/index/' . (string) ($landing->faq_image ?? '')));
                $landing->faq_image = $filename;
            }
        }

        if ($story = $targets->get('story')) {
            $filename = $this->storeRemoteImage(
                trim((string) ($story['source_url'] ?? '')),
                storage_path('app/public/admin-assets/images/index/'),
                'whoweare'
            );
            if ($filename) {
                $this->replaceFileIfExists(storage_path('app/public/admin-assets/images/index/' . (string) ($settings->whoweare_image ?? '')));
                $settings->whoweare_image = $filename;
            }
        }

        $this->syncBannerImage($vendorId, 0, $targets->get('hero'));
        $this->syncBannerImage($vendorId, 1, $targets->get('section_two') ?? $targets->get('hero'));
        $this->syncBannerImage($vendorId, 2, $targets->get('section_three') ?? $targets->get('faq') ?? $targets->get('hero'));

        $landing->save();
        $settings->save();
    }

    private function syncBannerImage(int $vendorId, int $section, ?array $asset): void
    {
        if (!is_array($asset)) {
            return;
        }

        $filename = $this->storeRemoteImage(
            trim((string) ($asset['source_url'] ?? '')),
            storage_path('app/public/admin-assets/images/banners/'),
            'section-' . $section
        );

        if (!$filename) {
            return;
        }

        $banner = Banner::where('vendor_id', $vendorId)
            ->where('section', $section)
            ->orderBy('reorder_id')
            ->orderBy('id')
            ->first();

        if (empty($banner)) {
            $banner = new Banner();
            $banner->vendor_id = $vendorId;
            $banner->section = $section;
            $banner->type = 1;
            $banner->category_id = 0;
            $banner->product_id = 0;
            $banner->reorder_id = 1;
            $banner->is_available = 1;
        } else {
            $this->replaceFileIfExists(storage_path('app/public/admin-assets/images/banners/' . (string) ($banner->banner_image ?? '')));
        }

        $banner->banner_image = $filename;
        $banner->save();
    }

    private function storeRemoteImage(string $url, string $directory, string $prefix): ?string
    {
        if ($url === '') {
            return null;
        }

        try {
            $response = Http::timeout(20)->get($url);
        } catch (\Throwable $exception) {
            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        File::ensureDirectoryExists($directory);
        $extension = $this->detectRemoteImageExtension($url, (string) $response->header('Content-Type', ''));
        $filename = $prefix . '-' . uniqid() . '.' . $extension;
        File::put(rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename, $response->body());

        return $filename;
    }

    private function detectRemoteImageExtension(string $url, string $contentType): string
    {
        $contentType = strtolower(trim($contentType));
        if (str_contains($contentType, 'png')) {
            return 'png';
        }
        if (str_contains($contentType, 'webp')) {
            return 'webp';
        }
        if (str_contains($contentType, 'gif')) {
            return 'gif';
        }

        $path = parse_url($url, PHP_URL_PATH);
        $extension = strtolower((string) pathinfo((string) $path, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)
            ? ($extension === 'jpeg' ? 'jpg' : $extension)
            : 'jpg';
    }

    private function replaceFileIfExists(string $path): void
    {
        if ($path !== '' && File::exists($path)) {
            @unlink($path);
        }
    }
}
