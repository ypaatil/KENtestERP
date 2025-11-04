@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Cut Panel Stock Summary</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Cut Panel Stock Summary</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@php
         $appendSql = '';
         $mainstyle_id =  isset($_GET['mainstyle_id']) ? $_GET['mainstyle_id'] : 0;
         $sales_order_no =  isset($_GET['sales_order_no']) ? $_GET['sales_order_no'] : 0;
         $item_code =  isset($_GET['item_code']) ? $_GET['item_code'] : 0;
         $fg_id =  isset($_GET['fg_id']) ? $_GET['fg_id'] : 0;
         $color_id =  isset($_GET['color_id']) ? $_GET['color_id'] : 0;
@endphp
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
                <form action="CutPanelStockSummary" method="GET" enctype="multipart/form-data"> 
                    <div class="row">
                        <div class="col-md-2">
                             <div class="mb-3">
                                <label for="mainstyle_id" class="form-label">Style Category</label>
                                 <select name="mainstyle_id" class="form-control select2" onchange="GetSalesOrder(this.value);" >
                                   <option value="">--All--</option>
                                   @foreach($MainStyleList as  $row)
                                   {    
                                        @php
                                            if($row->mainstyle_id == $mainstyle_id)
                                            {
                                                $selected = 'selected';
                                            }
                                            else
                                            {
                                                $selected = '';
                                            }
                                        @endphp
                                        <option value="{{ $row->mainstyle_id }}" {{$selected}} >{{ $row->mainstyle_name }}</option>
                                   }
                                   @endforeach
                                </select>
                             </div>
                          </div> 
                          <div class="col-md-2">
                             <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Sales Order no</label>
                                <select name="sales_order_no" class="form-control select2" id="sales_order_no" onchange="GetItemData(this.value);" >
                                   <option value="">--All--</option>  
                                </select>
                             </div>
                          </div>
                           <div class="col-md-2">
                             <div class="mb-3">
                                <label for="item_code" class="form-label">Item Name</label>
                                <select name="item_code" class="form-control select2" id="item_code" onchange="GetStyleData(this.value);" >
                                   <option value="">--All--</option>
                                </select>
                             </div>
                          </div>
                           <div class="col-md-2">
                             <div class="mb-3">
                                <label for="fg_id" class="form-label">Style No.</label>
                                <select name="fg_id" class="form-control select2" id="fg_id" onchange="GetGarmentColor(this.value);" >
                                   <option value="">--All--</option>
                                </select>
                             </div>
                          </div>
                           <div class="col-md-2">
                             <div class="mb-3">
                                <label for="color_id" class="form-label">Garment Color</label>
                                <select name="color_id" class="form-control select2" id="color_id"  >
                                   <option value="">--All--</option>
                                </select>
                             </div>
                          </div>
                           <div class="col-sm-2">
                             <div class="mb-3">
                              <label class="form-label"></label>
                              <div class="form-group mt-3">
                                 <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                 <a href="CutPanelStockSummary" class="btn btn-danger btn-sm">Clear</a>
                              </div>
                             </div>
                           </div>
                       </div>
                </form>
            @php     
             if($mainstyle_id != "")
             {
                $m_id = ' AND cut_panel_grn_size_detail2.mainstyle_id ='.$mainstyle_id;
             }
             else
             {
                 $m_id = '';
             }
             
             if($sales_order_no != "")
             {
                $s_id = ' AND cut_panel_grn_size_detail2.sales_order_no ="'.$sales_order_no.'"';
             }
             else
             {
                 $s_id = '';
             }
             
             if($item_code != "")
             {
                $I_id = ' AND cut_panel_grn_size_detail2.item_code ="'.$item_code.'"';
             }
             else
             {
                 $I_id = '';
             }
             
             if($fg_id != "")
             {
                $F_id = ' AND cut_panel_grn_size_detail2.fg_id ="'.$fg_id.'"';
             }
             else
             {
                 $F_id = '';
             }
             
             if($color_id != "")
             {
                $c_id = ' AND cut_panel_grn_size_detail2.color_id ="'.$color_id.'"';
             }
             else
             {
                 $c_id = '';
             }
             
             $appendSql = $m_id.' '.$s_id.' '.$I_id.' '.$F_id.' '.$c_id;
          
             //echo $appendSql;
            //DB::enableQueryLog();  
            if($appendSql == 0 && $appendSql == "")
            {
                $MasterdataList = DB::select("SELECT    sales_order_no, mainstyle_name,
                    cut_panel_grn_size_detail2.style_no, cut_panel_grn_size_detail2.item_code,item_name,quality_master.quality_name,
                    cut_panel_grn_size_detail2.color_id, color_master.color_name, cut_panel_grn_size_detail2.size_id, size_name,fg_name,
                    ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 
                    inner join main_style_master on main_style_master.mainstyle_id=cut_panel_grn_size_detail2.mainstyle_id
                    inner join fg_master on fg_master.fg_id=cut_panel_grn_size_detail2.fg_id 
                    inner join item_master on item_master.item_code=cut_panel_grn_size_detail2.item_code 
                    inner join quality_master on quality_master.quality_code=item_master.quality_code
                    inner join color_master on color_master.color_id=cut_panel_grn_size_detail2.color_id 
                    inner join size_detail on size_detail.size_id=cut_panel_grn_size_detail2.size_id
                    where sales_order_no in (select distinct(sales_order_no) as sales_order_no from vendor_purchase_order_master)   
                    group by cut_panel_grn_size_detail2.sales_order_no,cut_panel_grn_size_detail2.color_id ,cut_panel_grn_size_detail2.size_id");
            }
            else
            {
                $MasterdataList = DB::select("SELECT    sales_order_no, mainstyle_name,
                    cut_panel_grn_size_detail2.style_no, cut_panel_grn_size_detail2.item_code,item_name,quality_master.quality_name,
                    cut_panel_grn_size_detail2.color_id, color_master.color_name, cut_panel_grn_size_detail2.size_id, size_name,fg_name,
                    ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 
                    inner join main_style_master on main_style_master.mainstyle_id=cut_panel_grn_size_detail2.mainstyle_id
                    inner join fg_master on fg_master.fg_id=cut_panel_grn_size_detail2.fg_id 
                    inner join item_master on item_master.item_code=cut_panel_grn_size_detail2.item_code 
                    inner join quality_master on quality_master.quality_code=item_master.quality_code
                    inner join color_master on color_master.color_id=cut_panel_grn_size_detail2.color_id 
                    inner join size_detail on size_detail.size_id=cut_panel_grn_size_detail2.size_id
                    where 1 ".$appendSql."  
                    group by cut_panel_grn_size_detail2.sales_order_no,cut_panel_grn_size_detail2.color_id ,cut_panel_grn_size_detail2.size_id");
            }
            // $query = DB::getQueryLog();
            // $query = end($query);
            // dd($query);
            @endphp
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>SrNo</th>
                     <th>Style Category</th>
                     <th>Sales Order No</th>
                     <th>Item Name</th>
                     <th>Style No</th>
                     <th>Garment Color</th>
                     <th>Size</th>
                     <th>Total GRN</th>
                     <th>Total Outward</th>
                     <th>Total Stock</th>
                  </tr>
               </thead>
               <tbody>
                  @php          $no=1;
                  foreach ($MasterdataList as $row) 
                  {
                  echo '
                  <tr>
                     ';
                     echo '
                     <td>'.$no.'</td>
                     ';
                     echo '
                     <td style="text-align:center; white-space:nowrap">'.$row->mainstyle_name.' </td>
                     ';
                     echo '
                     <td style="text-align:center; white-space:nowrap">'.$row->sales_order_no.' </td>
                     ';
                     echo '
                     <td>'.$row->item_name.' </td>
                     ';
                     echo '
                     <td style="text-align:center; white-space:nowrap">'.$row->fg_name.'('.$row->style_no.')'. '</td>
                     ';
                     echo '
                     <td style="text-align:center; white-space:nowrap">'.$row->color_name.' </td>
                     ';
                     // DB::enableQueryLog();  
                     $List = DB::select("SELECT cut_panel_issue_size_detail2.color_id,   cut_panel_issue_size_detail2.size_id,  
                     ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
                     where 
                     cut_panel_issue_size_detail2.sales_order_no='".$row->sales_order_no."' and
                     cut_panel_issue_size_detail2.color_id='".$row->color_id."' and cut_panel_issue_size_detail2.size_id='".$row->size_id."'");
                     // $query = DB::getQueryLog();
                     //  $query = end($query);
                     // dd($query);
                     echo '
                     <td style="text-align:center; ">'.$row->size_name.' </td>
                     ';
                     echo '
                     <td style="text-align:right; ">'.$row->size_qty.' </td>
                     ';
                     echo '
                     <td style="text-align:right; ">'.$List[0]->size_qty.' </td>
                     ';
                     echo '
                     <td style="text-align:right; ">'.($row->size_qty-$List[0]->size_qty).' </td>
                     ';
                     $no=$no+1;
                     }
                     @endphp                                 
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    $(function()
    {
        GetSalesOrder(0);
        GetItemData(0);
        GetStyleData(0);
        GetGarmentColor(0);
    });
    function GetSalesOrder(obj)
    {
         $.ajax({
            type: "GET",
            url: "{{ route('GetSalesOrder') }}",
            data:{'mainstyle_id':obj, },
            success: function(data)
            {
                $("#sales_order_no").html(data.html);
            }
        });
    }
    
    function GetItemData(obj)
    {
        $.ajax({
            type: "GET",
            url: "{{ route('GetBuyerItemData') }}",
            data:{'sales_order_no':obj, },
            success: function(data)
            {
                $("#item_code").html(data.html);
                GetGarmentColor(0);
            }
        });
    }
        
    function GetStyleData(obj)
    {
         $.ajax({
            type: "GET",
            url: "{{ route('GetStyleData') }}",
            data:{'item_code':obj, },
            success: function(data)
            {
                $("#fg_id").html(data.html);
            }
        });
    }
    
    function GetGarmentColor(obj)
    {
        var sales_order_no = $('#sales_order_no').val();
        var fg_id = $('#fg_id').val();
        var item_code = $('#item_code').val();
        $.ajax({
            type: "GET",
            url: "{{ route('GetGarmentColor') }}",
            data:{'fg_id':obj, 'sales_order_no':sales_order_no,'fg_id':fg_id,'item_code':item_code },
            success: function(data)
            {
                $("#color_id").html(data.html);
            }
        });
    }
</script>
@endsection