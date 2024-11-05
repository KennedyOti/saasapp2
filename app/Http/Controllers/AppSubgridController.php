<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Gridphp;

class AppSubgridController extends Controller
{
    public function view()
    {
        $g = Gridphp::get();

        $opt = [
            "caption" => "Apps",
            "height" => "",
            "rowNum" => "5",
            "subGrid" => true,
            "subgridparams" => "app_id" // app_id will be posted to subgrid
        ];
        $opt["subgridurl"] = "subdetail.blade.php";
        $opt["loadComplete"] = "function(){ expand_all(); }";


        $g->set_options($opt);

        $g->table = "apps";

        $g->set_actions([
            "add" => true,
            "edit" => true,
            "delete" => true,
            "rowactions" => true,
            "search" => "advance",
            "showhidecolumns" => false
        ]);

        $out = $g->render("list1");

        return view('subgrid', [
            'appsubgrid' => $out
        ]);
    }
}
