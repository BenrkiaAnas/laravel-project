<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function index()
    {
        $title = "Welcome To Laravel";
        return view('pages.index',compact('title'));
    }
    public function about()
    {
        $about = "About Us";
        return view('pages.about')->with('about',$about);
    }
    public function services()
    {
        $data = array(
            'title' => "Welcome To Services",
            'services' => ['Web design','Web developer','SEO']
        );
        return view('pages.service')->with($data);
    }
}
