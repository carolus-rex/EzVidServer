<?php

namespace Components\Vids\Models;

use Illuminate\Database\Eloquent\Model;

class Vid extends Model
{
	public function votes()
	{
		return $this->hasMany('Components\Vids\Models\VidVote');
	}
}
