@extends ('layouts.app')

@section('content')
    <?php

    $rows = \App\report_items_openvas::where('idReport', $id)->get();

    // Pole adres, které jsou v reportu, celé objeky "jen jednou"
    $addresses = \App\report_items_openvas::where('idReport',$id)->select('IP')->distinct()->get();


    //vytvoření polí s příslužnými IP v názvu
    foreach ($addresses as $ad){
        $addre = $ad->IP;
        $portsArray[$addre] = array();
        $servicesArray[$addre]= array();
        $othersArray[$addre]= array();
    }

        //pole, ktere delaji kontrolu, zda uz bylo zaznamenano, nebo ne
    $array_polozky = array();
    $array_porty = array();

    //služby  a porty
    foreach($rows as $row){

        $string = preg_split('/ version:/', $row->SpecificResult, -1, PREG_SPLIT_NO_EMPTY);
        $name = preg_split('/[\s]+/', $row->NVTName, -1, PREG_SPLIT_NO_EMPTY);


            if (!empty($string[1])){

            $verze = preg_split('/[\s]+/', $string[1], -1, PREG_SPLIT_NO_EMPTY);
            $pair = $row->IP.' '. $row->Port. ' '. $verze[0]. ' '. $name[0];
            $infoVerze = $name[0].' '.  $verze[0]. ' Port: '. $row->Port;

            if (!in_array($pair, $array_polozky)){
                //kontrolani pole, kde se ukladaji jiz zaznamenane polozky
                array_push($array_polozky, $pair);

                array_push($servicesArray[$row->IP], $infoVerze);


            }

        }

        //PORTY do array
        if ($row->Port != null){

        $pairPort = $row->IP .' '. $row->Port;

        if (!in_array($pairPort, $array_porty)){
            array_push($array_porty, $pairPort);
            $port = $row->Port;
           array_push($portsArray[$row->IP], $port);



        }


    }}




    ?>






    @foreach($addresses as $ad)
        <h3 >INFO o: {{$ad->IP}} </h3>


        <h4>Nalezené služby:</h4>
        @foreach($servicesArray[$ad->IP] as $row)
            <li>{{$row}}</li>
        @endforeach

        <h4></h4>
        @foreach($othersArray[$ad->IP] as $row)
            <li>{{$row}}</li>
        @endforeach

        <h4>Otevřené porty:</h4>
        @foreach($portsArray[$ad->IP] as $row)
            <li>{{$row}}</li>
        @endforeach
        <br>

    @endforeach



@endsection
