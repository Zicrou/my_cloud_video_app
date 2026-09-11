<?php

	use Illuminate\Support\Facades\Route;

	use Illuminate\Http\Request;	

	use App\Models\Video;

	use App\Http\Controllers\VideoController;

	use App\Http\Controllers\LikeController;

	use App\Http\Controllers\CommentController;

	use App\Http\Controllers\AuthController;

	use App\Http\Controllers\FollowController;

	use App\Http\Controllers\UserController;


	Route::get('/test', function () {

    		return response()->json([

        		'message' => 'API is working 🚀',

		]);

	});

	Route::post('/register', [AuthController::class, 'register']);

	Route::post('/login', [AuthController::class, 'login']);

	Route::get('/videos', [VideoController::class, 'index']);

	Route::middleware('auth:sanctum')->group(function () {
		
			// Videos
		Route::post('/videos/{video}/likes', [LikeController::class, 'toggle']);
		
		Route::post('/logout', [AuthController::class, 'logout']);
		
		Route::post('videos/{video}/comments', [CommentController::class, 'store']);
	
		Route::get('/users/{user}/videos', [UserController::class, 'videos']);

		Route::get('/videos/{video}/likes', [LikeController::class, 'show']);
		Route::post('/videos', [VideoController::class, 'store']);
		
			// Comments
		Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
		
			// Follow
		Route::post('/users/{user}/follow', [FollowController::class, 'follow']);
    		
		Route::delete('/users/{user}/follow', [FollowController::class, 'unfollow']);

		Route::get('/users/{user}/followers',[FollowController::class, 'followers']);

    		Route::get('/users/{user}/following', [FollowController::class, 'following']);
		
		Route::get('/users/{user}/follow-status',[FollowController::class, 'status']);
		
			// Users

		Route::get('/users/{user}', [UserController::class, "show"]);

		Route::get('/user', function (Request $request) {return $request->user();});
	});

	Route::get('/videos/{video}/comments', [CommentController::class, 'index']);
	
