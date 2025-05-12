<?php

namespace App\Http\Controllers\Api\v1\Comment;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post = Post::find($postId);

        if (!$post) {
            return $this->errorResponse('Post not found.', 404);
        }

        $comment = auth()->user()->comments()->create([
            'content' => $request->content,
            'post_id' => $postId,
        ]);

        try {
            return $this->successResponse($comment,'Comment created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create comment.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $postId, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post = Post::find($postId);

        if (!$post) {
            return $this->errorResponse('Post not found.', 404);
        }

        if (!$comment = $post->comments()->find($id)) {
            return $this->errorResponse('Comment not found.', 404);
        }

        $comment = auth()->user()->comments()->find($id);

        if (!$comment) {
            return $this->errorResponse('Comment not found.', 404);
        }


        try {
            $comment->update([
                'content' => $request->content,
            ]);
            return $this->successResponse($comment,'Comment updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update comment.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($postId, $id)
    {
        $post = Post::find($postId);

        if (!$post) {
            return $this->errorResponse('Post not found.', 404);
        }

        if (!$comment = $post->comments()->find($id)) {
            return $this->errorResponse('Comment not found.', 404);
        }

        $comment = auth()->user()->comments()->find($id);

        if (!$comment) {
            return $this->errorResponse('Comment not found.', 404);
        }

        try {
            $comment->delete();
            return $this->successResponse([],'Comment deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete comment.', 500);
        }
    }
}
