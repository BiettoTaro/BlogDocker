<?php

namespace App\Http\Controllers;

use App\Jobs\SendCommentNotificationJob;
use App\Models\Comment;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    // Apply auth middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Comment::with(['user', 'commentable'])->get(), 200);
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
            'body' => 'required|string',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string|in:App\Models\Blog,App\Models\User',
        ]);

        // Define allowed commentable types for security reason
        $allowedTypes = [
            'blog' => Blog::class,
            'user' => User::class,
        ];

        $comment = Comment::create([
            'body' => $validated['body'],
            'commentable_id' => $validated['commentable_id'],
            'commentable_type' => $validated['commentable_type'],
            'user_id' => Auth::id(),
        ]);

        // Dispatch the job to send the email notification
        dispatch((new SendCommentNotificationJob($comment)));

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
        $comment = Comment::findOrFail($id);
        $this->authorize('update', $comment);
        
        $comment->delete();
        return response()->json(['message' => 'Comment soft deleted'], 200);
    }

    public function restore($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $this->authorize('update', $comment);

        $comment->restore();
        return response()->json(['message' => 'comment restored'], 200);
    }

    // Hard delete
    public function forceDelete($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $this->authorize('update', $comment);
        
        $comment-> forceDelete();

        return response()->json(['message' => 'comment permanently deleted'], 200);
    }
}
