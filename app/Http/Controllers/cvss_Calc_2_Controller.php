<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class cvss_Calc_2_Controller extends Controller
{
    public static function calculateBASE($row)
    {

        $array_AV = array(
            "L" => 0.395,
            "A" => 0.646,
            "N" => 1.0);

        $array_AC = array(
            "H" => 0.35,
            "M" => 0.61,
            "L" => 0.71);

        $array__Au = array(
            "M" => 0.45,
            "S" => 0.56,
            "N" => 0.704);

        $array_CIA = array(
            "N" => 0.0,
            "P" => 0.275,
            "C" => 0.660);


        $Exploitability = 20 * $array_AV[$row->AV] * $array_AC[$row->AC] * $array__Au[$row->Au];

        $Impact = 10.41 * (1 - (1 - $array_CIA[$row->I]) * (1 - $array_CIA[$row->C]) * (1 - $array_CIA[$row->A]));

        if ($Impact == 0) {
            $var = 0;
        } else {
            $var = 1.176;
        }

        $BASE = ((0.6 * $Impact) + (0.4 * $Exploitability) - 1.5) * $var;
        return round($BASE, 1);
    }

    public static function calculateTEMP($row)
    {

        $BASE = self::calculateBASE($row);


        $array_E = array("U" => 0.85,
            "POC" => 0.9,
            "F" => 0.95,
            "H" => 1.0,
            "ND" => 1.0,
            "" => 1.0);

        $array_RL = array("OF" => 0.87,
            "TF" => 0.9,
            "W" => 0.95,
            "U" => 1.0,
            "ND" => 1.0,
            "" => 1.0);

        $array_RC = array("UC" => 0.9,
            "UR" => 0.95,
            "C" => 1.0,
            "ND" => 1.0,
            "" => 1.0);
        $TempSore = $BASE * $array_E[$row->E] * $array_RL[$row->RL] * $array_RC[$row->RC];

        return round($TempSore, 1);
    }

    public static function calculateENVI($row)
    {


        $array_CDP = array(
            "N" => 0,
            "L" => 0.1,
            "LM" => 0.3,
            "MH" => 0.4,
            "H" => 0.5,
            "ND" => 0.0,
            "" => 0.0);

        $array_TD = array(
            "N" => 0,
            "L" => 0.25,
            "M" => 0.75,
            "H" => 1.0,
            "ND" => 1,
            "" => 1);

        $array_CIA_req = array(
            "L" => 0.5,
            "M" => 1.0,
            "H" => 1.51,
            "ND" => 1.0,
            "" => 1.0);

        $array_CIA = array(
            "N" => 0.0,
            "P" => 0.275,
            "C" => 0.660);


        $AdjustImpact = 10.41 * (1 - (1 - $array_CIA[$row->C] * $array_CIA_req[$row->CR]) * (1 - $array_CIA[$row->I] * $array_CIA_req[$row->IR]) * (1 - $array_CIA[$row->A] * $array_CIA_req[$row->AR]));
        $AdjustImpact = min($AdjustImpact, 10);

        $array_AV = array(
            "L" => 0.395,
            "A" => 0.646,
            "N" => 1.0);

        $array_AC = array(
            "H" => 0.35,
            "M" => 0.61,
            "L" => 0.71);

        $array__Au = array(
            "M" => 0.45,
            "S" => 0.56,
            "N" => 0.704);


        $Exploitability = 20 * $array_AV[$row->AV] * $array_AC[$row->AC] * $array__Au[$row->Au];


        if ($AdjustImpact == 0) {
            $var = 0;
        } else {
            $var = 1.176;
        }

        $AdjustedBASE = ((0.6 * $AdjustImpact) + (0.4 * $Exploitability) - 1.5) * $var;


        $array_E = array(
            "U" => 0.85,
            "POC" => 0.9,
            "F" => 0.95,
            "H" => 1.0,
            "ND" => 1.0,
            "" => 1.0);

        $array_RL = array(
            "OF" => 0.87,
            "TF" => 0.9,
            "W" => 0.95,
            "U" => 1.0,
            "ND" => 1.0,
            "" => 1.0);

        $array_RC = array(
            "UC" => 0.9,
            "UR" => 0.95,
            "C" => 1.0,
            "ND" => 1.0,
            "" => 1.0);
        $AdjustedTemporal = $AdjustedBASE * $array_E[$row->E] * $array_RL[$row->RL] * $array_RC[$row->RC];

        $ENVI = ($AdjustedTemporal + (10 - $AdjustedTemporal) * $array_CDP[$row->CDP]) * $array_TD[$row->TD];


        if ($ENVI == 0) {
            $TEMP = self::calculateTEMP($row);
            return round($TEMP, 1);
        } else {
            return round($ENVI, 1);
        }
    }
}
