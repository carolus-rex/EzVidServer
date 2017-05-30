<?php

namespace Components\Vids\Listeners;

use Components\Vids\Events\VidsDisplayWasRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Storage;

use Components\Vids\Repositories\VidRepository;

use Symfony\Component\Process\Process;

class AddVids
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    private $vidRepository;

    public function __construct(VidRepository $vidRepository)
    {
        $this->vidRepository = $vidRepository;
    }

    /**
     * Handle the event.
     *
     * @param  VidsDisplayWasRequested  $event
     * @return void
     */
    public function handle(VidsDisplayWasRequested $event)
    {
        $ROOT = config('filesystems.disks.vids.root');
        $THUMBSIZE = 200;
        
        $vids_names = array_filter(Storage::disk('vids')->files(), function ($file)
        {
            return preg_match('/(\.mp4)$/', $file);
        });
        
        $db_vids_names = $this->vidRepository->get_all_names();
        $vids = array();
        
        foreach($vids_names as $name){
            $name = explode(".mp4", $name)[0];
            if (!$db_vids_names->contains($name)){
                // generate thumbnail
                $process = new Process('ffprobe -i "'.$name.'.mp4" -show_streams -print_format json',
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
                
                $process = new Process('ffmpeg -i "'.$name.'.mp4" -vf  "thumbnail,scale='.$thumbwidth.':'.$thumbheight.'" -frames:v 1 "thumbnails\\'.$name.'.png"',
                                       $ROOT);
                $process->run();
                
                $this->vidRepository->add($name);
            }
        }
    }
}
