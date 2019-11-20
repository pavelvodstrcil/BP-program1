@extends ('layouts.app')

@section('content')


    <?php
    use App\device_type;
    use App\device;

    use App\report_items_openvas;
    use App\report_items_nessus;

    use App\CVSS_OpenVas;
    use App\CVSS_Nessus;


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
        }else {return " ";}
    }


    $types = device_type::all();

    if (!empty($_GET['type'])){
        $typeSelected = $_GET['type'];
        $devices = device::where('criticality',$typeSelected)->get();
        $message = "";

    }
    else {
        $devices = array();
        $message = "Prosím vyberte skupinu kritičnosti...";
    }





    ?>




    <h1 align="center" >Vybrat skupinu kritičnosti:</h1>
    <div align="center">
        <form  action="#"  >
            <select size="1" name="type">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>

            </select>
            </select><input type="submit" value="Zobrazit"> </form>

    </div>

    <h1 align="center" style="color:red">{{$message}}</h1>

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


                $reportsOV=report_items_openvas::where('IP', $device->IP)->pluck('id');
                $reportsNE=report_items_nessus::where('Host', $device->IP)->pluck('id');

                $CVSSs = array();
                $CVSSs_NE = array();


                foreach ($reportsOV as $row){

                    //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                    if (CVSS_OpenVas::where('idRow', $row)->value("falsePositive") != true)
                    {
                        $push = CVSS_OpenVas::where('idRow', $row)->value("ENVI");
                        array_push($CVSSs, $push);
                    }
                }


                foreach ($reportsNE as $row){

                    //kontrola, jestli je radek falsePositive, pokud jo, tak preskocit a nepridavat do pole na vyhodniceni
                    if (CVSS_Nessus::where('idRow', $row)->value("falsePositive") != true)
                    {
                        $push = CVSS_Nessus::where('idRow', $row)->value("ENVI");
                        array_push($CVSSs_NE, $push);
                    }
                }


                if (empty($CVSSs)){
                    $worstCVSS = "Žádný záznam CVSS";
                }else {
                    $worstCVSS = max($CVSSs);
                }


                if (empty($CVSSs_NE)){
                    $worstCVSS_NE = "Žádný záznam CVSS";
                }else {
                    $worstCVSS_NE = max($CVSSs_NE);
                }

                //do odkazu na zobrazeni CVSS - vyuziva funkci jiz vytvorenou
                $ipAdress = str_replace(".", "/", $device->IP);

                ?>
                <td>{{$device->name}}</td>
                <td><a href="../../display/{{$ipAdress}}">{{$device->IP}}</td>
                <td style="color:black" bgcolor="{{getColor($worstCVSS)}}"> {{$worstCVSS}}</td>
                <td style="color:black" bgcolor="{{getColor($worstCVSS_NE)}}">{{$worstCVSS_NE}}</td>


            </tr>
        @endforeach
    </table>

    <h4 align="center">Kliknutím na IP adresu zobrazíte všechny CVSS záznamy k dané IP!</h4>

@endsection