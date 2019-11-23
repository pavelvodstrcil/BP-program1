@extends ('layouts.app')

@section('content')
    <?php

    use App\report_items_nessus ;
    use App\report_items_openvas;
    use App\device;
    use App\report;

    $IP = $NET.".".$SUBNET.".".$SUBSUBNET.".".$HOST;
//------------------------------------------------------------PISKOVISTE------------------------------------------------------

            // přidat tlačítko pro editaci
            //  nebo neignorovat, ale řadit na konec? ? ?



    $reportOP = DB::table('report_items_openvas')
        ->leftJoin('CVSS_OpenVas', 'report_items_openvas.id', '=', 'CVSS_OpenVas.idRow')->
        where('report_items_openvas.IP', $IP)->where('CVSS_OpenVas.ignore', false)->get();




    $reportNES = report_items_nessus::where('Host', $IP)->get();



//------------------------------------------------------------PISKOVISTE------------------------------------------------------
    //serazeni? podle zavaznosti?
    // odkaz na otevreni celeho radku
    // odkaz na editaci CVSS? ? ?
    // vytvorit objekt? asi radek (aby to pak slo radit?)
    //




    function getCVSSTEMP3($row){

        $rowCalc = \App\CVSS_Nessus::where('idRow', $row->id)->get();
        foreach ($rowCalc as $item){
            $TEMP = app('\App\Http\Controllers\cvss_nessusController')->calculateCVSS_TEMP($item->id);
            return $TEMP;
        }

    }

    function getCVSSENVI3($row){

        $rowCalc = \App\CVSS_Nessus::where('idRow', $row->id)->get();
        foreach ($rowCalc as $item){
            $ENVI = app('\App\Http\Controllers\cvss_nessusController')->calculateCVSS_ENVI($item->id);
            return $ENVI;
        }

    }



    function getColor($value, $row){
         if (getfalseOpen($row) == "true"){
         return "";
     }elseif(getfalseNes($row) =="true"){
         return "";

     }else{

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


    function getfalseOpen($row){
        $return = \App\CVSS_OpenVas::where('idRow', $row->id)->value("falsePositive");

        if ($return){
            return "true";
        }
        return "false";
    }


    //Tohle tu byt musi, aby se vypisovalo true/false -> jinak vraci 1/0 - neni tak pekne
    function getfalseNes($row){
        $return = \App\CVSS_Nessus::where('idRow', $row->id)->value("falsePositive");
            if (empty($return)){
                return "false";
            }
            return "true";

    }



    function getFalseColor($return){
     if ($return == "true"){
         return "green";
         }else {
         return "red";
     }

}

    ?>

    <h2 align="center">Výpis záznamů pro {{$IP}}</h2>
    <h3 align="center">Z reportů OpenVAS byly detekovány tyto výsledky: </h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Popis</th>

            <th scope="col">Datum</th>
            <th scope="col">CVSS Envi</th>
            <th scope="col">CVSS Temp</th>
            <th scope="col">false Positive</th>
            <th scope="col">Editovat hodnoty</th>
            <th scope="col">Zobrazit záznam<th>

        </tr>
        </thead>
    <tbody>
        <tr>
        @foreach($reportOP as $row)
                <td>{{$row->NVTName}}</td>
                <td>{{$row->Timestamp}}</td>
                <td style="color: black"  bgcolor="{{getColor($row->ENVI, $row)}}">{{$row->ENVI}}</td>
                <td style="color: black" bgcolor="{{getColor($row->TEMP, $row)}}"  > {{$row->TEMP}}</td>
                <td style="color: black" bgcolor="{{getFalseColor(getfalseOpen($row))}}"  > {{getfalseOpen($row)}}</td>
                <td><a href="../../../../reports/cvss/OpenVas/edit/{{$row->id}}">Editovat</a></td>
                <td><a href="../../../../reports/OpenVas/row/{{$row->id}}">Zobrazit řádek</a></td>

        </tr>
        @endforeach

    </tbody>
    </table>

    <h3 align="center">Z reportů NESSUS byly detekovány tyto výsledky: </h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Popis</th>
            <th scope="col">Datum</th>
            <th scope="col">CVSS Envi</th>
            <th scope="col">CVSS Temp</th>
            <th scope="col">false Positive</th>


            <th scope="col">Editovat hodnoty</th>
            <th scope="col">Zobrazit řádek <th>

        </tr>
        </thead>

        <tr>
            @foreach($reportNES as $row)
                <?php $cvssTEMP = getCVSSTEMP3($row);
                        $cvssENVI = getCVSSENVI3($row);?>

                        <td>{{$row->Name}}</td>
                        <td>{{$row->Timestamp}}</td>
                        <td style="color: black" bgcolor="{{getColor($cvssENVI, $row)}}">{{$cvssENVI}}</td>
                        <td style="color: black" bgcolor="{{getColor($cvssTEMP, $row)}}">{{$cvssTEMP}}</td>
                        <td style="color: black" bgcolor="{{getFalseColor(getfalseNes($row))}}"  > {{getfalseNes($row)}}</td>
                        <td><a href="../../../../reports/cvss/Nessus/edit/{{$row->id}}">Editovat</a></td>
                        <td><a href="../../../../reports/Nessus/row/{{$row->id}}">Zobrazit řádek</a></td>


        </tr>
        @endforeach


    </table>



@endsection