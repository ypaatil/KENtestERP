<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\CategoryModel;
use App\Models\QualityModel;
use App\Models\ClassificationModel;
use App\Models\TrimsInwardDetailModel;
use Image;
use App\Models\MaterialTypeModel;
use Illuminate\Support\Facades\DB;
use Session;
use App\Imports\ItemImport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

class ItemMasterController extends Controller
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
        ->where('form_id', '8')
        ->first();


        $data = ItemModel::join('item_category', 'item_category.cat_id', '=', 'item_master.cat_id')
        ->join('classification_master', 'classification_master.class_id', '=', 'item_master.class_id', 'left outer')
        ->join('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
        ->join('usermaster', 'usermaster.userId', '=', 'item_master.userId')
        ->join('unit_master', 'unit_master.unit_id', '=', 'item_master.unit_id')
        ->get(['item_master.*','usermaster.username','item_category.cat_name','classification_master.class_name','quality_master.quality_name','unit_master.unit_name']);
        
        if ($request->ajax()) 
        {
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('status1',function ($row) 
            {
                if($row->delflag==0)
                {
                     $statusData = ' <span class="badge badge-pill badge-soft-success font-size-11">Active</span>';
                }
                else
                {
                     $statusData = ' <span class="badge badge-pill badge-soft-danger font-size-11">Deactive</span>';
                }
                return $statusData;
            }) 
            ->addColumn('imagePath', function ($row) 
            {
                
                    if($row->item_image_path!='')
                    {
                        $path = 'thumbnail/'.$row->item_image_path;
                        $imagePath = '<a href="'.$path.'" target="_blank"><img src="'.$path.'" alt="'.$path.'"></a>';
                    }
                    else
                    {
                         $imagePath = 'No Image;';
                    }
                     
                return $imagePath;
            })
            ->addColumn('action1', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('Item.edit', $row->item_code).'" >
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
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->item_code.'"  data-route="'.route('Item.destroy', $row->item_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['status1','imagePath','action1','action2'])
    
            ->make(true);
        }
        return view('Item_Master_List', compact('data','chekform'));
        
         
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
        $Classificationlist = ClassificationModel::where('delflag','=', '0')->get();
        $MaterialTypeList = MaterialTypeModel::where('delflag','=', '0')->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
        $QualityList = QualityModel::where('delflag','=', '0')->get();
     return view('ItemMaster',compact('Classificationlist','Categorylist','MaterialTypeList','UnitList','QualityList'));
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
            'class_id' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            
         
        ]);


    $item_image_path=$request->file('item_image_path');
    if($item_image_path) 
    {
   
        $image = $request->file('item_image_path');
        $input['imagename'] = time().'I1.'.$image->getClientOriginalExtension();
     
        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->getRealPath());
        $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
    $ItemImageName=$input['imagename'];
}
else
{
    $ItemImageName='';
}


        $input = $request->all();
        $input['item_image_path'] = $ItemImageName;
          $input['delflag'] = $request->active;
        ItemModel::create($input);

        return redirect()->route('Item.index');

    }



        public function itemimport(Request $request)
    {


      Excel::import(new ItemImport,request()->file('itemfile'));


        return redirect()->route('Item.index');

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
    
    
       public function activeDeactiveList($id)
    {
        
         $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '8')
        ->first();


        $data = ItemModel::join('item_category', 'item_category.cat_id', '=', 'item_master.cat_id')
        ->join('classification_master', 'classification_master.class_id', '=', 'item_master.class_id', 'left outer')
        ->join('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
        ->join('usermaster', 'usermaster.userId', '=', 'item_master.userId')
        ->where('item_master.delflag','=', $id)
        ->get(['item_master.*','usermaster.username','item_category.cat_name','classification_master.class_name']);

        return view('itemListAD', compact('data','chekform'));
      
    }
    

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $Classificationlist = ClassificationModel::where('delflag','=', '0')->get();
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
        $QualityList = QualityModel::where('delflag','=', '0')->get();
        $MaterialTypeList = MaterialTypeModel::where('delflag','=', '0')->get();
        $items = ItemModel::find($id);

        return view('ItemMaster', compact('items','Classificationlist','Categorylist','MaterialTypeList','UnitList','QualityList'));
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
    
        $item = ItemModel::findOrFail($id);

        $this->validate($request, [
            'cat_id' => 'required',
            'class_id' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            
           
        ]);



    $item_image_path=$request->file('item_image_path');
    if($item_image_path) 
    {
       
        $image = $request->file('item_image_path');
        $input['imagename'] = time().'I1.'.$image->getClientOriginalExtension();
        
        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->getRealPath());
        $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
  
   $ItemImageName=$input['imagename'];
   
}
else
{
    $ItemImageName= $request->old_item_image_path;
}

// print_r($ItemImageName);
// exit();


        $input = $request->all();
        $input['item_image_path'] = $ItemImageName;
        $input['delflag'] = $request->active;
        $item->fill($input)->save();
       ItemModel::where('item_code', $id)->update(array('delflag' => $request->active));
       return redirect()->route('Item.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        ItemModel::where('item_code', $id)->update(array('delflag' => 1));

        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    
   public function GetClassList(Request $request)
    {  $html = '';
          if (!$request->cat_id) {
        $html = '<option value="">-Classification-</option>';
        } 
        else
        {
            $html = '<option value="">-Classification-</option>';
            $ClassList = DB::table('classification_master')->where('cat_id', $request->cat_id)->get();
            foreach ($ClassList as $row) 
            {
                    $html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';
            }
        }
       return response()->json(['html' => $html]);
    } 
    
    
      
public function itemexist(Request $request)
{

    $item_name = $request->item_name;


$item = DB::table('item_master')->where('item_name', '=', $request->item_name)->where('delflag','=',0)

->first();



if ($item != null) {


      $html ='<span style="color:#FF0000; weight:bold;">Item is already exist</span>';

$html .= '
<div class="mb-3" id="itemshow">
<label for="formrow-email-input" class="form-label">Item Name</label>
<input type="text" name="item_name" class="form-control" id="formrow-email-input" value="" onBlur="itemExist(this.value)" required>
</div>
';

}

else {
 
$html = '
<div class="mb-3" id="itemshow">
<label for="formrow-email-input" class="form-label">Item Name</label>
<input type="text" name="item_name" class="form-control" id="formrow-email-input" value="'.$item_name.'" onBlur="itemExist(this.value)" required>
</div>
';
  
}


return response()->json(['html' => $html]);

}
    
    public function Get_Ledger_Item_Report()
    {
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
        
        return view('Get_Ledger_Item_Report',compact('Categorylist'));
    }
    
    public function GetClassifictionData(Request $request)
    {
    
        $cat_id = $request->cat_id;
        
        if($cat_id > 0)
        {
            $Classificationlist = ClassificationModel::where('delflag','=', '0')->where('cat_id', '=', $cat_id)->get();
        }
        else
        {
             $Classificationlist = ClassificationModel::where('delflag','=', '0')->get();
        }
        $html = '<option value="All">--All--</option>';
        
        foreach($Classificationlist as $row)
        {
            $html .= '<option value="'.$row->class_id .'">'.$row->class_name .'</option>';
        }
        return response()->json(['html' => $html]);
    
    }  
    
        
    public function GetClassifictionTrimsData(Request $request)
    {
    
        $cat_id = $request->cat_id;
        $bom_code = $request->bom_code;
        
        if($cat_id == 2)
        {
            if (strpos($bom_code, 'SIN-') === 0) 
            {
                
                $Classificationlist = ClassificationModel::join('item_master','item_master.class_id','=','classification_master.class_id')
                                    ->join('sample_indent_sewing_trims','sample_indent_sewing_trims.sewing_trims_item_code','=','item_master.item_code')
                                    ->where('classification_master.delflag','=', '0')
                                    ->where('classification_master.cat_id', '=', $cat_id)
                                    ->where('sample_indent_sewing_trims.sample_indent_code', '=', $bom_code)
                                    ->groupBy('item_master.class_id')
                                    ->get();
            
            }
            else
            {   
                $Classificationlist = ClassificationModel::join('bom_sewing_trims_details','bom_sewing_trims_details.class_id','=','classification_master.class_id')
                                    ->where('classification_master.delflag','=', '0')->where('classification_master.cat_id', '=', $cat_id)->where('bom_sewing_trims_details.bom_code', '=', $bom_code)->groupBy('bom_sewing_trims_details.class_id')->get();
            }
        }
        else if($cat_id == 3)
        {
            
            if (strpos($bom_code, 'SIN-') === 0) 
            {
                  $Classificationlist = ClassificationModel::join('item_master','item_master.class_id','=','classification_master.class_id')
                                    ->join('sample_indent_packing_trims','sample_indent_packing_trims.packing_trims_item_code','=','item_master.item_code')
                                    ->where('classification_master.delflag','=', '0')
                                    ->where('classification_master.cat_id', '=', $cat_id)
                                    ->where('sample_indent_packing_trims.sample_indent_code', '=', $bom_code)
                                    ->groupBy('item_master.class_id')
                                    ->get();
            }
            else
            {
                $Classificationlist = ClassificationModel::join('bom_packing_trims_details','bom_packing_trims_details.class_id','=','classification_master.class_id')
                                ->where('classification_master.delflag','=', '0')->where('classification_master.cat_id', '=', $cat_id)->where('bom_packing_trims_details.bom_code', '=', $bom_code)->groupBy('bom_packing_trims_details.class_id')->get();
        
            }
        }
        else
        {
            $Classificationlist = ClassificationModel::where('delflag','=', '0')->get();
        }
        
        $html = '<option value="All">--All--</option>';
        
        foreach($Classificationlist as $row)
        {
            $html .= '<option value="'.$row->class_id .'">'.$row->class_name .'</option>';
        }
        return response()->json(['html' => $html]);
    
    }  
    
    public function GetItemData(Request $request)
    {
    
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        
        if($cat_id > 0)
        {
            $cId = " AND cat_id=".$cat_id;
        }
        else
        {
            $cId ="";
        }
        
        if($class_id > 0)
        {
            $clId = " AND class_id=".$class_id;
        }
        else
        {
            $clId = "";
        }
        //DB::enableQueryLog();
        $Itemlist = DB::select("SELECT item_code,item_name FROM item_master WHERE delflag=0".$clId."".$cId);
        //dd(DB::getQueryLog());
        $html = '<option value="All">--All--</option>';
        
        foreach($Itemlist as $row)
        {
            $html .= '<option value="'.$row->item_code .'">'.$row->item_name .'-('.$row->item_code .')</option>';
        }
        return response()->json(['html' => $html]);
    
    }
    
    public function rptItemLedger(Request $request)
    {
        $fdate = $request->fdate;
        $tdate = $request->tdate;
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        $item_code = $request->item_code;
        
        $catData = CategoryModel::where('delflag','=', '0')->where('cat_id', '=', $cat_id)->first();
        $classData = ClassificationModel::where('delflag','=', '0')->where('class_id', '=', $class_id)->first();
        $ItemData = ItemModel::where('delflag','=', '0')->where('cat_id', '=', $cat_id)->where('item_code', '=', $item_code)->first();
       
        if($catData != "")
        {
            $cat_name = $catData->cat_name;
        }
        else
        {
            $cat_name = "-";
        }
        if($classData != "")
        {
            $class_name = $classData->class_name;
        }
        else
        {
            $class_name = "-";
        }
        if($ItemData != "")
        {
            $item_name = $ItemData->item_name;
        } 
        else
        {
            $item_name = "-";
        }
        
        if($item_code > 0)
        {
            $ic = " AND item_code = '". $item_code."'";
        }
        else
        {
            $ic = "";
        }
        if($cat_id == 1)
        {
           //DB::enableQueryLog();
             $ItemLedgerData = DB::select("SELECT po_code,in_date as in_date,in_code,'Goods receipt note' as inward_type, ledger_master.ac_name,sum(meter) as meter,
                    po_code as po_no,'Combined PO' as order_ref_no,(SELECT sum(`meter`) as passed FROM `fabric_checking_details` WHERE 1 ".$ic.") as passed,
                    (SELECT ROUND(sum(`reject_short_meter`),2) as rejected FROM `fabric_checking_details` WHERE  1 ".$ic.") as rejected FROM inward_details 
                    LEFT JOIN ledger_master ON ledger_master.ac_code = inward_details.Ac_code
                    WHERE 1 ".$ic." AND in_date BETWEEN '".$fdate."' AND '".$tdate."' GROUP BY in_date
                    UNION SELECT 
                    (SELECT po_code from inward_details WHERE inward_details.track_code = fabric_outward_details.track_code) as po_code,fout_date as out_date ,
                    fout_code,'Delivery Challan' as outward_type,ledger_master.ac_name as vendor_name,meter,fabric_outward_details.vpo_code,
                    (select sales_order_no from vendor_purchase_order_master where vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code) as sales_order_no,
                    (SELECT sum(`meter`) as passed FROM `fabric_checking_details` WHERE  1 ".$ic.") as passed,
                    (SELECT ROUND(sum(`reject_short_meter`),2) as rejected FROM `fabric_checking_details` WHERE  1 ".$ic.") as rejected
                    FROM fabric_outward_details 
                    LEFT JOIN ledger_master ON ledger_master.ac_code = fabric_outward_details.vendorId WHERE  1 ".$ic." AND fout_date BETWEEN '".$fdate."' AND '".$tdate."' group by fabric_outward_details.item_code ");
        
            //dd(DB::getQueryLog());
        }
        else
        {
           //DB::enableQueryLog();
            $ItemLedgerData = DB::select("SELECT po_code,trimDate as trimDate,trimCode,'Goods receipt note' as inward_type, ifnull(item_qty,0) as in_qty, ledger_master.ac_name,'-' as vw_code FROM trimsInwardDetail 
                    LEFT JOIN ledger_master ON ledger_master.ac_code = trimsInwardDetail.Ac_code
                    WHERE  1 ".$ic." AND trimDate BETWEEN '".$fdate."' AND '".$tdate."' 
                    UNION SELECT  po_code as trimOutPo,tout_date , trimOutCode,'Delivery Challan' as outward_type, ifnull(sum(item_qty),0) as out_qty, ledger_master.ac_name as vendor_name,vw_code
                    FROM trimsOutwardDetail 
                    LEFT JOIN ledger_master ON ledger_master.ac_code = trimsOutwardDetail.vendorId WHERE  1 ".$ic." AND tout_date BETWEEN '".$fdate."' AND '".$tdate."' group by trimsOutwardDetail.item_code");
           
            // $ItemLedgerData = TrimsInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardDetail.Ac_code')
            //       ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
            //       ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'trimsInwardDetail.rack_id')
            //       ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
            //       ->groupby('trimsInwardDetail.trimCode')
            //       ->get(['trimsInwardDetail.*', 'trimsInwardMaster.is_opening', 'trimsInwardMaster.invoice_no','trimsInwardMaster.po_code',  'trimsInwardMaster.invoice_date',  'ledger_master.ac_name','item_master.dimension', 'item_master.item_name','item_master.color_name','item_master.item_description', 'rack_master.rack_name']);
    
            //dd(DB::getQueryLog());
        }
        return view('rptItemLedger', compact('fdate','tdate','cat_name','cat_id','class_name','item_name','ItemLedgerData','item_code'));
    }
    
    public function GetItemUnits(Request $request)
    {
        $unitData = DB::SELECT("SELECT unit_name FROM item_master INNER JOIN unit_master ON unit_master.unit_id = item_master.unit_id WHERE item_code=".$request->item_code);
        $unit_name = isset($unitData[0]->unit_name) ? $unitData[0]->unit_name : 0;
        return response()->json(['unit_name' => $unit_name]);
    }
    
} 
