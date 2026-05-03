<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function show($id)
    {
        $album = Album::with('images')->findOrFail($id);
        return view('albums.show', compact('album'));
    }
}
