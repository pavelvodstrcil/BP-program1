<?php

namespace App\Http\Controllers;


use App\device_groups;
use App\device_groups_asoc;
use Illuminate\Http\Request;

class device_groupsController extends Controller
{
    public function addNew(Request $request)
    {

        $group = new device_groups();

        $group->name = $request->name;

        $group->save();

        $message = "Skupina ".$request->name." byla vytvořena!";
        return view('display.goups.manage.show_groups')->with('message', $message);

    }

    function addToGroup($id, $id2){

        if (device_groups_asoc::where('idGroup', '=', $id)->where('idDevice', '=', $id2)->exists()) {
            $message = "Záznam již existuje. Zkontrolujte své zadání!";
        }else {

        $record = new device_groups_asoc();
        $record->idGroup = $id;
        $record->idDevice=$id2;
        $record-> save();
        $message  = "Záznam uložen, toto okno můžete zavřit a pokračovat s přidáváním...";
        }
        return view('message')->with('message', $message)->with('close', true);
    }

    public function search(Request $request)
    {
       return view('display.goups.manage.add_to_group')->with('id', $request->id)->with('search', $request->name);

    }

    public function destroy($id)
    {

        try {
            $group = device_groups::find($id);

        } catch (Exception $e){}

        $return =  device_groups::destroy($id);



        if ($return == 1){
            $message = "Smazání skupiny \"$group->name\" proběhlo OK";}
        else {
            $message = "Smazání neproběhlo, prosím opakujte akci";}


        return view('display.goups.manage.show_groups')->with('message', $message);
    }
}
