@extends ('layouts.app')



@section('content')



    <?php
    use App\user;
    use App\permissions;

    if (!empty($_GET['search'])) {
        $search = $_GET['search'];
        $users = \App\user::where('name', 'ilike', '%' . $search . '%')->orwhere('username', 'ilike', '%' . $search . '%')->paginate(15);
    } else {

        $users = \App\user::orderBy('id', 'ASC')->paginate(15);
    }


    ?>

    <?php
    $permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "report_CVSS");

    ?>

    @if ($permission)
    <h2 align="center">Správa uživatelů</h2>


    <br>
    @if($errors->any())
        <div align="center" class="alert alert-danger">
            {{$errors->first()}}
            </div>
    @endif

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <form class="form-horizontal" action="#" method="GET">

        <div class="form-group">
            <label class="control-label col-sm-2">Hledání:</label>
            <div class="col-md-4">
                <input type="text" maxlength="20" class="form-control" name="search">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Hledat</button>
            </div>
        </div>
    </form>



    <table class="table">
        <thead>
        <tr>
            <th scope="col">Jméno</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Oprávnění</th>
            <th scope="col">EDIT</th>
            <th scope="col">Smazání</th>


        </tr>
        </thead>
        <tbody>
        <tr>
            <?php $count = 1; ?>
            @foreach($users as $user)

                <?php $premission = \App\permissions::find($user->permissions)->name;
                ?>
                <th scope="row">{{$user->name}}</th>
                <td>{{$user->username}} </td>
                <td>{{$user->email}}</td>
                <td>{{$premission}}</td>

                <td><a  href="edit/{{$user->id}}">Editovat</a></td>
                <td><a data-toggle="modal" href="#delete{{$user->id}}">SMAZAT</a></td>


        </tr>




        <div id="delete{{$user->id}}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 align="center" style="color: RED;" class="modal-title">Opravdu chcete
                            smazat {{$user->name}}</h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zrušit</button>
                        <a href="delete/{{$user->id}}">
                            <button type="button" class="btn btn-secondary">ANO</button>
                        </a>


                    </div>
                </div>


        @endforeach
        </tbody>
    </table>


    <div align="center">{{$users->links()}}</div>

    @else
        <h1 align="center" > Na tuto operaci namáte oprávnění!  :-)</h1>
    @endif


@endsection
