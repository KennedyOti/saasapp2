<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Gridphp;

class AppMasterController extends Controller
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
        $opt["caption"] = "Apps";
        $opt["height"] = "150";
        $opt["hidefirst"] = true;

        $opt["detail_grid_id"] = "list2";
        $opt["subgridparams"] = "client_id,gender,company";
        $opt["hidefirst"] = true;
        $opt["multiselect"] = true;

        $opt["multiboxonly"] = true;

        $opt["onSelectRow"] = "function(rid){
            var rowdata = $('#list1').getRowData(rid);
            if (rowdata.client_id == 5)
                jQuery('#list2_pager #import_list2, #list2_toppager #import_list2').addClass('ui-state-disabled');
            else	
                jQuery('#list2_pager #import_list2, #list2_toppager #import_list2').removeClass('ui-state-disabled');
        }";

        $opt["beforeGrid"] = "function(){ $.jgrid.nav.addtext = 'Add Master Record'; }";
        $g->set_options($opt);

        $g->table = "apps";

        $cols = array();

        $col = array();
        $col["name"] = "name";
        $col["title"] = "Name";
        $col["stype"] = "select";
        $col["searchoptions"]["value"] = $g->get_dropdown_values("SELECT distinct name as k, name as v FROM apps");
        $cols[] = $col;

        $g->set_columns($cols, true);
        $out_master = $g->render("list1");

        $g = Gridphp::get();

        $re = '/^([0-9]+[,]?)+$/';
        preg_match_all($re, $_GET["rowid"], $matches);
        if (count($matches[0]))
            $id = $_GET["rowid"];
        else
            $id = intval($_GET["rowid"]);

        $opt = array();

        $opt["beforeGrid"] = "function(){ $.jgrid.nav.addtext = 'Add Detail Record'; }";
        $opt["datatype"] = "local"; // stop loading detail grid at start
        $opt["height"] = ""; // autofit height of subgrid
        $opt["caption"] = "Invoice Data"; // caption of grid
        $opt["multiselect"] = true; // allow you to multi-select through checkboxes
        $opt["reloadedit"] = true; // reload after inline edit
        $opt["hidefirst"] = true;

        // fill detail grid add dialog with master grid id
        $opt["add_options"]["afterShowForm"] = 'function(frm) { 
                                                                var selr = jQuery("#list1").jqGrid("getGridParam","selrow");  
                                                                var n = jQuery("#list1").jqGrid("getCell",selr,"name");  
                                                                jQuery("#app_id",frm).val( n ) 
                                                            }';

        // reload master after detail update
        $opt["onAfterSave"] = "function(){ jQuery('#list1').trigger('reloadGrid',[{current:true}]); }";

        $opt["delete_options"]["afterSubmit"] = 'function(response) { if(response.status == 200)
            {
                jQuery("#list1").trigger("reloadGrid",[{current:true}]);
                return [true,""];
            }
        }';

        $g->set_options($opt);

        $g->table = "forms";

        $cols = array();

        $col = array();
        $col["title"] = "Apps";
        $col["name"] = "app_id";
        $col["dbname"] = "i.app_id";
        $col["width"] = "100";
        $col["align"] = "left";
        $col["search"] = true;
        $col["editable"] = true;
        $col["editoptions"] = array("readonly"=>"readonly");
        $col["show"] = array("list"=>false,"edit"=>true,"add"=>true,"view"=>false);
        $cols[] = $col;
        
        
        $col = array();
        $col["title"] = "Company"; // caption of column
        $col["name"] = "company"; // field name, must be exactly same as with SQL prefix or db field
        $col["width"] = "100";
        $col["editable"] = false;
        $col["show"] = array("list"=>true,"edit"=>true,"add"=>false,"view"=>false);
        $cols[] = $col;
        
        $col = array();
        $col["title"] = "forms";
        $col["name"] = "note";
        $col["width"] = "100";
        $col["search"] = true;
        $col["editable"] = true;
        $col["edittype"] = "select";
        $str = $g->get_dropdown_values("select distinct note as k, note as v from invheader");
        $col["editoptions"] = array("value"=>":;".$str);
        $cols[] = $col;
        
        $g->set_columns($cols,true);
        
        $e["on_insert"] = array("add_app", null, true);
        $e["on_update"] = array("update_app", null, true);
        $g->set_events($e);
        
        function add_app(&$data)
        {
            $id = intval($_GET["rowid"]);
            $data["params"]["app_id"] = $id;
            $data["params"]["total"] = $data["params"]["amount"] + $data["params"]["tax"];
        }
        
        function update_app(&$data)
        {
            $id = intval($_GET["rowid"]);
            $gd = $_GET["app_name"] . ' description';
            $data["params"]["note"] = $gd;
            $data["params"]["client_id"] = $id;
            $data["params"]["total"] = $data["params"]["amount"] + $data["params"]["tax"];
        }

        return view('appmaster', [
            'grid' => $out_master
        ]);
    }
}
