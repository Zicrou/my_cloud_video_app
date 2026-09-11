<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;

use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
	public function index()
	{
	    $videos = Video::with('user')
		->withCount(['likes', 'comments'])
		->latest()
		->get();

	    $videos->transform(function ($video) {
		$video->url = Storage::disk('s3')->temporaryUrl(
		    $video->url,
		    now()->addMinutes(30)
		);

		return $video;
	    });

	    return response()->json($videos);
	}

	public function store(Request $request)
	{
	    $request->validate([
		'title' => ['required', 'string', 'max:255'],
		'video' => [
		    'required',
		    'file',
		    'mimes:mp4,mov,avi,mkv,webm',
		    'max:102400',
		],
	    ]);

	    $path = $request->file('video')->store(
		'videos',
		's3'
	    );

	    $video = Video::create([
	   	'user_id' => $request->user()->id,
		'title' => $request->title,
		'url' => $path,
	    ]);

	    return response()->json(
		$video,
		201
	    );
	}
}
