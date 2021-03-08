@extends ('layouts.app')

@section('content')
    <?php





    $IP = $NET.".".$SUBNET.".".$SUBSUBNET.".".$HOST;


    $reportOP = DB::table('report_items_openvas')
        ->leftJoin('CVSS_OpenVas', 'report_items_openvas.id', '=', 'CVSS_OpenVas.idRow')->
        where('report_items_openvas.IP', $IP)->where('CVSS_OpenVas.ignore', false)->get();




    $reportNES   = DB::table('report_items_nessus')
        ->leftJoin('CVSS_Nessus', 'report_items_nessus.id', '=', 'CVSS_Nessus.idRow')->
        where('report_items_nessus.Host', $IP)->where('CVSS_Nessus.ignore', false)->get();



    //serazeni? podle zavaznosti?
    // odkaz na otevreni celeho radku
    // odkaz na editaci CVSS? ? ?
    // vytvorit objekt? asi radek (aby to pak slo radit?)
    //





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

    //Tohle tu byt musi, aby se vypisovalo true/false -> jinak vraci 1/0 -aspoň se muze zmenit text...
    function getfalseOpen($row){
        $return = \App\CVSS_OpenVas::where('idRow', $row->id)->value("falsePositive");

        if ($return){
            return "true";
        }
        return "false";
    }


    //Tohle tu byt musi, aby se vypisovalo true/false -> jinak vraci 1/0 - neni tak pekne
    function getfalseNes($row){
        $return = \App\CVSS_Nessus::where('idRow', $row->idRow)->value("falsePositive");
            if ($return){
                return "true";
            }
            return "false";

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
                <td><a href="../../../../reports/cvss/OpenVas/edit/{{$row->idRow}}">Editovat</a></td>
                <td><a href="../../../../reports/OpenVas/row/{{$row->idRow}}">Zobrazit řádek</a></td>

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
                        <td>{{$row->Name}}</td>
                        <td>Není dostupné</td>
                        <td style="color: black" bgcolor="{{getColor($row->ENVI, $row)}}">{{$row->ENVI}}</td>
                        <td style="color: black" bgcolor="{{getColor($row->TEMP, $row)}}">{{$row->TEMP}}</td>
                        <td style="color: black" bgcolor="{{getFalseColor(getfalseNes($row))}}"  > {{getfalseNes($row)}}</td>
                        <td><a href="../../../../reports/cvss/Nessus/edit/{{$row->idRow }}">Editovat</a></td>
                        <td><a href="../../../../reports/Nessus/row/{{$row->idRow}}">Zobrazit řádek</a></td>


        </tr>
        @endforeach


    </table>



@endsection