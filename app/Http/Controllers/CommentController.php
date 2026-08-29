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
		return $video
			->comments()

			->with('user')

			->latest()	
		
			->get();
    	}

   	public function store(Request $request, Video $video)
   	{
				
		$validated = $request->validate([
			
			'comment' => ['required', 'string'],
   		 ]);
	
		$validated['user_id'] = $request->user()->id;


    		return  $comment = Comment::create([
   	  	        'video_id' => $video->id,
        		'user_id' => $request->user()->id,
        		'comment' => $validated['comment'],
    		]);

		return response()->json($comment, 201);		

    	}
}
