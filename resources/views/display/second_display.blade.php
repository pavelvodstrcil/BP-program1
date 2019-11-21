@extends ('layouts.app')

@section('content')
    <?php

    use App\report_items_nessus ;
    use App\report_items_openvas;


    $ipadresses = array();

    $rows = report_items_nessus::all()->pluck("Host");

    foreach ($rows as $row){

        if (!in_array($row, $ipadresses)){
            array_push($ipadresses, $row);
        }
    }

    $rowsOpen = report_items_openvas::all()->pluck("IP");

    foreach ($rowsOpen as $row){

        if (!in_array($row, $ipadresses)){
            array_push($ipadresses, $row);
        }
    }


    $IP_part1 = array();
    foreach ($ipadresses as $ip){
        $ip_parts = explode (".", $ip);

        if ($ip_parts[0] == $NET){
            if (!in_array($ip_parts[1], $IP_part1)){
                array_push($IP_part1, $ip_parts[1]);

            }
        }
    }



    function getColor($value){


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
    }



    ?>





    <h2 align="center">Vyberte prosím další kus sítě: z {{$NET}}.0.0.0 </h2>
    <h3 align="center">Z reportů byly detekovány tyto sítě:</h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Síť</th>
            <th scope="col">CVSS OpenVas</th>
            <th scope="col">CVSS Nessus</th>
        </tr>
        </thead>

        <tr>

            @foreach($IP_part1 as $row)





                <?php



                 $IPaddr = $NET.'.'.$row;

                $reportsOP = report_items_openvas::where('IP', 'like', $IPaddr.'%')->get();
                $reportsNes = report_items_nessus::where('Host', 'like', $IPaddr.'%')->get();

                $arrayCVSS = array();
                $arrayCVSSOP = array();



                foreach ($reportsNes as $rowNes){
                    // vybitam jen radky s "zacatkem" dane site, pouze ty, ktere jsou falsePositive null nebo false
                    $rowCalc = \App\CVSS_Nessus::where('idRow', $rowNes->id)->where('ignore', false)->where('falsePositive' ,false)->get();

                    foreach ($rowCalc as $item){
                        $ENVI = app('\App\Http\Controllers\cvss_nessusController')->getENVI($item->id);

                        array_push($arrayCVSS, $ENVI);
                    }

                }


                foreach ($reportsOP as $rowOP){

                    $rowCalc = \App\CVSS_OpenVas::where('idRow', $rowOP->id)->where('ignore', false)->where('falsePositive' ,false)->get();
                    foreach ($rowCalc as $item){
                        $ENVI = app('\App\Http\Controllers\cvss_openvasController')->getENVI($item->id);

                        array_push($arrayCVSSOP, $ENVI);
                    }

                }


                if (!empty($arrayCVSSOP)){
                    $CVSSOP= max($arrayCVSSOP);

                }else {$CVSSOP = 0;}





                if (!empty($arrayCVSS)){
                    $CVSSNes= max($arrayCVSS);

                }else {$CVSSNes = 0;}











                ?>










                <td><a href="{{$NET}}/{{$row}}" >{{$NET}}.{{$row}}.0.0</a></td>

                    <td style="color: black;" bgcolor="{{getColor($CVSSOP)}}">{{$CVSSOP}}</td>
                    <td style="color: black;" bgcolor="{{getColor($CVSSNes)}}">{{$CVSSNes}}</td>
        </tr>
        @endforeach
    </table>



@endsection
