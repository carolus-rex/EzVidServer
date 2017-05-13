<?php

namespace Components\Vids\Services;

use Illuminate\Support\Facades\Storage;

use Components\Vids\Repositories\VidRepository;

use Components\Vids\Events\VidsDisplayWasRequested;

class VidService
{

	private $vidRepository;

	public function __construct(VidRepository $vidRepository) {
		$this->vidRepository = $vidRepository;
	}

	public function setfilter($chosen_filter, $chosen_filter_val,
							  $filters, $show_all, $response) {

		if ($chosen_filter != 'show_all') {
			$filters[$chosen_filter] = $chosen_filter_val === 'true' ? 'false' : 'true';
			
			//see if we have all the filters selected or none
			$sum = 0;
			foreach($filters as $filter){
				$filter === 'true' ? $sum += 1 : $sum += 0; 
			}
			
			// if all or none filters selected 
			// set all their cookies to "false" and the show_all cookie to "true"
			if ($sum == 0 || $sum == 3) {
				$response->cookie("show_all", "true", 60 * 60 * 24 * 30);
				
				foreach($filters as $filter => $filter_value) {
					$response->cookie($filter, "false", 60 * 60 * 24 * 30);
				}
				
			} else {
				// Switch my cookie value
				// Remember that you already switched my value in the $filters array
				$response->cookie($chosen_filter, $filters[$chosen_filter]);
				
				// If show_all "true" set its cookie to "false"
				if ($show_all === "true")
					$response->cookie("show_all", "false", 60 * 60 * 24 * 30);
			}
		} else {
			// if show_all filter was selected
			// check if it's already set to "true"
			// If so, we shall ignore it 
			if ($show_all === "true") { // Maybe we shall use js to make it more "pretty"
				// CHANGE THIS TO STAY IN THE SAME PAGE
				return $response; //DONT MOVE THIS UNTIL CHANGING IT
			} else {
				//if not, we shall set all the other filters' cookies to "false" and mine to "true"
				$response->cookie("show_all", "true", 60 * 60 * 24 * 30);
				
				foreach($filters as $filter => $filter_value) {
					$response->cookie($filter, "false", 60 * 60 * 24 * 30);
				}
			}
		}

		return $response;
	}

	private function query_with_filters($request)
	{
		$show_unchecked = $request->cookie('show_unchecked', "false");
		$show_checked = $request->cookie("show_checked", "false");
		$show_aproved = $request->cookie("show_aproved", "false");
		$show_all = $request->cookie("show_all", "true");
	
		return $this->vidRepository->query_with_filters($show_all, $show_unchecked,
														$show_checked, $show_aproved);
	}

	public function index($request) {

		event(new VidsDisplayWasRequested);
		
		$ELEMENTS_PER_PAGE = 8;
		
		$query = $this->query_with_filters($request);
		
		// Lets order the query alphabetically for now
		// maybe in the future we choose to order it by date or dynamically
		// chosen by the user
		return $query->orderBy('name', 'asc')->paginate($ELEMENTS_PER_PAGE);
	}

	public function show($name) {
		$vid = $this->vidRepository->get($name);
		
		if ($vid->state == VID_STATE_UNCHECKED)
			$this->vidRepository->update_state($name, VID_STATE_CHECKED);

		return $vid;
	}

	public function prev($name) {
		return $this->vidRepository->get_prev($name);
	}

	public function next($name) {
		return $this->vidRepository->get_next($name);
	}

	public function gomain($request, $name) {
		event(new VidsDisplayWasRequested);

		$ELEMENTS_PER_PAGE = 8;
		
		$vids_after_me_included = $this->query_with_filters($request)->where('name', '<=' , $name)
								   					 			     ->orderBy('name', 'asc')
								   					 			     ->count();
		
		return floor(($vids_after_me_included - 1)/ $ELEMENTS_PER_PAGE) + 1;
	}

	public function vote($request, $name)
	{
		if($request->has('KEEP')) {
			$vote = true;
		} else if($request->has('NOTKEEP')) {
			$vote = false;
		} else {
			return 'please dont hack me D:';
		}
		
		$this->vidRepository->vote($name, $vote);
	}

	public function update($name) {
		$this->vidRepository->update_state($name, VID_STATE_APROVED);		
	}

	public function destroy($name) {
		Storage::disk('vids')->delete("$name.mp4");
		Storage::disk('vids')->delete("thumbnails/$name.png");
		$this->vidRepository->delete($name);
	}
}
