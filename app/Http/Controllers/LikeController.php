<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use \App\Models\Like;

use \App\Models\Video;

class LikeController extends Controller
{
		
	public function toggle(Request $request, Video $video)
    	{

		$user = $request->user();

       		 $like = Like::where('video_id', $video->id)

           		->where('user_id', $user->id)
            	
			->first();
	
        	if ($like) {
        
		    $like->delete();
        
    		$liked = false;

        	} else {

        	    Like::create([

                	'video_id' => $video->id,

                	'user_id' => $user->id,

            		]);

            		$liked = true;

        	}

        	return response()->json([

        	    'liked' => $liked,

        	    'likes_count' => $video->likes()->count(),

        	]);

    	}


	public function show(Request $request, Video $video)
	{
    		$liked = Like::where('user_id', $request->user()->id)
       		
		 ->where('video_id', $video->id)
        	
		->exists();

		$likesCount = Like::where('video_id', $video->id)->count();

    		return response()->json([
        	
			'liked' => $liked,
    	
			'likes_count' => $likesCount,	
		]);
	}
}
