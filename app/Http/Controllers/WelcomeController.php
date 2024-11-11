<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Gridphp;
 
class WelcomeController extends Controller
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
        $opt["caption"] = "Customers";
        $opt["height"] = "400";
        $opt["hidefirst"] = true;
        $g->set_options($opt);

        $g->table = "customers";     
                          
        $out = $g->render("list1");
        
        return view('welcome', [
            'grid' => $out
        ]);
    }
}