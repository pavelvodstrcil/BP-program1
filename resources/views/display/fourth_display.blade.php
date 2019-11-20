@extends ('layouts.app')

@section('content')
    <?php

    use App\report_items_nessus ;
    use App\report_items_openvas;
    use App\device;
    use App\CVSS_OpenVas;
    use App\CVSS_Nessus;


    $ipadresses = array();


    $rows = report_items_nessus::all()->pluck("Host");

    foreach ($rows as $row){

      if (!in_array($row, $ipadresses)){
        array_push($ipadresses, $row);
     }
    }

    $rowsOpen = report_items_openvas::all()->pluck("IP");

    foreach ($rowsOpen as $rowoPEN){

       if (!in_array($rowoPEN, $ipadresses)){
         array_push($ipadresses, $rowoPEN);
    }
    }



    $IP_part1 = array();
    foreach ($ipadresses as $ip){

        $ip_parts = explode (".", $ip);

        if ($ip_parts[0] == $NET and $ip_parts[1] == $SUBNET  and $ip_parts[2] == $SUBSUBNET){

            if (!in_array($ip_parts[3], $IP_part1)){
                array_push($IP_part1, $ip_parts[3]);

            }
        }
    }

    //"lidské" seřazení celého pole, aby šlo od 1 po XX
    natsort($IP_part1);


     function isInDevices($NET, $SUBNET, $SUBSUBNET, $row){
         $IPaddress = $NET.".".$SUBNET.".".$SUBSUBNET.".".$row;
        $device = device::where('IP',$IPaddress)->first();

       if (!empty($device)  ){
            return "✓";
        }else {
          return "!!!!! NENALEZENO !!!!";}
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




    <h2 align="center">Výpis sítě {{$NET}}.{{$SUBNET}}.{{$SUBSUBNET}}.0 </h2>
    <h3 align="center">Z reportů byly detekovány tyto výsledky: </h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">IP</th>
            <th scope="col">CVSS OpenVas</th>
            <th scope="col">CVSS Nessus</th>

            <th scope="col">Zaznamenáno v zařízeních?</th>

        </tr>
        </thead>

        <tr>

            @foreach($IP_part1 as $row)

<?php


                    //reporty s danou jednou IP
                $IPaddre = $NET.".".$SUBNET.".".$SUBSUBNET.".".$row;
                $reportsOP = report_items_openvas::where('IP', $IPaddre)->pluck('id');
                $reportsNE = report_items_nessus::where('Host',  $IPaddre)->pluck('id');




                $arrayCVSS = array();
                $arrayCVSSOP = array();







                foreach ($reportsOP as $rowOP){
                    //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                    if (CVSS_OpenVas::where('idRow', $rowOP)->value("falsePositive") != true)
                    {
                        $push = CVSS_OpenVas::where('idRow', $rowOP)->value("ENVI");
                      array_push($arrayCVSSOP, $push);
                    }
               }




                foreach ($reportsNE as $rowNE){

                    //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                    if (CVSS_Nessus::where('idRow', $rowNE)->value("falsePositive") != true)
                    {
                        $push = CVSS_Nessus::where('idRow', $rowNE)->value("ENVI");
                        array_push($arrayCVSS, $push);
                    }
                }




                if (!empty($arrayCVSSOP)){
                $CVSSOP= max($arrayCVSSOP);

                }else {$CVSSOP = 0;}





                if (!empty($arrayCVSS)){
                $CVSSNes= max($arrayCVSS);

                }else {$CVSSNes = 0;}



?>


                <td><a href="{{$SUBSUBNET}}/{{$row}}" >{{$NET}}.{{$SUBNET}}.{{$SUBSUBNET}}.{{$row}}</a></td>
                <td style="color: black;" bgcolor="{{getColor($CVSSOP)}}">{{$CVSSOP}}</td>
                <td style="color: black;" bgcolor="{{getColor($CVSSNes)}}">{{$CVSSNes}}</td>
                <td style="color: red">{{isInDevices($NET, $SUBNET, $SUBSUBNET, $row)}}</td>
        </tr>
        @endforeach
    </table>



@endsection
