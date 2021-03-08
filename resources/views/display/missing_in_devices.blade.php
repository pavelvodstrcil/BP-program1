@extends ('layouts.app')

@section('content')
    <?php
    use \App\report;
    use App\device;
    use App\report_items_openvas;
    use App\report_items_nessus;
    $report = App\report::where('id', $id)->first();
    $scanner = $report->scanner;
    $name = $report->name;


    //vsechny IP z tabulky zarizeni
    $IPsDevices = device::all()->pluck("IP")->toArray();

    $missingIPs = array();


    //OpenVas
    if ($scanner == 1) {
        $IPs = report_items_openvas::where('idReport', $id)->pluck("IP")->toArray();
        $items = report_items_openvas::where('idReport', $id)->get();
    }


    //Nessus
    if ($scanner == 2) {
        $IPs = report_items_nessus::where('idReport', $id)->pluck("Host")->toArray();
        $items = report_items_nessus::where('idReport', $id)->get();
    }



    //prochazim vsechny IP - pokud chybi, pridam do pole
    foreach ($IPs as $IP) {


        if (!in_array($IP, $IPsDevices)) {
            array_push($missingIPs, $IP);
        }

    }

    $missingIPs1 = array_unique($missingIPs);


    ?>
    <h2 align="center">Detekované neznámí IP adresy z reporu {{$name}}</h2>


   <table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">IP</th>
        <th scope="col">počet záznamů v reportu</th>
        <th scope="col">Dostupnost (PING)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($missingIPs1 as $IP)

        <?php

        $indexes = array_keys($missingIPs, $IP); //array(0, 1)
        $value = count($indexes);

        exec("ping -c 1 $IP", $output, $status);

        ?>
        <tr>
        <th scope="row">{{$IP}}</th>
        <td>{{$value}}</td>
            @if ($status == 0)
                <th align="center" style="color:green">odpovídá</th>
            @else
                <th align="center" style="color:red" >NEODPOVÍDÁ</th>
            @endif


    </tr>
    @endforeach


    </tbody>
    </table>

    <h3 align="center">Pokud si přejete tyto zařízení ignorovat, přidejte ho do seznamu zařízení!</h3>
    <h4 align="center">Jedná se o neautorizované zařízení!!!!</h4>

@endsection