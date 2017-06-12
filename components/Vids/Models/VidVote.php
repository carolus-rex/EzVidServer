<?php

namespace Components\Vids\Models;

use Illuminate\Database\Eloquent\Model;

class VidVote extends Model
{
	// Make sure we use the right table
	protected $table = 'vids_votes';

	public $timestamps = false;

	public function vid()
	{
		return $this->belongsTo('Components\Vids\Models\Vid');
	}

	public function user()
	{
		return $this->belongsTo('Components\Users\Models\User');
	}
}
