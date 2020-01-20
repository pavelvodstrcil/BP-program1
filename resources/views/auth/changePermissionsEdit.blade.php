@extends('layouts.app')

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif


<?php
    use App\permissions;
        $permission = permissions::find($id);
$permissionUser = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "users_permissions");
        print_r($permission);
    ?>

@if ($permissionUser)


@if (empty($permission))
<h2 align="center">Taková skupina uživatelů neexistije... zkuste to znovu....</h2>
@else

        @if($id == 1)

        <h2 align="center">Skupina FULL nelze měnit!!!</h2>


        @else
            <form class="form-inline" method="POST" action="edit/update">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$id}}" >
            <h3>{{$permission->name}}</h3>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Zobrazení reportů:</label>
                <select name="report_display" class="form-control" id="exampleFormControlSelect1">
                    <option value="{{$permission->report_display}}">{{$permission->report_display}} </option>
                    <option value="" >0</option>
                    <option value="1">1</option>

                </select>
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Nahrávání reportů:</label>
                <select name="report_upload" class="form-control" id="exampleFormControlSelect1">
                    <option>{{$permission->report_upload}} </option>
                    <option>0</option>
                    <option>1</option>

                </select>
            </div>


            <div class="form-group">
                <label for="exampleFormControlSelect1">Úprava CVSS:</label>
                <select name="report_CVSS" class="form-control" id="exampleFormControlSelect1">
                    <option>{{$permission->report_CVSS}} </option>
                    <option>0</option>
                    <option>1</option>

                </select>
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Přidávání zařízení:</label>
                <select name="device_add" class="form-control" id="exampleFormControlSelect1">
                    <option>{{$permission->device_add}} </option>
                    <option>0</option>
                    <option>1</option>

                </select>
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Úprava zařízení:</label>
                <select name="device_edit" class="form-control" id="exampleFormControlSelect1">
                    <option>{{$permission->device_edit}} </option>
                    <option>0</option>
                    <option>1</option>

                </select>
            </div>



            <div class="form-group">
                <label for="exampleFormControlSelect1">Mazání reportů:</label>
                <select name="report_delete" class="form-control" id="exampleFormControlSelect1">
                    <option>{{$permission->report_delete}} </option>
                    <option>0</option>
                    <option>1</option>

                </select>
            </div>


            <div class="form-group">
                <label for="exampleFormControlSelect1">Změna oprávnění:</label>
                <select name="users_permissions" class="form-control" id="exampleFormControlSelect1">
                    <option>{{$permission->users_permissions}} </option>
                    <option>0</option>
                    <option>1</option>

                </select>
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Správa uživatelů:</label>
                <select name="users" class="form-control" id="exampleFormControlSelect1">
                    <option>{{$permission->users}} </option>
                    <option>0</option>
                    <option>1</option>

                </select>
            </div>

                <p align="center">   <button class="btn btn-default btn-success"  type="submit" name="submit" value="Submit">Uložit změny</button>
                </p>

            </form>


        @endif
        @endif

@else
    <h1 align="center" > Na tuto operaci namáte oprávnění!  :-)</h1>
@endif




@endsection