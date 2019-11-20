<?php

namespace App\Http\Controllers;

use App\CVSS_Nessus;
use App\report_items_nessus;
use App\report;
use function foo\func;
use Illuminate\Http\Request;
use App\device;



class cvss_nessusController extends Controller
{

    //metoda, kdetera prepocita cely radek pokud je potreba
    public function calculateCVSS($id){
        $result = CVSS_Nessus::where('id', $id)->first();


        $BASE = $this->calculateCVSS_BASE($id);
        $TEMP = $this->calculateCVSS_TEMP($id);
        $ENVI = $this->calculateCVSS_ENVI($id);

        $result->BASE=$BASE;
        $result->ENVI=$ENVI;
        $result->TEMP=$TEMP;
        $result->date_processed= date("Y-m-d");
        $result->save();



    }

    //
    //tady se se pocita poze BASE a pro jeen radek
    function calculateCVSS_BASE($id)
    {
        $row = CVSS_Nessus::where('id', $id)->first();

        if($row->version == 3){

        $BASE = cvss_CalcController::calculateBASE($row);
        }
        else {
            $BASE = cvss_Calc_2_Controller::calculateBASE($row);
        }

        return $BASE;

    }

    function calculateCVSS_TEMP($id)
    {
        $row = CVSS_Nessus::where('id', $id)->first();

        if ($row->version == 3){

        $TEMP = cvss_CalcController::calculateTEMP($row);
        }
        else {
            $TEMP = cvss_Calc_2_Controller::calculateTEMP($row);
        }
        return $TEMP;
    }


    function calculateCVSS_ENVI($id)
    {
        $row = CVSS_Nessus::where('id', $id)->first();

        if ($row->version == 3){
        $ENVI =  cvss_CalcController::calculateENVI($row);
        }
        else {
            $ENVI = cvss_Calc_2_Controller::calculateENVI($row);
        }
        return $ENVI;
    }


    //ziskani BASE -> koukne do DB pokud je vypocteno, pokud neni - vypocita a ulozi
    //tim se pokazde neprepocitava - stejne to funguje i pro ostatni polozky
    public function getBASE($id){
        $result = CVSS_Nessus::where('id', $id)->first();

        if (empty($result->BASE)){

            $this->calculateCVSS($id);
        }
        $result = CVSS_Nessus::where('id', $id)->first();

        return $result->BASE;

    }


    public function getTEMP($id){
        $result = CVSS_Nessus::where('id', $id)->first();
        if (empty($result->TEMP)){
            $this->calculateCVSS($id);
        }
        $result = CVSS_Nessus::where('id', $id)->first();

        return $result ->TEMP;

    }


    public function getENVI($id){
        $result = CVSS_Nessus::where('id', $id)->first();
        if (empty($result->ENVI)){
            $this->calculateCVSS($id);
        }
        $result = CVSS_Nessus::where('id', $id)->first();

        return $result ->ENVI;

    }

    public function edit($id)
    {
        $row = CVSS_Nessus::where('idRow', $id)->first();
        return view('report.CVSS.edit_nessus')->with('row', $row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {


        $edit = CVSS_Nessus::find($request->ID);
        $oldVector = $edit;
        $edit->falsePositive = $request->falsePositive;
        //BASE
        $edit->AV = $request->AV;
        $edit->AC = $request->AC;
        $edit->PR = $request->PR;
        $edit->UI = $request->UI;
        $edit->S = $request->S;
        $edit->C = $request->C;
        $edit->I = $request->I;
        $edit->A = $request->A;
        //TEMP
        $edit->E = $request->E;
        $edit->RL = $request->RL;
        $edit->RC = $request->RC;
        //ENV
        $edit->CR = $request->CR;
        $edit->IR = $request->IR;
        $edit->AR = $request->AR;
        $edit->MAV = $request->MAV;
        $edit->MAC = $request->MAC;
        $edit->MPR = $request->MPR;
        $edit->MUI = $request->MUI;
        $edit->MS = $request->MS;
        $edit->MC = $request->MC;
        $edit->MI = $request->MI;
        $edit->MA = $request->MA;


        $edit->save();

        $this->calculateCVSS($request->ID);
        return view('message')->with('message', "Změna proběhla OK!");


    }


    //kontrola, zda bylo jiz stazeno -> kdyz ne, tak stahne
    public function checkDown($idReport)
    {
        $reportCheck = report::where('id', $idReport)->get();

        if ($reportCheck->isEmpty()) {

            return "Teto report neexistuje!";


        } else {


            //Kontrola, jestli již byl report stažen -> pokud ne, proběhne stažeí + refresh stránky
            $check = CVSS_Nessus::where('idReport', $idReport)->get();
            if ($check->isEmpty()) {

                app('\App\Http\Controllers\cvss_nessusController')->getCVSS($idReport);
                header("Refresh:0");
            } else {

                return $check;

            }
        }


    }


    public function getCVSS($idReport)
    {


        //hledání pouze reportů s daným ID
        $rows = report_items_nessus::where('idReport', $idReport)->get();


        $count = 0;
        foreach ($rows as $row) {

            //  $count += $this->downloadCVSS($row->id, $row->PluginID, $idReport);
            $this->downloadCVSS($row->id, $row->PluginID, $idReport);


        }


    }




    //TUTO funkci odkomentovat a zakomentovat stejnou, pokud chcete používat stahování hodnot z internetu
    /*   function downloadCVSS($id, $PluginID, $idReport)
       {
           try {



               $url = "https://www.tenable.com/plugins/nessus/";
               $path = $url . $PluginID;
               $contents = htmlentities(file_get_contents($path));


               $findme = 'CVSS:3.0';


               $pos = strpos($contents, $findme);


               $stringpart = array();
               for ($i = 8; $i < 200; $i++) {

                   array_push($stringpart, $contents[$pos + $i]);

               }

               //stazeni stranky z webu
               //hledané slovo
               // protože můze být proměnlivá velikost raději pracuji s větším rozmezím



               $string = implode("", $stringpart);

               $words = preg_split('/sp/', $string, -1, PREG_SPLIT_NO_EMPTY);


               $stringtest = $words[0];


               $size = strlen($stringtest);

               $substring = substr($stringtest, 0, $size - 5);

               //nahore jsem hledal podle znaku sp ve </span>, tedy zbyly 2 znaky </, tady je odstranuji
               $vectoritems = preg_split('/\//', $substring, -1, PREG_SPLIT_NO_EMPTY);

               $size = sizeof($vectoritems);

               for ($i = 0; $i <= $size - 1; $i++) {
                   //$vectoritem = preg_split('/\:/', $vectoritems[$i], -1, PREG_SPLIT_NO_EMPTY);


                   $vectoritemtest = array();
                   foreach ($vectoritems as $item) {


                       array_push($vectoritemtest, preg_split('/\:/', $item, -1, PREG_SPLIT_NO_EMPTY));
                   }


                   $vector = new \App\CVSS_Nessus();
                   $vector->idRow = $id;
                   $vector->idReport = $idReport;


                   foreach ($vectoritemtest as $key => $value) {
                       $polozka = (string)$value[0];
                       //kontola, plnosti -> když tam nejsou data -> vrátí 0, může se pokračovat dále
                       if (!isset($value[1])) {
                           return 0;
                       } else {
                           $vector->$polozka = $value[1];
                       }
                   }
               }


               // získání IP adresy zařízení
               $row = report_items_nessus::where('id', $id)->first();
               $ip = $row->IP;

               //získání řádku zařízení podle IP
               $rowDevice = device::where('IP', $ip)->first();
               // přiřazení hodnot ze zařízení
               if (!empty($rowDevice)){
                   $vector -> IR = $rowDevice->IR;
                   $vector -> CR = $rowDevice->CR;
                   $vector -> AR = $rowDevice-> AR;
               }

                   $vector->save();
                   return 1;

           } catch (Exception $e) {
               return 0;
           }
       }

       function store()
       {
           $vector = new cvss_nessusController();


             $vector->save();


       }
    */

    function downloadCVSS($id, $PluginID, $idReport)
    {//kontrola, zda neni na Risk None -> znamena, ze to je jen informativni hlaska, chci preskovcit
        $riskVal = report_items_nessus::where('PluginID', $PluginID)->where('id', $id)->pluck("Risk")->first();
        if ($riskVal == "None") {

        } else {
            $version = 0;
            $path = '/opt/nessus/lib/nessus/plugins';
            $hledane = "'script_id($PluginID)'";
            $return = exec('grep  -R -F  ' . $hledane . ' ' . $path);

            //získání cesty a názvu souboru
            $pathfile = preg_split('/:/', $return, -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($pathfile[0])) {

                try {
                    $filecontent = file_get_contents($pathfile[0]);
                } catch (Exception $e) {
                    return 0;
                }
                $findme = 'script_set_cvss3_base_vector';
                $pos = strpos($filecontent, $findme);



            }

            // CVSS 2 ZACATEK!
            // pokud neni nalezen CVSS 3 vector -> hledám CVSS 2
            if ($pos == false){

                $findme = 'script_set_cvss_base_vector';
                $pos = strpos($filecontent, $findme);
                $stringpart = array();
                $version = 2;
                for ($i = 35; $i < 61; $i++) {

                    array_push($stringpart, $filecontent[$pos + $i]);

                }

                //KONEC CVSS 2!!!!!!!!!!!!!!!!!!
            }
            else {
                // CVSS 3
                $stringpart = array();
                $version = 3;
                for ($i = 38; $i < 75; $i++) {

                    array_push($stringpart, $filecontent[$pos + $i]);

                }}

            //SPOLECNA CAST
            //spojení do velého stringu bez mezer
            $string = implode("", $stringpart);


            $vectoritems = preg_split('/\//', $string, -1, PREG_SPLIT_NO_EMPTY);

            $size = sizeof($vectoritems);

            for ($i = 0; $i <= $size - 1; $i++) {
                $vectoritemtest = array();
                foreach ($vectoritems as $item) {
                    array_push($vectoritemtest, preg_split('/\:/', $item, -1, PREG_SPLIT_NO_EMPTY));
                }
            }

            //vytvoreni noveho vektoru -> naplneni a ulozeni
            $vector = new \App\CVSS_Nessus();
            $vector->idRow = $id;
            $vector->idReport = $idReport;
            $vector->version = $version;
            $vector->scanner = "N";
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
            $row = report_items_nessus::where('id', $id)->first();
            $ip = $row->Host;

            //získání řádku zařízení podle IP
            $rowDevice = device::where('IP', $ip)->first();
            // přiřazení hodnot ze zařízení
            if (!empty($rowDevice)) {
                $vector->IR = $rowDevice->IR;
                $vector->CR = $rowDevice->CR;
                $vector->AR = $rowDevice->AR;
                $vector->CDP = $rowDevice->CDP;
                $vector->TD = $rowDevice->TD;
            }




            $vector->save();


                return 1;
            }

    }
}