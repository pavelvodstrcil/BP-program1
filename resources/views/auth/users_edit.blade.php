
@extends ('layouts.app')



@section('content')

<?php
        use \App\user;
        use App\permissions;
$user = \App\user::find($id);
$permissionALL = permissions::all();
$premission = \App\permissions::find($user->permissions)->name;
?>

<h1 align="center">Změna uživatele</h1>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

                    <form class="form-horizontal" method="POST" action="save">
                        {{ csrf_field() }}
                       <input type="hidden" name="ID" value="{{$user->id}}">
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Celé jméno</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}" required autofocus>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{$user->username}}" required autofocus>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{$user->email}}" required>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="permissions" class="col-md-4 control-label">Typ uživatele</label>

                            <div class="col-md-6">

                                <select name="permissions" id="permissions" class="form-control form-control-lg">
                                    <option value="{{$user->permissions}}" >{{$premission}}</option>
                                    @foreach($permissionALL as $item)
                                        <option value="{{$item->id}}" >{{$item->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>


                     <p align="center">   <button class="btn btn-default btn-success"  type="submit" name="submit" value="Submit">Uložit</button>
                     </p>
                    </form>



@endsection