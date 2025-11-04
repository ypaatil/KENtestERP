<?php

namespace App\Http\Controllers;

use App\Models\BusinessTypeModel;
use Illuminate\Http\Request;
use Session;
use DB;

class BusinessTypeController extends Controller
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
        ->where('form_id', '23')
        ->first();
        
        
        $business_type1 = BusinessTypeModel::join('usermaster', 'usermaster.userId', '=', 'business_type.userId')
        ->where('business_type.delflag','=', '0')
        ->get(['business_type.*','usermaster.username']);
  
        return view('BusinessTypeMasterList', compact('business_type1','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('BusinessTypeMaster');
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
             
            'Bt_name'=> 'required',
            'description'=> 'required',
           
              
    ]);

    $input = $request->all();

    BusinessTypeModel::create($input);

    return redirect()->route('BusinessType.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BusinessTypeModel  $businessTypeModel
     * @return \Illuminate\Http\Response
     */
    public function show(BusinessTypeModel $businessTypeModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessTypeModel  $businessTypeModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_type1 = BusinessTypeModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('BusinessTypeMaster', compact('business_type1'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BusinessTypeModel  $businessTypeModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $business_type1 = BusinessTypeModel::findOrFail($id);

        $this->validate($request, [
            'Bt_name'=> 'required',
            'description'=> 'required',
        ]);

        $input = $request->all();

        $business_type1->fill($input)->save();

        return redirect()->route('BusinessType.index')->with('message', 'Update Record Succesfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessTypeModel  $businessTypeModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BusinessTypeModel::where('Bt_id', $id)->update(array('delflag' => 1));
        
         Session::flash('delete', 'Deleted record successfully'); 
    }
}
