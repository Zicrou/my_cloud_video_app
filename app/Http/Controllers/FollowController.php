<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class FollowController extends Controller
{
	public function follow(Request $request, User $user)
	{
		$currentUser = $request->user();

		if ($currentUser->id === $user->id) {
		    
			return response()->json([
			
			'message' => 'Vous ne pouvez pas vous suivre vous-même.',
		    	
			], 422);
		}

		$currentUser->following()->syncWithoutDetaching([
		   
			 $user->id,
		
		]);

		return response()->json([
		
		    'message' => 'Utilisateur suivi avec succès.',
		
		    'following' => true,
		
		]);
	}

	public function unfollow(Request $request, User $user)
	{
		$currentUser = $request->user();

		$currentUser->following()->detach($user->id);

		return response()->json([
		    
			'message' => 'Utilisateur retiré des abonnements.',
		    
			'following' => false,
		
		]);

	}

	public function followers(User $user)
	{
	    $followers = $user->followers()
		->select('users.id', 'users.name')
		->orderBy('follows.created_at', 'desc')
		->get();

	    return response()->json($followers);
	}

	public function following(User $user)
	{
	    $following = $user->following()
		->select('users.id', 'users.name')
		->orderBy('follows.created_at', 'desc')
		->get();

	    return response()->json($following);
	}

	public function status(Request $request, User $user)
	{
	    $currentUser = $request->user();

	    $following = $currentUser
		->following()
		->where('users.id', $user->id)
		->exists();

	    return response()->json([
		'following' => $following,
	    ]);
	}
}
