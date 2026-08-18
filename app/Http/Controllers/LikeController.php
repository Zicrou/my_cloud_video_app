<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use \App\Models\Like;

use \App\Models\Video;

class LikeController extends Controller
{
		
	public function toggle(Request $request)
    	{
        	$validated = $request->validate([
			'video_id' => ['required', 'exists:videos,id'],
        	]);

		$user = $request->user();

       		 $like = Like::where('video_id', $validated['video_id'])
           		->where('user_id', $user->id)
            	
			->first();
	
        	if ($like) {
        	    $like->delete();

            		$liked = false;
        	} else {
        	    Like::create([
                	'video_id' => $validated['video_id'],
                	'user_id' => $user->id,
            		]);

            		$liked = true;
        	}
		
		$video = Video::find($validated['video_id']);
        	return response()->json([
        	    'liked' => $liked,
        	    'likes_count' => $video->likes()->count(),
        	]);
    	}

	public function destroy(Video $video, Request $request)
    	{		
        	Like::where('video_id', $video->id)
            		->where('user_id', $request->user_id)
            		->delete();

        	return response()->json([
            		'message' => 'Video unliked',
        	]);
   	 }


	public function count(Video $video)
    	{	
        	return response()->json([
            		'video_id' => $video->id,
            		'likes_count' => $video->likes()->count(),
       		 ]);
   	 }
}
