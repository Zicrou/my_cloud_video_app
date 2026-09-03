<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable = [

		'user_id',

		'video_id',

		'comment',

		'parent_id',
	];

	public function video()
    	{
        	
		return $this->belongsTo(Video::class);
       
	}

	public function user()
    	{
        	return $this->belongsTo(User::class);
    	}

	public function parent()

	{

	    return $this->belongsTo(Comment::class, 'parent_id');

	}

	public function replies()

	{

	    return $this->hasMany(Comment::class, 'parent_id');

	}	

}
