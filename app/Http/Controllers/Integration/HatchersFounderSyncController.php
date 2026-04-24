<?php

namespace App\Http\Controllers\Integration;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Models\StoreCategory;
use App\Models\User;
use App\Services\HatchersOsSnapshotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class HatchersFounderSyncController extends Controller
{
    public function __construct(private HatchersOsSnapshotService $snapshotService)
    {
    }

    public function __invoke(Request $request)
    {
        if (!Schema::hasColumn('users', 'username')) {
            return response()->json([
                'success' => false,
                'error' => 'The users.username column is missing. Run migrations first.',
            ], 500);
        }

        $sharedSecret = trim((string) env('WEBSITE_PLATFORM_SHARED_SECRET', env('HATCHERS_SHARED_SECRET', '')));
        if ($sharedSecret === '') {
            return response()->json(['success' => false, 'error' => 'WEBSITE_PLATFORM_SHARED_SECRET is not configured.'], 500);
        }

        $rawBody = $request->getContent();
        $signature = trim((string) $request->header('X-Hatchers-Signature', ''));
        $expected = hash_hmac('sha256', $rawBody, $sharedSecret);
        if ($signature === '' || !hash_equals($expected, $signature)) {
            return response()->json(['success' => false, 'error' => 'Invalid sync signature.'], 403);
        }

        $payload = $request->json()->all();
        $username = trim((string) ($payload['username'] ?? ''));
        $email = trim((string) ($payload['email'] ?? ''));
        $password = (string) ($payload['password'] ?? '');
        $name = trim((string) ($payload['name'] ?? ''));

        if ($username === '') {
            return response()->json(['success' => false, 'error' => 'Username is required.'], 422);
        }

        $user = $this->findUser($payload);
        $isNew = empty($user);

        $usernameOwner = User::where('username', $username)->first();
        if (!empty($usernameOwner) && ($isNew || (int) $usernameOwner->id !== (int) $user->id)) {
            return response()->json(['success' => false, 'error' => 'Username already exists in Bazaar.'], 422);
        }

        if ($email !== '') {
            $emailOwner = User::where('email', $email)->first();
            if (!empty($emailOwner) && ($isNew || (int) $emailOwner->id !== (int) $user->id)) {
                return response()->json(['success' => false, 'error' => 'Email already exists in Bazaar.'], 422);
            }
        }

        if ($isNew) {
            $storeId = StoreCategory::where('is_available', 1)
                ->where('is_deleted', 2)
                ->orderBy('reorder_id')
                ->value('id');

            $userId = helper::vendor_register(
                $name !== '' ? $name : $username,
                $email !== '' ? $email : null,
                trim((string) ($payload['phone'] ?? '')),
                Hash::make($password !== '' ? $password : bin2hex(random_bytes(16))),
                '',
                $username,
                '',
                '',
                null,
                null,
                $storeId,
                1,
                $username
            );

            $user = User::find($userId);
        }

        $user->name = $name !== '' ? $name : $username;
        $user->username = $username;
        $user->email = $email !== '' ? $email : null;
        $user->mobile = trim((string) ($payload['phone'] ?? ''));
        $user->type = 2;
        $user->is_available = 1;
        $user->is_deleted = 2;
        $user->is_verified = 2;

        if ($password !== '') {
            $user->password = Hash::make($password);
        }

        $user->save();

        $this->snapshotService->syncFounder($user, 'founder_access_provisioned');

        return response()->json([
            'success' => true,
            'created' => $isNew,
            'user_id' => $user->id,
            'slug' => $user->slug,
        ]);
    }

    private function findUser(array $payload)
    {
        foreach (['username', 'previous_username'] as $field) {
            $value = trim((string) ($payload[$field] ?? ''));
            if ($value !== '') {
                $user = User::where('username', $value)->first();
                if (!empty($user)) {
                    return $user;
                }
            }
        }

        foreach (['email', 'previous_email'] as $field) {
            $value = trim((string) ($payload[$field] ?? ''));
            if ($value !== '') {
                $user = User::where('email', $value)->first();
                if (!empty($user)) {
                    return $user;
                }
            }
        }

        return null;
    }
}
