<?php

namespace Components\Vids\Repositories;

use Illuminate\Support\Facades\DB;

class VidRepository
{
    public function add($name)
    {
        DB::table('vids')->insert(compact('name'));
    }

	public function get_all_names() {
		return DB::table('vids')->pluck('name');
	}
	
	public function get_state($name) {
		return DB::table('vids')->where('name', $name)->value('state');
	}
	
    public function update_state($name, $state) {
		DB::table('vids')->where('name', $name)->update(compact('state'));
	}

	public function get_prev($name) {
		$prev = DB::table('vids')->where('name', '<' , $name)
								 ->orderBy('name','desc')
								 ->value('name');

		return $prev;
	}

	public function get_next($name) {
		$next = DB::table('vids')->where('name', '>' , $name)
									 ->orderBy('name','asc')
									 ->value('name');

		return $next;
	}

	public function delete($name) {
		DB::table('vids')->where('name', $name)->delete();
	}

	public function query_with_filters($show_all, $show_unchecked,
									   $show_checked, $show_aproved) {
		$query = DB::table('vids');
		
		if ($show_all === 'true') {
			// You don't have to do anything, you will query everything later
		} else {
			if ($show_unchecked === "true") {
				$query->orWhere("state", VID_STATE_UNCHECKED);
			}
			
			if ($show_checked === "true") {
				$query->orWhere("state", VID_STATE_CHECKED);
			}
			
			if ($show_aproved === "true") {
				$query->orWhere("state", VID_STATE_APROVED);
			}
		}

		return $query;
	}

}
