@extends('layouts.app')

@section('content')

    <?php
            use App\report;
    $reports = report::all();
    $size = $reports->count();
    ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif



           <?php
                        $worstCVSSarray = app('\App\Http\Controllers\HomeController')->getCVSSarray();
                       if (!empty($worstCVSSarray)){
                        $worstCVSSvalue = max($worstCVSSarray);
                       } else {$worstCVSSvalue = 0;}
            ?>
                        <li>Počet nahraných reportů: {{$size}}</li>
                        <li>Nejhorší CVSS ze všech nahraných reportů: {{$worstCVSSvalue}}</li>
                   <<<<<>>>>>>>> GRAF <<<<<>>>>>>>>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
