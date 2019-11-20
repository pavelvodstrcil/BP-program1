<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class cvss_CalcController extends Controller
{
    // moje metoda na zaokrouhlení nahoru
    public static function RoundUp($number){


        $decimal = $number - floor($number);
        $int = $number - $decimal;

        $decimal10 = $decimal * 10;

        $decimalDeleno = (ceil($decimal10))/10;
        $result = $int + $decimalDeleno;

        return $result;
    }


    //Trida, kde je kalkulacka obecna Uplne to neni kontroler, ale budeme delat, ze je..:-)
    //tato metoda prijma CELY obejekt z DB
    //
    public static function calculateBASE($row){
        //hodnoty ulozene v poli

        $array_CIA = array ("H" => 0.56,
                            "L" => 0.22,
                            "N" => 0);

        $array_AV = array(
                            "N" => 0.85,
                            "A" =>0.62,
                            "L" => 0.55,
                            "P" => 0.20);
        $array_AC = array(  "L" => 0.77,
                            "H" =>0.44);

        $array_PR = array(  "N" => 0.85,
                            "L" => 0.62,
                            "H" => 0.27);

        $array_UI = array( "N"=>0.85,
                            "R"=>0.62);


      if ($row->S == 'U'){

            //pocitani se scope unchanged
          $ISC= 1-((1-$array_CIA[$row->C]) * (1-$array_CIA[$row->I])* (1-$array_CIA[$row->A]));

          $Exploitability = 8.22 * $array_AV[$row->AV] * $array_AC[$row->AC] * $array_PR[$row->PR] * $array_UI[$row->UI];

          $result = (6.42*$ISC)+$Exploitability;

          return self::RoundUp($result);


      } else {
       //pocitani s changed scope (jine cisla!!)
          $ISC= 1-((1-$array_CIA[$row->C]) * (1-$array_CIA[$row->I])* (1-$array_CIA[$row->A]));
          $Exploitability = 8.22 * $array_AV[$row->AV] * $array_AC[$row->AC] * $array_PR[$row->PR] * $array_UI[$row->UI];

         $ImpactSub = 7.52 * ($ISC - 0.029) -3.25 * pow($ISC-0.02, 15);

         $result = 1.08 *($ImpactSub + $Exploitability);
          return self::RoundUp($result);




      }

    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function calculateTEMP($row){
       $BASE =  self::calculateBASE($row);

       $array_E = array(    "F" => 0.97,
                            "U"=>0.91,
                            "N"=>1,
                            "P"=>0.94,
                            "H"=>1,
                            ""=>1);
       $array_RL = array(   "N"=>1,
                            "U"=>1,
                            "W"=>0.97,
                            "T"=>0.96,
                            "O"=>0.95,
                            ""=>1);
       $array_RC = array(   "N"=>1,
                            "C"=>1,
                            "R"=>0.96,
                            "U"=>0.92,
                            ""=>1);


       $result = ($BASE*$array_E[$row->E]*$array_RC[$row->RC]*$array_RL[$row->RL]);

       if ($result == $BASE){
           return $BASE;
       }else {

       return self::RoundUp($result);
         }}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         public static function calculateENVI($row){

        $TEMP = self::calculateTEMP($row);

             $array_CIA = array ("H" => 0.56,
                 "L" => 0.20,
                 "N" => 0,
                 ""=>0);

             $array_CR_CIA = array(
                 "N"=>1,
                 "H"=>1.5,
                 "M"=>1,
                 "L"=>0.5,
                 ""=>1);

             $array_AV = array(
                 "N" => 0.85,
                 "A" =>0.62,
                 "L" => 0.55,
                 "P" => 0.2,
                 ""=>0.85);
             $array_AC = array(
                 "L" => 0.77,
                 "H" =>0.44,
                 ""=>0.77);

             $array_UI = array(
                 "N"=>0.85,
                 "R"=>0.62,
                 ""=>0.85);
             $array_PR = array(
                 "N" => 0.85,
                 "L" => 0.62,
                 "H" => 0.27,
                 ""=>0.85);

             $array_PR_changed = array(
                 "N"=>0.85,
                 "H"=>0.5,
                 "L"=>0.68,
                 ""=>0.85 );

             $array_ECM = array(
                 "F" => 0.97,
                 "U"=>0.91,
                 "N"=>1,
                 "P"=>0.94,
                 "H"=>1,
                 ""=>1);
             $array_RL = array(
                 "N"=>1,
                 "U"=>1,
                 "W"=>0.97,
                 "T"=>0.96,
                 "O"=>0.95,
                 ""=>1);
             $array_RC = array(
                 "N"=>1,
                 "C"=>1,
                 "R"=>0.96,
                 "U"=>0.92,
                 ""=>1);

             $ISC = (1-(1-$array_CIA[$row->MC]*$array_CR_CIA[$row->CR]) * (1-$array_CIA[$row->MI]*$array_CR_CIA[$row->IR]) * (1-$array_CIA[$row->MA]*$array_CR_CIA[$row->AR]));



             if ($ISC == 0){

                 return $TEMP;

             }elseif ($row->MS == 'U')

             //Modif SCOPE UNCHANGED
             {
                 $ISC = (1-(1-$array_CIA[$row->MC]*$array_CR_CIA[$row->CR]) * (1-$array_CIA[$row->MI]*$array_CR_CIA[$row->IR]) * (1-$array_CIA[$row->MA]*$array_CR_CIA[$row->AR]));
                 $ISC = min($ISC, 0.915);
                 $Exploitability = 8.22 * $array_AV[$row->MAV] * $array_AC[$row->MAC] * $array_PR[$row->MPR] * $array_UI[$row->MUI];

                 $ImpactSub = 6.42 * $ISC;

                 $result = self::RoundUp(($ImpactSub+$Exploitability));
                 $result = min ($result, 10);
                 $result = $result * $array_ECM[$row->E] * $array_RL[$row->RL] * $array_RC[$row->RC];


                 if ($result == 0 ){
                     return round($TEMP, 1);
                 }else  {

                 return self::RoundUp($result);
             }

                //CHANGED SCOPE
             } else {

                 $ISC = (1-((1-$array_CIA[$row->MC]*$array_CR_CIA[$row->CR]) * (1-$array_CIA[$row->MI]*$array_CR_CIA[$row->IR]) * (1-$array_CIA[$row->MA]*$array_CR_CIA[$row->AR])));
                //vybrani nejmenci but ISC nebo 0.915
                $ISC =  min($ISC, 0.915);


                 $Exploitability = 8.22 * $array_AV[$row->MAV] * $array_AC[$row->MAC] * $array_PR_changed[$row->MPR] * $array_UI[$row->MUI];


                 $meziImpact  = pow(($ISC - 0.02), 15);
                 $impactSub = 7.52 * ($ISC - 0.029) - 3.25*($meziImpact);




                 $result = self::RoundUp(1.08*($impactSub+$Exploitability));
                 $result = min ($result, 10);
                 $result = $result * $array_ECM[$row->E] * $array_RL[$row->RL] * $array_RC[$row->RC];



                 if ($result == 0){
                      return round($TEMP, 1);}else {
                     return self::RoundUp($result);
                 }
             }


         }

}
