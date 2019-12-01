@extends ('layouts.app')

@section('content')


    <?php
    use App\device;

    use App\report_items_openvas;
    use App\report_items_nessus;

    use App\CVSS_OpenVas;
    use App\CVSS_Nessus;


    function getColor($value){


        if($value == 0.0 ){
            return  "#00ff00";
        }elseif($value <=3.9 and $value >= 0.1) {
            return "#00ff33";
        }elseif ($value <=6.9 and $value >= 4.0){
            return   "#ff6600";
        }elseif($value <= 8.9and $value >= 7.0) {
            return "#ff0033";
        }elseif ($value <=10.0 and $value >= 9.0){
            return "#990000";
            }elseif ($value == -1){
            return "";
        }else {return "";}
    }
  $devices = App\device::all();
    $vypis = array();

    ?>




<h1 align="center">Zobrazení CVSS pro uložená zařízení</h1>
    <h3 align="center">Řazení: <a href="?filter=#">OpenVas</a>      <a href="?filter=Nessus">Nessus</a> </h3>

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
           <?php


                $reportsOV=report_items_openvas::where('IP', $device->IP)->pluck('id');
                $reportsNE=report_items_nessus::where('Host', $device->IP)->pluck('id');
                $CVSSs = array();
                $CVSSs_NE=array();


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


                }}



                        if (empty($CVSSs)){
                    $worstCVSS = -1;
                }else {
                    $worstCVSS = max($CVSSs);
                }


                if (empty($CVSSs_NE)){
                    $worstCVSS_NE = -1;
                }else {
                    $worstCVSS_NE = max($CVSSs_NE);
                }
                //do odkazu na zobrazeni CVSS - vyuziva funkci jiz vytvorenou
                $ipAdress = str_replace(".", "/", $device->IP);



               $objekt = (object)array("name" => $device->name, "IP" => $device->IP, "CVSSO" => $worstCVSS, "CVSS_NE" => $worstCVSS_NE);
               array_push($vypis, $objekt);


                ?>


        @endforeach

        <?php


        if (!empty($_GET['filter'])){

            function comparator($object1, $object2) {

                return $object1->CVSS_NE < $object2->CVSS_NE;
            }

        }
        else {

            function comparator($object1, $object2) {

                return $object1->CVSSO < $object2->CVSSO;
            }

        }




        usort($vypis, 'comparator');



        ?>

        @foreach($vypis as $row)
        <tr>
        <td>{{$row->name}}</td>
        <td><a href="../../display/{{$ipAdress}}">{{$row->IP}}</td>
        <td style="color:black" bgcolor="{{getColor($row->CVSSO)}}"> {{$row->CVSSO}}</td>
        <td style="color:black" bgcolor="{{getColor($row->CVSS_NE)}}">{{$row->CVSS_NE}}</td>
        @endforeach

        </tr>



    </table>

    <h5 align="center">Hodnota -1 znamená žádný záznam CVSS!!!!</h5>
    <h4 align="center">Kliknutím na IP adresu zobrazíte všechny CVSS záznamy k dané IP!</h4>


@endsection