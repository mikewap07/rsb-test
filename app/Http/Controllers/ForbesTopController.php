<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ForbesTop;
use Illuminate\Http\Response;

class ForbesTopController extends Controller
{
    const columnLength = [
        "Count" => 7
    ];

    const columnNames = [
        'Year',
        'Rank',
        'Recipient',
        'Country',
        'Career',
        'Tied',
        'Title'
    ];

    public function uploadCSVContent(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();

            //Check for file extension and size
            $validateResponseFile = $this->checkUploadedFileProperties($extension, $fileSize);
            if($this->checkUploadedFileProperties($extension, $fileSize) !== "") {
                return Controller::showMessagePage($validateResponseFile, 'csv-upload');
            }

            $location = 'uploads';
            $file->move($location, $filename);
            $filepath = public_path($location . "/" . $filename);

            // Reading file
            $file = fopen($filepath, "r");
            $importData_arr = array();
            $i = 0;

            //Read the contents of the uploaded file
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);

                // Do validations on headers
                if ($i == 0) {
                    $i++;
                    $validateResponse = $this->checkUploadedFileTemplate($filedata);
                    if($this->checkUploadedFileTemplate($filedata) !== "") {
                        return Controller::showMessagePage($validateResponse, 'csv-upload');
                    }
                    continue;
                }

                $now = date('Y-m-d H:i:s');

                $forbesTop = new ForbesTop();
                $forbesTop->year = $filedata[0];
                $forbesTop->rank = $filedata[1];
                $forbesTop->recipient = $filedata[2];
                $forbesTop->country = $filedata[3];
                $forbesTop->career = $filedata[4];
                $forbesTop->tied = $filedata[5];
                $forbesTop->title = $filedata[6];
                $forbesTop->timestamps = false;
                $forbesTop->created_at = $now;
                $forbesTop->updated_at = $now;
                $importData_arr[$i] = $forbesTop->attributesToArray();

                $i++;
            }
            fclose($file);

            // Clear database contents on validations success
            DB::beginTransaction();
                ForbesTop::truncate();
            DB::commit();

            $j = count($importData_arr);
            try {
                // Insert individual records to database
                ForbesTop::insert($importData_arr);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                return Controller::showMessagePage("$e uploading records failed.", 'csv-upload');
            }

            return Controller::showMessagePage("$j records successfully uploaded.", 'csv-upload');
        } else {
            //no file was uploaded
            return Controller::showMessagePage("No file was uploaded.", 'csv-upload');
        }
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv");
        $maxFileSize = 20097152;
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                return "No file was uploaded.";
            }
        } else {
            return "Invalid file extension.";
        }

        return "";
    }

    public function checkUploadedFileTemplate($recordEntry)
    {
        $length = count($recordEntry);

        if($this::columnLength['Count'] !== $length) return "Template Column Count[".$length."] not matched ".$this::columnLength['Count'];

        for ($i=0; $i < $length; $i++) {
            if($this::columnNames[$i] !== $recordEntry[$i]) return "Template Column Names not matched.";
        }

        return "";
    }

    public function getRecipients(){
        $recipients = ForbesTop::all();

        return view('records', compact('recipients'))->render();
    }

    public function findRecipients(Request $request){
        $recipients = ForbesTop::query()
            ->where(strtolower($request->type), $request->search)
            ->get();

        return view('records', compact('recipients'))->render();
    }

    public function getMostsByField($column){
        $totalColumnName = 'total';

        $mostEntry = ForbesTop::groupBy($column)
            ->select($column, DB::raw('count(*) as '.$totalColumnName))
            ->orderBy($totalColumnName, 'desc')
            ->take(5)
            ->get();
        $mostNames = $mostEntry->pluck($column)->toArray();
        $mostTotals = $mostEntry->pluck($totalColumnName)->toArray();

        return response()->json([
                'model'             => $mostEntry,
                'model_names'       => $mostNames,
                'model_totals'      => $mostTotals,
            ]);
    }

    public function getMostCounts()
    {
        $dashboardCollections = [];

        $mostRecipient = $this->getMostsByField('recipient')->getData();
        $mostCountry = $this->getMostsByField('country')->getData();
        $mostCareer = $this->getMostsByField('career')->getData();


        $dashboardCollections['most_top_recipient'] = [
            'name' => $mostRecipient->model[0]->recipient ?? "No Record",
            'total' => $mostRecipient->model[0]->total ?? ""
        ];
        $dashboardCollections['most_top_country'] = [
            'name' => $mostCountry->model[0]->country ?? "No Record",
            'total' => $mostCountry->model[0]->total ?? ""
        ];
        $dashboardCollections['most_top_career'] = [
            'name' => $mostCareer->model[0]->career ?? "No Record",
            'total' => $mostCareer->model[0]->total ?? ""
        ];

        $dashboardCollections['list_top_recipients']    = ['names' => $mostRecipient->model_names, 'totals' => $mostRecipient->model_totals];
        $dashboardCollections['list_top_countries']     = ['names' => $mostCountry->model_names, 'totals' => $mostCountry->model_totals];
        $dashboardCollections['list_top_careers']       = ['names' => $mostCareer->model_names, 'totals' => $mostCareer->model_totals];

        return view('dashboard', compact('dashboardCollections'))->render();
    }
}
