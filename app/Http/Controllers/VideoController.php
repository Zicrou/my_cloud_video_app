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

	public function destroy(Video $video)
	{
	    if ($video->user_id !== auth()->id()) {
		return response()->json([
		    'message' => 'Unauthorized',
		], 403);
	    }

	    if (
		$video->url &&
		Storage::disk('s3')->exists($video->url)
	    ) {
		Storage::disk('s3')->delete($video->url);
	    }

	    $video->delete();

	    return response()->json([
		'message' => 'Video deleted successfully',
	    ]);
	}

	public function update(Request $request, Video $video)
	{
	    if ($video->user_id !== auth()->id()) {
		return response()->json([
		    'message' => 'Unauthorized',
		], 403);
	    }

	    $request->validate([
		'title' => ['sometimes', 'string', 'max:255'],
		'video' => [
		    'sometimes',
		    'file',
		    'mimes:mp4,mov,avi,mkv,webm',
		    'max:102400',
		],
	    ]);

	    if ($request->has('title')) {
		$video->title = $request->title;
	    }

	    if ($request->hasFile('video')) {
		$oldPath = $video->url;

		$newPath = $request->file('video')->store(
		    'videos',
		    's3'
		);

		$video->url = $newPath;

		if (
		    $oldPath &&
		    Storage::disk('s3')->exists($oldPath)
		) {
		    Storage::disk('s3')->delete($oldPath);
		}
	    }

	    $video->save();

	    return response()->json($video);
	}
	
}
