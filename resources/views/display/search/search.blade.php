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
    if (!empty($_GET['search']) && !empty($_GET['type'])){
        $search = $_GET['search'];
       $type = $_GET['type'];
    }

    else{
        $search = "";
        $type = 0;
    }


    switch ($type) {
        case 0:
            break;
        case 1:
            $openvas = DB::table('report_items_openvas')
                ->leftJoin('CVSS_OpenVas', 'report_items_openvas.id', '=', 'CVSS_OpenVas.idRow')->
                where('report_items_openvas.CVEs', $search)->where('CVSS_OpenVas.ignore', false)->get();
            $nessus = DB::table('report_items_nessus')
                ->leftJoin('CVSS_Nessus', 'report_items_nessus.id', '=', 'CVSS_Nessus.idRow')->
                where('report_items_nessus.CVE', $search)->where('CVSS_Nessus.ignore', false)->get();
            break;
        case 2:
            echo "i equals 2";
            break;
        default:
            $openvas=array();
            $nessus = array();
    }





    function getfalseOpen($row){
        $return = \App\CVSS_OpenVas::where('idRow', $row->id)->value("falsePositive");

        if ($return){
            return "true";
        }
        return "false";
    }

    ?>









<h1 align="center">Vyhledávání</h1>


    <form class="form-horizontal">
        <fieldset>

            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Hledaný výraz:</label>
                <div class="col-md-4">
                    <input id="search" name="search"  value="{{$search}}" type="text"  class="form-control input-md">
                       </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label" for="selectbasic">Hledat mezi:</label>
                <div class="col-md-4">
                    <select id="type" name="type" class="form-control">
                        <option value="{{$type}}">Vyberte prosím</option>
                        <option value="1">CVE</option>
                        <option value="2">IP</option>
                        <option value="3">řádky reportu - název</option>
                        <option value="lol">Zkusím štěstí</option>
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
   @if ($type == 1)
        <h3 align="center">Pro výraz: {{$search}} byly nalezeny tyto výsledky pro OpenVas: </h3>
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
                <?php
                print_r($nessus);
                ?>
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




    @endif




@endsection