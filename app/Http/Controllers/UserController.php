<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {

	$currentUser = $request->user();

        $user->loadCount([
            'followers',
            'following',
        ]);

        $isFollowing = $currentUser
		->following()
		->where('users.id', $user->id)
		->exists();

	return response()->json([
		'id' => $user->id,
		'name' => $user->name,
		'followers_count' => $user->followers_count,
		'following_count' => $user->following_count,
		'is_following' => $isFollowing,
		'is_me' => $currentUser->id === $user->id,
	]);
    }

	public function videos(User $user)
	{
	    $videos = $user->videos()
		->withCount(['likes', 'comments'])
		->latest()
		->get();

	    return response()->json($videos);
	}
}
