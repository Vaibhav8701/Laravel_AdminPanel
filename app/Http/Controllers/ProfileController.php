<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return redirect()->route('login');
        }

        if ((int) $currentUser->id !== (int) $id) {
            abort(403, 'Unauthorized profile update attempt.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:10|max:13',
            'password' => 'nullable|string|min:4|max:50',
        ]);

        $data = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        User::where('id', $id)->update($data);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile.change-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:4|max:50|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $currentUser->password)) {
            return redirect()->route('password.change')
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $currentUser->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('password.change')
            ->with('success', 'Password changed successfully.');
    }

}