@extends ('layouts.app')

@section('content')

    <!--Vyhledávací okno se zobrazuje poze na prvni strane  -->
@if (empty($search))
    <form class="form-horizontal" action="{{$id}}/search" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" >Zadejte název nebo IP:</label>
            <div class="col-sm-10">
                <input type="text" required="required" maxlength="50" class="form-control" name="name">
                <input type="hidden" name="id" value="{{$id}}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Hledat</button>
            </div>
        </div>
    </form>
@endif

    @if (!empty($search))
        Výsledky hledání:
        <h3><a class="btn btn-primary" href="{{ URL::previous() }}">Zpět</a></h3>
        <?php

        $devices = \App\device::where('name', 'ilike', '%'.$search.'%')->orwhere('IP', 'ilike', '%'.$search.'%')->get();

        ?>
        @else
        <?php
        $devices = \App\device::all();
        ?>
    @endif



    <table class="table">
        <thead>
        <tr>
            <th scope="col">Název zařízení</th>
            <th scope="col">IP adresa</th>
            <th scope="col">Typ zařízení</th>
            <th scope="col">Přidat zařízení</th>

        </tr>
        </thead>

        <tr>
            @foreach($devices as $device)
                <?php $devicetype = App\device_type::find($device->type)->name;                ?>
                <td>{{$device->name}}</td>
                <td>{{$device->IP}}</td>
                <td>{{$devicetype}}</td>
                <td><a target="_blank" href="{{$id}}/add/{{$device->id}}">Přidat</a></td>




        </tr>

          @endforeach

    </table>



@endsection
