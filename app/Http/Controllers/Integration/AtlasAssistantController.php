<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Item;
use App\Models\Order;
use App\Models\PricingPlan;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Throwable;

class AtlasAssistantController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message'));
        if ($message === '') {
            return response()->json([
                'success' => false,
                'error' => 'Message is required.',
            ], 422);
        }

        $actor = Auth::user();
        if (!$actor || !in_array((int) $actor->type, [2, 4], true)) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized.',
            ], 403);
        }

        $founderId = (int) ((int) $actor->type === 4 ? $actor->vendor_id : $actor->id);
        $founder = User::find($founderId);
        if (!$founder) {
            return response()->json([
                'success' => false,
                'error' => 'Founder account was not found.',
            ], 404);
        }

        $secret = trim((string) env('WEBSITE_PLATFORM_SHARED_SECRET'));
        if ($secret === '') {
            return response()->json([
                'success' => false,
                'error' => 'WEBSITE_PLATFORM_SHARED_SECRET is not configured.',
            ], 500);
        }

        $settings = Settings::where('vendor_id', $founderId)->first();
        $currentPlan = PricingPlan::select('name')->where('id', $founder->plan_id)->first();

        $payload = [
            'app' => 'bazaar',
            'role' => (int) $actor->type === 4 ? 'staff' : 'founder',
            'current_page' => (string) $request->input('current_page', url()->previous()),
            'message' => $message,
            'name' => (string) ($founder->name ?? ''),
            'username' => (string) ($founder->username ?? ''),
            'email' => (string) ($founder->email ?? ''),
            'company' => [
                'company_name' => (string) ($founder->name ?? ''),
                'company_website' => url('/' . ltrim((string) ($founder->slug ?? ''), '/')),
                'company_description' => (string) ($founder->about ?? ''),
            ],
            'snapshot' => [
                'workspace' => 'Bazaar ecommerce builder',
                'website_title' => (string) ($settings->website_title ?? ''),
                'theme_template' => (string) ($settings->template ?? ''),
                'current_plan' => (string) ($currentPlan->name ?? ''),
                'product_count' => Item::where('vendor_id', $founderId)->count(),
                'order_count' => Order::where('vendor_id', $founderId)->count(),
                'customer_count' => Order::where('vendor_id', $founderId)->distinct('customer_email')->count('customer_email'),
                'blog_count' => Blog::where('vendor_id', $founderId)->count(),
                'revenue_total' => (float) Order::where('vendor_id', $founderId)
                    ->where('payment_status', 2)
                    ->sum('grand_total'),
            ],
            'operations' => [
                'orders' => [
                    'count' => Order::where('vendor_id', $founderId)->count(),
                    'paid_revenue' => (float) Order::where('vendor_id', $founderId)
                        ->where('payment_status', 2)
                        ->sum('grand_total'),
                ],
                'catalog' => [
                    'products' => Item::where('vendor_id', $founderId)->count(),
                    'blogs' => Blog::where('vendor_id', $founderId)->count(),
                ],
            ],
            'sync_summary' => 'Bazaar founder workspace snapshot updated from vendor admin.',
        ];

        $json = json_encode($payload);
        $endpoint = (string) env('ATLAS_ASSISTANT_URL', 'https://atlas.hatchers.ai/hatchers/assistant/chat');

        try {
            $response = Http::timeout(45)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Hatchers-Signature' => hash_hmac('sha256', $json, $secret),
                ])
                ->withBody($json, 'application/json')
                ->post($endpoint);
        } catch (Throwable $exception) {
            return response()->json([
                'success' => false,
                'error' => 'Atlas is temporarily unavailable. Please try again in a moment.',
            ], 502);
        }

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'error' => (string) ($response->json('error') ?: 'Atlas could not answer right now.'),
            ], 502);
        }

        return response()->json([
            'success' => true,
            'reply' => (string) $response->json('reply', ''),
        ]);
    }
}
