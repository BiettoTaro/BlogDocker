<?php

namespace App\Http\Controllers;

use Orion\Http\Requests\Request;
use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Orion\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;



class OrionBlogController extends Controller
{
    protected $model = Blog::class;

    public function index(Request $request): JsonResponse
    {
        $blogs = Blog::latest()->paginate(10);

        return response()->json($blogs);
    }

}
