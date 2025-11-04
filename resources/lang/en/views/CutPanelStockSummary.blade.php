      
   @extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
                          
                         
                        @if(session()->has('message'))
                        <div class="col-md-3">
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        </div>
                        @endif

                        @if(session()->has('messagedelete'))
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                {{ session()->get('messagedelete') }}
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                  
 
                 @php                           
        
 
    //DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT vpo_code,sales_order_no, 
      cut_panel_grn_size_detail2.style_no, cut_panel_grn_size_detail2.item_code,item_name,quality_master.quality_name,
      cut_panel_grn_size_detail2.color_id, color_master.color_name, cut_panel_grn_size_detail2.size_id, size_name,fg_name,
      ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 
      inner join fg_master on fg_master.fg_id=cut_panel_grn_size_detail2.fg_id 
      inner join item_master on item_master.item_code=cut_panel_grn_size_detail2.item_code 
      inner join quality_master on quality_master.quality_code=item_master.quality_code
      inner join color_master on color_master.color_id=cut_panel_grn_size_detail2.color_id 
      inner join size_detail on size_detail.size_id=cut_panel_grn_size_detail2.size_id
      
      where sales_order_no in (select distinct(sales_order_no) as sales_order_no from vendor_purchase_order_master)   
       group by cut_panel_grn_size_detail2.sales_order_no,cut_panel_grn_size_detail2.color_id ,cut_panel_grn_size_detail2.size_id  ");
       

// $query = DB::getQueryLog();
// $query = end($query);
// dd($query);
     @endphp
     
     
     
        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
              <thead>
              <tr>
              <th>SrNo</th>
              <th>Sales Order No</th>
              <th>Item Code</th> 
             
               <th>Style No</th> 
              <th>Quality</th> 
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
            echo '<tr>';
            echo '
            <td>'.$no.'</td>';
            echo '<td>'.$row->sales_order_no.' </td>';
            echo '<td>'.$row->item_code.' </td>';
             
            echo '<td>'.$row->fg_name.'('.$row->style_no.')'. '</td>';
            echo '<td>'.$row->quality_name.' </td>';
            echo '<td>'.$row->color_name.' </td>';
 
      
     
   // DB::enableQueryLog();  
      $List = DB::select("SELECT cut_panel_issue_size_detail2.color_id,   cut_panel_issue_size_detail2.size_id,  
      ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
      where 
      cut_panel_issue_size_detail2.sales_order_no='".$row->sales_order_no."' and
      cut_panel_issue_size_detail2.color_id='".$row->color_id."' and cut_panel_issue_size_detail2.size_id='".$row->size_id."'
        
       ");
       

       
       
       
        echo '<td>'.$row->size_name.' </td>';
       echo '<td>'.$row->size_qty.' </td>';
       echo '<td>'.$List[0]->size_qty.' </td>';
       echo '<td>'.($row->size_qty-$List[0]->size_qty).' </td>';
          
          
          
          }
                                           
 
@endphp                                 
                                            
                                                      </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        
                                                       <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
  
                        
                        @endsection