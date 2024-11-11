<?php

namespace App\Http\Controllers;

use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use App\Gridphp;

class UserController extends Controller
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
        $opt["caption"] = "Users";
        $opt["height"] = "400";
        $opt["hidefirst"] = true;
        $g->set_options($opt);

        $g->table = "users";     
                          
        $out = $g->render("list1");
        
        return view('userlist', [
            'userGrid' => $out
        ]);
    }
}
