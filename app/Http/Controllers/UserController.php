<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            'email' => 'required|email|unique:users,email'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email']
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email' 
        ]);

        $user->update([
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email
        ]);

        return response()->json($user, 200);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }


}
