<?php

namespace App\Http\Controllers;

use App\Gridphp;
use Illuminate\Http\Request;

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
        $opt = [
            "caption" => "App",
            "height" => "400",
            "hidefirst" => true,
            "export" => [
                "filename" => "AppExport",
                "heading" => "App Data",
                "orientation" => "landscape",
                "paper" => "a4",
                "sheetname" => "App Data",
                "range" => "filtered",
            ],
            "import" => [
                "allowreplace" => true,
                "hidefields" => ["client_id"],
                "url" => url('app/import') . '?_token=' . csrf_token(),
            ],
        ];

        $g->set_options($opt);
        $g->table = "apps";

        // Define the column model for the grid
        $colModel = [
            ["name" => "id", "title" => "ID"],
            ["name" => "name", "title" => "Name"],
            ["name" => "created_at", "title" => "Created At"],
            ["name" => "updated_at", "title" => "Updated At"],
            // Add more columns as necessary
        ];

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
            "showhidecolumns" => true,
        ]);

        $out = $g->render("list1");

        return view('app', [
            'grid' => $out,
            'colModel' => $colModel, // Pass columns to the view
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
        $csvData = ''; // Fetch or generate CSV data

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
        $pdfData = ''; // Fetch or generate PDF data

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
        $excelData = ''; // Fetch or generate Excel data

        return response()->stream(function () use ($excelData) {
            echo $excelData;
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="app_data.xlsx"',
        ]);
    }
}
