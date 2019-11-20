@extends ('layouts.app')



@section('content')



    <?php


    if (!empty($_GET['search'])){
        $search = $_GET['search'];
        $devices = \App\device::where('name', 'ilike', '%'.$search.'%')->orwhere('IP', 'ilike', '%'.$search.'%')->paginate(15);
    }

    else{

        $devices = \App\device::paginate(15);
    }


    ?>

    <h2 align="center">Vypis uložených zařízení</h2>


    <br>


    <form class="form-horizontal" action="#" method="GET">

            <div class="form-group">
                <label class="control-label col-sm-2" >Zadejte název nebo IP:</label>
                <div class="col-md-4">
                    <input type="text"  maxlength="20" class="form-control" name="search">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Hledat</button>
                </div>
            </div>
    </form>



    <table class="table">
        <thead>
        <tr>
            <th scope="col">IP</th>
            <th scope="col">Název</th>
            <th scope="col">Typ</th>
            <th scope="col">Popis</th>
            <th scope="col">Umístění</th>
            <th scope="col">Kritičnost</th>


            <th scope="col">akce</th>
            <th scope="col">Poznámky</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <?php $count = 1; ?>
            @foreach($devices as $device)

                <?php $devicetype = App\device_type::find($device->type)->name;
                ?>
                <th scope="row">{{$device->IP}}</th>
                <td>{{$device->name}} </td>
                <td>{{$devicetype}}</td>
                <td>{{$device->description}}</td>
                <td>{{$device->location}}</td>
                <td>{{$device->criticality}}</td>

            <td>

                    <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Akce
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li><a target="_blank" href="ping/{{$device->id}}">Pingnout</a></li>
                                <li><a  data-toggle="modal" data-target="#nmap{{$device->id}}">NMAP -sV</a> </li>
                                <li><a href="nmap2/{{$device->id}}" target="_blank" >NMAP bez přepínače</a> </li>
                                <li><a data-toggle="modal" href="edit/{{$device->id}}">Upravit</a>  </li>
                                <li role="separator" class="divider"></li>
                                <li><a  data-toggle="modal" data-target="#delete{{$device->id}}">Smazat</a></li>
                            </ul>
                        </div>


                </td>

                    <td>{{$device->notes}}</td>

        </tr>
        <?php $count++; ?>



        <div id="nmap{{$device->id}}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 align="center" style="color: RED;" class="modal-title">Spuštění NMAP {{$device->name}}</h4>
                    </div>
                    <div class="modal-body">
                      Tato operace bude trvat déle. Otevře se nová záložka, kde bude nmap pracovat.
                        Během této operace můžete aplikaci používat dále. Pro zobrazení výsleků
                        se vraťte na nově otevřenou záložku.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                        <a target="_blank" href="nmap/{{$device->id}}">   <button data-dismis="modal"  type="button" class="btn btn-secondary">SPUSTIT</button></a>
                    </div>
                </div>
            </div>
        </div>


                <div id="delete{{$device->id}}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 align="center" style="color: RED;" class="modal-title">Opravdu chcete smazat {{$device->name}}</h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                     <a href="delete/{{$device->id}}">   <button type="button" class="btn btn-secondary">ANO</button></a>
                    </div>
                </div>


        @endforeach
        </tbody> </table>
        <p align="center">Funkce PING se otevře v novém okně, může trvat déle, prosím o strpení!</p>

        <div align="center">{{$devices->links()}}</div>




@endsection
