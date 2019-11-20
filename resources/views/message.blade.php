@extends ('layouts.app')



@section('content')

<h1 align="center">Výsledek požadavku:</h1>

    <h2 align="center">{{$message}}</h2>

        <!-- Kdyz nebude promenna prazda, ukaze se tlacitko zavrit :-) -->
    @if (!empty($close))

        <p align="center"><a class="btn btn-primary"  href="javascript:self.close();">Zavřít okno</a></p>

    @endif

@endsection