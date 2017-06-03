<?php

namespace Components\Vids\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
	public $timestamps = false;

	public function vids()
	{
		return $this->hasMany('Components\Vids\Models\Vids');
	}
}
