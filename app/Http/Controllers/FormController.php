<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Gridphp;

class FormController extends Controller
{
    public function view()
    {
        // Initialize GridPHP instance
        $g = Gridphp::get();

        // Define grid columns for form data
        $cols = [];

        // ID Column
        $col = array();
        $col["title"] = "ID";
        $col["name"] = "id";
        $col["width"] = "10";
        $cols[] = $col;

        // User ID Column - now editable for manual input
        $col = array();
        $col["title"] = "User ID";
        $col["name"] = "user_id";
        $col["width"] = "50";
        $col["editable"] = true;  // Set as editable to allow manual entry
        $cols[] = $col;

        // App ID Column
        $col = array();
        $col["title"] = "App ID";
        $col["name"] = "app_id";
        $col["width"] = "50";
        $col["editable"] = true;
        $cols[] = $col;

        // Form Name Column
        $col = array();
        $col["title"] = "Form Name";
        $col["name"] = "form_name";
        $col["width"] = "100";
        $col["editable"] = true;
        $col["search"] = true;
        $cols[] = $col;

        // Form Description Column
        $col = array();
        $col["title"] = "Description";
        $col["name"] = "form_description";
        $col["width"] = "200";
        $col["editable"] = true;
        $cols[] = $col;

        // Default Form Style Column
        $col = array();
        $col["title"] = "Default Style";
        $col["name"] = "default_form_style";
        $col["width"] = "100";
        $col["editable"] = true;
        $cols[] = $col;

        // Configure grid options
        $grid = array();
        $grid["sortname"] = "id";
        $grid["sortorder"] = "desc";
        $grid["caption"] = "Form Data";
        $grid["autowidth"] = true;
        $grid["multiselect"] = false;
        $grid["export"] = array(
            "filename" => "form_data",
            "heading" => "Form Details",
            "orientation" => "landscape",
            "paper" => "a4"
        );
        $grid["export"]["sheetname"] = "Form Details";
        $grid["export"]["range"] = "filtered";

        $g->set_options($grid);

        // Set actions for add, edit, delete, export
        $g->set_actions(array(
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
        ));

        // Set custom SQL query for grid data
        $g->select_command = "SELECT id, user_id, app_id, form_name, form_description, default_form_style FROM forms";

        // Set the database table to be used for CRUD operations
        $g->table = "forms";

        // Apply the configured columns to the grid
        $g->set_columns($cols);

        // Render the grid
        $out = $g->render("list2");

        // Return view with the form grid
        return view('form', [
            'formgrid' => $out
        ]);
    }
}
