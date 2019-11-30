<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Auth;


class ImportNessusController extends Controller
{
    public function index()
    {
        return view('import/nessus');
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


            $request->cvs_file->storeAs('cvs_nessus', $request->cvs_file->getClientOriginalName());



            //Delám si rovnou zápis do DB, že se takový soubor nahrál
            //zatím bez uživatele -> natvrdo
            $date = date("Y-m-d");
            $user = Auth::user()->id;
            $id =  DB::table('report')->insertGetId(['name' => $filename, 'date' => $date, 'scanner' => "2", 'user' => $user]);


            //iportovani do tabulky report_items_openvas

            $filepath =

            $data = \Excel::load('storage/app/cvs_nessus/'.$filename)->get();

            if ($data->count()) {
                foreach ($data as $key => $value) {
                    $arraydata[] = [
                        'PluginID' => $value->plugin_id,
                        'CVE' => $value->cve,
                        'CVSS' => $value->cvss,
                        'Risk' => $value->risk,
                        'Host' => $value->host,
                        'Protocol' => $value->protocol,
                        'Port' => $value->port,
                        'Name' => $value->name,
                        'Synopsis' => $value->synopsis,
                        'Description' => $value->description,
                        'Solution' => $value->solution,
                        'SeeAlso' => $value->see_also,
                        'PluginOutput' => $value->plugin_output,
                        'idReport' => $id

                    ];
                }
                if (!empty($arraydata)) {
                   DB::table('report_items_nessus')->insert($arraydata);
                    app('\App\Http\Controllers\cvss_nessusController')->getCVSS($id);
                    $message = "Report ".$filename. " byl nahrán OK";
                    return view('message')->with('message', $message);

                }
            }
        }
    }
}
