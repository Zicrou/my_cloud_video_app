<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use \App\Models\Like;

use \App\Models\Comment;

class Video extends Model
{
    protected $fillable = [

	'title',

	'url',

    ];

    public function likes()
    
    {

	return $this->hasMany(Like::class);
    
    }

    public function comments(){
	    
	    return $this->hasMany(Comment::class);

    }

	public function user()
	
	{
    	
		return $this->belongsTo(User::class);
	
	}

	public function comments()
	{
    	
		return $this->hasMany(Comment::class);
	
	}

}
