<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImovelImageController extends Controller
{
    /**
     * Serve the unified property featured image dynamically.
     *
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function serve($slug)
    {
        $path = public_path('images/imovel-destaque.jpg');
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
