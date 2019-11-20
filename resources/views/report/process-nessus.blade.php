@extends ('layouts.app')

@section('content')
<?php

 $rows = \App\report_items_nessus::where('idReport', $id)->get();

 // Pole adres, které jsou v reportu, celé objeky "jen jednou"
 $addresses = \App\report_items_nessus::where('idReport',$id)->select('Host')->distinct()->get();


//vytvoření polí s příslužnými IP v názvu
 foreach ($addresses as $ad){
$addre = $ad->Host;
$portsArray[$addre] = array();
$servicesArray[$addre]= array();
$othersArray[$addre]= array();
 }


    ?>



<!-- procházení vsech radek  -->
@foreach($rows as $row)
    <!-- projití vsech IP adress -> aby byly po grupách -->
    @foreach($addresses as $add)
   @if($row->Host == $add->Host)

    @switch($row->PluginID)

        @case(11219)
        <!-- Otevrene porty -->
        <?php   $words = preg_split('/[\s]+/',$row->PluginOutput , -1, PREG_SPLIT_NO_EMPTY);
        array_push($portsArray[$row->Host], $words[1]);
        ?>


        @break

        <!-- FTP detection -->
        @case(10092)
        <?php  $words = preg_split('/[\s]+/',$row->PluginOutput , -1, PREG_SPLIT_NO_EMPTY);
        $string = "FTP detekovano: ". $words[10].' '.$words[11];
        array_push($othersArray[$row->Host], $string);
        ?>

        @break

        <!-- SSH server type and version -->
        @case(10267)
        <?php  $words = preg_split('/[\s]+/',$row->PluginOutput , -1, PREG_SPLIT_NO_EMPTY);
                $string = "SSH detekovano: ".$words[3];
                array_push($othersArray[$row->Host], $string);
        ?>
        @break

        <!-- HTTP server and type ver -->
        @case(10107)
        <?php  $words = preg_split('/[\s]+/',$row->PluginOutput , -1, PREG_SPLIT_NO_EMPTY);
        $string = "HTTP server ". $words[7]. " na portu: ".$row->Port;
        array_push($othersArray[$row->Host], $string);
        ?>

        @break

        <!--  Service detection on port -->
        @case(22964)
        <?php $words = preg_split('/[\s]+/',$row->PluginOutput , -1, PREG_SPLIT_NO_EMPTY);
        $service = $words[1].' '. $words[2]. ' port: '.$row->Port;
        array_push($servicesArray[$row->Host], $service);
        ?>
      <!--  <p>{{$row->Host}}:{{$row->Port}}-> {{$words[1]}} {{$words[2]}}</p> -->
        @break

        <!-- -->

       @default

@endswitch

    @endif
    @endforeach

@endforeach



    @foreach($addresses as $ad)
   <h3 style="text-underline: ">INFO o :{{$ad->Host}} </h3>


    <h4>Nalezené služby:</h4>
   @foreach($servicesArray[$ad->Host] as $row)
       {{$row}}<br>
   @endforeach

   <h4></h4>
   @foreach($othersArray[$ad->Host] as $row)
       {{$row}}<br>
   @endforeach

    <h4>Otevřené porty:</h4>
   @foreach($portsArray[$ad->Host] as $row)
       {{$row}}<br>
   @endforeach

    @endforeach



@endsection
