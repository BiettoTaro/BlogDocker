<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Retrieve all users
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    

    public function createUser(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($request->password)
        ]);

        return response()->json($user, 201);
    }

    public function getUser(User $user)
    {
        return response()->json($user, 200);
    }


    public function updateUser(Request $request, User $user)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email',
            'password' => 'sometimes|min:8',
        ]);

        $user->update([
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
            'password' => $validated['password'] ?? Hash::make($request->password),
        ]);

        return response()->json($user, 200);
    }

    // Soft delete
    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User soft deleted'], 204);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return response()->json(['message' => 'User restored'], 200);
    }

    // Hard delete
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user-> forceDelete();

        return response()->json(['message' => 'User permanently deleted'], 204);
    }


}
