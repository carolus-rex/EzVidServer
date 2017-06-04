<?php

namespace Components\Vids\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Components\Vids\Models\Vid;
use Components\Vids\Models\VidVote;

class VidRepository
{
	public function get($name)
	{
		return Vid::where('name', $name)->first();
	}

    public function add($name)
    {
        $vid = new Vid;
        $vid->name = $name;
        //TODO: add error handling
        $vid->save();
    }

	public function get_all_names() {
		return Vid::pluck('name');
	}
	
	public function get_state($name) {
		return Vid::where('name', $name)->value('state_id');
	}
	
    public function update_state($name, $state_id) {
    	//TODO: Add error handling
		Vid::where('name', $name)->update(compact('state_id'));
	}

	public function get_prev($name) {
		$prev = Vid::where('name', '<' , $name)
				     ->orderBy('name','desc')
					 ->value('name');

		return $prev;
	}

	public function get_next($name) {
		$next = Vid::where('name', '>' , $name)
					 ->orderBy('name','asc')
					 ->value('name');

		return $next;
	}

	public function delete($name) {
		Vid::where('name', $name)->delete();
	}

	public function query_with_filters($show_all, $show_unchecked,
									   $show_checked, $show_aproved) {
		$query = (new Vid)->newQuery();
		
		if ($show_all === 'true') {
			// You don't have to do anything, you will query everything later
		} else {
			if ($show_unchecked === "true") {
				$query->orWhere("state_id", VID_STATE_UNCHECKED);
			}
			
			if ($show_checked === "true") {
				$query->orWhere("state_id", VID_STATE_CHECKED);
			}
			
			if ($show_aproved === "true") {
				$query->orWhere("state_id", VID_STATE_APROVED);
			}
		}
		return $query;
	}

	public function vote($name, $vote)
	{
		$vid_vote = new VidVote;

		$vid_vote->vid_id = $this->get($name)->id;
		$vid_vote->user_id = Auth::user()->id;
		$vid_vote->should_keep = $vote;

		$vid_vote->save();
	}
}
