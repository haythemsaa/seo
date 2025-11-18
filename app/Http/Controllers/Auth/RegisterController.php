<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'organization_name' => 'required|string|max:255',
        ]);

        // Create organization
        $organization = Organization::create([
            'name' => $request->organization_name,
            'slug' => \Str::slug($request->organization_name) . '-' . \Str::random(6),
            'owner_id' => 0, // Will be updated after user creation
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
        ]);

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $organization->id,
            'role' => 'admin',
        ]);

        // Update organization owner
        $organization->update(['owner_id' => $user->id]);

        // Generate API token
        $token = $user->createToken('api-token')->plainTextToken;
        $user->update(['api_token' => $token]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
