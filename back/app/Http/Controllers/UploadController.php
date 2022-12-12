<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('file')){
            $file    = $request->file('file');
            $nombre     = time().".".$file->getClientOriginalExtension();
            $ruta=public_path('/imagenes/'.$nombre);
            Image::make($file->getRealPath())
                ->resize(300,300
                    ,function ($constraint){
                        $constraint->aspectRatio();
                    }
                )
                ->save($ruta,72);
        }
        return $nombre;
    }
}
