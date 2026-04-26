<?php

namespace App\Services;

use App\Helpers\helper;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Coupons;
use App\Models\Extra;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Tax;
use App\Models\User;
use App\Models\Variants;
use Illuminate\Support\Facades\Http;

class HatchersOsSnapshotService
{
    public function syncFounder(User $user, ?string $currentPage = null): void
    {
        $sharedSecret = trim((string) env('WEBSITE_PLATFORM_SHARED_SECRET', env('HATCHERS_SHARED_SECRET', '')));
        $baseUrl = rtrim((string) env('HATCHERS_OS_URL', 'https://app.hatchers.ai'), '/');

        if ($sharedSecret === '' || $baseUrl === '') {
            return;
        }

        $founderId = (int) $user->id;
        $settings = Settings::where('vendor_id', $founderId)->first();
        $productCount = Item::where('vendor_id', $founderId)->count();
        $orderCount = Order::where('vendor_id', $founderId)->count();
        $customerCount = Order::where('vendor_id', $founderId)
            ->distinct('customer_email')
            ->count('customer_email');
        $blogCount = Blog::where('vendor_id', $founderId)->count();
        $recentOrders = Order::where('vendor_id', $founderId)
            ->latest('id')
            ->limit(8)
            ->get([
                'order_number',
                'customer_name',
                'customer_email',
                'mobile',
                'address',
                'building',
                'landmark',
                'pincode',
                'delivery_area',
                'sub_total',
                'discount_amount',
                'delivery_charge',
                'payment_type',
                'delivery_date',
                'delivery_time',
                'order_type',
                'order_notes',
                'grand_total',
                'status_type',
                'payment_status',
                'transaction_id',
                'vendor_note',
                'created_at',
            ])
            ->map(fn (Order $order) => [
                'sub_total' => (float) ($order->sub_total ?? 0),
                'discount_amount' => (float) ($order->discount_amount ?? 0),
                'delivery_charge' => (float) ($order->delivery_charge ?? 0),
                'payment_type' => (string) ($order->payment_type ?? ''),
                'delivery_date' => (string) ($order->delivery_date ?? ''),
                'delivery_time' => (string) ($order->delivery_time ?? ''),
                'order_type' => (string) ($order->order_type ?? ''),
                'order_notes' => (string) ($order->order_notes ?? ''),
                'order_number' => (string) $order->order_number,
                'customer_name' => (string) ($order->customer_name ?? 'Customer'),
                'customer_email' => (string) ($order->customer_email ?? ''),
                'customer_mobile' => (string) ($order->mobile ?? ''),
                'address' => (string) ($order->address ?? ''),
                'building' => (string) ($order->building ?? ''),
                'landmark' => (string) ($order->landmark ?? ''),
                'postal_code' => (string) ($order->pincode ?? ''),
                'delivery_area' => (string) ($order->delivery_area ?? ''),
                'grand_total' => (float) ($order->grand_total ?? 0),
                'status' => $this->formatWorkflowStatus((int) ($order->status_type ?? 1)),
                'payment_status' => ((int) ($order->payment_status ?? 1)) === 2 ? 'paid' : 'unpaid',
                'transaction_id' => (string) ($order->transaction_id ?? ''),
                'vendor_note' => (string) ($order->vendor_note ?? ''),
                'line_items' => OrderDetails::where('order_id', $order->id)
                    ->limit(12)
                    ->get(['item_name', 'variants_name', 'qty', 'price'])
                    ->map(fn (OrderDetails $detail) => [
                        'item_name' => (string) ($detail->item_name ?? ''),
                        'variant_name' => (string) ($detail->variants_name ?? ''),
                        'qty' => (int) ($detail->qty ?? 0),
                        'price' => (float) ($detail->price ?? 0),
                    ])
                    ->values()
                    ->all(),
                'created_at' => optional($order->created_at)->toIso8601String(),
            ])
            ->values()
            ->all();
        $recentCoupons = Coupons::where('vendor_id', $founderId)
            ->latest('id')
            ->limit(8)
            ->get([
                'offer_name',
                'offer_code',
                'offer_amount',
                'offer_type',
                'min_amount',
                'is_available',
                'exp_date',
            ])
            ->map(fn (Coupons $coupon) => [
                'title' => (string) ($coupon->offer_name ?? ''),
                'code' => (string) ($coupon->offer_code ?? ''),
                'discount_value' => (float) ($coupon->offer_amount ?? 0),
                'discount_type' => ((int) ($coupon->offer_type ?? 1)) === 2 ? 'percent' : 'fixed',
                'min_amount' => (float) ($coupon->min_amount ?? 0),
                'status' => ((int) ($coupon->is_available ?? 2)) === 1 ? 'active' : 'inactive',
                'expires_at' => (string) ($coupon->exp_date ?? ''),
            ])
            ->values()
            ->all();
        $recentProducts = Item::where('vendor_id', $founderId)
            ->latest('id')
            ->limit(8)
            ->get([
                'id',
                'item_name',
                'sku',
                'qty',
                'low_qty',
                'stock_management',
                'item_price',
                'is_available',
            ])
            ->map(fn (Item $item) => [
                'id' => (int) ($item->id ?? 0),
                'title' => (string) ($item->item_name ?? ''),
                'sku' => (string) ($item->sku ?? ''),
                'qty' => (int) ($item->qty ?? 0),
                'low_qty' => (int) ($item->low_qty ?? 0),
                'stock_management' => (int) ($item->stock_management ?? 2),
                'price' => (float) ($item->item_price ?? 0),
                'status' => ((int) ($item->is_available ?? 1)) === 1 ? 'active' : 'inactive',
                'variants' => Variants::where('item_id', $item->id)
                    ->limit(12)
                    ->get(['name', 'price', 'qty', 'low_qty'])
                    ->map(fn (Variants $variant) => [
                        'name' => (string) ($variant->name ?? ''),
                        'price' => (float) ($variant->price ?? 0),
                        'qty' => (int) ($variant->qty ?? 0),
                        'low_qty' => (int) ($variant->low_qty ?? 0),
                    ])->values()->all(),
                'extras' => Extra::where('item_id', $item->id)
                    ->limit(12)
                    ->get(['name', 'price'])
                    ->map(fn (Extra $extra) => [
                        'name' => (string) ($extra->name ?? ''),
                        'price' => (float) ($extra->price ?? 0),
                    ])->values()->all(),
            ])
            ->values()
            ->all();
        $shippingZones = Shipping::where('vendor_id', $founderId)
            ->latest('id')
            ->limit(8)
            ->get([
                'area_name',
                'delivery_charge',
                'is_available',
            ])
            ->map(fn (Shipping $shipping) => [
                'title' => (string) ($shipping->area_name ?? ''),
                'fee' => (float) ($shipping->delivery_charge ?? 0),
                'status' => ((int) ($shipping->is_available ?? 2)) === 1 ? 'active' : 'inactive',
            ])
            ->values()
            ->all();
        $recentCategories = Category::where('vendor_id', $founderId)
            ->where('is_deleted', 2)
            ->latest('id')
            ->limit(8)
            ->get(['name', 'is_available'])
            ->map(fn (Category $category) => [
                'title' => (string) ($category->name ?? ''),
                'status' => ((int) ($category->is_available ?? 2)) === 1 ? 'active' : 'inactive',
            ])
            ->values()
            ->all();
        $recentTaxes = Tax::where('vendor_id', $founderId)
            ->where('is_deleted', 2)
            ->latest('id')
            ->limit(8)
            ->get(['name', 'tax', 'type', 'is_available'])
            ->map(fn (Tax $tax) => [
                'title' => (string) ($tax->name ?? ''),
                'value' => (float) ($tax->tax ?? 0),
                'type' => ((int) ($tax->type ?? 2)) === 1 ? 'fixed' : 'percent',
                'status' => ((int) ($tax->is_available ?? 2)) === 1 ? 'active' : 'inactive',
            ])
            ->values()
            ->all();
        $grossRevenue = (float) Order::where('vendor_id', $founderId)
            ->where('payment_status', 2)
            ->sum('grand_total');
        $themeTemplate = (string) ($settings->template ?? '');
        $websiteTitle = trim((string) ($settings->website_title ?? ''));
        $themeSelected = $themeTemplate !== '';
        $storeReady = $themeSelected && $websiteTitle !== '' && $productCount > 0;
        $readinessScore = min(
            100,
            10
            + ($themeSelected ? 20 : 0)
            + ($websiteTitle !== '' ? 15 : 0)
            + ($productCount > 0 ? 25 : 0)
            + ($orderCount > 0 ? 30 : 0)
        );

        $body = [
            'email' => $user->email,
            'username' => $user->username,
            'updated_at' => now()->toIso8601String(),
            'readiness_score' => $readinessScore,
            'current_page' => $currentPage ?: ($storeReady ? 'store_dashboard' : 'store_setup'),
            'key_counts' => [
                'product_count' => $productCount,
                'order_count' => $orderCount,
                'customer_count' => $customerCount,
                'blog_count' => $blogCount,
            ],
            'status_flags' => [
                'store_connected' => true,
                'theme_selected' => $themeSelected,
                'store_ready' => $storeReady,
            ],
            'recent_activity' => [
                'Founder workspace synced from Bazaar.',
                'Products: ' . $productCount . ', orders: ' . $orderCount . ', customers: ' . $customerCount . '.',
            ],
            'summary' => [
                'website_title' => $websiteTitle !== '' ? $websiteTitle : ($user->name ? $user->name . ' Store' : 'New Bazaar Store'),
                'theme_template' => $themeTemplate,
                'website_url' => helper::storefront_url($user),
                'gross_revenue' => $grossRevenue,
                'currency' => (string) ($settings->default_currency ?? 'usd'),
            ],
            'recent_orders' => $recentOrders,
            'recent_products' => $recentProducts,
            'recent_coupons' => $recentCoupons,
            'shipping_zones' => $shippingZones,
            'recent_categories' => $recentCategories,
            'recent_taxes' => $recentTaxes,
        ];

        $json = json_encode($body);
        if ($json === false) {
            return;
        }

        try {
            Http::timeout(10)
                ->withHeaders([
                    'X-Hatchers-Signature' => hash_hmac('sha256', $json, $sharedSecret),
                    'Content-Type' => 'application/json',
                ])
                ->post($baseUrl . '/integrations/snapshots/bazaar', $body);
        } catch (\Throwable $exception) {
        }
    }

    private function formatWorkflowStatus(int $statusType): string
    {
        return match ($statusType) {
            2 => 'processing',
            3 => 'completed',
            4 => 'cancelled',
            default => 'pending',
        };
    }
}
