<?php

namespace Components\Vids\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\App;

use App\Http\Controllers\Controller;

use Components\Vids\Services\VidService;

class VidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	
    private $vidService;

	public function __construct(VidService $vidService){
		$this->vidService = $vidService;
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
		
		$response = $this->vidService->setfilter($chosen_filter,
												 $chosen_filter_val,
												 $filters,
												 $show_all,
												 $response);
		
		return $response;
	}
	
	public function index(Request $request, $page=NULL)
    {
		$vids = $this->vidService->index($request, $page);
		
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
    public function show($name)
    {	
        $vid = $this->vidService->show($name);
		
		return view("vids.show", ["vid" => $vid,
								  "name" => $vid->name,
								  "state" => $vid->state_id,
								  "vidpath" => Storage::disk('vids')->url("$name.mp4")]);
    }
	
	public function prev($name) {
		$prev_vid = $this->vidService->prev($name);
		
		return redirect()->route("vids.show", ['vid' => $prev_vid]);
	}
	
	public function next($name) {
		$next_vid = $this->vidService->next($name);
		
		return redirect()->route("vids.show", ['vid' => $next_vid]);
	}
	
	public function gomain(Request $request, $name){
		$page = $this->vidService->gomain($request, $name);

		return redirect()->route('vids.index', compact('page'));
	}

	public function vote(Request $request, $name) 
	{
		$this->vidService->vote($request, $name);

		return redirect()->route('vids.show', ['vid' => $name]);
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
    public function update($name)
    {
        $this->vidService->update($name);
		
		return redirect()->route('vids.next', compact('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
    	$this->vidService->destroy($name);
		
		return redirect()->route('vids.next', compact('name'));
    }
}
