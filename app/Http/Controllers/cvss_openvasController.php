<?php

namespace App\Http\Controllers;

use App\CVSS_OpenVas;
use App\device;
use App\report_items_openvas;
use Illuminate\Http\Request;
use App\report;

class cvss_openvasController extends Controller
{

    public function edit($id)
    {
        $row = CVSS_OpenVas::where('idRow', $id)->first();
        return view('report.CVSS.edit_openvas')->with('row', $row);
    }

    public function update(Request $request)
    {


        $edit = CVSS_OpenVas::find($request->ID);
        $oldVector = $edit;
        $edit->falsePositive = $request->falsePositive;
        //BASE
        $edit->AV = $request->AV;
        $edit->AC = $request->AC;
        $edit->Au = $request->Au;
        $edit->C = $request->C;
        $edit->I = $request->I;
        $edit->A = $request->A;
        //TEMP
        $edit->E = $request->E;
        $edit->RL = $request->RL;
        $edit->RC = $request->RC;
        //ENV
        $edit->CDP = $request->CDP;
        $edit->TD = $request->TD;
        $edit->CR = $request->CR;
        $edit->IR = $request->IR;
        $edit->AR = $request->AR;

        $edit->save();
        //po ulozeni spustim prepocet
        $this->calculateCVSS($request->ID);
        return view('message')->with('message', "Změna proběhla OK!");


    }


    function calculateCVSS_BASE($id)
    {
        $row = CVSS_OpenVas::where('id', $id)->first();
        $BASE = cvss_Calc_2_Controller::calculateBASE($row);
        return $BASE;
    }

    function calculateCVSS_TEMP($id)
    {
        $row = CVSS_OpenVas::where('id', $id)->first();


        return cvss_Calc_2_Controller::calculateTEMP($row);

    }


    function calculateCVSS_ENVI($id)
    {
        $row = CVSS_OpenVas::where('id', $id)->first();


        return cvss_Calc_2_Controller::calculateENVI($row);

    }

    //kontrola zda takovy report vubec existuje!
    public function checkDown($idReport)
    {
        $reportCheck = report::where('id', $idReport)->get();

        if ($reportCheck->isEmpty()) {

            echo "Tento report neexistuje!";
            return view('message')->with('message', 'NEEXISTUJE');

        } else {


            //Kontrola, jestli již byl report stažen -> pokud ne, proběhne stažeí + refresh stránky
            $check = CVSS_OpenVas::where('idReport', $idReport)->get();
            if ($check->isEmpty()) {

                app('\App\Http\Controllers\cvss_openvasController')->getCVSS($idReport);
                header("Refresh:0");
            } else {

                return $check;


            }
        }


    }


    public function getCVSS($idReport)
    {
        //Kontrola, jestli již byl report stažeb.
        $check = CVSS_OpenVas::where('idReport', $idReport)->get();
        if (!$check->isEmpty()) {

            $message = "Tento report byl již zpracován, není potřeba znova!";
            return view('message')->with('message', $message);
        } else {


            //hledání pouze reportů s daným ID
            $rows = report_items_openvas::where('idReport', $idReport)->get();


            $count = 0;
            foreach ($rows as $row) {

                $count += $this->downloadCVSS($row->id, $row->NVTOID, $idReport);


            }


            $message = "Report byl zpracován, bylo nalezeno " . $count . " záznamů.";
            return view('message')->with('message', $message);
        }


    }

    //bere ze souboru, ale aby byl zachovany nazev jako u Nessusu, je zde download
    function downloadCVSS($idRow, $oid, $idReport)
    {
        $path = '/var/lib/openvas/plugins';

        $hledane = "'script_oid(\"$oid\")'";

        $return = exec('grep  -R -F  ' . $hledane . ' ' . $path);


        //získání cesty a názvu souboru
        $pathfile = preg_split('/:/', $return, -1, PREG_SPLIT_NO_EMPTY);

        if (!empty($pathfile[0])) {

            try {
                $filecontent = file_get_contents($pathfile[0]);
            } catch (Exception $e) {
                return 0;
            }
            $findme = 'cvss_base_vector';
            $pos = strpos($filecontent, $findme);


            $stringpart = array();
            for ($i = 26; $i < 52; $i++) {

                array_push($stringpart, $filecontent[$pos + $i]);

            }

            //spojení do velého stringu bez mezer
            $string = implode("", $stringpart);


            $vectoritems = preg_split('/\//', $string, -1, PREG_SPLIT_NO_EMPTY);

            $size = sizeof($vectoritems);

            for ($i = 0; $i <= $size - 1; $i++) {
                //$vectoritem = preg_split('/\:/', $vectoritems[$i], -1, PREG_SPLIT_NO_EMPTY);


                $vectoritemtest = array();
                foreach ($vectoritems as $item) {


                    array_push($vectoritemtest, preg_split('/\:/', $item, -1, PREG_SPLIT_NO_EMPTY));
                }
            }


            $vector = new \App\CVSS_OpenVas();
            $vector->idRow = $idRow;
            $vector->idReport = $idReport;
            $vector->falsePositive = 0;


            foreach ($vectoritemtest as $key => $value) {
                $polozka = (string)$value[0];
                //kontola, plnosti -> když tam nejsou data -> vrátí 0, může se pokračovat dále
                if (!isset($value[1])) {
                    return 0;
                } else {
                    $vector->$polozka = $value[1];

                }
            }
            // získání IP adresy zařízení
            $row = report_items_openvas::where('id', $idRow)->first();
            $ip = $row->IP;

            //získání řádku zařízení podle IP
            $rowDevice = device::where('IP', $ip)->first();
            // přiřazení hodnot ze zařízení
            if (!empty($rowDevice)) {
                $vector->IR = $rowDevice->IR;
                $vector->CR = $rowDevice->CR;
                $vector->AR = $rowDevice->AR;
                $vector->TD = $rowDevice->TD;
                $vector->CDP = $rowDevice->CDP;
            }


            $vector->save();
            return 1;
        }

    }

    //metoda, kdetera prepocita cely radek pokud je potreba
    public function calculateCVSS($id)
    {
        $result = CVSS_OpenVas::where('id', $id)->first();


        $BASE = $this->calculateCVSS_BASE($id);
        $TEMP = $this->calculateCVSS_TEMP($id);
        $ENVI = $this->calculateCVSS_ENVI($id);

        $result->BASE = $BASE;
        $result->ENVI = $ENVI;
        $result->TEMP = $TEMP;
        $result->date_processed = date("Y-m-d");

        $result->save();


    }


    //ziskani BASE -> koukne do DB pokud je vypocteno, pokud neni - vypocita a ulozi
    //tim se pokazde neprepocitava - stejne to funguje i pro ostatni polozky
    public function getBASE($id)
    {
        $result = CVSS_OpenVas::where('id', $id)->first();

        if (empty($result->BASE)) {

            $this->calculateCVSS($id);
        }
        $result = CVSS_OpenVas::where('id', $id)->first();

        return $result->BASE;

    }


    public function getTEMP($id)
    {
        $result = CVSS_OpenVas::where('id', $id)->first();
        if (empty($result->TEMP)) {
            $this->calculateCVSS($id);
        }
        $result = CVSS_OpenVas::where('id', $id)->first();

        return $result->TEMP;

    }


    public function getENVI($id)
    {
        $result = CVSS_OpenVas::where('id', $id)->first();
        if (empty($result->ENVI)) {
            $this->calculateCVSS($id);
        }
        $result = CVSS_OpenVas::where('id', $id)->first();

        return $result->ENVI;

    }

}
