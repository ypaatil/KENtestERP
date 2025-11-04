<?php

namespace App\Http\Controllers;

use App\Models\StyleNoModel;
use App\Models\LedgerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;  
use Image;
use DataTables;

class StyleNoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '47')
        ->first();
        // DB::enableQueryLog();

         $StyleNoList = StyleNoModel::join('usermaster', 'usermaster.userId', '=', 'style_no_master.userId')
         ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'style_no_master.Ac_code')
        ->where('style_no_master.delflag','=', '0')
        ->get(['style_no_master.*','usermaster.username','ledger_master.ac_short_name']);
        // dd(DB::getQueryLog());
        if ($request->ajax()) 
        {
            return Datatables::of($StyleNoList)
            ->addIndexColumn() 
            ->addColumn('action1', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('StyleNo.edit', $row->style_no_id).'" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                }
                else
                { 
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';   
                }
                return $btn3;
            })
            ->addColumn('action2', function ($row) use ($chekform){
         
                if($chekform->delete_access==1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->style_no_id.'"  data-route="'.route('StyleNo.destroy', $row->style_no_id).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['action1','action2'])
            ->make(true);
        }
        return view('StyleNoMasterList', compact('StyleNoList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Ledger = LedgerModel::SELECT('ledger_master.*')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '2')->get();
        return view('StyleNoMaster', compact('Ledger'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $data=array(
            'style_no'=>$request->style_no, 
            'Ac_code'=>$request->Ac_code, 
            'userId'=>$request->userId,
            'created_at'=>date("Y-m-d h:i:s"),
         );

        StyleNoModel::insert($data);

        return redirect()->route('StyleNo.index')->with('message', 'Saved Record Succesfully');
    }
  
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StyleNoModel  $StyleNoModel
     * @return \Illuminate\Http\Response
     */
    public function show(StyleNoModel $StyleNoModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StyleNoModel  $StyleNoModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $StyleNoList = StyleNoModel::find($id);
        $Ledger = LedgerModel::SELECT('ledger_master.*')->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '2')->get();
        
        return view('StyleNoMaster',compact('StyleNoList', 'Ledger'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StyleNoModel  $StyleNoModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $StyleNoList = StyleNoModel::findOrFail($id);
 
        $data=array( 
            'style_no'=>$request->style_no, 
            'Ac_code'=>$request->Ac_code, 
            'userId'=>$request->userId,
            'updated_at'=>date("Y-m-d h:i:s")
        );
 
        $StyleNoList->fill($data)->save();

        return redirect()->route('StyleNo.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StyleNoModel  $StyleNoModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        StyleNoModel::where('style_no_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
