@extends('layouts.app')

@section('content')


<?php

use App\device;
use App\User;
use App\permissions;
use App\permissionsController;






$permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "report_display");



print_r($permission);

if ($permission){

    echo "polozeno";
}else {

    echo "naser siiiii";
}

?>

    @endsection