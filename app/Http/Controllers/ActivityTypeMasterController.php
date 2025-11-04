<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ActivityTypeMasterModel;
use Illuminate\Support\Facades\DB;
use Session;

class ActivityTypeMasterController extends Controller
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
        ->where('form_id', '151')
        ->first();
        //  DB::enableQueryLog();
        $activityTypeMasterList = ActivityTypeMasterModel::join('usermaster', 'usermaster.userId', '=', 'activity_type_master.userId')
        ->get(['activity_type_master.*','usermaster.username']);
        return view('ActivityTypeMasterList',compact('activityTypeMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ActivityTypeMaster');    
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
        'act_type_name' => 'required',
        ]);
        $input = $request->all();
        ActivityTypeMasterModel::create($input);
        return redirect()->route('ActivityTypeMaster.index');
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
        $ActivityTypeMasterEdit= ActivityTypeMasterModel::find($id);
        // dd(DB::getQueryLog());
        return view('ActivityTypeMaster', compact('ActivityTypeMasterEdit'));    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $act_type_id = ActivityTypeMasterModel::findOrFail($id);

        $this->validate($request, [
            'act_type_name' => 'required',
        ]);
         // DB::enableQueryLog();
        $input = $request->all();
        $act_type_id->fill($input)->save();
        // dd(DB::getQueryLog());

       return redirect()->route('ActivityTypeMaster.index')->with('message', 'Update Record Succesfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('activity_type_master')->where('act_type_id', $id)->delete();       
        Session::flash('delete', 'Deleted record successfully');   
    }
}
