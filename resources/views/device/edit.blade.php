@extends ('layouts.app')


@section('content')

    <?php
    $permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "device_edit");
    $deviceedit = App\device::find($id);
    $devices = App\device_type::all();
    ?>

    @if ($permission)


    <h2 align="center">Editace zařízení</h2>



    <form class="form-horizontal" action="update" method="POST">
        <input type="hidden" name="ID" value="{{$id}}" >
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" >Název zařízení:</label>
            <div class="col-sm-10">
                <input type="text" maxlength="50" value="{{$deviceedit->name}}" class="form-control" name="Name">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" >IPv4</label>
            <div class="col-sm-10">
                <input type="text" maxlength="15" value="{{$deviceedit->IP}}" minlength="7" class="form-control" name="IP" placeholder="IPv4 adresa pouze!">
            </div>
        </div>

        <div class="form-group">
            <label  class="control-label col-sm-2"  >Typ zařízení:</label>
            <div class="col-sm-10" >
                <select class="form-control"  name="type">
                        <option value="{{$deviceedit->type}}" >{{$devices->find($deviceedit->type)->name}}</option>
                    @foreach($devices as $device)
                        <option value="{{$device->id}}">{{$device->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label  class="control-label col-sm-2"  >Kritičnost</label>
            <div class="col-sm-10" >
                <select class="form-control" name="criticality">

                    <option value="{{$deviceedit->criticality}}">{{$deviceedit->criticality}}</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>

                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Popis zařízení:</label>
            <div class="col-sm-10">
                <input type="text" value="{{$deviceedit->description}}" class="form-control" name="description">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" >Umístění</label>
            <div class="col-sm-10">
                <input type="text"  value="{{$deviceedit->location}}" class="form-control" name="location" >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" >Poznámky:</label>
            <div class="col-sm-10">
                <input type="text" value="{{$deviceedit->notes}}" class="form-control" name="notes" >
            </div>
        </div>

            <div><h3 align="center">Hodnoty CVSS</h3></div>
        <div class="form-group">
            <label class="control-label col-sm-2" >Collateral Damage Potential (CDP)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="CDP">
                    <option value="{{$deviceedit->CDP}}">{{$deviceedit->CDP}}</option>
                    <option value="N" >None</option>
                    <option value="L" >Low</option>
                    <option value="LM" >Low - Medium</option>
                    <option value="MH" >Medium - High</option>
                    <option value="H" >High</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Target Distribution (TD)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="TD">
                    <option value="{{$deviceedit->TD}}">{{$deviceedit->TD}}</option>
                    <option value="N" >None</option>
                    <option value="L" >Low</option>
                    <option value="M" >Medium</option>
                    <option value="H" >High</option>
                    <option value="ND" >Not Defined</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Confidentiality Requirement (CR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="CR">
                    <option value="{{$deviceedit->CR}}">{{$deviceedit->CR}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Integrity Requirement (IR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="IR">
                    <option value="{{$deviceedit->IR}}">{{$deviceedit->IR}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Availability Requirement(AR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="AR">
                    <option value="{{$deviceedit->AR}}">{{$deviceedit->AR}}</option>
                    <option value="ND" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>



        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Zpracovat</button>
            </div>
        </div>
    </form>

    @else
        <h1 align="center" >Na tuto operaci namáte oprávnění!  :-)</h1>
    @endif

@endsection
