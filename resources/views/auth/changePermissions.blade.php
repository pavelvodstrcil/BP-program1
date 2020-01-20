@extends('layouts.app')

@section('content')

<?php
$permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "users_permissions");

$permissions = \App\permissions::all();

?>
@if ($permission)
<h1 align="center" >Úprava oprávnění</h1>






<table class="table">
    <thead>
    <tr>
        <th scope="col">Název skupiny</th>
        <th scope="col">Zobrazení reportů</th>
        <th scope="col">Nahrávání reportů</th>
        <th scope="col">Úprava CVSS</th>
        <th scope="col">Přidávání zařízení</th>
        <th scope="col">Úprava zařízení</th>
        <th scope="col">Mazání reportů</th>
        <th scope="col">Změna oprávnění</th>
        <th scope="col">Správa uživatelů</th>
        <th scope="col">EDIT</th>


    </tr>
    </thead>
    <tbody>
    <tr>
        <?php $count = 1; ?>
            @foreach($permissions as $row)




            <td>{{$row->name}}</td>
            <td>{{$row->report_display}}</td>
            <td>{{$row->report_upload}}</td>
            <td>{{$row->report_CVSS}}</td>
            <td>{{$row->device_add}}</td>
            <td>{{$row->device_edit}}</td>
            <td>{{$row->report_delete}}</td>
            <td>{{$row->users_permissions}}</td>
            <td>{{$row->users}}</td>
            <td><a  href="permissions/{{$row->id}}">Editovat</a></td>



    </tr>



    @endforeach
    </tbody>
</table>



@else
    <h1 align="center" > Na tuto operaci namáte oprávnění!  :-)</h1>
@endif



@endsection