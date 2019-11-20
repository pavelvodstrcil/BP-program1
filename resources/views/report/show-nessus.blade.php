@extends ('layouts.app')

@section('content')

    <?php

    $report = App\report_items_nessus::where('idReport', $id)->paginate(15);

    ?>



    <div align="center">{{$report->links()}}</div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Host/IP</th>
            <th scope="col">Protocol</th>
            <th scope="col">Port</th>
            <th scope="col">Risk</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">CVE</th>
            <th scope="col">CVSS Report</th>
            <th scope="col">Zobrazit celý řádek</th>

        </tr>
        </thead>
        <tbody>
        <tr>
            @foreach($report as $item)
            <th scope="row"> <a  data-toggle="modal" data-target="#myModal{{$item->id}}">{{$item->Host}}</a></th>
            <td>{{$item->Protocol}}</td>
            <td>{{$item->Port}}</td>
            <td>{{$item->Risk}}</td>
            <td>{{$item->Name}}</td>
            <td>{{$item->Description}}</td>
            <td>{{$item->CVE}}</td>
            <td>{{$item->CVSS}}</td>
            <td><a href="row/{{$item->id}}">Zobrazit</a> </td>
        </tr>





        <div id="myModal{{$item->id}}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Info o IP {{$item->Host}}</h4>
                    </div>
                    <div class="modal-body">
                        <?php

                        $ip = str_replace(' ', '', $item->Host);
                        $dev = App\device::where('IP', $ip)->get();


                     ?>
                            @if ($dev == "[]")
                                <h2 align="center">Toto zařízení nebylo nalezeno!</h2>
                                <h3 align="center">Není uloženo mezi uloženými zařízeními...</h3>
                            @endif
                        @foreach($dev as $de)
                              <?php   $type = App\device_type::find($de->type)->name; ?>

                        <p>Název: {{$de->name}}</p>
                        <p>IP:{{$ip}}</p>
                        <p>Typ:{{$type}}</p>
                        <p>Popis: {{$de->description}}</p>
                        <p>Umístění: {{$de->location}}</p>
                        <p>Poznámky: {{$de->notes}}</p>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>

            </div>





        @endforeach
         </tbody>
    </table>


    </div>

    <div align="center">{{$report->links()}}</div>
@endsection



