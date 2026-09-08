<?php

use Illuminate\Support\Facades\Route;

use App\Models\Video;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/videos/{video}', function (Video $video) {
    return view('videos.show', compact('video'));
})->name('videos.show');
