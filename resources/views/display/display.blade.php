@extends ('layouts.app')

@section('content')
    <?php

    use App\report_items_nessus ;
    use App\report_items_openvas;
    use App\device;

    $IP = $NET.".".$SUBNET.".".$SUBSUBNET.".".$HOST;

    //udělat kontrolu falsePositive-> pokud true -> ignorovat řádek
            // přidat tlačítko pro editaci
            // e nebo neignorovat, ale řadit na konec? ? ?


    $reportOP = report_items_openvas::where('IP', $IP)->get();
    $reportNES = report_items_nessus::where('Host', $IP)->get();

    //serazeni? podle zavaznosti?
    // odkaz na otevreni celeho radku
    // odkaz na editaci CVSS? ? ?
    // vytvorit objekt? asi radek (aby to pak slo radit?)
    //

 function getCVSSEVNVI($row){

     $rowCalc = \App\CVSS_OpenVas::where('idRow', $row->id)->get();
     foreach ($rowCalc as $item){
         $ENVI = app('\App\Http\Controllers\cvss_openvasController')->getENVI($item->id);
        return $ENVI;
     }

 }
    function getCVSSTEMP($row){

           $rowCalc = \App\CVSS_OpenVas::where('idRow', $row->id)->get();
        foreach ($rowCalc as $item){
         $TEMP = app('\App\Http\Controllers\cvss_openvasController')->getTEMP($item->id);
        return $TEMP;
     }

    }

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
        //tady pak jen pridat nebo a pridat nessus
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
        $return = \App\CVSS_OpenVas::where('idRow', $row->id)->get();
    foreach ($return as $ret){
        if (empty($ret->falsePositive)){
            return "false";
        }
        return "true";
    }
    }

    function getfalseNes($row){
        $return = \App\CVSS_Nessus::where('idRow', $row->id)->get();
        foreach ($return as $ret){
            if (empty($ret->falsePositive)){
                return "false";
            }
            return "true";
        }
    }



    function getFalseColor($return){

     if ($return == "true"){
         return "green";
         }else {return "red";}

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
    <?php

               $cvss2TEMP = getCVSSTEMP($row);
                 $cvss2ENVI = getCVSSEVNVI($row);

                ?>

                <td>{{$row->NVTName}}</td>

                <td>{{$row->Timestamp}}</td>
                <td style="color: black"  bgcolor="{{getColor($cvss2ENVI, $row)}}">{{$cvss2ENVI}}</td>
                <td style="color: black" bgcolor="{{getColor($cvss2TEMP, $row)}}"  > {{$cvss2TEMP}}</td>
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
                    @if ( !empty($cvssTEMP) )
                        <td>{{$row->Name}}</td>
                        <td>{{$row->Timestamp}}</td>
                        <td style="color: black" bgcolor="{{getColor($cvssENVI, $row)}}">{{$cvssENVI}}</td>
                        <td style="color: black" bgcolor="{{getColor($cvssTEMP, $row)}}">{{$cvssTEMP}}</td>
                        <td style="color: black" bgcolor="{{getFalseColor(getfalseNes($row))}}"  > {{getfalseNes($row)}}</td>
                        <td><a href="../../../../reports/cvss/Nessus/edit/{{$row->id}}">Editovat</a></td>
                        <td><a href="../../../../reports/Nessus/row/{{$row->id}}">Zobrazit řádek</a></td>
                    @endif

        </tr>
        @endforeach


    </table>



@endsection