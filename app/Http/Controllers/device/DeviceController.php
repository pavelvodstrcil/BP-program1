<?php

namespace App\Http\Controllers\device;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\device;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('device.addNew');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $value = $request;
                    $arraydata[] = [
                    'IP' => $value->IP,
                    'name' => $value->Name,
                    'type' => $value->type,
                    'description' => $value->description,
                    'location' => $value->location,
                    'notes' => $value->notes,
                    'criticality'=>$value->criticality,
                                   ];

            if (!empty($arraydata)) {
               DB::table('device')->insert($arraydata);

               return view('message')->with('message', 'Nové zařízení bylo přidáno!');


            }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('device.edit')->with('id', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

            $edit= device::find($request->ID);

            $edit->IP = $request->IP;
            $edit->name= $request->Name;
            $edit ->type = $request->type;
            $edit->description= $request->description;
            $edit->location = $request->location;
            $edit->notes = $request->notes;
            $edit ->TD = $request->TD;
            $edit -> IR = $request->IR;
            $edit -> CR = $request->CR;
            $edit-> AR = $request->AR;
            $edit->CDP = $request->CDP;
            $edit->criticality = $request->criticality;

            $edit-> save();

            $message= "Změna probehla v pořádku...";
            return view('message')->with('message', $message);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
        $device = device::find($id);

        } catch (Exception $e){}

       $return =  device::destroy($id);



        if ($return == 1){
        $message = "Smazání zařízení \"$device->name\" proběhlo OK";}
        else {
              $message = "Smazání neproběhlo, prosím opakujte akci";}


        return view('message')->with('message', $message);
    }
}
