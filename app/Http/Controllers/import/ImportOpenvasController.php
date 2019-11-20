<?php

namespace App\Http\Controllers\import;

use http\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ImportOpenvasController extends Controller
{

    public function index()
    {
        return view('import/openvas');
    }


    public function fileupload(Request $request)
    {

        $filename = $request->cvs_file->getClientOriginalName();


        //kontrola koncovky souboru
        $extension = \File::extension($filename);
        if ($extension != "csv") {

            $message = "Soubor nemá příponu .cvs, prosím  opakujete akci!";
            return view('message')->with('message', $message);
        } else {


           $request->cvs_file->storeAs('cvs_openvas', $request->cvs_file->getClientOriginalName());






            //Delám si rovnou zápis do DB, že se takový soubor nahrál
            //zatím bez uživatele -> natvrdo
            $date = date("Y-m-d");
           $id =  DB::table('report')->insertGetId(['name' => $filename, 'date' => $date, 'scanner' => "1", 'user' => "1"]);



            //iportovani do tabulky report_items_openvas

            $filepath =  $data = \Excel::load('storage/app/cvs_openvas/'.$filename)->get();

            if ($data->count()) {
                foreach ($data as $key => $value) {
                    $arraydata[] = [
                        'IP' => $value->ip,
                        'Hostname' => $value->hostname,
                        'Port' => $value->port,
                        'PortProtocol' => $value->port_protocol,
                        'CVSS' => $value->cvss,
                        'Severity' => $value->severity,
                        'SolutionType' => $value->solution_type,
                        'NVTName' => $value->nvt_name,
                        'Summary' => $value->summary,
                        'SpecificResult' => $value->specific_result,
                        'NVTOID' => $value->nvt_oid,
                        'TaskID' => $value->task_id,
                        'CVEs' => $value->cves,
                        'TaskName' => $value->task_name,
                        'Timestamp' => $value->timestamp,
                        'ResultID' => $value->result_id,
                        'Impact' => $value->impact,
                        'Solution' => $value->solution,
                        'AffectedSoftwareOS' => $value->affected_softwareos,
                        'VulnerabilityInsight' => $value->vulnerability_insight,
                        'VulnerabilityDetectionMethod' => $value->vulnerability_detection_method,
                        'ProductDetectionResult' => $value->product_detection_result,
                        'BIDs' => $value->bids,
                        'CERTs' => $value->certs,
                        'OtherReferences' => $value->other_references,
                        'idReport' => $id

                    ];
                }
                if (!empty($arraydata)) {
                   DB::table('report_items_openvas')->insert($arraydata);
                    app('\App\Http\Controllers\cvss_openvasController')->getCVSS($id);
                        $message = "Report ".$filename. " byl nahrán OK";
                        return view('message')->with('message', $message);
                }
            }
        }
    }





}
