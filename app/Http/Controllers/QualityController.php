<?php

namespace App\Http\Controllers;

use App\Models\QualityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LedgerModel;
use Session;
use App\Imports\QualityImport;
use Maatwebsite\Excel\Facades\Excel;


class QualityController extends Controller
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
        ->where('form_id', '51')
        ->first();
        
        
        
        // DB::enableQueryLog(); 

            $QualityList = QualityModel::join('usermaster', 'usermaster.userId', '=', 'quality_master.userId')
            ->where('quality_master.delflag','=', '0')
            ->get(['quality_master.*','usermaster.username']);
    
//    $query = DB::getQueryLog();
//           $query = end($query);
//         dd($query);

            return view('QualityMasterList', compact('QualityList','chekform'));

     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

      
        return view('QualityMaster');
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
             
           
            'quality_name'=> 'required',
            
            
    ]);

    $input = $request->all();

    QualityModel::create($input);

    return redirect()->route('Quality.index');
    }




        public function qualityimport(Request $request)
    {


      Excel::import(new QualityImport,request()->file('qualityfile'));


        return redirect()->route('Quality.index');

    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QualityModel  $qualityModel
     * @return \Illuminate\Http\Response
     */
    public function show(QualityModel $qualityModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QualityModel  $qualityModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $QualityList = QualityModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('QualityMaster', compact('QualityList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QualityModel  $qualityModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $QualityList = QualityModel::findOrFail($id);

        $this->validate($request, [
           
            'quality_name'=> 'required',
          
           
            
        ]);

        $input = $request->all();

        $QualityList->fill($input)->save();

        return redirect()->route('Quality.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QualityModel  $qualityModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        QualityModel::where('quality_code', $id)->update(array('delflag' => 1));
     Session::flash('delete', 'Deleted record successfully'); 
    }
}
