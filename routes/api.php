<?php

	use Illuminate\Support\Facades\Route;

	use Illuminate\Http\Request;	

	use App\Models\Video;

	use App\Http\Controllers\VideoController;

	use App\Http\Controllers\LikeController;

	use App\Http\Controllers\CommentController;

	use App\Http\Controllers\AuthController;

	Route::get('/test', function () {

    		return response()->json([

        		'message' => 'API is working 🚀',

		]);

	});

	Route::post('/register', [AuthController::class, 'register']);

	Route::post('/login', [AuthController::class, 'login']);

	Route::get('/videos', [VideoController::class, 'index']);

	Route::middleware('auth:sanctum')->group(function () {

		Route::post('/videos/{video}/likes', [LikeController::class, 'toggle']);
		
		Route::post('/logout', [AuthController::class, 'logout']);
		
		Route::post('videos/{video}/comments', [CommentController::class, 'store']);
	
		Route::get('/user', function (Request $request) {

        		return $request->user();

    		});

		Route::get('/videos/{video}/likes', [LikeController::class, 'show']);

	});

	Route::get('/videos', function () {

    		return \App\Models\Video::withCount('likes')->withCount('comments')->get();

	});

	
	Route::get('/videos/{video}/comments', [CommentController::class, 'index']);
	

