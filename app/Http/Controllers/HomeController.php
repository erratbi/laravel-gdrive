<?php

namespace App\Http\Controllers;

use App\Events\DownloadStartedEvent;
use App\Jobs\ProcessUrl;
use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller {

    public function index() {
        $urls = Url::whereIn('status', ['PENDING', 'NOT STARTED'])->get();
        return view('welcome', compact('urls'));
    }

    public function upload(Request $request) {
        $urls = $request->post('urls');

        $models = [];

        foreach ($urls as $text_url) {
            if (!$text_url) continue;

            $name = urldecode(basename($text_url));
            $headers = array_change_key_case(get_headers($text_url, 1));

            if (!stripos($headers[0], '200 OK')) continue;

            $size = $headers['content-length'];

            $url = Url::create([
                'name' => $name,
                'size' => $size,
                'link' => $text_url,
            ]);

            $url = $url->refresh();

            array_push($models, $url);

            ProcessUrl::dispatch($url);
        }

        return response()->json(['urls' => $models]);
    }
}
