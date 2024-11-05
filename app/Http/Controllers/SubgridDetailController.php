<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Gridphp;

class SubgridDetailController extends Controller
{
    public function view(Request $request)
    {
        $g = Gridphp::get();

        // Retrieve app_id from parent grid
        // $app_id = $_POST['app_id'] ?? 0;
        $app_id = $request->input('app_id', 0);

        // Define columns to match the forms table structure
        $cols = [
            ["title" => "ID", "name" => "id", "width" => "10"],
            ["title" => "User ID", "name" => "user_id", "width" => "50", "editable" => true],
            ["title" => "App ID", "name" => "app_id", "width" => "50", "editable" => true],
            ["title" => "Form Name", "name" => "form_name", "width" => "100", "editable" => true, "search" => true],
            ["title" => "Description", "name" => "form_description", "width" => "200", "editable" => true],
            ["title" => "Default Style", "name" => "default_form_style", "width" => "100", "editable" => true]
        ];

        $opt = [
            "caption" => "Forms for Selected App",
            "sortname" => "id",
            "sortorder" => "desc",
            "autowidth" => true,
            "multiselect" => false,
            "subGrid" => false
        ];
        $g->set_options($opt);

        $g->set_actions([
            "add" => true,
            "edit" => true,
            "delete" => true,
            "rowactions" => true,
            "search" => "advance",
            "showhidecolumns" => true
        ]);

        // Select forms associated with the specified app_id
        $g->select_command = "SELECT id, user_id, app_id, form_name, form_description, default_form_style
                              FROM forms WHERE app_id = $app_id";

        $g->table = "forms";
        $g->set_columns($cols);

        $out = $g->render("list1");

        return view('subdetail', [
            'subgriddetail' => $out
        ]);
    }
}
