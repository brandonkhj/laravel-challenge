<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostReactionRequest;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function list(Request $request)
    {
        $request->validate([
            'perPage' => 'int|min:1',
        ]);

        $posts = Post::query()->with('likes', 'tags');

        return PostResource::collection(
            $posts->simplePaginate($request->input('perPage', 10))
        );
    }

    public function toggleReaction(PostReactionRequest $request)
    {
        $post = Post::find($request->post_id);

        if (! $post) {
            return response()->json([
                'status' => 404,
                'message' => 'model not found',
            ]);
        }

        $like = Like::query()
            ->where('post_id', $request->post_id)
            ->where('user_id', auth()->id());

        if ($like->exists() && $request->like === true) {
            return response()->json([
                'status' => 400,
                'message' => 'You already liked this post',
            ], 400);
        }

        if (! $like->exists() && $request->like === false) {
            return response()->json([
                'status' => 400,
                'message' => 'You already unlike this post',
            ], 400);
        }

        if ($request->like === false) {
            $like->delete();

            return response()->json([
                'status' => 200,
                'message' => 'You unlike this post successfully',
            ]);
        }

        Like::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'You like this post successfully',
        ]);
    }
}
