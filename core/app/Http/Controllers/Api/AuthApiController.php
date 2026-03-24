<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Login: username/email + password. Returns user + token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->username]);

        if (!Auth::attempt($request->only($field, 'password'))) {
            throw ValidationException::withMessages([
                'username' => [__('The provided credentials are incorrect.')],
            ]);
        }

        $user = Auth::user();
        if ($user->status != Status::USER_ACTIVE) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => [__('Your account has been disabled.')],
            ]);
        }

        $user->tv = $user->ts == Status::VERIFIED ? Status::UNVERIFIED : Status::VERIFIED;
        $user->save();

        $ip = getRealIP();
        $exist = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        // Copy geo fields from previous login for same IP, else resolve fresh
        if ($exist) {
            $userLogin->longitude = $exist->longitude;
            $userLogin->latitude = $exist->latitude;
            $userLogin->city = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country = $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude = @implode(',', $info['long']);
            $userLogin->latitude = @implode(',', $info['lat']);
            $userLogin->city = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country = @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        // Revoke other tokens if you want single device; else remove next line
        $user->tokens()->delete();
        $token = $user->createToken('app')->plainTextToken;

        $userData = $user->toArray();
        $userData['wallet'] = ['balance' => (float) ($user->balance ?? 0)];

        return response()->json([
            'message' => 'Login successful',
            'user' => $userData,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Register: username, email, password. Returns user + token.
     */
    public function register(Request $request): JsonResponse
    {
        $general = gs();
        $agreeRule = $general->agree ? 'required' : 'nullable';
        $request->validate([
            'username' => 'required|string|min:4|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'agree' => $agreeRule,
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->email = strtolower($request->email);
        $user->password = Hash::make($request->password);
        $user->address = [
            'country' => @$request->country,
            'mobile' => @$request->mobile,
            'state' => @$request->state,
            'zip' => @$request->zip,
            'city' => @$request->city,
        ];
        $user->status = Status::USER_ACTIVE;
        $user->ts = Status::UNVERIFIED;
        $user->tv = Status::UNVERIFIED;
        $user->save();

        Auth::login($user);
        $user->tokens()->delete();
        $token = $user->createToken('app')->plainTextToken;

        $userData = $user->toArray();
        $userData['wallet'] = ['balance' => (float) ($user->balance ?? 0)];

        return response()->json([
            'message' => 'Registration successful',
            'user' => $userData,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Logout: revoke current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
