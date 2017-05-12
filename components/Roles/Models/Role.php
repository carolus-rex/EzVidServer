<?php

namespace Components\Roles\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public function users()
	{
		return $this->hasMany('Components\Users\Models\User');
	}
}
