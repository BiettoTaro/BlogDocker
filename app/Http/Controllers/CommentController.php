<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required| integer',
            'body' => 'required|string',
        ]);

        // Define allowed commentable types for security reason
        $allowedTypes = [
            'blog' => Blog::class,
            'user' => User::class,
        ];

        if (!isset($allowedTypes[$validated['commentable_type']])) {
            return response()->json(['error' => 'Invalid commentable type.'], 400);
        }

        $modelClass = $allowedTypes[$validated['commentable_type']];

        // Find the commentable entity (blog or user)
        $commentable = $modelClass::findOrFail($validated['commentable_id']);

        // Prepare the comment data
        $commentData = [
            'body' => $validated['body'], 
            // If the user is authenticated, record trhe user_id; otherwise, null (visitor comment)
            'user_id' => Auth::check() ? Auth::id() : null,
        ];

        // Create comment using the polymorphic relationship
        $comment = $commentable->comments()->create($commentData);

        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
