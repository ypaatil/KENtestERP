@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Finished Goods Stock By Buyer's Brand</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Finished Goods Stock By Buyer's Brand</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<style>
   .alignRight{
   text-align:right;
   }
   tfoot {
   display: table-header-group;
   }
</style>
<div class="row">
   <div class="col-md-10">
      <div class="card mini-stats-wid">
         <div class="card-body">
            <form action="/FGStockSummaryReport" method="GET">
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="job_status_id" class="form-label">PO Status</label>
                        <select name="job_status_id" class="form-control" id="job_status_id">
                           <option value="0" {{ $job_status_id == 0 ? 'selected="selected"' : '' }}>--All--</option>
                           <option value="1" {{ $job_status_id == 1 ? 'selected="selected"' : '' }}>Moving</option>
                           <option value="2" {{ $job_status_id == 2 ? 'selected="selected"' : '' }}>Non Moving</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="order_type" class="form-label">Date</label>
                        <input type="date" class="form-control" name="tr_date" id="tr_date" value="{{$tr_date}}">
                     </div>
                  </div>
                  <div class="col-md-3 mt-3"> 
                     <button type="submit" class="btn btn-primary">Search</button>
                     <a href="/FGStockSummaryReport" class="btn btn-warning">Clear</a>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table  data-order='[[ 3, "desc" ]]' data-page-length='50' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 footable_2">
               <thead>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>Buyer Name</th>
                     <th>Buyer Brand</th>
                     <th>Main Style Category</th>
                     <th>Pack</th>
                     <th>Sold</th>
                     <th>Transfer</th>
                     <th>FG Stock</th>
                     <th>FOB Rate</th>
                     <th>Value (In Lakh)</th>
                  </tr>
               </thead>
               <tbody>
                  @php 
                  $TotalPack=0;
                  $TotalSold=0;
                  $TotalTransfer=0;
                  $TotalStock=0;
                  $TotalValue=0;  
                  @endphp
                  @foreach($FinishedGoodsStock as $FG) 
                  @php 
                  if($job_status_id == 1)
                  {
                        $FinishedGoodsStock1 = DB::select("SELECT tr_code,buyer_purchse_order_master.order_rate,ifnull(sales_order_costing_master.total_cost_value,0) as total_cost_value
                          FROM buyer_purchse_order_master
                          left JOIN sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code 
                          WHERE buyer_purchse_order_master.job_status_id = 1 AND buyer_purchse_order_master.mainstyle_id='".$FG->mainstyle_id."' AND brand_id=".$FG->brand_id." GROUP BY buyer_purchse_order_master.tr_code");
                  }
                  else if($job_status_id == 2)
                  {
                        $FinishedGoodsStock1 = DB::select("SELECT tr_code,buyer_purchse_order_master.order_rate,ifnull(sales_order_costing_master.total_cost_value,0) as total_cost_value
                          FROM buyer_purchse_order_master
                          left JOIN sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code 
                          WHERE buyer_purchse_order_master.job_status_id != 1 AND buyer_purchse_order_master.mainstyle_id='".$FG->mainstyle_id."' AND brand_id=".$FG->brand_id." GROUP BY buyer_purchse_order_master.tr_code");
                  }
                  else
                  {
                        $FinishedGoodsStock1 = DB::select("SELECT tr_code,buyer_purchse_order_master.order_rate,ifnull(sales_order_costing_master.total_cost_value,0) as total_cost_value
                          FROM buyer_purchse_order_master
                          left JOIN sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code 
                          WHERE buyer_purchse_order_master.mainstyle_id='".$FG->mainstyle_id."' AND brand_id=".$FG->brand_id." GROUP BY buyer_purchse_order_master.tr_code");
                 
                  }
                  
                 
                  $pk = 0; 
                  $cart = 0;
                  $trans = 0;
                  $stock = 0;
                  $value = 0;
                  foreach($FinishedGoodsStock1 as $row)
                  { 
                      $packing = DB::SELECT("SELECT ifnull(sum(packing_inhouse_detail.size_qty_total),0) as pck_qty from packing_inhouse_detail where sales_order_no ='".$row->tr_code."' AND pki_date <='".$tr_date."'");
                      $carton = DB::SELECT("SELECT ifnull(sum(sale_transaction_detail.order_qty),0) as sold_qty from sale_transaction_detail where sales_order_no ='".$row->tr_code."' AND sale_date <='".$tr_date."'");
                      $transfer = DB::SELECT("SELECT ifnull(sum(transfer_packing_inhouse_size_detail2.size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 where main_sales_order_no ='".$row->tr_code."' AND tpki_date <='".$tr_date."'");
                      
                      $pk += isset($packing[0]->pck_qty) ? $packing[0]->pck_qty : 0; 
                      $cart += isset($carton[0]->sold_qty) ? $carton[0]->sold_qty : 0;
                      $trans += isset($transfer[0]->transfer_qty) ? $transfer[0]->transfer_qty : 0;
                     
                      $pk1 = isset($packing[0]->pck_qty) ? $packing[0]->pck_qty : 0; 
                      $cart1 = isset($carton[0]->sold_qty) ? $carton[0]->sold_qty : 0;
                      $trans1 = isset($transfer[0]->transfer_qty) ? $transfer[0]->transfer_qty : 0;
                      $stock = $pk-$cart-$trans;
                      $stock1 = $pk1-$cart1-$trans1;
                     
                      if($row->total_cost_value > 0)
                      { 
                      $rate  = $row->total_cost_value;
                      }
                      else
                      {
                      $rate  = $row->order_rate;
                      } 
                      $value += ($stock1) * $rate; 
                      }
                      if($stock > 0 && $value > 0)
                      {
                        $fob_rate = $value/$stock;
                      }
                      else
                      {
                        $fob_rate = 0;
                      }
                  @endphp 
                  <tr>
                     <td nowrap>{{$FG->Ac_name }}</td>
                     <td>{{$FG->brand_name }}</td>
                     <td>{{$FG->mainstyle_name }}</td>
                     <td class="alignRight">{{money_format('%!.0n',($pk))}}</td>
                     <td class="alignRight">{{money_format('%!.0n',($cart))}}</td>
                     <td class="alignRight">{{money_format('%!.0n',($trans))}}</td>
                     <td class="alignRight">{{money_format('%!.0n',($stock)) }}</td>
                     <td class="alignRight">{{ round($fob_rate,2)}}</td>
                     <td class="alignRight">{{ money_format('%!.0n',($value)) }}</td>
                  </tr>
                  @php      
                  $TotalPack = $TotalPack + $pk;
                  $TotalSold = $TotalSold + $cart;
                  $TotalTransfer = $TotalTransfer + $trans;
                  $TotalStock = $TotalStock + ($stock);
                  $TotalValue =$TotalValue + $value;
                 
                  if($TotalStock > 0 && $TotalValue > 0)
                  {
                     $avg_fob_rate = $TotalValue/$TotalStock;
                  }
                  else
                  {
                     $avg_fob_rate = 0;                  
                  }
                  @endphp
                  @endforeach
               </tbody>
               <tfoot style="background-color:#d7ed92; font-weight:bold;">
                  <td></td>
                  <td></td>
                  <td class="alignRight">Total Stock</td>
                  <td class="alignRight">{{money_format('%!.0n',$TotalPack) }}</td>
                  <td class="alignRight">{{money_format('%!.0n',$TotalSold) }}</td>
                  <td class="alignRight">{{money_format('%!.0n',$TotalTransfer) }}</td>
                  <td class="alignRight">{{money_format('%!.0n',$TotalStock) }}</td>
                  <td class="alignRight">{{round($avg_fob_rate,2)}}</td>
                  <td class="alignRight">{{ money_format('%!.0n',$TotalValue)}}</td>
               </tfoot>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" ></script>
<script>
   $('tfoot').each(function () {
       $(this).insertBefore($(this).siblings('thead'));
   });
</script>
@endsection