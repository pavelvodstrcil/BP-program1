@extends ('layouts.app')

<?php
$devices = App\device_type::all();
?>

@section('content')

    <?php
    $permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "device_add");
    ?>

    @if ($permission)

<h2 align="center">Přidání zařízení</h2>



<form class="form-horizontal" action="store" method="POST">
    {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label col-sm-2" >Název zařízení:</label>
            <div class="col-sm-10">
                <input type="text" maxlength="50" class="form-control" name="Name">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" >IPv4</label>
            <div class="col-sm-10">
                <input type="text" maxlength="15"  minlength="7" class="form-control" name="IP" placeholder="IPv4 adresa pouze!">
            </div>
        </div>

    <div class="form-group">
        <label  class="control-label col-sm-2"  >Typ zařízení:</label>
       <div class="col-sm-10" >
           <select class="form-control" name="type">
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

                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>

            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" >Popis zařízení:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="description">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" >Umístění</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="location" >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" >Pznámky:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="notes" >
        </div>
    </div>

        <div><h3 align="center"> Hodnoty pro výpočet CVSS zadejte prosím v editaci zařízení</h3></div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Zpracovat</button>
            </div>
        </div>
    </form>

    @else
        <h1 align="center" > Na tuto operaci namáte oprávnění!  :-)</h1>
    @endif

    @endsection
