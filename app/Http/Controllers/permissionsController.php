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


    }

 function   update(Request $request){



         $edit = permissions::find($request->id);
         $edit->report_display = $request->report_display;
         $edit->report_upload = $request->report_upload;
         $edit->report_CVSS = $request->report_CVSS;
         $edit->device_add = $request->device_add;
         $edit->device_edit = $request->device_edit;
         $edit->report_delete = $request->report_delete;
       $edit->users_permissions = $request->users_permissions;
       $edit->users = $request->users;


         $edit->save();

         return redirect()->back()->with('message', 'Změna proběhla OK...');


     }

}


