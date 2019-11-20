@extends ('layouts.app')

@section('content')
<?php
use App\report_items_nessus;
use App\device;


$return = app('\App\Http\Controllers\cvss_nessusController')->checkDown($idReport);

function getColor($value, $row){
    //tady pak jen pridat nebo a pridat nessus
    if ($row == "true"){
        return "";
    }

    else{

        if($value == 0.0 ){
            return  "#00ff00";
        }elseif($value <=3.9) {
            return "#00ff33";
        }elseif ($value <=6.9){
            return   "#ff6600";
        }elseif($value <= 8.9) {
            return "#ff0033";
        }elseif ($value <=10.0){
            return "#990000";
        }else {return "";}
    }}



function getFalsePositive($a){

    if ($a == 0){
        return "False";
    }elseif($a ==1){
        return "True";
    }else {return "Nevyplněno";}
}

?>




    <div class="container">
        <h2 align="center">Výpis CVSS z reportu </h2>
       <table class="table">
            <thead>
            <tr>
                <th>Host/IP</th>
                <th>Název problému</th>
                <th>BASE CVSS</th>
                <th>TEMP CVSS</th>
                <th>ENVI CVSS</th>
                <th>false positive</th>
                <th>Editovat vector</th>
                <th>Datum přepočtu</th>
            </tr>
            </thead>
            <tbody>

            @foreach($return as $item)
                <?php
                $report = report_items_nessus::where('idReport', $idReport)->where('id', $item->idRow)->first();
                $BASE = app('\App\Http\Controllers\cvss_nessusController')->getBASE($item->id);
                $TEMP = app('\App\Http\Controllers\cvss_nessusController')->getTEMP($item->id);
                $ENVI = app('\App\Http\Controllers\cvss_nessusController')->getENVI($item->id);
                ?>
                <tr>
                    <td>{{$report->Host}}</td>
                    <td>{{$report->Name}}</td>
                    <td style="color: black;" bgcolor="{{getColor($BASE, $item->falsePositive)}}">{{$BASE}}</td>
                    <td style="color: black;" bgcolor="{{getColor($TEMP, $item->falsePositive)}}">{{$TEMP}}</td>
                    <td style="color: black;" bgcolor="{{getColor($ENVI, $item->falsePositive)}}">{{$ENVI}}</td>
                    <td >{{getFalsePositive($item->falsePositive)}}</td>
                    <td><a href="edit/{{$item->idRow}}">EDIT</a></td>
                    <td>{{$item->date_processed}}</td>

                </tr>
            </tbody>
           @endforeach

        </table>
    </div>













@endsection