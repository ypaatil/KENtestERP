<?php

namespace App\Http\Controllers;

use App\Models\OrderGroupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class OrderGroupController extends Controller
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
->where('form_id', '74')
->first();  
        
        $OrderGroupList = OrderGroupModel::join('usermaster', 'usermaster.userId', '=', 'order_group_master.userId')
        ->where('order_group_master.delflag','=', '0')
        ->get(['order_group_master.*','usermaster.username']);
  
        return view('OrderGroupMasterList', compact('OrderGroupList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
         
        return view('OrderGroupMaster');
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
             
             
            'order_group_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    OrderGroupModel::create($input);

    return redirect()->route('OrderGroup.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderGroupModel  $OrderGroupModel
     * @return \Illuminate\Http\Response
     */
    public function show(OrderGroupModel $OrderGroupModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderGroupModel  $OrderGroupModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $OrderGroupList = OrderGroupModel::find($id);
         
        return view('OrderGroupMaster', compact('OrderGroupList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderGroupModel  $OrderGroupModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $OrderGroupList = OrderGroupModel::findOrFail($id);

        $this->validate($request, [
            
            'order_group_name'=> 'required',
           
        ]);

        $input = $request->all();

        $OrderGroupList->fill($input)->save();

        return redirect()->route('OrderGroup.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderGroupModel  $OrderGroupModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OrderGroupModel::where('og_id', $id)->update(array('delflag' => 1));
        return redirect()->route('OrderGroup.index')->with('message', 'Delete Record Succesfully');
    }
}
