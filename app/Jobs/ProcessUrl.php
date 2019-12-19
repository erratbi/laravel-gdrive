<?php

namespace App\Jobs;

use App\Events\DownloadDoneEvent;
use App\Events\DownloadErrorEvent;
use App\Events\DownloadProgressEvent;
use App\Events\DownloadStartedEvent;
use App\Events\UploadStartedEvent;
use App\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProcessUrl implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    /**
     * Create a new job instance.
     *
     * @param Url $url
     */
    public function __construct(Url $url) {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        event(new DownloadStartedEvent($this->url));

        $remote_file = fopen($this->url->link, 'rb');

        if (!$remote_file) {
            event(new DownloadErrorEvent($this->url, new \Exception("can't open remote file")));
            return null;
        }

        $local_file = fopen(storage_path($this->url->name), 'wb');
        if (!$local_file) {
            event(new DownloadErrorEvent($this->url, new \Exception("can't open local file")));
            return null;
        }

        $buffer = min(1024 * 8, $this->url->size);
        $received = 0;

        try {
            while (!feof($remote_file)) {
                $received += $buffer;
                $progress = min(floor($received * 10000 / $this->url->size) / 100, 100);
                fwrite($local_file, fread($remote_file, $buffer), $buffer);
                $this->url->update(['progress' => $progress]);
                event(new DownloadProgressEvent($this->url));
            }

            fclose($local_file);
            event(new UploadStartedEvent($this->url));
            $file = File::get(storage_path($this->url->name));
            Storage::disk('google')->put($this->url->name, $file);
            unlink(storage_path($this->url->name));
            event(new DownloadDoneEvent($this->url));
        } catch(\Exception $e) {
            event(new DownloadErrorEvent($this->url, $e));
        }

    }
}
