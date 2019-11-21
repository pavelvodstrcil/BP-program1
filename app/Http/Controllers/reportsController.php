<?php

namespace App\Http\Controllers;

use App\device;
use App\report_items_openvas;
use App\report_items_nessus;
use App\report;
use App\CVSS_OpenVas;
use App\CVSS_Nessus;
use mysql_xdevapi\Exception;
use Auth;

class reportsController extends Controller
{
    function delete($id)
    {

        $permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "report_delete");

        if ($permission){
        try {
            $report = report::find($id);

        } catch (Exception $e) {
        }

        $return = report::destroy($id);



        if ($return == 1) {
            $message = "Smazání reportu \"$report->name\" proběhlo OK";
        } else {
            $message = "Smazání neproběhlo, prosím opakujte akci";
        }
        }

        else {
            $message = " Na tuto operaci namáte oprávnění!  :-)";
        }

        return view('message')->with('message', $message);


    }


    public function getWorstCVSS($id)
    {

        $report = report::where('id', $id)->first();

        if ($report->scanner == 1) {
            $worst = 0;
            $items = CVSS_OpenVas::where('idReport', $id)->where('falsePositive' ,false)->get();
            foreach ($items as $item) {

                if ($worst < $item->ENVI) {
                    $worst = $item->ENVI;
                }

            }

            return $worst;

        } else {

            if ($report->scanner == 2) {
                $worst = 0;
                $test = 0;
                $items = CVSS_Nessus::where('idReport', $id)->where('falsePositive' ,false)->get();
                foreach ($items as $item) {

                  //  $maxItem = max($item->BASE, $item->TEMP, $item->ENVI);
                    if ($worst < $item->ENVI) {
                        $worst = $item->ENVI;

                    }

                }
                return $worst;

            }
        }
    }


    public function getMissingDevices($id)
    {
        //na zaklade druhiuu scanneru budu prispovat k reportum
        $scanner = report::where('id', $id)->value("scanner");

        //vsechny IP z tabulky zarizeni
        $IPsDevices = device::all()->pluck("IP")->toArray();


        $missingIPs = array();


        //OpenVas
        if ($scanner == 1) {
            $IPs = report_items_openvas::where('idReport', $id)->pluck("IP")->toArray();
        }


        //Nessus
        if ($scanner == 2) {

            $IPs = report_items_nessus::where('idReport', $id)->pluck("Host")->toArray();
        }



            //prochazim vsechny IP - pokud chybi, pridam do pole
        foreach ($IPs as $IP) {


            if (!in_array($IP, $IPsDevices)){
           array_push($missingIPs, $IP);
            }

        }

        $missingIPs = array_unique($missingIPs);


            return $missingIPs;


    }


    // změna change ignore pro report, provedení u řádku v CVSS se postará databáze
    function changeIgnore ($id){
    $valueOriginal = report::where('id',$id)->value("ignore");

    $edit= report::find($id);

    if ($valueOriginal){

        $edit-> ignore = false;

    }else {

        $edit-> ignore = true;

    }

        $edit-> save();
        return redirect('reports');
    }

}
