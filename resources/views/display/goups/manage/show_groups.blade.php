@extends ('layouts.app')

@section('content')

    <?php
    $groups = App\device_groups::all();
    ?>

    <p><a  data-toggle="modal" data-target="#newGroup"> <button type="button" class="btn btn-default">
                Vytvořit novou skupinu</button></a></p>

    <!--  Pokud je predana zrava, tka se sobrazi, jinak ne :-) -->
    @if (!empty($message))
    <h2>Výsledek požadavku: {{$message}}</h2>
    <?php
    header( "refresh:5;url=../show" );
    ?>
    @endif

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif



    <table class="table">
        <thead>
        <tr>
            <th scope="col">Název skupiny</th>
            <th scope="col">Přidat zařízení do skupiny</th>
            <th scope="col">Zobrazit obsah skupiny</th>
            <th scope="col">SMAZAT</th>

        </tr>
        </thead>

        <tr>
            @foreach($groups as $group)
            <td>{{$group->name}}</td>
            <td><a href="addToGroup/{{$group->id}}">Přidat zařízení</a> </td>
            <td><a  data-toggle="modal" data-target="#show{{$group->id}}">Zobrazit</a></td>
            <td><a  data-toggle="modal" data-target="#delete{{$group->id}}">Smazat</a></td>


        </tr>
    <!--    //MODAL MAZANI  -->
         <div id="delete{{$group->id}}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 align="center" style="color: RED;" class="modal-title">Opravdu chcete smazat {{$group->name}}</h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                        <a href="delete/{{$group->id}}">   <button type="button" class="btn btn-secondary">ANO</button></a>
                    </div>
                </div>
            </div>
        </div>
                <!-- MODAL ZOBRAZENI -->
                <div id="show{{$group->id}}" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 align="center" style="color: RED;" class="modal-title">Zobrazení skupiny {{$group->name}}</h4>
                            </div>
                            <div class="modal-body" >
                               <?php
                                $devices = App\device_groups_asoc::where('idGroup', '=', $group->id)->get();
                                ?>
                                @foreach($devices as $device)
                                    <?php
                                       $deviceName = App\device::where('id', '=', $device->idDevice)->value('name');


                                       ?>

                                       <li>{{$deviceName}} - <a href="deleteFromGroup/{{$device->id}}">Smazat se skupiny</a></li>

                                 @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                            </div>
                        </div>
                    </div>
                </div>



        @endforeach

    </table>



    <!-- MODAL nová skupina  -->
    <div id="newGroup" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 align="center" style="color: RED;" class="modal-title">Vytvoření nové skupiny</h4>
                </div>
                <div class="modal-body">




                    <form class="form-horizontal" action="add/addNew" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-sm-2" >Název skupiny:</label>
                            <div class="col-sm-10">
                                <input type="text" maxlength="50" class="form-control" name="name">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Vytvořit</button>
                            </div>
                        </div>
                    </form>





                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                </div>
            </div>
        </div>
    </div>


@endsection