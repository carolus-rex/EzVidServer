<?php

namespace Components\Vids\Jobs;

use Components\Vids\Repositories\VidRepository;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Storage;

use Symfony\Component\Process\Process;


class AddVids implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $vidRepository;

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(VidRepository $vidRepository)
    {
        $start_time = time();

        $ROOT = config('filesystems.disks.vids.root');
        $THUMBSIZE = 200;
        
        $vids_names = array_filter(Storage::disk('vids')->files(), function ($file)
        {
            return preg_match('/(\.mp4)$/', $file);
        });
        
        $db_vids_names = $vidRepository->get_all_names();

        $vids = array();
        
        foreach($vids_names as $name){
            $name = explode(".mp4", $name)[0];
            if (!$db_vids_names->contains($name)){
                dump("Encoding $name");
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

                $process = new Process('ffmpeg -i "'.$name.'.mp4" -vf  "thumbnail,scale='.$thumbwidth.':'.$thumbheight.'" -frames:v 1 "thumbnails\\'.$name.'.png"',
                                       $ROOT);
                $process->run();
                
                $vidRepository->add($name);

                // Easy fix for timeout incrementation instead of set_time_limit wich is ignored by symfony process
                if (time() - $start_time >= 55) {
                    dump(__("Demasiados videos. Se ha creado un nuevo job"));
                    dispatch(new AddVids());
                    break;
                }
            }
        }
    }
}
