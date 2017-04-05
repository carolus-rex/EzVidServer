<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

use Symfony\Component\Process\Process;

use Illuminate\Database\Eloquent\Collection;

class VidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	
	private function get_all_vids(){
		return DB::table('estado_videos')->pluck('video');
	}
	
	private function get_vid_status($vid){
		return DB::table('estado_videos')->where('video', $vid)->value('estado');
	}
	
    private function update_vid_status($vid, $status){
		DB::table('estado_videos')->where('video', $vid)->update(['estado' => $status]);
	}
	
	private function add_new_vids(){
		$ROOT = config('filesystems.disks.vids.root');
		$THUMBSIZE = 200;
		
		$nombres_vids = array_filter(Storage::disk('vids')->files(),function ($file)
		{
			return preg_match('/(\.mp4)$/', $file);
		});
		
		$nombres_vids_db = $this->get_all_vids();
		$vids = array();
		
		foreach($nombres_vids as $nombre){
			$nombre = explode(".mp4", $nombre)[0];
			if (!$nombres_vids_db->contains($nombre)){
				// generate thumbnail
				$process = new Process('ffprobe -i '.$nombre.'.mp4 -show_streams -print_format json',
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
				
				$process = new Process('ffmpeg -i '.$nombre.'.mp4 -vf  "thumbnail,scale='.$thumbwidth.':'.$thumbheight.'" -frames:v 1 thumbnails\\'.$nombre.'.png',
									   $ROOT);
				$process->run();
				
				//add to db
				DB::table('estado_videos')->insert(['video' => $nombre]);
			}
		}
	}
	
	public function setfilter(Request $request, $from, $to=NULL){
		if ($from === 'fromindex') {
			$response = redirect()->route('vids.index');
		} else if ($from === 'fromshow'){
			$response = redirect()->route('vids.show', ['nombre' => $to]);
		} else {
			return view('vids.redirectionerror', compact($from, $to));
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
		
		$query = DB::table('estado_videos');
		
		if ($show_all === 'true') {
			// You don't have to do anything, you will query everything later
		} else {
			if ($show_unchecked === "true") {
				$query->orWhere("estado", VID_STATUS_UNCHECKED);
			}
			
			if ($show_checked === "true") {
				$query->orWhere("estado", VID_STATUS_CHECKED);
			}
			
			if ($show_aproved === "true") {
				$query->orWhere("estado", VID_STATUS_APROVED);
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
		$vids = $query->orderBy('video', 'asc')->paginate($ELEMENTS_PER_PAGE);
		
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
     * @param  str  $nombre
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $nombre)
    {	
        $estado = $this->get_vid_status($nombre);
		
		if ($estado == VID_STATUS_UNCHECKED)
			$this->update_vid_status($nombre, VID_STATUS_CHECKED);
		
		return view("vids.show", ["nombre" => $nombre,
								  "estado" => $estado,
								  "videopath" => "/".Storage::disk('vids')->url("$nombre.mp4")]);
    }
	
	public function prev($vid) {
		$prev_vid = DB::table('estado_videos')->where('video', '<' , $vid)
											  ->orderBy('video','desc')
											  ->value('video');
		
		return redirect()->route("vids.show", ['vid' => $prev_vid]);
	}
	
	public function next($vid) {
		$next_vid = DB::table('estado_videos')->where('video', '>' , $vid)
											  ->orderBy('video','asc')
											  ->value('video');
		
		return redirect()->route("vids.show", ['vid' => $next_vid]);
	}
	
	public function gomain(Request $request, $vid){
		$this->add_new_vids();

		$ELEMENTS_PER_PAGE = 40;
		
		$vids_after_me_included  = $this->query_vids_with_filters($request)->where('video', '<=' , $vid)
														 ->orderBy('video', 'asc')->count();
		
		$page = floor(($vids_after_me_included - 1)/ $ELEMENTS_PER_PAGE) + 1;
		return redirect()->route('vids.index', ['page' => $page]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  str  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nombre)
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
    public function update(Request $request, $nombre)
    {
        $this->update_vid_status($nombre, VID_STATUS_APROVED);
		
		return redirect()->action('VidController@next', ['vid' => $nombre]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($vid)
    {
		Storage::disk('vids')->delete("$vid.mp4");
		Storage::disk('vids')->delete("thumbnails/$vid.png");
		DB::table('estado_videos')->where('video', $vid)->delete();
		
		return redirect()->action('VidController@next', ['vid' => $vid]);
    }
}
