@extends ('layouts.app')

@section('content')
<?php
$permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "report_upload");
?>

 @if ($permission)
<form action="openvas/fileupload" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    Vyberte prosím soubor reportu z OpenVas:
   <br/> Operace může trvat déle, současně s nahrátím reportu probíhá dohledání položek pro CVSS.
    <br />
    <input type="file" name="cvs_file" />
    <br /><br />
    <input type="submit" value=" Nahrát " />
</form>
@else
    <h1 align="center" > Na tuto operaci namáte oprávnění!  :-)</h1>
    @endif
    @endsection