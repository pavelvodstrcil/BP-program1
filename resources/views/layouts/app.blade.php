<!DOCTYPE html>
<!-- <html lang="{{ app()->getLocale() }}"> -->
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">

                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Přihlásit') }}</a>
                            </li>
                        @else
                            <li><div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Import reportů
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="/import/openvas">OpenVas import</a></li>
                                    <li><a href="/import/nessus">Nessus import</a></li>
                                </ul>
                            </div></li>



                        <li><a href="/reports">Reporty</a></li>

                        <li><div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Zobrazení sítě
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

                    <!-- Uzivatele dropdown -->
                            <li><div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Uživatelé
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li><a href="/register">Přidat nového uživatele</a></li>
                                        <li><a href="/users/change">Změna vlastního hesla</a></li>
                                        <li><a href="/users/premissions">Změna oprávnění</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="/users/show">Správa uživatelů</a></li>
                                    </ul>
                                </div></li>



                        <li>      <a class="btn btn-secondary" href="{{ URL::previous() }}">Zpět</a>  </li>



                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Přihlásit</a></li>

                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Odhlásit
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                    <li><a href="/users/change">Změna vlastního hesla</a></li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

