<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HSNModel; 
use App\Models\CategoryModel;
use Illuminate\Support\Facades\DB;
use Session; 
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

class HSNMasterController extends Controller
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
        ->where('form_id', '366')
        ->first();


        $data = HSNModel::join('item_category', 'item_category.cat_id', '=', 'hsn_master.cat_id') 
        ->join('usermaster', 'usermaster.userId', '=', 'hsn_master.userId')->where('hsn_master.delflag', '=', 0)
        ->get(['hsn_master.*','usermaster.username','item_category.cat_name']);
        
        if ($request->ajax()) 
        {
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action1', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('HSN.edit', $row->hsn_id).'" >
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
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->hsn_id.'"  data-route="'.route('HSN.destroy', $row->hsn_id).'"><i class="fas fa-trash"></i></a>'; 
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
        return view('HSNMasterList', compact('data','chekform'));
        
         
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
        
        return view('HSNMaster',compact('Categorylist'));
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
            'cat_id' => 'required',
            'hsn_code' => 'required', 
        ]);

        $input = $request->all();
        HSNModel::create($input);

        return redirect()->route('HSN.index');

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
        $Categorylist = CategoryModel::where('delflag','=', '0')->get(); 
        $HSN = HSNModel::find($id);

        return view('HSNMaster', compact('HSN','Categorylist'));
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
    
        $HSN = HSNModel::findOrFail($id);

        $this->validate($request, [
            'cat_id' => 'required',
            'hsn_code' => 'required'
        ]);
 
        $input = $request->all(); 
        $HSN->fill($input)->save(); 
        return redirect()->route('HSN.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HSNModel::where('hsn_id', $id)->update(array('delflag' => 1));

        Session::flash('delete', 'Deleted record successfully'); 
    }
    
     
} 
