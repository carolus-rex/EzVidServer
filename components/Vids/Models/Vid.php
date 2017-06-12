<?php

namespace Components\Vids\Models;

use Illuminate\Database\Eloquent\Model;

class Vid extends Model
{

	public $timestamps = false;

	public function votes()
	{
		return $this->hasMany('Components\Vids\Models\VidVote');
	}

	public function state()
	{
		return $this->belongsTo('Components\Vids\Models\State');
	}
}
