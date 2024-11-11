<?php

namespace App\Http\Controllers;

use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use App\Gridphp;

class ClientMasterController extends Controller
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
        $opt["caption"] = "Clients";
        $opt["height"] = "150";
        $opt["multiselect"] = true;

        $opt["detail_grid_id"] = "list2";
        $opt["subgridparams"] = "client_id,gender,company";
        $opt["hidefirst"] = true;
    
        // keep multiselect only by checkbox, otherwise single selection
        $opt["multiboxonly"] = true;

        // disable detail grid import if client_id 5 is selected
        $opt["onSelectRow"] = "function(rid){
            var rowdata = $('#list1').getRowData(rid);
            if (rowdata.client_id == 5)
                jQuery('#list2_pager #import_list2, #list2_toppager #import_list2').addClass('ui-state-disabled');
            else	
                jQuery('#list2_pager #import_list2, #list2_toppager #import_list2').removeClass('ui-state-disabled');
        }";

        $opt["beforeGrid"] = "function(){ $.jgrid.nav.addtext = 'Add Master Record'; }";
        $g->set_options($opt);
        $g->table = "clients";  
        
        $cols = array();

        $col = array();
        $col["name"] = "name";
        $col["title"] = "Name";
        $col["stype"] = "select";
        $col["searchoptions"]["value"] = $g->get_dropdown_values("SELECT distinct name as k, name as v FROM clients");
        $cols[] = $col;

        $g->set_columns($cols,true);                  
        $out_master = $g->render("list1");

        // detail grid
        $g = Gridphp::get();

        // receive id, selected row of parent grid
        // check if comma sep numeric ids
        $re = '/^([0-9]+[,]?)+$/';
        preg_match_all($re, $_GET["rowid"], $matches);
        if (count($matches[0]))
            $id = $_GET["rowid"];
        else
            $id = intval($_GET["rowid"]);

        $gender = $_GET["gender"];
        $company = utf8_encode($_GET["company"]); // if passed param contains utf8
        // $company = urldecode($_GET["company"]); // if passed param contains utf8
        // $company = iconv("ISO-8859-1", "UTF-8", $_GET["company"]);

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
            jQuery("#client_id",frm).val( n ) 
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

        // and use in sql for filteration
        $g->select_command = "SELECT id,i.client_id,invdate,amount,tax,note,total FROM invheader i
        INNER JOIN clients ON clients.client_id = i.client_id
        WHERE i.client_id IN ($id)";

        $g->table = "invheader";

        $cols = array();

        $col = array();
        $col["title"] = "Client";
        $col["name"] = "client_id";
        $col["dbname"] = "i.client_id";
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
        $col["title"] = "Invoices";
        $col["name"] = "note";
        $col["width"] = "100";
        $col["search"] = true;
        $col["editable"] = true;
        $col["edittype"] = "select";
        $str = $g->get_dropdown_values("select distinct note as k, note as v from invheader");
        $col["editoptions"] = array("value"=>":;".$str);
        $cols[] = $col;

        $g->set_columns($cols,true);

        $e["on_insert"] = array("add_client", null, true);
        $e["on_update"] = array("update_client", null, true);
        $g->set_events($e);
        
        function add_client(&$data)
        {
            $id = intval($_GET["rowid"]);
            $data["params"]["client_id"] = $id;
            $data["params"]["total"] = $data["params"]["amount"] + $data["params"]["tax"];
        }
        
        function update_client(&$data)
        {
            $id = intval($_GET["rowid"]);
            $gender = $_GET["gender"] . ' client note';
            $data["params"]["note"] = $gender;
            $data["params"]["client_id"] = $id;
            $data["params"]["total"] = $data["params"]["amount"] + $data["params"]["tax"];
        }

        // generate grid output, with unique grid name as 'list1'
        $out_detail = $g->render("list2");
        
        return view('clientMaster', [
            'clientMasterGrid' => $out_master,
            'clientDetailGrid' => $out_detail
        ]);
    }
}
