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
echo $from;
    if ($false == 0){
    $openvas = DB::table('report_items_openvas')
        ->leftJoin('CVSS_OpenVas', 'report_items_openvas.id', '=', 'CVSS_OpenVas.idRow')->
        where('CVSS_OpenVas.ENVI','<=', $to)->where('ENVI', '>=', $from)->where('CVSS_OpenVas.ignore', false)
        ->where('falsePositive',false)->get();
    }
    else

        {
            $openvas = DB::table('report_items_openvas')
                ->leftJoin('CVSS_OpenVas', 'report_items_openvas.id', '=', 'CVSS_OpenVas.idRow')->
                where('CVSS_OpenVas.ENVI','<=', $to)->where('ENVI', '>=', $from)->where('CVSS_OpenVas.ignore', false)->get();

        }

   // $openvas = CVSS_OpenVas::where('ENVI', '<=', $to)->where('ENVI', '>=', $from)->get();
    $nessus = array();

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

    ?>









    <h1 align="center">Vyhledávání</h1>

    <form class="form-horizontal">
        <fieldset>

            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Hodnota CVSS od:</label>
                <div class="col-md-4">
                    <input id="from" name="from"  value="{{$from}}" type="text"  class="form-control input-md">
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Hodnota CVSS do:</label>
                <div class="col-md-4">
                    <input id="to" name="to"  value="{{$to}}" type="text"  class="form-control input-md">
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label" for="selectbasic">Zahrnout i falsePositive záznamy</label>
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


                    <td>{{$row->NVTName}}</td>
                    <td>{{$row->Timestamp}}</td>
                    <td>{{$row->ENVI}}</td>
                    <td> {{$row->TEMP}}</td>
                    <td> {{getfalseOpen($row)}}</td>
                    <td><a href="../../../../reports/cvss/OpenVas/edit/{{$row->id}}">Editovat</a></td>
                    <td><a href="../../../../reports/OpenVas/row/{{$row->id}}">Zobrazit řádek</a></td>

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
                    <td>{{$row->ENVI}}</td>
                    <td>{{$row->TEMP}}</td>
                    <td> {{getfalseNes($row)}}</td>
                    <td><a href="../../../../reports/cvss/Nessus/edit/{{$row->idRow }}">Editovat</a></td>
                    <td><a href="../../../../reports/Nessus/row/{{$row->idRow}}">Zobrazit řádek</a></td>


            </tr>
            @endforeach


        </table>








@endsection

