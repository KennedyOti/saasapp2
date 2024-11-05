<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Gridphp;

class FormController extends Controller
{
    public function view()
    {
        $g = Gridphp::get();

        // Define columns for the form data
        $cols = [];

        $cols[] = ["title" => "ID", "name" => "id", "width" => "10"];
        $cols[] = ["title" => "User ID", "name" => "user_id", "width" => "50", "editable" => true];
        $cols[] = ["title" => "App ID", "name" => "app_id", "width" => "50", "editable" => true];
        $cols[] = ["title" => "Form Name", "name" => "form_name", "width" => "100", "editable" => true, "search" => true];
        $cols[] = ["title" => "Description", "name" => "form_description", "width" => "200", "editable" => true];
        $cols[] = ["title" => "Default Style", "name" => "default_form_style", "width" => "100", "editable" => true];

        $grid = [
            "sortname" => "id",
            "sortorder" => "desc",
            "caption" => "Form Data",
            "autowidth" => true,
            "multiselect" => false,
            "export" => [
                "filename" => "form_data",
                "heading" => "Form Details",
                "orientation" => "landscape",
                "paper" => "a4",
                "sheetname" => "Form Details",
                "range" => "filtered"
            ]
        ];
        $g->set_options($grid);

        $g->set_actions([
            "add" => true,
            "edit" => true,
            "delete" => true,
            "rowactions" => true,
            "export_excel" => true,
            "export_pdf" => true,
            "export_csv" => true,
            "export_html" => true,
            "autofilter" => true,
            "showhidecolumns" => true,
            "search" => "advance"
        ]);

        // Use new field names in SQL query
        $g->select_command = "SELECT id, user_id, app_id, form_name, form_description, default_form_style FROM forms";
        $g->table = "forms";
        $g->set_columns($cols);

        $out = $g->render("list1");

        return view('form', [
            'formgrid' => $out
        ]);
    }
}
