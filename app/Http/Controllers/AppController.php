<?php

namespace App\Http\Controllers;

use App\Gridphp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AppController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @return \Illuminate\View\View
     */
    public function view(Request $request)
    {
        $g = Gridphp::get();

        // Configure grid options
        $opt = [];
        $opt["caption"] = "App";
        $opt["height"] = "400";
        $opt["hidefirst"] = true;

        // Enable export functionality
        $opt["export"] = [
            "filename" => "AppExport",
            "heading" => "App Data",
            "orientation" => "landscape",
            "paper" => "a4",
            "sheetname" => "App Data",
            "range" => "filtered", // export filtered data or all data
        ];

        // Enable import functionality
        $csrfToken = csrf_token(); // Get CSRF token
        $opt["import"] = [
            "allowreplace" => true,
            "hidefields" => ["client_id"],
            "url" => url('app/import') . '?_token=' . $csrfToken
        ];

        $g->set_options($opt);

        // Specify the database table
        $g->table = "apps";

        // Add action settings to show export and import options
        $g->set_actions([
            "add" => true,
            "edit" => true,
            "delete" => true,
            "rowactions" => true,
            "export_excel" => true, // Enable Excel export
            "export_pdf" => true,    // Enable PDF export
            "export_csv" => true,     // Enable CSV export
            "export_html" => true,    // Optionally enable HTML export
            "import" => true,
            "autofilter" => true,
            "showhidecolumns" => true
        ]);

        $out = $g->render("list1");

        return view('app', [
            'grid' => $out
        ]);
    }

    /**
     * Handle import requests for the grid.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // Handle the import functionality here.
        return response()->json(['status' => 'Import successful']);
    }

    /**
     * Export data in the specified format.
     *
     * @param Request $request
     * @param string $format
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, $format)
    {
        // Determine the export format and handle accordingly
        switch ($format) {
            case 'csv':
                return $this->exportCsv();
            case 'pdf':
                return $this->exportPdf();
            case 'excel':
                return $this->exportExcel();
            default:
                return response()->json(['error' => 'Unsupported format'], 400);
        }
    }

    /**
     * Export data as CSV.
     */
    protected function exportCsv()
    {
        // Logic to fetch data and generate CSV file
        // ...

        return response()->stream(function () use ($csvData) {
            echo $csvData;
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="app_data.csv"',
        ]);
    }

    /**
     * Export data as PDF.
     */
    protected function exportPdf()
    {
        // Logic to fetch data and generate PDF file
        // ...

        return response()->stream(function () use ($pdfData) {
            echo $pdfData;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="app_data.pdf"',
        ]);
    }

    /**
     * Export data as Excel.
     */
    protected function exportExcel()
    {
        // Logic to fetch data and generate Excel file
        // ...

        return response()->stream(function () use ($excelData) {
            echo $excelData;
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="app_data.xlsx"',
        ]);
    }
}
