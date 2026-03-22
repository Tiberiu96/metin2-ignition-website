<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableLivewireAssets
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('ishop/*')) {
            return $next($request);
        }

        config()->set('livewire.inject_assets', false);
        config()->set('boost.browser_logger', false);

        $response = $next($request);

        if ($response instanceof \Illuminate\Http\Response && $response->getContent()) {
            $content = $response->getContent();
            $content = preg_replace('/<script[^>]*>.*?<\/script>\s*/s', '', $content);
            $response->setContent($content);
        }

        return $response;
    }
}
