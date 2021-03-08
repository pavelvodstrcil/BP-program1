@extends ('layouts.app')

@section('content')
    <?php


    use App\device_groups;
    use App\device_groups_asoc;
    use App\device;

    use App\report_items_openvas;
    use App\report_items_nessus;

    use App\CVSS_OpenVas;
    use App\CVSS_Nessus;



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
            return " ";
        }
    }


    $name = device_groups::find($id)->name;

    $devices = device_groups_asoc::where('idGroup', $id)->get();

    ?>


    <h2 align="center">Zobrazení skupiny: {{$name}}</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Zařízení</th>
            <th scope="col">IP</th>
            <th scope="col">Nejvyšší CVSS OpenVas</th>
            <th scope="col">Nejvyšší CVSS Nessus</th>
        </tr>
        </thead>

        @foreach($devices as $device)
            <tr>
                <?php
                $dev = device::find($device->idDevice);


                $reportsOV = report_items_openvas::where('IP', $dev->IP)->pluck('id');
                $reportsNE = report_items_nessus::where('Host', $dev->IP)->pluck('id');

                $CVSSs = array();
                $CVSSs_NE = array();


                foreach ($reportsOV as $row) {

                    //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                    if (CVSS_OpenVas::where('idRow', $row)->value("falsePositive") != true) {
                        $push = CVSS_OpenVas::where('idRow', $row)->value("ENVI");
                        array_push($CVSSs, $push);
                    }
                }

                if (empty($CVSSs)) {
                    $worstCVSS = "záznam nenalezen";
                } else {
                    $worstCVSS = max($CVSSs);
                }


                foreach ($reportsNE as $row) {

                    //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                    if (CVSS_Nessus::where('idRow', $row)->value("falsePositive") != true) {
                        $push = CVSS_Nessus::where('idRow', $row)->value("ENVI");
                        array_push($CVSSs_NE, $push);
                    }
                }
                if (empty($CVSSs_NE)) {
                    $worstCVSS_NE = "záznam nenalezen";
                } else {
                    $worstCVSS_NE = max($CVSSs_NE);
                }



                //do odkazu na zobrazeni CVSS - vyuziva funkci jiz vytvorenou
                $ipAdress = str_replace(".", "/", $dev->IP);

                ?>
                <td>{{$dev->name}}</td>
                <td><a href="../../display/{{$ipAdress}}">{{$dev->IP}}</td>
                <td style="color:black" bgcolor="{{getColor($worstCVSS)}}"> {{$worstCVSS}}</td>
                <td style="color:black" bgcolor="{{getColor($worstCVSS_NE)}}"> {{$worstCVSS_NE}}</td>





            </tr>
        @endforeach
    </table>

    <h4 align="center">Kliknutím na IP adresu zobrazíte všechny CVSS záznamy k dané IP!</h4>
@endsection
