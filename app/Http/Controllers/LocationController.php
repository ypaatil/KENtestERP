<?php

namespace App\Http\Controllers;

use App\Models\LocationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session; 



class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
           $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '30')
        ->first();
           
        
    // DB::enableQueryLog(); 
        $LocationList = LocationModel::join('usermaster', 'usermaster.userId', '=', 'location_master.userId')
        ->where('location_master.delflag','=', '0')
        ->get(['location_master.*','usermaster.username']);
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
        return view('LocationMasterList', compact('LocationList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('LocationMaster');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
             
            'location'=> 'required',
        ]);

        $input = $request->all();
    
        LocationModel::create($input);

    return redirect()->route('Location.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LocationModel  $locationModel
     * @return \Illuminate\Http\Response
     */
    public function show(LocationModel $locationModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LocationModel  $locationModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $LocationList = LocationModel::find($id);
        
        return view('LocationMaster', compact('LocationList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LocationModel  $locationModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $LocationList = LocationModel::findOrFail($id);

        $this->validate($request, [
            'location'=> 'required',
        ]);

        $input = $request->all();

        $LocationList->fill($input)->save();

        return redirect()->route('Location.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LocationModel  $locationModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LocationModel::where('loc_id', $id)->update(array('delflag' => 1));
          Session::flash('delete', 'Deleted record successfully'); 
    }
}
