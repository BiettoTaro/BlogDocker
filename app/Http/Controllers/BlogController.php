<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{

    public function __construct()
    {
        // Protect the store method so that only authenticated users can create a blog
        $this->middleware('auth')->only('store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with('author', 'comments')->get();
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
    // If no user is authenticated, immediately return a 401 response.
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $validated = $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    
    $blog = Auth::user()->blogs()->create($validated);

    return response()->json($blog, 201);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::with('author', 'comments')->findOrFail($id);
        return response()->json($blog);
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
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $blog->update($validated);

        return response()->json($blog);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json(['message' => 'Blog soft deleted'], 204);
    }

    public function restore($id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog->restore();
        return response()->json(['message' => 'Blog restored'], 200);
    }

    // Hard delete
    public function forceDelete($id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog-> forceDelete();

        return response()->json(['message' => 'Blog permanently deleted'], 204);
    }
}
