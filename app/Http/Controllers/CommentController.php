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

   	public function store(Request $request)
   	{
		
		$validated = $request->validate([
			
			'video_id' => ['required', 'exists:videos,id'],
        		
        		
			'user_id' => ['required'],

			
			'comment' => ['required', 'string'],
   		 ]);


    		return  Comment::create($validated);
			
    	}
}
