@extends ('layouts.app')
@section('content')


<?php


        $IP = App\device::find($id)->IP;

       $IPsub = substr($IP,0,15);
       exec("ping -c 3 $IPsub", $output, $status);
?>
    <h1 align="center">PING {{$IP}}</h1>

    @if ($status == 0)
       <h2 align="center" style="color:green">IP adresa {{$IPsub}} je OK a odpovídá</h2>
    @else
        <h2 align="center" style="color:red" >IP adresa {{$IPsub}} NEODPOVÍDÁ</h2>
    @endif

<h3 align="center">Výpis:</h3>
@foreach($output as $out)
<p align="center">{{$out}}</p>
@endforeach

    <p align="center"><a class="btn btn-primary"  href="javascript:self.close();">Zavřít PING</a></p>

@endsection