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
	

		$user = $request->user();

       		 $like = Like::where('video_id', $request->videoId
)
           		->where('user_id', $user->id)
            	
			->first();
	
        	if ($like) {
        
		    $like->delete();
        
    		$liked = false;

        	} else {

        	    Like::create([

                	'video_id' => $request->videoId,

                	'user_id' => $user->id,

            		]);

            		$liked = true;

        	}
		
		$video = Video::find($request->videoId);

        	return response()->json([

        	    'liked' => $liked,

        	    'likes_count' => $video->likes()->count(),

        	]);

    	}



	public function count(Video $video)
    	{	
        	return response()->json([
            		'video_id' => $video->id,
            		'likes_count' => $video->likes()->count(),
       		 ]);
   	 }

	public function show(Request $request, Video $video)
	{
    		$liked = Like::where('user_id', $request->user()->id)
       		
		 ->where('video_id', $video->id)
        	
		->exists();

    		return response()->json([
        	
			'liked' => $liked,
    		
		]);
	}
}
