<?php

namespace App\Http\Controllers;

use App\Models\SizeModel;
use App\Models\SizeDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session; 

class SizeController extends Controller
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
        ->where('form_id', '29')
        ->first();
        
         $SizeList = SizeModel::join('usermaster', 'usermaster.userId', '=', 'size_master.userId')
        ->where('size_master.delflag','=', '0')
        ->get(['size_master.*','usermaster.username']);
  
        return view('SizeMasterList', compact('SizeList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
               
        return view('SizeMaster');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
  $max_id = SizeModel::max('sz_code');
 
//  $TrNo=$codefetch->tr_no; 
        
    $this->validate($request, [
             
            'sz_name'=> 'required',
                       
            ]);

    $input = $request->all();

SizeModel::create($input);
     
$size_name = $request->input('size_name');
for($x=0; $x<count($size_name); $x++) {
    # code...
        $data2[]=array(
                  
                            'sz_code'=>$max_id+1,
                            'size_name'=>$request->size_name[$x],
                           
                 );
        }
         SizeDetailModel::insert($data2);
 
    return redirect()->route('Size.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SizeModel  $sizeModel
     * @return \Illuminate\Http\Response
     */
    public function show(SizeModel $sizeModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SizeModel  $sizeModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SizeList = SizeModel::find($id);
         $SizeDetaillist = SizeDetailModel::where('size_detail.sz_code','=', $SizeList->sz_code)
        ->get(['size_detail.*']);
        // select * from business_type where Bt_id=$id;
        return view('SizeMaster', compact('SizeList','SizeDetaillist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SizeModel  $sizeModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $SizeList = SizeModel::findOrFail($id);
        
        $this->validate($request, [
            'sz_name'=> 'required',
         ]);

        $input = $request->all();

        $SizeList->fill($input)->save();

DB::table('size_detail')->where('sz_code', $id)->delete();
$size_name = $request->input('size_name');
for($x=0; $x<count($size_name); $x++) {
    # code...
    // if($request->size_id[$x]!='0'){$size_id=$request->size_id[$x];}else{ $size_id=++$max_id;}
       $data2[]=array(
                  
                            'sz_code'=>$id,
                            'size_id'=>$request->size_id[$x],
                            'size_name'=>$request->size_name[$x],
                           
                 );
                 
                  
        }
        SizeDetailModel::insert($data2);
 






        return redirect()->route('Size.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SizeModel  $sizeModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SizeModel::where('sz_code', $id)->update(array('delflag' => 1));
        DB::table('size_detail')->where('sz_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
