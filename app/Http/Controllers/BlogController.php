<?php

namespace App\Http\Controllers;

use App\Events\BlogCreated;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    /**
     * Display the a certain blog.
     */
    public function show(Blog $blog): JsonResponse
    {
        $this->authorize('view', $blog);

        return response()->json($blog);
    }

    /**
     * Store a newly created blog .
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'domain' => ['required', 'url'],
        ]);

        if ($request->has('owner_id')) {
            return response()->json([
                'message' => 'The owner_id field cannot be set manually.',
            ], 422);
        }

        $blog = Blog::create([
            'name' => $validated['name'],
            'domain' => $validated['domain'],
            'owner_id' => $request->user()->id,
        ]);

        event(new BlogCreated($blog));

        return response()->json($blog, 201);
    }

    /**
     * Update the specified blog .
     */
    public function update(Request $request, Blog $blog): JsonResponse
    {
        $this->authorize('update', $blog);

        $validated = $request->validate([
            'name' => ['required', 'string'],
            'domain' => ['required', 'url'],
        ]);

        $blog->update($validated);

        return response()->json($blog);
    }

    /**
     * Remove the a selcted blog.
     */
    public function destroy(Blog $blog): JsonResponse
    {
        $this->authorize('delete', $blog);

        $blog->delete();

        return response()->json(null, 204);
    }
}
