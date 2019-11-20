@extends ('layouts.app')
@section('content')


    <?php


    $IP = App\device::find($id)->IP;

    $IPsub = substr($IP,0,15);
    exec("nmap -sV $IPsub", $output, $status);
    ?>
    <h1 align="center">NMAP pro {{$IP}}</h1>


    <h3 align="center">Výpis:</h3>
    @foreach($output as $out)
        <p>{{$out}}</p>
    @endforeach

    <p align="center"><a class="btn btn-primary"  href="javascript:self.close();">Zavřít okno</a></p>

@endsection