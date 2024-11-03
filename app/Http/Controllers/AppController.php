<?php

namespace App\Http\Controllers;

use App\Gridphp;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $g = Gridphp::get();

        $opt = array();
        $opt["caption"] = "App";
        $opt["height"] = "400";
        $opt["hidefirst"] = true;
        $g->set_options($opt);

        $g->table = "apps";     
                          
        $out = $g->render("list1");
        
        return view('app', [
            'grid' => $out
        ]);
    }
}

