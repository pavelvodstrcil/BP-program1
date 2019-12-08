<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

//groupa, aby vsechny routy byly za prihlasenim!
Route::group(['middleware' => ['auth']], function () {


    /*
    |--------------------------------------------------------------------------
    | Routy k reportum a zpracování
    |--------------------------------------------------------------------------
    |
    |Routy k reportům, ke cteni, uprave a pod.
    |
    */

Route::get ('/reports', function(){

   return view('reports');
})->middleware('auth');

route::get ('/reports/delete/{id}', 'reportsController@delete');
route::get ('reports/OpenVas/{id}', function($id){
    return view('report.show-openvas')->with('id', $id);

});
route::get ('reports/Nessus/{id}', function($id){
    return view('report.show-nessus')->with('id', $id);

});
route::get ('reports/Nessus/row/{row}', function( $row){
    return view('report.show-row-nessus')->with('row', $row);

});

route::get ('reports/OpenVas/row/{row}', function( $row){
    return view('report.show-row-openvas')->with('row', $row);

});

route::get ('reports/OpenVas/process/{id}', function ($id){
    return view('report.process-openvas')->with('id',$id);
});


route::get ('reports/Nessus/process/{id}', function ($id){
    return view('report.process-nessus')->with('id', $id);
});

route::get ('reports/ignore/{id}', 'reportsController@changeIgnore' );


/*
|--------------------------------------------------------------------------
| Routy k importu
|--------------------------------------------------------------------------
|
|Routy jsou dvoje, jedny k Nessusu a druhé k Openvasu
|
*/



Route::get('/import/openvas', 'import\ImportOpenvasController@index');
Route::post('/import/openvas/fileupload', 'import\ImportOpenvasController@fileupload');

Route::get('/import/nessus', 'import\ImportNessusController@index');
Route::post('/import/nessus/fileupload', 'import\ImportNessusController@fileupload');






/*
|--------------------------------------------------------------------------
| DEVICE
|--------------------------------------------------------------------------
|
|Routy k zařízení vše
|
*/
Route::get('/device/show', function () {
    return view('device.show');
});
Route::resource('/device/add', 'device\DeviceController');
Route::post('/device/store', 'device\DeviceController@store');

Route::get ('/device/edit/{id}', 'device\DeviceController@edit');
Route::post ('/device/edit/update', 'device\DeviceController@update');

Route::get ('/device/ping/{id}', function ($id){
    return view('device.ping')->with('id', $id);
});
Route::get ('device/delete/{id}', 'device\DeviceController@destroy');

Route::get('/device/nmap/{id}', function($id){
    return view('device.nmap')->with('id', $id);
});

Route::get('/device/nmap2/{id}', function($id){
    return view('device.nmap2')->with('id', $id);
});




/*
|--------------------------------------------------------------------------
| CVSS routy
|--------------------------------------------------------------------------
|
|Routy okolo CVSS
|
*/

route::get ('reports/cvss/Nessus/{idReport}', function($idReport){
    return view('report.cvss_nessus')->with('idReport', $idReport);
});


Route::get ('reports/cvss/Nessus/edit/{id}', 'cvss_nessusController@edit');

Route::post ('reports/cvss/Nessus/edit/update', 'cvss_nessusController@update');


route::get ('reports/cvss/OpenVas/{idReport}', function($idReport){
    return view('report.cvss_openvas')->with('idReport', $idReport);
});

Route::get ('reports/cvss/OpenVas/edit/{id}', 'cvss_openvasController@edit');

Route::post ('reports/cvss/OpenVas/edit/update', 'cvss_openvasController@update');


/*
|--------------------------------------------------------------------------
| Vykreslovani site dle siti
|--------------------------------------------------------------------------
|
|Routy okolo vykreslovani
|
*/

//první fáze
Route::get ('display/', function(){
    return view('display.first_display');
});

Route::get ('display/{NET}', function($NET){
    return view('display.second_display')->with('NET', $NET);
});


Route::get ('display/{NET}/{SUBNET}', function($NET,$SUBNET ){
    return view('display.third_display')->with('NET', $NET)->with('SUBNET', $SUBNET);
});


Route::get ('display/{NET}/{SUBNET}', function($NET,$SUBNET ){
    return view('display.third_display')->with('NET', $NET)->with('SUBNET', $SUBNET);
});

Route::get ('display/{NET}/{SUBNET}/{SUBSUBNET}', function($NET,$SUBNET, $SUBSUBNET ){
    return view('display.fourth_display')->with('NET', $NET)->with('SUBNET', $SUBNET)->with('SUBSUBNET', $SUBSUBNET);
});

Route::get ('display/{NET}/{SUBNET}/{SUBSUBNET}/{HOST}', function($NET,$SUBNET, $SUBSUBNET, $HOST ){
    return view('display.display')->with('NET', $NET)->with('SUBNET', $SUBNET)->with('SUBSUBNET', $SUBSUBNET) ->
        with('HOST', $HOST);
});

route::get ('display_host/{NAME}', function($NAME){
    return view('display.display_hostname')->with ('NAME', $NAME);
});


/*
|--------------------------------------------------------------------------
| SKUPINY zařízení
|--------------------------------------------------------------------------
|
|Routy Skupin zařízení (pro zobrazení reportů)
|
*/

route::get('groups/manage/show', function (){
   return view('display.goups.manage.show_groups');});

Route::post ('groups/manage/add/addNew', 'device_groupsController@addNew');
Route::get ('groups/manage/delete/{id}', 'device_groupsController@destroy');

Route::get ('groups/manage/addToGroup/{id}', function ($id){
    return view('display.goups.manage.add_to_group')->with ('id', $id);
});
Route::post ('groups/manage/addToGroup/{id}/search', 'device_groupsController@search');
Route::get ('groups/manage/addToGroup/{id}/add/{id2}', 'device_groupsController@addToGroup');

Route::get('groups/display', function(){
    return view('display.goups.groups_display');
});

Route::get('groups/display/{id}', function($id){
    return view('display.goups.groups_display_second')->with('id', $id);
});

//zobrazeni typy zarizeni
Route::get ('types/display', function (){
    return view('display.types.types_display');
});

//zobrazeni kriticnost zarizeni
Route::get ('criticality/display', function (){
    return view('display.types.criticality_show');
});


//zobrazeni "od - do" cvss
    Route::get ('values/display', function (){
        return view('display.values.values');
    });


//zobrazeni podle  IP
Route::get ('device/show/CVSS', function (){
    return view('display.types.devices_all_show');
});

/*
|--------------------------------------------------------------------------
| Chybejici zarizeni
|--------------------------------------------------------------------------
|
*/
Route::get ('missingDevices/{id}', function ($id){
    return view('display.missing_in_devices')->with('id', $id);
});


    /*
    |--------------------------------------------------------------------------
    | vyhledavani
    |--------------------------------------------------------------------------
    |
    */
    Route::get ('/search', 'searchController@index');




    /*
|--------------------------------------------------------------------------
|UZIVATELE
|-------------------------------------------------------------------------
|
|Routy okolo uzivatelu - sprava
|
*/


    route::get ('users/change', function(){return view('auth.changePass');});
 //   route::post ('users/update', function(){return view('message')->with ("message", "test");});
    Route::post ('users/update', 'changePassController@changePassword');

    route::get ('users/show', function(){return view('auth.users');});
    Route::get ('users/delete/{id}', 'UserController@deleteUser');

/*
|--------------------------------------------------------------------------
| TESTOVACNIIIII
|-------------------------------------------------------------------------
|
|Routy okolo TESTOVANI
|
*/


route::get ('test/', function(){return view('test');});

route::get ('test/{id}', function($id){return view('test')->with('id', $id);});





//konec groupy
});

Auth::routes();

