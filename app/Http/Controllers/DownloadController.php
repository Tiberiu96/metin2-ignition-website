<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    public function client(): RedirectResponse|Response
    {
        $url = config('services.downloads.client_url');

        if (! $url) {
            abort(503, 'Download not available yet.');
        }

        return redirect()->away($url);
    }

    public function patch(): RedirectResponse|Response
    {
        $url = config('services.downloads.patch_url');

        if (! $url) {
            abort(503, 'Download not available yet.');
        }

        return redirect()->away($url);
    }
}
