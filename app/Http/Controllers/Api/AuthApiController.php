<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthApiController extends Controller
{
    /**
     * Mobile / Flutter API Login
     * POST /api/v1/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (! $user->canAuthenticate()) {
            return response()->json([
                'message' => 'Your account or assigned company/branch is inactive or misconfigured.',
            ], Response::HTTP_FORBIDDEN);
        }

        // Log into session for session-based API or token response
        Auth::login($user);

        $roles = $user->roles()->where('roles.is_active', true)->pluck('slug')->all();
        $permissions = $user->roles()
            ->where('roles.is_active', true)
            ->with('permissions')
            ->get()
            ->flatMap(fn ($r) => $r->permissions->pluck('slug'))
            ->unique()
            ->values()
            ->all();

        return response()->json([
            'message' => 'Login successful.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'roles' => $roles,
                'is_admin' => $user->isAdmin(),
                'is_manager' => $user->isManager(),
                'is_waiter' => $user->isWaiter(),
                'company' => $user->company ? [
                    'id' => $user->company->id,
                    'name' => $user->company->name,
                    'code' => $user->company->code,
                ] : null,
                'branch' => $user->branch ? [
                    'id' => $user->branch->id,
                    'name' => $user->branch->name,
                    'code' => $user->branch->code,
                ] : null,
            ],
            'permissions' => $user->isAdmin() ? ['*'] : $permissions,
        ]);
    }

    /**
     * Current authenticated user info
     * GET /api/v1/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'roles' => $user->roles()->pluck('slug')->all(),
            'is_admin' => $user->isAdmin(),
            'is_manager' => $user->isManager(),
            'is_waiter' => $user->isWaiter(),
            'branch' => $user->branch ? [
                'id' => $user->branch->id,
                'name' => $user->branch->name,
                'code' => $user->branch->code,
            ] : null,
        ]);
    }

    /**
     * Logout
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}

