@extends ('layouts.app')

@section('content')

    <?php

    $row = App\report_items_nessus::all()->where('id', $row);
    ?>
    @foreach($row as $item)

<h2 align="center">Detailní výpis zvoleného řádku</h2>
<h3><a class="btn btn-primary" href="{{ URL::previous() }}">Zpět do reportu</a></h3>

    <div class="panel panel-default">
        <div class="panel-heading">Host/IP</div>
        <div class="panel-body">
           {{$item->Host}}
        </div>
    </div>


<div class="panel panel-default">
    <div class="panel-heading">Port + protokol</div>
    <div class="panel-body">
        {{$item->Port}} {{$item->Protocol}}
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Name</div>
    <div class="panel-body">
        {{$item->Name}}
    </div>



</div>


<div class="panel panel-default">
    <div class="panel-heading">Risk</div>
    <div class="panel-body">
        {{$item->Risk}}
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Synopsis</div>
    <div class="panel-body">
        {{$item->Synopsis}}
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Description</div>
    <div class="panel-body">
        {{$item->Description}}
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">Solution</div>
    <div class="panel-body">
        {{$item->Solution}}
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">See Also</div>
    <div class="panel-body">
        {{$item->SeeAlso}}
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Plugin Output</div>
    <div class="panel-body">
        {{$item->PluginOutput}}
    </div>
</div>



<div class="panel panel-default">
    <div class="panel-heading">CVE + CVSS z reportu</div>
    <div class="panel-body">
        {{$item->CVE}}  {{$item->CVSS}}
    </div>
</div>


    @endforeach
@endsection