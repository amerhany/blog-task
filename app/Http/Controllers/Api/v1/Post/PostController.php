<?php

namespace App\Http\Controllers\Api\v1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Post\PostStoreRequest;
use App\Http\Requests\Api\v1\Post\PostUpdateRequest;
use App\Http\Resources\Api\v1\post\PostShowResource;
use App\Models\Post;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $query = Post::query();

            if (request()->has('category')) {
                $query->where('category', request('category'));
            }

            if (request()->has('author')) {
                $query->where('user_id', request('author'));
            }

            if (request()->has('start_date') && request()->has('end_date')) {
                $query->whereBetween('created_at', [request('start_date'), request('end_date')]);
            }

            $posts = $query->paginate(10);

            return $this->successResponse($posts, 'Posts retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve posts.', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        try {
            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'category' => $request->category,
                'user_id' => auth()->id(),
            ]);

            return $this->successResponse($post, 'Post created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create post.', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::with('user')->find($id);

            if (!$post) {
                return $this->errorResponse('Post not found.', 404);
            }

            return $this->successResponse(new PostShowResource($post), 'Post retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve post.', 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, string $id)
    {
        try {
            $post = Post::findOrFail($id);

            if (auth()->id() !== $post->user_id && auth()->user()->group_id !== 1) {
                return $this->errorResponse('Unauthorized to update this post.', 403);
            }

            $data = $request->only(['title', 'content', 'category']);

            $post->update($data);


            return $this->successResponse($post, 'Post updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update post.', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::findOrFail($id);

            if (auth()->id() !== $post->user_id && auth()->user()->group_id !== 1) {
                return $this->errorResponse('Unauthorized to delete this post.', 403);
            }

            $post->delete();

            return $this->successResponse(null, 'Post deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete post.', 500, $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->query('query');


        $postsQuery = Post::query();

        if ($request->has('category')) {
            $postsQuery->where('category', $request->category);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $postsQuery->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('author')) {
            $postsQuery->where('user_id', $request->author);
        }

        $postsQuery->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%");
        });

        $posts = $postsQuery->paginate(10);

        return $this->successResponse($posts, 'Posts retrieved successfully.');
    }
}
