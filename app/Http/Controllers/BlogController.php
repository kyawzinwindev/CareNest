<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     */
    public function index()
    {
        $blogs = Blog::with('user')
            ->latest()
            ->paginate(6);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Display the specified blog.
     */
    public function show(string $slug)
    {
        $blog = Blog::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('blogs.show', compact('blog'));
    }
}
