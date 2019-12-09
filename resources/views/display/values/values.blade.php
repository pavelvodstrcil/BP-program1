@extends ('layouts.app')

@section('content')
    <?php

    use App\report_items_nessus ;
    use App\report_items_openvas;
    use App\CVSS_OpenVas;
    use App\CVSS_Nessus;
    use App\device;
    use App\report;


    //abych věděl co mam dale ukazovat a co davat do switche - a co vkladat do formu
    if (!empty($_GET['from']) && !empty($_GET['to'])){
        $from = $_GET['from'];
        $to = $_GET['to'];
        $false = $_GET['false'];

    }
    else{
        $from = 10;
        $to = 10;
        $false = 0;

    }
//nezahrnuje falseP
    if ($false == 0){
    $openvas = DB::table('report_items_openvas')
        ->leftJoin('CVSS_OpenVas', 'report_items_openvas.id', '=', 'CVSS_OpenVas.idRow')->
        where('CVSS_OpenVas.ENVI','<=', $to)->where('ENVI', '>=', $from)->where('CVSS_OpenVas.ignore', false)
        ->where('falsePositive',false)->get();


        $nessus = DB::table('report_items_nessus')
            ->leftJoin('CVSS_Nessus', 'report_items_nessus.id', '=', 'CVSS_Nessus.idRow')->
            where('CVSS_Nessus.ENVI','<=', $to)->where('ENVI', '>=', $from)->where('CVSS_Nessus.ignore', false)
            ->where('CVSS_Nessus.falsePositive',false)->get();
    }
    else
//zahrnuje falseP (chybi na koneci where fP)
        {
            $openvas = DB::table('report_items_openvas')
                ->leftJoin('CVSS_OpenVas', 'report_items_openvas.id', '=', 'CVSS_OpenVas.idRow')->
                where('CVSS_OpenVas.ENVI','<=', $to)->where('ENVI', '>=', $from)->where('CVSS_OpenVas.ignore', false)
                ->orderby('Timestamp', 'dsc')->get();

            $nessus = DB::table('report_items_nessus')
                ->leftJoin('CVSS_Nessus', 'report_items_nessus.id', '=', 'CVSS_Nessus.idRow')->
                where('CVSS_Nessus.ENVI','<=', $to)->where('ENVI', '>=', $from)->where('CVSS_Nessus.ignore', false)->get();


        }



    function getfalseNes($row){
        $return = \App\CVSS_Nessus::where('idRow', $row->idRow)->value("falsePositive");
        if ($return){
            return "true";
        }else {
        return "false";
    }}


    function getfalseOpen($row){
        $return = \App\CVSS_OpenVas::where('idRow', $row->idRow)->value("falsePositive");

        if ($return){
            return "true";
        }else {
       return "false";
        }
    }

    function getValue($false){
        if ($false == 1){
            return "ANO";
        }else
        {return "NE";}
    }



    function getColor($value, $row){

        if (getfalseOpen($row) == "true"){
            return "";
        }elseif(getfalseNes($row) =="true"){
            return "";

        }else{

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
    }}

    function getFalseColor($return){
        if ($return == "true"){
            return "green";
        }else {
            return "red";
        }}



    ?>









    <h1 align="center">Zobrazení dle rozpětí </h1>

    <form class="form-horizontal">
        <fieldset>

            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Hodnota CVSS od:</label>
                <div class="col-md-4">
                    <input id="from" name="from"  value="{{$from}}" type="text"  class="form-control input-md">
                </div>
            </div>


            <div class="form-group row">
                <label class="col-md-4 control-label" for="textinput">Hodnota CVSS do:</label>
                <div class="col-md-4">
                    <input id="to" name="to"  value="{{$to}}" type="text"  class="form-control input-md">
                </div>
            </div>


            <div class="form-group row">
                <label class="col-md-4 control-label" for="textinput">Zahrnout falsePositive záznamy:</label>
                <div class="col-md-4">
                    <select id="false" name="false" class="form-control">
                        <option value="{{$false}}">{{getValue($false)}}</option>
                        <option value="1">ANO</option>
                        <option value="0">NE</option>
                        </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="singlebutton"></label>
                <div class="col-md-4">
                    <button  type="submit" class="btn btn-primary">Hledat</button>
                </div>
            </div>




        </fieldset>
    </form>

    <!-- KONEC FORMULARE! -->

        <table class="table">
            <thead>
            <tr>
                <th scope="col">Popis</th>
                <th scope="col">Report</th>

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

                @foreach($openvas as $row)
<?php
                        $report = report::where('id', $row->idReport)->first();

                    ?>

                    <td>{{$row->NVTName}}</td>
                    <td><a href="/reports/OpenVas/{{$report->id}}">{{$report->name}}</a> </td>
                    <td>{{$row->Timestamp}}</td>
                    <td style="color:black" bgcolor="{{getColor($row->ENVI, $row)}}"> {{$row->ENVI}}</td>
                    <td style="color:black" bgcolor="{{getColor($row->TEMP, $row)}}">{{$row->TEMP}}</td>
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

                @foreach($nessus as $row)
                    <td>{{$row->Name}}</td>
                    <td>Není dostupné</td>
                    <td style="color:black" bgcolor="{{getColor($row->ENVI, $row)}}"> {{$row->ENVI}}</td>
                    <td style="color:black" bgcolor="{{getColor($row->TEMP, $row)}}">{{$row->TEMP}}</td>
                    <td style="color: black" bgcolor="{{getFalseColor(getfalseNes($row))}}"  > {{getfalseNes($row)}}</td>
                    <td><a href="../../../../reports/cvss/Nessus/edit/{{$row->idRow }}">Editovat</a></td>
                    <td><a href="../../../../reports/Nessus/row/{{$row->idRow}}">Zobrazit řádek</a></td>


            </tr>
            @endforeach


        </table>








@endsection

