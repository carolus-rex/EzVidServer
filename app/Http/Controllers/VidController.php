<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\App;

use Symfony\Component\Process\Process;

use Illuminate\Database\Eloquent\Collection;

class VidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	
	private function get_all_vids_names(){
		return DB::table('vids')->pluck('name');
	}
	
	private function get_vid_state($name){
		return DB::table('vids')->where('name', $name)->value('state');
	}
	
    private function update_vid_state($name, $state){
		DB::table('vids')->where('name', $name)->update(compact('state'));
	}
	
	private function add_new_vids(){
		$ROOT = config('filesystems.disks.vids.root');
		$THUMBSIZE = 200;
		
		$vids_names = array_filter(Storage::disk('vids')->files(), function ($file)
		{
			return preg_match('/(\.mp4)$/', $file);
		});
		
		$db_vids_names = $this->get_all_vids_names();
		$vids = array();
		
		foreach($vids_names as $name){
			$name = explode(".mp4", $name)[0];
			if (!$db_vids_names->contains($name)){
				// generate thumbnail
				$process = new Process('ffprobe -i '.$name.'.mp4 -show_streams -print_format json',
									   $ROOT);
				$process->run();
				
				$probejson = json_decode($process->getOutput(), true);
				$width = $probejson["streams"][0]["width"];
				$height = $probejson["streams"][0]["height"];
				$thumbwidth = $THUMBSIZE;
				$thumbheight = $THUMBSIZE;
				if ($width > $height)
					$thumbheight = $height * ($THUMBSIZE / $width);
				else
					$thumbwidth = $width * ($THUMBSIZE / $height);
				set_time_limit(30);
				
				$process = new Process('ffmpeg -i '.$name.'.mp4 -vf  "thumbnail,scale='.$thumbwidth.':'.$thumbheight.'" -frames:v 1 thumbnails\\'.$name.'.png',
									   $ROOT);
				$process->run();
				
				//add to db
				dump(DB::table('vids')->insert(compact('name')));
			}
		}
	}
	
	public function setfilter(Request $request, $from, $to=NULL){
		if ($from === 'fromindex') {
			$response = redirect()->route('vids.index');
		} else if ($from === 'fromshow'){
			$response = redirect()->route('vids.show', ['name' => $to]);
		} else {
			return view('vids.redirectionerror', compact('from', 'to'));
		}
	
		if ($request->has('unchecked')){
			$chosen_filter = 'show_unchecked';
		} else if ($request->has('checked')){
			$chosen_filter = 'show_checked';
		} else if ($request->has('aproved')) {
			$chosen_filter = 'show_aproved';
		} else if ($request->has('all')){
			$chosen_filter = 'show_all';
		} else {
			return "Don't hack me please D:";
		}
		
		$filters = ['show_unchecked' => $request->cookie("show_unchecked", "false"),
					'show_checked' => $request->cookie("show_checked", "false"),
					'show_aproved' => $request->cookie("show_aproved", "false")];
		
		$chosen_filter_val = $request->cookie($chosen_filter, "false"); //it will change
		
		$show_all = $request->cookie("show_all", "true");
		
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
	
	private function query_vids_with_filters($request)
	{
		$show_unchecked = $request->cookie('show_unchecked', "false");
		$show_checked = $request->cookie("show_checked", "false");
		$show_aproved = $request->cookie("show_aproved", "false");
		$show_all = $request->cookie("show_all", "true");
		
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
	
	public function index(Request $request, $page = NULL)
    {
		$this->add_new_vids();
		
		$ELEMENTS_PER_PAGE = 40;
		
		$query = $this->query_vids_with_filters($request);
		
		// Lets order the query alphabetically for now
		// maybe in the future we choose to order it by date or dynamically
		// chosen by the user
		$vids = $query->orderBy('name', 'asc')->paginate($ELEMENTS_PER_PAGE);
		
		return view("vids.index", ["vids" => $vids,
								   "thumbs_url" => Storage::disk("vids")->url("thumbnails")]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $name)
    {	
        $state = $this->get_vid_state($name);
		
		if ($state == VID_STATE_UNCHECKED)
			$this->update_vid_state($name, VID_STATE_CHECKED);
		
		return view("vids.show", ["name" => $name,
								  "state" => $state,
								  "vidpath" => Storage::disk('vids')->url("$name.mp4")]);
    }
	
	public function prev($name) {
		$prev_vid = DB::table('vids')->where('name', '<' , $name)
		
		return redirect()->route("vids.show", ['vid' => $prev_vid]);
	}
	
	public function next($name) {
		$next_vid = DB::table('vids')->where('name', '>' , $name)
		
		return redirect()->route("vids.show", ['vid' => $next_vid]);
	}
	
	public function gomain(Request $request, $name){
		$this->add_new_vids();

		$ELEMENTS_PER_PAGE = 40;
		
		$vids_after_me_included  = $this->query_vids_with_filters($request)->where('name', '<=' , $name)
														 ->orderBy('name', 'asc')->count();
		
		$page = floor(($vids_after_me_included - 1)/ $ELEMENTS_PER_PAGE) + 1;
		return redirect()->route('vids.index', compact('page'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  str  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        $this->update_vid_state($name, VID_STATE_APROVED);
		
		return redirect()->action('VidController@next', compact('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
		Storage::disk('vids')->delete("$name.mp4");
		Storage::disk('vids')->delete("thumbnails/$name.png");
		DB::table('vids')->where('name', $name)->delete();
		
		return redirect()->action('VidController@next', compact('name'));
    }
}
