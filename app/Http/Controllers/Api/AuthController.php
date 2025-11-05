<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 *
 * APIs for user authentication and account management
 */
class AuthController extends Controller
{
    /**
     * Register a new user
     * 
     * Register a new user account with role assignment (landlord, tenant, or service_provider).
     * Admin accounts cannot be created through public registration.
     * 
     * @unauthenticated
     * 
     * @bodyParam name string required The user's full name. Example: John Doe
     * @bodyParam email string required The user's email address. Example: john@example.com
     * @bodyParam password string required The user's password (minimum 8 characters). Example: password123
     * @bodyParam password_confirmation string required Password confirmation. Example: password123
     * @bodyParam phone string The user's phone number. Example: +97150123456
     * @bodyParam role string required The user's role. Must be one of: landlord, tenant, service_provider. Example: tenant
     * 
     * @response 201 {
     *   "user": {
     *     "id": 1,
     *     "uuid": "550e8400-e29b-41d4-a716-446655440000",
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "phone": "+97150123456",
     *     "status": "active",
     *     "roles": ["tenant"],
     *     "created_at": "2025-11-05T10:00:00.000000Z"
     *   },
     *   "token": "1|abc123xyz...",
     *   "message": "User registered successfully"
     * }
     * 
     * @response 422 {
     *   "message": "The email has already been taken.",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:landlord,tenant,service_provider',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status' => 'active',
        ]);

        $user->assignRole($validated['role']);
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'message' => 'User registered successfully',
        ], 201);
    }

    /**
     * Login user
     * 
     * Authenticate a user and receive an access token. The token must be included
     * in the Authorization header for all subsequent authenticated requests.
     * 
     * @unauthenticated
     * 
     * @bodyParam email string required The user's email address. Example: admin@thelobby.com
     * @bodyParam password string required The user's password. Example: password
     * @bodyParam device_name string The device name for token identification. Example: mobile-app
     * 
     * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "uuid": "77ef470a-c9b4-495f-9633-1f9517e7763e",
     *     "name": "System Administrator",
     *     "email": "admin@thelobby.com",
     *     "phone": "+97150123456",
     *     "status": "active",
     *     "roles": ["admin"],
     *     "permissions": ["view-users", "create-users", "..."],
     *     "profile": {
     *       "first_name": "System",
     *       "last_name": "Administrator"
     *     },
     *     "last_login_at": "2025-11-05T19:58:31.000000Z"
     *   },
     *   "token": "2|DCacC2h81yjDmbLgKZ7tMavTNv7E3AQxPiVFlozQ",
     *   "message": "Login successful"
     * }
     * 
     * @response 422 {
     *   "message": "The provided credentials are incorrect.",
     *   "errors": {
     *     "email": ["The provided credentials are incorrect."]
     *   }
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact support.'],
            ]);
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        $deviceName = $request->device_name ?? 'web';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'user' => new UserResource($user->load('roles', 'profile')),
            'token' => $token,
            'message' => 'Login successful',
        ]);
    }

    /**
     * Logout user (Revoke current token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Logout from all devices (Revoke all tokens)
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out from all devices successfully']);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return new UserResource($request->user()->load('roles', 'profile'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
        ]);

        $request->user()->update($validated);

        return response()->json([
            'user' => new UserResource($request->user()->load('roles', 'profile')),
            'message' => 'Profile updated successfully',
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $request->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }
}
