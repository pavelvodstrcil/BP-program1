<!
<html lang="cs-CZ">
<head>
    <meta charset="UTF-8">
    <meta name="Analyza Reportu"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <script src="{{asset('js/app.js')}}"></script>

    <script src="{{asset('js/bootstrap.js')}}"></script>




    <title>{{config('app.name', 'Analyza reportu')}}</title>
</head>
<body>



<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://bakalarka.jcu">{{config('app.name')}}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">


                <li>
                <li><div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Import reportů
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="/import/openvas">OpenVas import</a></li>
                            <li><a href="/import/nessus">Nessus import</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Neco neco </a></li>
                        </ul>
                    </div></li></li>
                <li><a href="/reports">Nahrane reporty</a></li>

                <li><div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Zobrazezí sítě
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="/display">Zobrazení dle sítí</a></li>
                            <li><a href="/groups/display">Zobrazení dle skupin</a></li>
                            <li><a href="#">Zobrazení dle kritérií</a></li>
                            <li><a href="/types/display">Zobrazení dle typu zařízení</a></li>
                            <li><a href="/criticality/display">Zobrazení dle kritičnosti</a></li>
                            <li><a href="/device/show/CVSS">Zobrazit všechny zařízení</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/groups/manage/show">Správa skupin zařízení</a></li>
                        </ul>
                    </div></li></li>




                <li><div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Zařízení
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="/device/show">Zobrazit zařízení</a></li>
                            <li><a href="/device/add">Přidat zařízení</a></li>
                        </ul>
                    </div></li>


                <li>      <a class="btn btn-secondary" href="{{ URL::previous() }}">Zpět</a>  </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

@yield ('content')

</body>
</html>