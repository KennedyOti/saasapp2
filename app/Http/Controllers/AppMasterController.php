<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Gridphp;

class AppMasterController extends Controller
{
    /**
     * Display the apps and associated forms.
     */
    public function view()
    {
        // Initialize the GridPHP instance
        $g = Gridphp::get();

        // Master Grid options for Apps
        $opt = [];
        $opt["caption"] = "Apps";
        $opt["height"] = "150";
        $opt["hidefirst"] = true;
        $opt["detail_grid_id"] = "list2";
        $opt["subgridparams"] = "user_id, app_name, description, status";
        $opt["multiselect"] = true;
        $opt["multiboxonly"] = true;

        // Function to handle row selection for enabling/disabling import
        $opt["onSelectRow"] = "function(rid){
            var rowdata = $('#list1').getRowData(rid);
            if (rowdata.user_id == 5)
                jQuery('#list2_pager #import_list2, #list2_toppager #import_list2').addClass('ui-state-disabled');
            else
                jQuery('#list2_pager #import_list2, #list2_toppager #import_list2').removeClass('ui-state-disabled');
        }";

        $opt["beforeGrid"] = "function(){ $.jgrid.nav.addtext = 'Add New App'; }";
        $g->set_options($opt);

        // Set the table to 'apps'
        $g->table = "apps";

        // Define columns for Apps
        $cols = [];

        $col = [];
        $col["name"] = "app_name";
        $col["title"] = "App Name";
        $col["editable"] = true;
        $cols[] = $col;

        $col = [];
        $col["name"] = "description";
        $col["title"] = "Description";
        $col["editable"] = true;
        $cols[] = $col;

        $col = [];
        $col["name"] = "status";
        $col["title"] = "Status";
        $col["editable"] = true;
        $col["edittype"] = "select";
        $col["editoptions"] = ["value" => "private:Private;public:Public"];
        $cols[] = $col;

        $g->set_columns($cols, true);
        $out_master = $g->render("list1");

        // Detail Grid options for Forms
        $g = Gridphp::get();

        $opt = [];
        $opt["beforeGrid"] = "function(){ $.jgrid.nav.addtext = 'Add Form'; }";
        $opt["datatype"] = "local"; // Stop loading detail grid at start
        $opt["height"] = ""; // Autofit height of subgrid
        $opt["caption"] = "Form Data"; // Caption of grid
        $opt["multiselect"] = true;
        $opt["reloadedit"] = true;
        $opt["hidefirst"] = true;

        $opt["add_options"]["afterShowForm"] = 'function(frm) { 
            var selr = jQuery("#list1").jqGrid("getGridParam","selrow");  
            var n = jQuery("#list1").jqGrid("getCell",selr,"app_name");  
            jQuery("#app_id",frm).val( n ) 
        }';

        // Reload master after detail update
        $opt["onAfterSave"] = "function(){ jQuery('#list1').trigger('reloadGrid',[{current:true}]); }";

        $opt["delete_options"]["afterSubmit"] = 'function(response) { 
            if(response.status == 200) {
                jQuery("#list1").trigger("reloadGrid",[{current:true}]);
                return [true,""];
            }
        }';

        $g->set_options($opt);

        // SQL Command to fetch Forms for a specific App
        $g->select_command = "SELECT id, app_id, form_name, form_description, default_form_style FROM forms WHERE app_id = " . intval($_GET["rowid"]);

        // Set the table to 'forms'
        $g->table = "forms";

        // Define columns for Forms
        $cols = [];

        $col = [];
        $col["title"] = "App ID";
        $col["name"] = "app_id";
        $col["editable"] = true;
        $col["editoptions"] = ["readonly" => "readonly"];
        $cols[] = $col;

        $col = [];
        $col["title"] = "Form Name";
        $col["name"] = "form_name";
        $col["editable"] = true;
        $cols[] = $col;

        $col = [];
        $col["title"] = "Form Description";
        $col["name"] = "form_description";
        $col["editable"] = true;
        $cols[] = $col;

        $col = [];
        $col["title"] = "Default Form Style";
        $col["name"] = "default_form_style";
        $col["edittype"] = "select";
        $col["editoptions"] = ["value" => "Table:Table;Form:Form"];
        $col["editable"] = true;
        $cols[] = $col;

        $g->set_columns($cols, true);

        // Define events for handling data insert and update
        $e["on_insert"] = ["add_form", null, true];
        $e["on_update"] = ["update_form", null, true];
        $g->set_events($e);

        // Function to handle form insertion
        function add_form(&$data)
        {
            $data["params"]["app_id"] = intval($_GET["rowid"]);
        }

        // Function to handle form updates
        function update_form(&$data)
        {
            $data["params"]["app_id"] = intval($_GET["rowid"]);
        }

        // Generate output for detail grid
        $out_detail = $g->render("list2");

        return view('appmaster', [
            'appmastergrid' => $out_master,
            'appdetailgrid' => $out_detail
        ]);
    }
}
