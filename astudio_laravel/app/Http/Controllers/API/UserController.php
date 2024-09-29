<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Removed the __construct() method as middleware is now applied in routes

    public function index(Request $request)
    {
        $users = User::query();

        $filters = ['first_name', 'last_name', 'gender', 'email'];

        foreach ($filters as $filter) {
            if ($request->has($filter)) {
                $users->where($filter, $request->$filter);
            }
        }

        if ($request->has('date')) {
            $users->whereDate('date_of_birth', '=', $request->date);
        }

        $users = $users->get();
        return response()->json(['users' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['user' => $user, 'message' => 'User created successfully'], 201);
    }

    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($user->id),
                'max:255'
            ],
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json(['user' => $user, 'message' => 'User updated successfully'], 200);
    }

    public function destroy(User $user)
    {
        $user->timesheets()->delete();
        $user->projects()->detach();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function register(Request $request)
    {
        try {
            Log::info('Registration attempt', $request->all());

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            Log::info('Validation passed');

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('User created', ['user_id' => $user->id]);

            $token = $user->createToken('api-token')->plainTextToken;
            
            Log::info('Token created');

            return response()->json(['user' => $user, 'token' => $token, 'message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred during registration: ' . $e->getMessage()], 500);
        }
    }

public function login(Request $request)
{
    try {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'message' => 'Login successful'
            ], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    } catch (\Exception $e) {
        \Log::error('Login error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response()->json(['message' => 'An error occurred during login'], 500);
    }
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful']);
    }
}