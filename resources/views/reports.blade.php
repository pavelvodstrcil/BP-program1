@extends ('layouts.app')

@section('content')


    <?php

    //vypsani vsech, ale serazenych podle ID -> aby bylo od nejhovejsich a stránkování po 10
    $reports = App\report::orderBy('id', 'desc')->paginate(10);


    function getColorMiss($value){
        if($value >= 1 ){
            return "RED";
        }

    }

    function getIgnore($val){
        if ($val == true){
            return "Ignorován!";
        }
            else
                {return "NE";}
        }




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
    ?>

    <h2 align="center">Výpis všech reportů</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Název reportu</th>
            <th scope="col">Verze a porty</th>
            <th scope="col">CVSS</th>
            <th scope="col">Nejhorší CVSS</th>
            <th scope="col">Neznámé IP</th>
            <th scope="col">Akce/ info</th>
            <th scope="col">Ignorován?</th>
            <th scope="col">Scanner</th>
            <th scope="col">Datum nahrátí</th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <?php $count = 1; ?>
            @foreach($reports as $report)

                <?php
                //dohledani jmena autora a nazvu scanneru
                $user = App\User::find($report->user)->name;
                $scanner = App\scanner::find($report->scanner)->name;

                //orezani mezer, aby fungovala obre routa
                $scanner = str_replace(' ', '', $scanner);

                $worstCVSS = app('\App\Http\Controllers\reportsController')->getWorstCVSS($report->id);
                $missingDevices = app('\App\Http\Controllers\reportsController')->getMissingDevices($report->id);
                $missingDevicesCount = sizeof($missingDevices);


                ?>
                <th scope="row">{{$count}}</th>
                <td><a href="reports/{{$scanner}}/{{$report->id}}">{{$report->name}}</a></td>
                <td><a href="reports/{{$scanner}}/process/{{$report->id}}">Zobrazit</a></td>
                <td><a href="reports/cvss/{{$scanner}}/{{$report->id}}">CVSS</a></td>
                <td style="color: black"  bgcolor="{{getColor($worstCVSS)}}">{{$worstCVSS}}</td>
                <td style="color: black"  bgcolor="{{getColorMiss($missingDevicesCount)}}"><a style="color:black" data-toggle="modal" data-target="#missing{{$report->id}}" >
                        {{$missingDevicesCount}} - zobrazit</a></td>

                <td>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Akce
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li>Přepočítat CVSS</li>
                            <li><a href="reports/ignore/{{$report->id}}">Změna statusu ignorovat</a></li>
                            <li>Uživatel: {{$user}}</li>
                            <li><a data-toggle="modal" data-target="#delete{{$report->id}}">SMAZAT report</a></li>
                        </ul>
                    </div>
                </td>



                    <td>{{getIgnore($report->ignore)}}</td>
                    <td>{{$scanner}}</td>
                    <td>{{$report->date}}</td>
        </tr>
        <?php $count++; ?>
        <!-- MODAL SMAZANI -->
        <div id="delete{{$report->id}}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 align="center" style="color: RED;" class="modal-title">Opravdu chcete
                            smazat {{$report->name}}</h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                        <a href="reports/delete/{{$report->id}}">
                            <button type="button" class="btn btn-secondary">ANO</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>


                <!-- MODAL NEZANAME ZARIZENI -->
                <div id="missing{{$report->id}}" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 align="center"  class="modal-title">Detekované neznámé IP z reportu</h4>
                            </div>

                            <div class="modal-body">
                                @foreach($missingDevices as $deviceIP)
                                    <li>{{$deviceIP}}</li>
                                    @endforeach
                                Ping trvá delší dobu, pokud jsou zařízení nedostupná....
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
                               @if($missingDevicesCount > 0)
                                <a href="missingDevices/{{$report->id}}">
                                    <button type="button" class="btn btn-secondary">Spustit ping..</button>
                                </a>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
        @endforeach
        </tbody>
    </table>


    <div align="center">{{$reports->links()}}</div>




@endsection