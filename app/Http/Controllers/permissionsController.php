<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\permissions;

class permissionsController extends Controller
{
    public function getPermission($user, $permission)
    {
        $user = $user->permissions;


      $perm =  permissions::where('id', $user)->value($permission);

    return $perm;

  //    if ($perm->$permission == 1 ){
   //       return true;
   //   }else {return false;}



    }
}
