@extends ('layouts.app')

@section('content')
    <?php


    use App\device_groups;
    USE App\device_groups_asoc;
    use App\device;
    use App\report_items_openvas;
    use App\report_items_nessus;
    use App\CVSS_OpenVas;
    use App\CVSS_Nessus;

    $groups = App\device_groups::all();



    function getColor($value)
    {


        if ($value == 0.0) {
            return "#00ff00";
        } elseif ($value <= 3.9) {
            return "#00ff33";
        } elseif ($value <= 6.9) {
            return "#ff6600";
        } elseif ($value <= 8.9) {
            return "#ff0033";
        } elseif ($value <= 10.0) {
            return "#990000";
        } else {
            return "";
        }
    }




    ?>


    <h2 align="center">Vyberte prosím skupinu...</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Skupina</th>
            <th scope="col">Zobrazit</th>

            <th scope="col">Nejvyšší CVSS OpenVas</th>
            <th scope="col">Nejvyšší CVSS Nessus</th>
        </tr>
        </thead>

        <tr>

            @foreach($groups as $group)
                <?php

                $ipAdresses = array();
                $idDevices = array();
                $idDevices = device_groups_asoc::where('idGroup', $group->id)->pluck('idDevice');
                //ziskani vsech IP ve skupine
                foreach ($idDevices as $device) {
                    array_push($ipAdresses, device::where('id', $device)->value("IP"));

                }


                //Projetí OpenVas + Nessus reportů - kde jsou IP ze skupiny

                //vsechny reporty od vsech IP ve skupine
                $reportsOV_ALL = array();
                $reportsNE_ALL = array();
                foreach ($ipAdresses as $ipAdr) {
                    //reporty od jedne IP do mega pole
                    $reportsOV = report_items_openvas::where('IP', $ipAdr)->pluck('id');
                    $reportsNE = report_items_nessus::where('Host', $ipAdr)->pluck('id');

                    array_push($reportsOV_ALL, $reportsOV);
                    array_push($reportsNE_ALL, $reportsNE);
                }



                //CVSS pro OpenVas
                $CVSSs = array();
                //CVSS pro Nessus
                $CVSSs_NE = array();

                //OpenVas
                foreach ($reportsOV_ALL as $reports){
                    foreach ($reports as $row){

                    //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                    if (CVSS_OpenVas::where('idRow', $row)->value("falsePositive") != true)
                    {
                        $push = CVSS_OpenVas::where('idRow', $row)->value("ENVI");
                        array_push($CVSSs, $push);
                    }
                }}


                if (empty($CVSSs)){
                    $worstCVSS = "záznam nenalezen";
                }else {
                    $worstCVSS = max($CVSSs);
                }

                //Nessus
                foreach ($reportsNE_ALL as $reports){
                    foreach ($reports as $row){

                        //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                        if (CVSS_Nessus::where('idRow', $row)->value("falsePositive") != true)
                        {
                            $push = CVSS_Nessus::where('idRow', $row)->value("ENVI");
                            array_push($CVSSs_NE, $push);
                        }
                    }}


                if (empty($CVSSs_NE)){
                    $worstCVSS_NE = "záznam nenalezen";
                }else {
                    $worstCVSS_NE = max($CVSSs_NE);
                }

                ?>






                <td><a href="display/{{$group->id}}">{{$group->name}}</td>
                <td><a href="display/{{$group->id}}">Zobrazit</td>

                <td style="color:black" bgcolor="{{getColor($worstCVSS)}}">{{$worstCVSS}}</td>
                <td style="color:black" bgcolor="{{getColor($worstCVSS_NE)}}">{{$worstCVSS_NE}}</td>


        </tr>
        @endforeach
    </table>


@endsection
