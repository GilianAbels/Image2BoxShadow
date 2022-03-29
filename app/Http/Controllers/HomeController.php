<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Utils\ImageToBoxShadow;

class HomeController extends Controller {
    public function index(Request $request) {
        return view('pages.Home');
    }

    public function upload(Request $request) {
        if(!$request->hasFile('image')) { return abort(402); }
        $quality = ($request->quality > 0 && $request->quality < 25) ? $request->quality : 5;

        $validatedFile = $request->validate([
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=2200,max_height=2200',
        ]);            
        
        if(!$validatedFile) {  return abort(403); }

        // Start generation process
        $ImageToBoxShadow = new ImageToBoxShadow();
        $ImageToBoxShadow->setQuality($quality);
        $ImageToBoxShadow->addImageContent($request->file('image')->getContent());
        $ImageProcessed = $ImageToBoxShadow->generate();
        $ImageProcessed = (is_array($ImageProcessed)) ? json_encode($ImageProcessed) : $ImageProcessed;
        return response($ImageProcessed, 200);

    }
}