<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;


use App\Models\Comment;


class CommentController extends Controller
{
	
	public function index($videoId)
	{
	
		$video = Video::find($videoId);
		
		$comments = $video->comments()
			
			->whereNull('parent_id')
			
			->with(['user','replies.user',])

			->latest()
			->get();

		return response()->json($comments);	
    	}

   	public function store(Request $request, Video $video)
   	
	{
				
		$validated = $request->validate([	
			
			'comment' => ['required', 'string'],
			
			'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
   		
		 ]);
	
		$validated['user_id'] = $request->user()->id;


		$parentId = $validated['parent_id'] ?? null;

		if ($parentId !== null) {

			$parentComment = Comment::findOrFail($parentId);

			if ($parentComment->video_id !== $video->id) {

				return response()->json([
		
					'message' => 'La réponse doit appartenir à la même vidéo.',
				], 422);
			}
		
		}

    		return  $comment = Comment::create([
   	  	
		        'video_id' => $video->id,
        	
			'user_id' => $request->user()->id,
        	
			'comment' => $validated['comment'],
    	
			'parent_id' => $validated['parent_id'] ?? null,	
		
		]);

		return response()->json($comment, 201);		

    	}

	public function destroy(Request $request, Comment $comment)
	{
    		
		if ($comment->user_id !== $request->user()->id) {
        	
			return response()->json([
		
			    'message' => 'Vous ne pouvez pas supprimer ce commentaire.',
			
			], 403);

		}

    		$comment->delete();

   
		 return response()->json([
        	
			'message' => 'Commentaire supprimé.',
    		]);
	}

}
