      
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
                                          $Sold=0;  $TransferQty=0;
                                          $SoldData=DB::select("SELECT ifnull(sum(sale_transaction_detail.order_qty),0)  as sold_qty
                                          from sale_transaction_detail
                                          inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=sale_transaction_detail.sales_order_no
                                          where buyer_purchse_order_master.brand_id='".$FG->brand_id."' and 
                                          buyer_purchse_order_master.Ac_code='".$FG->Ac_code."'
                                          group by  buyer_purchse_order_master.brand_id
                                            ");
                                            foreach($SoldData as $rowsold)
                                         { $Sold= $Sold + $rowsold->sold_qty; }
                                           
                                          $TransferData=DB::select("SELECT ifnull(sum(transfer_packing_inhouse_detail.size_qty_total),0)  as transfer_qty
                                          from transfer_packing_inhouse_detail
                                          inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=transfer_packing_inhouse_detail.main_sales_order_no
                                          where buyer_purchse_order_master.brand_id='".$FG->brand_id."' and 
                                          buyer_purchse_order_master.Ac_code='".$FG->Ac_code."' and transfer_packing_inhouse_detail.usedFlag=1
                                          group by  buyer_purchse_order_master.brand_id"); 
                                          foreach($TransferData as $rowtrasn)
                                          {$TransferQty= $TransferQty + $rowtrasn->transfer_qty ; }
                                          
                                          
                                          $FGQty= $FG->packing_grn_qty-$Sold-$TransferQty;
                                          
                                          @endphp 
                                          
                                            <tr>
                                                  <td nowrap>{{$FG->Ac_name }}</td>
                                                  <td>{{$FG->brand_name }}</td>
                                                  <td>{{$FG->mainstyle_name }}</td>
                                                 <td class="alignRight">{{money_format('%!.0n',($FG->packing_grn_qty))}}</td>
                                                 <td class="alignRight">{{money_format('%!.0n',($Sold))}}</td>
                                                 <td class="alignRight">{{money_format('%!.0n',($TransferQty))}}</td>
                                                 
                                                  <td class="alignRight">{{money_format('%!.0n',($FG->packing_grn_qty-$Sold-$TransferQty)) }}</td>
                                                  <td class="alignRight">{{ money_format('%!i',$FG->order_rate)}}</td>
                                                  <td class="alignRight">{{ number_format(((($FG->packing_grn_qty-$Sold-$TransferQty) * $FG->order_rate)/100000),2)}}</td>
                                            </tr>
                                          @php      
                                            $TotalPack = $TotalPack + $FG->packing_grn_qty;
                                            $TotalSold = $TotalSold + $Sold;
                                            $TotalTransfer = $TotalTransfer + $TransferQty;
                                            $TotalStock = $TotalStock + ($FG->packing_grn_qty-$Sold-$TransferQty);
                                            $TotalValue =$TotalValue + ((($FG->packing_grn_qty-$Sold-$TransferQty) * $FG->order_rate)/100000);
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
                                              <td class="alignRight"></td>
                                              <td class="alignRight">{{ money_format('%!.2n',$TotalValue)}}</td>
                                             </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" ></script>
  
                        <script>
                            $('tfoot').each(function () {
                                $(this).insertBefore($(this).siblings('thead'));
                            });
                        </script>
                        
                        @endsection