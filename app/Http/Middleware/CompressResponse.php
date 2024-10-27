<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompressResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Compresser la réponse si elle est de type texte
        if ($response->headers->has('Content-Type') && strpos($response->headers->get('Content-Type'), 'text/') === 0) {
            $compressedContent = gzencode($response->getContent(), 9);
            $response->setContent($compressedContent);
            $response->headers->set('Content-Encoding', 'gzip');
            $response->headers->set('Content-Length', strlen($compressedContent));
        }
        
        return $response;
    }
}
