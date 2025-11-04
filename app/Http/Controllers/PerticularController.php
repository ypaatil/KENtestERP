<?php

namespace App\Http\Controllers;
  
use App\Models\PerticularModel; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PerticularController extends Controller
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
        ->where('form_id', '272')
        ->first();

        $perticularData = PerticularModel::where('delflag', '=', '0')->get();

        return view('PerticularMasterList', compact('perticularData','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        return view('PerticularMaster');
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $data1=array
        (  
            'perticular_name'=>$request->perticular_name, 
            'perticular_code'=>$request->perticular_code, 
            'userId'=>Session::get('userId'), 
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'delflag'=>0
         );
     
        PerticularModel::insert($data1); 
            
        return redirect()->route('Perticular.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perticular  $Perticular
     * @return \Illuminate\Http\Response
     */
    public function show(Perticular $Perticular)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perticular  $Perticular
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $PerticularMaster = PerticularModel::find($id);   
        return view('PerticularMasterEdit',compact('PerticularMaster')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perticular  $Perticular
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
      
        $data1=array
        (  
            'perticular_name'=>$request->perticular_name, 
            'perticular_code'=>$request->perticular_code, 
            'userId'=>Session::get('userId'), 
            'updated_at'=>date("Y-m-d H:i:s"),
            'delflag'=>0
        );
         
         
        $PerticularList = PerticularModel::findOrFail($request->perticular_id);
        $PerticularList->fill($data1)->save(); 
         
        return redirect()->route('Perticular.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perticular  $Perticular
     * @return \Illuminate\Http\Response
     */ 
    
    public function destroy($id)
    { 
        PerticularModel::where('perticular_id', $id)->delete();   

        return 1;
    } 
    
}
