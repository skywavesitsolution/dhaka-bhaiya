<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function usersList()
    {
        $users = User::all();
        return view('adminPanel.userManagement.userList', ['users' => $users]);
    }

    public function createUser()
    {
        return view('adminPanel.userManagement.createUser');
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'userRight' => ['nullable', 'array'],
            'userRight.*' => ['string', 'exists:permissions,name'], // Validate permissions exist
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::where('name', 'user')->firstOrFail();
        $user->assignRole($role);

        if ($request->has('userRight')) {
            $user->syncPermissions($request->userRight);
        }

        return redirect('/users-list')->with(['success' => 'User created successfully']);
    }

    public function updateUser(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user_id],
            'userRight' => ['nullable', 'array'],
            'userRight.*' => ['string', 'exists:permissions,name'], // Validate permissions exist
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            if ($request->has('userRight')) {
                $user->syncPermissions($request->userRight); // Sync permissions (add new, remove unchecked)
            } else {
                $user->syncPermissions([]); // Remove all permissions if none selected
            }

            $user->save();

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the user: ' . $e->getMessage());
        }
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'current_password' => ['required', 'string', 'min:8'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'string'],
            ]);

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.'
                ], 422);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the password.'
            ], 500);
        }
    }
}
