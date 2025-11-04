<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;  
use App\Models\OutletSaleMasterModel;
use App\Models\OutletSaleDetailModel;
use Session;
use DataTables;
use DB;

require_once '/home/kenerp/public_html/app/Libraries/TCPDF/tcpdf.php';

use TCPDF; 


class OutletSaleController extends Controller
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
        ->where('form_id', '277')
        ->first();
        $OutletList = OutletSaleMasterModel::select('outlet_sale_master.*','employeemaster.fullName', 'usermaster.username','payment_options.payment_option_name')
                        ->join('employeemaster', 'employeemaster.employeeCode', '=', 'outlet_sale_master.employeeCode', 'left outer')
                        ->join('usermaster', 'usermaster.userId', '=', 'outlet_sale_master.userId', 'left outer')
                        ->join('payment_options', 'payment_options.payment_option_id', '=', 'outlet_sale_master.payment_option_id', 'left outer')
                        ->where('outlet_sale_master.delflag','=', '0')
                        ->orderBy('outlet_sale_id', 'DESC')
                        ->get();  
        
        return view('OutletSaleList', compact('OutletList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $employeeList = DB::SELECT('SELECT * FROM employeemaster where delflag=0 AND employee_status_id NOT IN(3,4)');
        $paymentOptionList = DB::SELECT('SELECT * FROM payment_options where delflag=0');
        
        return view('OutletSaleMaster',compact('employeeList','paymentOptionList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
            $outlet_sale_id = DB::table('outlet_sale_master')->max('outlet_sale_id');
            $maxId = $outlet_sale_id+1;
            $data1=array(
                'bill_date'=>$request->bill_date, 
                'bill_no'=>"000".$maxId,  
                'payment_option_id'=>$request->payment_option_id,
                'employeeCode'=>isset($request->employeeCode) ? $request->employeeCode : "",
                'other_customer'=>isset($request->other_customer) ? $request->other_customer : "",
                'employee_type'=>$request->employee_type, 
                'total_qty'=>$request->total_qty,
                'gross_amount'=>$request->gross_amount,
                'total_disc_amount'=>$request->total_disc_amount,
                'total_gst_amount'=>$request->total_gst_amount,
                'net_amount'=>$request->net_amount,
                'remark'=>$request->remark,
                'mobile_no'=>$request->mobile_no,
                'gst_type'=>$request->gst_type,
                'delflag'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'userId'=>$request->userId, 
            );
         
            OutletSaleMasterModel::insert($data1);
            $outlet_sale_id = DB::table('outlet_sale_master')->max('outlet_sale_id');
            for($i=0;$i<count($request->product_id);$i++)
            {
                $data2=array(
                'outlet_sale_id'=>$outlet_sale_id, 
                'bill_date'=>$request->bill_date,  
                'bill_no'=>"KEN100".$maxId,  
                'scan_barcode'=>$request->scan_barcode[$i], 
                'brand_id'=>$request->brand_id[$i], 
                'product_id'=>$request->product_id[$i], 
                'product_name'=>$request->product_name[$i], 
                'style_no'=>$request->style_no[$i], 
                'qty'=>$request->qty[$i], 
                'size_id'=>$request->size_id[$i], 
                'stock_qty'=>$request->stock_qty[$i], 
                'rate'=>$request->rate[$i], 
                'amount'=>$request->amount[$i], 
                'discount'=>$request->discount[$i], 
                'discount_amount'=>$request->discount_amount[$i], 
                'gst_per'=>$request->gst_per[$i], 
                'gst_amount'=>$request->gst_amount[$i], 
                'total_amount'=>$request->total_amount[$i], 
                );
                
                OutletSaleDetailModel::insert($data2);   
            } 
            return redirect()->route('OutletSale.index')->with('message', 'Data Saved Succesfully');  
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($bom_code)
    {
        
         $BOMList = OutletSaleMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'bom_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'bom_master.cost_type_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'bom_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'bom_master.substyle_id', 'left outer')  
        ->join('fg_master', 'fg_master.fg_id', '=', 'bom_master.fg_id', 'left outer') 
        ->where('bom_master.delflag','=', '0')
        ->where('bom_master.bom_code','=', $bom_code)
        
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name'
        ,'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
   
        return view('budgetPrint', compact('BOMList'));  
      
    }
 
 
    public function edit($id)
    {   
        $OutletSaleMasterList = OutletSaleMasterModel::find($id); 
        $OutletSaleDetailList = OutletSaleDetailModel::select('outlet_sale_detail.*', 'size_detail.size_name','brand_master.brand_name')
                                ->join('size_detail', 'size_detail.size_id', '=', 'outlet_sale_detail.size_id')
                                ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'outlet_sale_detail.brand_id')
                                ->where('outlet_sale_id','=', $id)
                                ->get();
                                 
        $employeeList = DB::SELECT('SELECT * FROM employeemaster where delflag=0 AND employee_status_id NOT IN(3,4)');
        $paymentOptionList = DB::SELECT('SELECT * FROM payment_options where delflag=0');
        
        return view('OutletSaleEdit',compact('OutletSaleMasterList','OutletSaleDetailList','employeeList','paymentOptionList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $outlet_sale_id)
    {  
            $data1=array(
                'bill_date'=>$request->bill_date, 
                'bill_no'=>$request->bill_no, 
                'payment_option_id'=>$request->payment_option_id,
                'employeeCode'=>isset($request->employeeCode) ? $request->employeeCode : "",
                'other_customer'=>isset($request->other_customer) ? $request->other_customer : "",
                'employee_type'=>$request->employee_type, 
                'total_qty'=>$request->total_qty,
                'gross_amount'=>$request->gross_amount,
                'total_disc_amount'=>$request->total_disc_amount,
                'total_gst_amount'=>$request->total_gst_amount,
                'net_amount'=>$request->net_amount,
                'remark'=>$request->remark,
                'mobile_no'=>$request->mobile_no,
                'gst_type'=>$request->gst_type,
                'delflag'=>0,
                'updated_at'=>date("Y-m-d H:i:s"),
                'userId'=>$request->userId, 
            );
 
            $OutletSaleMasterList = OutletSaleMasterModel::findOrFail($outlet_sale_id); 
 
            $OutletSaleMasterList->fill($data1)->save();
            DB::table('outlet_sale_detail')->where('outlet_sale_id', $outlet_sale_id)->delete();  
            
            for($i=0;$i<count($request->product_id);$i++)
            {
                $data2=array(
                'outlet_sale_id'=>$outlet_sale_id, 
                'bill_date'=>$request->bill_date,  
                'bill_no'=>$request->bill_no, 
                'scan_barcode'=>$request->scan_barcode[$i],  
                'brand_id'=>$request->brand_id[$i], 
                'product_id'=>$request->product_id[$i], 
                'product_name'=>$request->product_name[$i], 
                'style_no'=>$request->style_no[$i], 
                'qty'=>$request->qty[$i], 
                'size_id'=>$request->size_id[$i], 
                'stock_qty'=>$request->stock_qty[$i], 
                'rate'=>$request->rate[$i], 
                'amount'=>$request->amount[$i], 
                'discount'=>$request->discount[$i], 
                'discount_amount'=>$request->discount_amount[$i], 
                'gst_per'=>$request->gst_per[$i], 
                'gst_amount'=>$request->gst_amount[$i], 
                'total_amount'=>$request->total_amount[$i], 
                );
                
                OutletSaleDetailModel::insert($data2);   
            }
            
            return redirect()->route('OutletSale.index')->with('message', 'Data Updated Succesfully');  
    }
     
     
      
    public function destroy($id)
    { 
        DB::table('outlet_sale_master')->where('outlet_sale_id', $id)->delete(); 
        DB::table('outlet_sale_detail')->where('outlet_sale_id', $id)->delete();  
        Session::flash('messagedelete', 'Deleted record successfully');  
    }
     
 
    
    public function GetEmployeeDetails(Request $request)
    {
       
        $employeeList = DB::table('employeemaster')
            ->select('employeemaster.fullName','department_master.dept_name','branch_master.branch_name')
            ->join('department_master', 'department_master.dept_id', '=', 'employeemaster.dept_id', 'left outer') 
            ->join('branch_master', 'branch_master.branch_id', '=', 'employeemaster.branch_id', 'left outer')  
            ->where('employeemaster.employeeCode','=', $request->employeeCode)
            ->get();   
       
        return $employeeList;
    }
    
    public function GetBarcodeDetails(Request $request)
    {
            // DB::enableQueryLog();
        $ProductList = DB::table('fg_location_transfer_inward_size_detail2')->select('fg_location_transfer_inward_size_detail2.*', 'size_detail.size_name', 'fg_master.fg_name','brand_master.brand_name','brand_master.brand_id',
                       DB::raw("
                        SUM(fg_location_transfer_inward_size_detail2.size_qty) 
                        - (
                            SELECT COALESCE(SUM(qty),0) 
                            FROM outlet_sale_detail 
                            WHERE outlet_sale_detail.size_id = fg_location_transfer_inward_size_detail2.size_id 
                              AND outlet_sale_detail.scan_barcode = '".$request->scan_barcode."'
                        ) as stock")) 
                    ->join('fg_master', 'fg_master.fg_id', '=', 'fg_location_transfer_inward_size_detail2.fg_id') 
                    ->join('size_detail', 'size_detail.size_id', '=', 'fg_location_transfer_inward_size_detail2.size_id') 
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'fg_location_transfer_inward_size_detail2.sales_order_no') 
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id') 
                    ->where('barcode','=',$request->scan_barcode) 
                    ->get();
                    
                    // dd(DB::getQueryLog());
        if(count($ProductList) == 0)
        {

               $ProductList = DB::table('fg_outlet_opening_size_detail2')->select('fg_outlet_opening_size_detail2.*', 'size_detail.size_name', 'main_style_master.mainstyle_name as fg_name','brand_master.brand_name','brand_master.brand_id',
                    DB::raw("
                        SUM(fg_location_transfer_inward_size_detail2.size_qty) 
                        - (
                            SELECT COALESCE(SUM(qty),0) 
                            FROM outlet_sale_detail 
                            WHERE outlet_sale_detail.size_id = fg_location_transfer_inward_size_detail2.size_id 
                              AND outlet_sale_detail.scan_barcode = '".$request->scan_barcode."'
                        ) as stock")) 
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'fg_location_transfer_inward_size_detail2.sales_order_no') 
                    ->leftjoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'fg_outlet_opening_size_detail2.mainstyle_id') 
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id') 
                    ->join('size_detail', 'size_detail.size_id', '=', 'fg_outlet_opening_size_detail2.size_id') 
                    ->where('barcode','=',$request->scan_barcode) 
                    ->get();
        }
        $html = ''; 
        $sr = 1;
        
        if(count($ProductList) > 0)
        {
            foreach ($ProductList as $row) 
            {
            
                if($row->fg_name != '' && $row->size_id != '' && $row->stock > 0)
                {
                    $html .= '<tr>
                            <td><a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" > X </a></td> 
                            <td><input type="number" step="any" name="srno[]" class="form-control" value="'.$sr++.'"  style="width:80px;"></td>
                            <td><input type="text"  name="scan_barcode[]" class="form-control" value="'.$request->scan_barcode.'"  style="width:120px;" readonly></td> 
                            <td><input type="text"  name="brand_name[]" class="form-control" value="'.$row->brand_name.'"  style="width:120px;" readonly>
                                <input type="hidden"  name="brand_id[]" class="form-control" value="'.$row->brand_id.'"  style="width:120px;" readonly></td> 
                            <td><input type="text"  name="product_name[]" class="form-control" value="'.$row->fg_name.'"  style="width:150px;" readonly>
                                <input type="hidden"  name="product_id[]" class="form-control" value="'.($row->color_id."".$row->size_id).'"  style="width:120px;" readonly>
                                <input type="hidden"  name="style_no[]" class="form-control" value="'.(isset($row->style_no) ? $row->style_no : '-').'"  style="width:120px;" readonly>
                            </td> 
                            <td><input type="hidden"  name="size_id[]" class="form-control" value="'.$row->size_id.'"><input type="text"  name="size_name[]" class="form-control" value="'.$row->size_name.'" style="width:100px;" readonly></td> 
                            <td><input type="number" step="any" name="stock_qty[]" class="form-control" value="'.$row->stock.'"   style="width:100px;" readonly></td> 
                            <td><input type="number" step="any" name="rate[]" class="form-control" value="'.$row->size_rate.'"   style="width:100px;" readonly></td> 
                            <td><input type="number" step="any" name="qty[]" class="form-control" value="1" onchange="calQty(this);"  style="width:100px;"></td> 
                            <td>
                                <input type="number" step="any" name="amount[]" class="form-control" value="0" style="width:100px;" readonly>
                                <input type="hidden" step="any" name="discount[]" class="form-control" value="0"   style="width:100px;" onchange="calQty(this);">
                                <input type="hidden" step="any" name="discount_amount[]" class="form-control" value="0"   style="width:100px;" readonly>
                                <input type="hidden" step="any" name="gst_per[]" class="form-control" value="0"   style="width:100px;" readonly>
                                <input type="hidden" step="any" name="gst_amount[]" class="form-control" value="0"  style="width:100px;" readonly>
                                <input type="hidden" step="any" name="total_amount[]" class="form-control" value="0"  style="width:100px;" readonly>
                            </td>  
                    </tr>';
                }
            }
        }
        return response()->json(['html' => $html]);
    }
    
    
  
    
    public function GetBarcodeDetailsTest(Request $request)
    {
            // DB::enableQueryLog();
        $barcode = $request->scan_barcode;

        $query1 = DB::table('fg_location_transfer_inward_size_detail2')
            ->select(
                'fg_location_transfer_inward_size_detail2.size_id',
                'fg_location_transfer_inward_size_detail2.size_rate',
                'fg_location_transfer_inward_size_detail2.color_id',
                'fg_location_transfer_inward_size_detail2.barcode',
                'size_detail.size_name',
                'fg_master.fg_name',
                'brand_master.brand_name',
                'brand_master.brand_id',
                DB::raw("
                    SUM(fg_location_transfer_inward_size_detail2.size_qty)
                    - (
                        SELECT COALESCE(SUM(qty),0)
                        FROM outlet_sale_detail
                        WHERE outlet_sale_detail.size_id = fg_location_transfer_inward_size_detail2.size_id
                          AND outlet_sale_detail.scan_barcode = '$barcode'
                    ) as stock
                ")
            )
            ->join('fg_master', 'fg_master.fg_id', '=', 'fg_location_transfer_inward_size_detail2.fg_id')
            ->join('size_detail', 'size_detail.size_id', '=', 'fg_location_transfer_inward_size_detail2.size_id')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'fg_location_transfer_inward_size_detail2.sales_order_no')
            ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
            ->where('fg_location_transfer_inward_size_detail2.barcode', '=', $barcode);
        
        $query2 = DB::table('fg_outlet_opening_size_detail2')
            ->select(
                'fg_outlet_opening_size_detail2.size_id',
                'fg_outlet_opening_size_detail2.size_rate',
                'fg_outlet_opening_size_detail2.color_id',
                'fg_outlet_opening_size_detail2.barcode',
                'size_detail.size_name',
                'fg_master.fg_name',
                'brand_master.brand_name',
                'brand_master.brand_id',
                DB::raw("
                    SUM(fg_outlet_opening_size_detail2.size_qty)
                    - (
                        SELECT COALESCE(SUM(qty),0)
                        FROM outlet_sale_detail
                        WHERE outlet_sale_detail.size_id = fg_outlet_opening_size_detail2.size_id
                          AND outlet_sale_detail.scan_barcode = '$barcode'
                    ) as stock
                ")
            )
            ->join('fg_master', 'fg_master.fg_id', '=', 'fg_outlet_opening_size_detail2.fg_id')
            ->join('size_detail', 'size_detail.size_id', '=', 'fg_outlet_opening_size_detail2.size_id')
            ->join('brand_master', 'brand_master.brand_id', '=', 'fg_outlet_opening_size_detail2.brand_id')
            ->where('fg_outlet_opening_size_detail2.barcode', '=', $barcode);
        
        $ProductList = $query1
            ->union($query2)
            ->get();

                    
                    // dd(DB::getQueryLog());
        if(count($ProductList) == 0)
        {
            $barcode = $request->scan_barcode;

            $Query1 = DB::table('fg_location_transfer_inward_size_detail2')
                ->select(
                    'fg_location_transfer_inward_size_detail2.barcode',
                    'fg_location_transfer_inward_size_detail2.size_id',
                    'fg_location_transfer_inward_size_detail2.size_rate',
                    'fg_location_transfer_inward_size_detail2.color_id',
                    'size_detail.size_name',
                    DB::raw('fg_master.fg_name AS fg_name'),
                    DB::raw('brand_master.brand_name AS brand_name'),
                    DB::raw('brand_master.brand_id AS brand_id'),
                    DB::raw("
                        (SUM(fg_location_transfer_inward_size_detail2.size_qty)
                        - (
                            SELECT COALESCE(SUM(qty),0)
                            FROM outlet_sale_detail 
                            WHERE outlet_sale_detail.size_id = fg_location_transfer_inward_size_detail2.size_id 
                              AND outlet_sale_detail.scan_barcode = '$barcode'
                        )) AS stock
                    ")
                )
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'fg_location_transfer_inward_size_detail2.sales_order_no')
                ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'fg_location_transfer_inward_size_detail2.fg_id')
                ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
                ->join('size_detail', 'size_detail.size_id', '=', 'fg_location_transfer_inward_size_detail2.size_id')
                ->where('fg_location_transfer_inward_size_detail2.barcode', '=', $barcode);
            
            $Query2 = DB::table('fg_outlet_opening_size_detail2')
                ->select(
                    'fg_outlet_opening_size_detail2.barcode',
                    'fg_outlet_opening_size_detail2.size_id',
                    'fg_outlet_opening_size_detail2.size_rate',
                    'fg_outlet_opening_size_detail2.color_id',
                    'size_detail.size_name',
                    DB::raw('main_style_master.mainstyle_name AS fg_name'),
                    DB::raw('brand_master.brand_name AS brand_name'),
                    DB::raw('brand_master.brand_id AS brand_id'),
                    DB::raw("
                        (SUM(fg_outlet_opening_size_detail2.size_qty)
                        - (
                            SELECT COALESCE(SUM(qty),0)
                            FROM outlet_sale_detail 
                            WHERE outlet_sale_detail.size_id = fg_outlet_opening_size_detail2.size_id 
                              AND outlet_sale_detail.scan_barcode = '$barcode'
                        )) AS stock
                    ")
                )
                ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'fg_outlet_opening_size_detail2.mainstyle_id')
                ->join('brand_master', 'brand_master.brand_id', '=', 'fg_outlet_opening_size_detail2.brand_id')
                ->join('size_detail', 'size_detail.size_id', '=', 'fg_outlet_opening_size_detail2.size_id')
                ->where('fg_outlet_opening_size_detail2.barcode', '=', $barcode);
            
            $ProductList = $Query1->union($Query2)->get();


        }
        $html = ''; 
        $sr = 1;
        
        if(count($ProductList) > 0)
        {
            foreach ($ProductList as $row) 
            {
            
                if($row->fg_name != '' && $row->size_id != '' && $row->stock > 0)
                {
                    $html .= '<tr>
                            <td><a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" > X </a></td> 
                            <td><input type="number" step="any" name="srno[]" class="form-control" value="'.$sr++.'"  style="width:80px;"></td>
                            <td><input type="text"  name="scan_barcode[]" class="form-control" value="'.$request->scan_barcode.'"  style="width:120px;" readonly></td> 
                            <td><input type="text"  name="brand_name[]" class="form-control" value="'.$row->brand_name.'"  style="width:120px;" readonly>
                                <input type="hidden"  name="brand_id[]" class="form-control" value="'.$row->brand_id.'"  style="width:120px;" readonly></td> 
                            <td><input type="text"  name="product_name[]" class="form-control" value="'.$row->fg_name.'"  style="width:150px;" readonly>
                                <input type="hidden"  name="product_id[]" class="form-control" value="'.($row->color_id."".$row->size_id).'"  style="width:120px;" readonly>
                                <input type="hidden"  name="style_no[]" class="form-control" value="'.(isset($row->style_no) ? $row->style_no : '-').'"  style="width:120px;" readonly>
                            </td> 
                            <td><input type="hidden"  name="size_id[]" class="form-control" value="'.$row->size_id.'"><input type="text"  name="size_name[]" class="form-control" value="'.$row->size_name.'" style="width:100px;" readonly></td> 
                            <td><input type="number" step="any" name="stock_qty[]" class="form-control" value="'.$row->stock.'"   style="width:100px;" readonly></td> 
                            <td><input type="number" step="any" name="rate[]" class="form-control" value="'.$row->size_rate.'"   style="width:100px;" readonly></td> 
                            <td><input type="number" step="any" name="qty[]" class="form-control" value="1" onchange="calQty(this);"  style="width:100px;" readonly></td> 
                            <td>
                                <input type="number" step="any" name="amount[]" class="form-control" value="0" style="width:100px;" readonly>
                                <input type="hidden" step="any" name="discount[]" class="form-control" value="0"   style="width:100px;" onchange="calQty(this);">
                                <input type="hidden" step="any" name="discount_amount[]" class="form-control" value="0"   style="width:100px;" readonly>
                                <input type="hidden" step="any" name="gst_per[]" class="form-control" value="0"   style="width:100px;" readonly>
                                <input type="hidden" step="any" name="gst_amount[]" class="form-control" value="0"  style="width:100px;" readonly>
                                <input type="hidden" step="any" name="total_amount[]" class="form-control" value="0"  style="width:100px;" readonly>
                            </td>  
                    </tr>';
                }
            }
        }
        return response()->json(['html' => $html]);
    }
    
    
    
    public function OutletSalePrint($id)
    {
        // Generate barcode and get base64 string
        $masterData =  DB::table('outlet_sale_master')->select('outlet_sale_master.*', 'employeemaster.fullName','payment_options.payment_option_name')
            ->join('employeemaster', 'employeemaster.employeeCode', '=', 'outlet_sale_master.employeeCode', 'left outer')
            ->join('payment_options', 'payment_options.payment_option_id', '=', 'outlet_sale_master.payment_option_id', 'left outer')
            ->where('outlet_sale_id', $id)
            ->first(); 
        $detailData = DB::table('outlet_sale_detail')->select('outlet_sale_detail.*', 'size_detail.size_name')
        ->leftjoin('size_detail', 'size_detail.size_id', '=', 'outlet_sale_detail.size_id')
        ->where('outlet_sale_id', $id)
        ->get();
        
        $srno = 1;
        $data = $detailData->map(function ($item) use (&$srno){
            return [
                $srno++,             // Replace with the actual column name for ID
                $item->product_name,   // Replace with the actual column name for Product Name
                $item->size_name,    // Replace with the actual column name for Product Name
                $item->qty,       // Replace with the actual column name for Quantity
                number_format($item->rate, 2),  
                number_format(($item->qty * $item->rate), 2),           // Replace with the actual column name for Price
            ]; 
        })->toArray();
 
        $barcodeData = $this->generateBarcode($masterData, $data);
        // Return the view with the barcode data
        return view('OutletSalePrint', ['generateBarcode' => $barcodeData]);
    }

    public function generateBarcode($masterData, $detailData) 
    { 
        try {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(78, 100), true, 'UTF-8', false);
            $pdf->SetMargins(1, 1, 1, 1);
            $pdf->SetAutoPageBreak(false); // Disable auto page breaks
    
            // Add the first page
            $pdf->AddPage('P', array(78, 100));
    
            // Set font and add header content
            // $pdf->SetFont('helvetica', 'B', 10);
            // $companyName = "KEN GLOBAL DESIGNS PVT. LTD.";
            // $pdf->SetXY(1, 2);
            // $pdf->MultiCell(0, 6, $companyName, 0, 'C');
            
            // $pdf->SetFont('helvetica', '', 9);
            // $detailsTop = "18/20, Back Side Of Hotel City In,\nIndustrial Estate, Ichalkarnji-416115,\nGSTIN : 27ABCCS7591Q1ZD";
            // $pdf->SetXY(1, 8);
            // $pdf->MultiCell(0, 5, $detailsTop, 0, 'C');
            
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetXY(1, 1);
            $pdf->Cell(0, 10, 'Receipt', 0, 1, 'C');
            
            // Add "====" line before the first table
            $pdf->SetXY(1, 10); 
            $pdf->Cell(0, 0, '=========================================================', 0, 1, 'C');
            
            // Add bill details
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetXY(1, 15);
            $pdf->MultiCell(0, 5, "Bill No: ".$masterData->bill_no, 0, 'L');
            $pdf->SetXY(50, $pdf->GetY() - 5);
            $pdf->MultiCell(0, 5, "Date: ".date("d-m-Y", strtotime($masterData->bill_date)), 0, 'L');
            $pdf->SetXY(1, $pdf->GetY() + 2);
            $pdf->MultiCell(0, 5, "Name: ".($masterData->fullName ? $masterData->fullName : $masterData->other_customer), 0, 'L');
            $pdf->SetXY(1, $pdf->GetY() + 2);
            $pdf->MultiCell(0, 5, "Paymode: ".$masterData->payment_option_name, 0, 'L');
            
            // Add table header
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetXY(4, $pdf->GetY() + 4);
            $header = ['SN.', 'Item', 'Size', 'Qty', 'Rate', 'Amount'];
            $columnWidths = [7, 20, 8, 9, 11, 15];
            foreach ($header as $i => $colName) {
                $pdf->Cell($columnWidths[$i], 5, $colName, 1, 0, 'C', false);
            }
            $pdf->Ln();
            
            $pdf->SetFont('helvetica', '', 9);
            // Add table rows
            $lineHeight = 5;
            $currentY = $pdf->GetY();
            // Define column indices that should have a left border
            foreach ($detailData as $row) {
                if ($currentY + $lineHeight > $pdf->getPageHeight() - 30) { // Adjusted for space
                    $pdf->AddPage(); // Add a new page if needed
                    $currentY = 10; // Reset Y position for new page
                    $pdf->SetXY(1, $currentY); // Ensure the new page has the correct starting position
                }
                $pdf->SetXY(4, $pdf->GetY() + 0);
                foreach ($row as $i => $cell) {
                    
                    if($i == 1)
                    {
                        $align = 'L';
                    }
                    else if($i == 2)
                    {
                        $align = 'C';
                    }
                    else
                    {
                        $align = 'R';
                    }
                    
                    $pdf->Cell($columnWidths[$i], $lineHeight, $cell, 1, 0, $align, false);
                }
                $pdf->Ln();
                $currentY = $pdf->GetY();
            }


            
            // Position and draw the "Total Qty" and "Total Amount" in a single row
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetXY(4, $currentY);
            $pdf->Cell($columnWidths[0] + $columnWidths[1] + $columnWidths[2], $lineHeight, 'Qty', 1, 0, 'R');
            $pdf->Cell($columnWidths[3], $lineHeight, round($masterData->total_qty), 1, 0, 'R');
            $pdf->Cell($columnWidths[4], $lineHeight, 'Total', 1, 0, 'R'); // Add label for Total Amount
            $pdf->Cell($columnWidths[5], $lineHeight, number_format($masterData->gross_amount, 2), 1, 0, 'R'); // Add total amount value
            $pdf->Ln(); 
            
            
            // Update the current Y position
            $currentY += 10;

            // Assume you have the table height stored in a variable
            $tableHeight = $pdf->GetY() - 2; // Replace this with the actual height of your table
            
            $pageHeight = $pdf->getPageHeight(); // Get the height of the page
            $bottomMargin =  $pdf->GetY()+3; // Adjust the bottom margin as needed
            
            // Center the text
            $pdf->SetY($pdf->GetY() + 4);
            $pdf->SetX(($pdf->getPageWidth() - $pdf->GetStringWidth('Thank You... Visit Again...!!!')) / 8); 
            
            // Add the text
            $pdf->Cell(0, 0, 'No Returns or Exchanges After Purchase', 0, 1, 'C'); 
            $pdf->Cell(0, 0, 'Thank You... Visit Again...!!!', 0, 1, 'C'); 
            $pdf->Cell(0, 0, '*** Happy Diwali ***', 0, 1, 'C'); 

            
            // Output the final PDF
            $pdf->Output('tax_invoice_outsale.pdf', 'I');
    
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function OutletSaleReport(Request $request)
    {
        $filter = ''; 
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate : date('Y-m-d');
        
        if($fromDate != '' && $toDate != '')
        {
            $filter .= " AND outlet_sale_detail.bill_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        $OutletSaleData = DB::SELECT("SELECT outlet_sale_detail.*,
                                       ms1.mainstyle_name as mainstyle_name1,ms2.mainstyle_name as mainstyle_name2,
                                       size_detail.size_name, 
                                       c1.color_name as color_name1, c2.color_name as color_name2,
                                       lm1.ac_short_name as ac_short_name1,lm2.ac_short_name as ac_short_name2,payment_options.payment_option_name,
                                       employeemaster.fullName,outlet_sale_master.other_customer,outlet_sale_master.employee_type,outlet_sale_master.mobile_no,
                                       SUM(outlet_sale_detail.qty) AS size_qty,brand_master.brand_name
                                FROM outlet_sale_detail 
                                INNER JOIN outlet_sale_master ON outlet_sale_master.outlet_sale_id = outlet_sale_detail.outlet_sale_id 
                                LEFT JOIN fg_location_transfer_inward_size_detail2 ON fg_location_transfer_inward_size_detail2.barcode = outlet_sale_detail.scan_barcode  
                                LEFT JOIN fg_outlet_opening_size_detail2 ON fg_outlet_opening_size_detail2.barcode = outlet_sale_detail.scan_barcode  
                                LEFT JOIN main_style_master as ms1 ON ms1.mainstyle_id = fg_location_transfer_inward_size_detail2.mainstyle_id 
                                LEFT JOIN main_style_master as ms2 ON ms2.mainstyle_id = fg_outlet_opening_size_detail2.mainstyle_id 
                                LEFT JOIN ledger_master as lm1 ON lm1.ac_code = fg_location_transfer_inward_size_detail2.Ac_code
                                LEFT JOIN ledger_master as lm2 ON lm2.ac_code = fg_outlet_opening_size_detail2.Ac_code
                                LEFT JOIN size_detail ON size_detail.size_id = outlet_sale_detail.size_id 
                                LEFT JOIN payment_options ON payment_options.payment_option_id = outlet_sale_master.payment_option_id 
                                LEFT JOIN employeemaster ON employeemaster.employeeCode = outlet_sale_master.employeeCode  
                                LEFT JOIN color_master as c1 ON c1.color_id = fg_location_transfer_inward_size_detail2.color_id 
                                LEFT JOIN color_master as c2 ON c2.color_id = fg_outlet_opening_size_detail2.color_id   
                                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = fg_location_transfer_inward_size_detail2.sales_order_no 
                                LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
                                WHERE 1 ".$filter."
                                GROUP BY outlet_sale_detail.scan_barcode, 
                                         outlet_sale_detail.size_id");      
                                         
        return view('OutletSaleReport', compact('OutletSaleData', 'fromDate', 'toDate'));
    }


    public function OutletEmployeeWiseReport(Request $request)
    {
        $filter = ''; 
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate : date('Y-m-d');
        
        if($fromDate != '' && $toDate != '')
        {
            $filter .= " AND outlet_sale_detail.bill_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        $OutletSaleData = DB::SELECT("SELECT outlet_sale_detail.*,
                                       payment_options.payment_option_name,
                                       employeemaster.fullName,employeemaster.employeeCode,
                                       SUM(outlet_sale_detail.qty) AS qty,SUM(outlet_sale_detail.amount) AS value, 
                                       sum(discount_amount) as discount_amount, sum(gst_amount) as gst_amount, sum(total_amount) as amount
                                FROM outlet_sale_detail 
                                INNER JOIN outlet_sale_master ON outlet_sale_master.outlet_sale_id = outlet_sale_detail.outlet_sale_id 
                                LEFT JOIN fg_location_transfer_inward_size_detail2 ON fg_location_transfer_inward_size_detail2.barcode = outlet_sale_detail.scan_barcode  
                                LEFT JOIN fg_outlet_opening_size_detail2 ON fg_outlet_opening_size_detail2.barcode = outlet_sale_detail.scan_barcode  
                                LEFT JOIN payment_options ON payment_options.payment_option_id = outlet_sale_master.payment_option_id 
                                LEFT JOIN employeemaster ON employeemaster.employeeCode = outlet_sale_master.employeeCode  
                                WHERE outlet_sale_master.employee_type = 1 ".$filter."
                                GROUP BY outlet_sale_detail.bill_date");      
                                         
        return view('OutletEmployeeWiseReport', compact('OutletSaleData', 'fromDate', 'toDate'));
    }
    
    public function OutletUI(Request $request)
    {
        return view('OutletUI');
    }
    
    public function BrandWiseInwardOutwardReport(Request $request)
    {
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate : date('Y-m-d');    
        
        $filter = '';
 
        if($fromDate != '' && $toDate != '')
        {
            $filter .= " AND outlet_sale_detail.bill_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        // $OutletSaleData = DB::SELECT("SELECT outlet_sale_detail.*, brand_master.brand_name,outlet_sale_detail.rate as outlet_rate,
        //                               SUM(outlet_sale_detail.qty) AS outlet_qty,SUM(outlet_sale_detail.amount) AS outlet_value,
        //                               (SELECT sum(fg_location_transfer_inward_size_detail2.size_qty) FROM fg_location_transfer_inward_size_detail2 
        //                                 INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = fg_location_transfer_inward_size_detail2.sales_order_no 
        //                                 WHERE buyer_purchse_order_master.brand_id = outlet_sale_detail.brand_id) as inward_qty,
        //                               (SELECT sum(fg_location_transfer_inward_size_detail2.size_qty * fg_location_transfer_inward_size_detail2.size_rate) FROM fg_location_transfer_inward_size_detail2 
        //                                 INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = fg_location_transfer_inward_size_detail2.sales_order_no 
        //                                 WHERE buyer_purchse_order_master.brand_id = outlet_sale_detail.brand_id) as inward_value
        //                         FROM outlet_sale_detail 
        //                         INNER JOIN outlet_sale_master ON outlet_sale_master.outlet_sale_id = outlet_sale_detail.outlet_sale_id   
        //                         LEFT JOIN brand_master ON brand_master.brand_id = outlet_sale_detail.brand_id  
        //                         WHERE 1 $filter GROUP BY outlet_sale_detail.brand_id");   
        
        $OutletSaleData = DB::select("
            SELECT 
                t.brand_id,
                b.brand_name,
                SUM(t.outlet_qty) AS outlet_qty,
                SUM(t.outlet_value) AS outlet_value,
                SUM(t.inward_qty) AS inward_qty,
                SUM(t.inward_value) AS inward_value
            FROM (
                -- Part 1: Brands from outlet_sale_detail
                SELECT 
                    outlet_sale_detail.brand_id,
                    SUM(outlet_sale_detail.qty) AS outlet_qty,
                    SUM(outlet_sale_detail.amount) AS outlet_value,
                    0 AS inward_qty,
                    0 AS inward_value
                FROM outlet_sale_detail
                INNER JOIN outlet_sale_master 
                    ON outlet_sale_master.outlet_sale_id = outlet_sale_detail.outlet_sale_id
                WHERE 1 $filter
                GROUP BY outlet_sale_detail.brand_id
        
                UNION ALL
         
                SELECT 
                    buyer_purchse_order_master.brand_id,
                    0 AS outlet_qty,
                    0 AS outlet_value,
                    SUM(fg_location_transfer_inward_size_detail2.size_qty) AS inward_qty,
                    SUM(fg_location_transfer_inward_size_detail2.size_qty * fg_location_transfer_inward_size_detail2.size_rate) AS inward_value
                FROM fg_location_transfer_inward_size_detail2
                INNER JOIN buyer_purchse_order_master 
                    ON buyer_purchse_order_master.tr_code = fg_location_transfer_inward_size_detail2.sales_order_no
                GROUP BY buyer_purchse_order_master.brand_id
            ) AS t
            LEFT JOIN brand_master b ON b.brand_id = t.brand_id
            GROUP BY t.brand_id, b.brand_name
        ");
    

        return view('BrandWiseInwardOutwardReport', compact('OutletSaleData','fromDate', 'toDate'));
        
    }
    
    
    public function OutletSalesReport(Request $request)
    {
        $fromDate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');
        $toDate = isset($request->toDate) ? $request->toDate : date('Y-m-d');    
        
        $filter = '';
 
        if($fromDate != '' && $toDate != '')
        {
            $filter .= " AND outlet_sale_master.bill_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        $OutletSaleData = DB::select("SELECT outlet_sale_master.bill_date, (SELECT SUM(net_amount) FROM outlet_sale_master as os1 WHERE payment_option_id = 1 AND os1.bill_date = outlet_sale_master.bill_date) as cash_sale, 
                                        (SELECT SUM(net_amount) FROM outlet_sale_master as os2 WHERE payment_option_id = 2 AND os2.bill_date = outlet_sale_master.bill_date) as upi_sale,
                                        (SELECT SUM(net_amount) FROM outlet_sale_master as os3 WHERE payment_option_id = 3 AND os3.bill_date = outlet_sale_master.bill_date) as dedection_sale
                                        FROM outlet_sale_master WHERE 1 $filter GROUP BY outlet_sale_master.bill_date");
                                        
        return view('OutletSalesReport', compact('OutletSaleData','fromDate','toDate'));
    }
        
}

