@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp  
<!-- end page title -->
<style>
    #lblSync
    {
        background: #a8e94269;
        padding: 10px;
        font-weight: 900;
        background-position: left top;
        padding-top:95px;
        margin-bottom:60px;
        -webkit-animation-duration: 10s;animation-duration: 10s;
        -webkit-animation-fill-mode: both;animation-fill-mode: both;
    }
    .hide
    {
        display:none;
    }
</style>
  <div class="row">
    <div class="col-12">
        <div class="card"> 
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tbl" class="table table-bordered   nowrap w-100">
                      <thead> 
                        <tr style="text-align:center; white-space:nowrap">
			               <th nowrap>Order No</th>
                           <th nowrap>Vendor Name</th>
                           <th class="text-center" nowrap>Color</th>
                           <th class="text-center" nowrap>Style</th>
                           <th class="text-center" nowrap>Work Order Qty</th>
                           <th class="text-center" nowrap>FOB Rate</th>
                           <th class="text-center" nowrap>Cut Qty</th>
                           <th class="text-center" nowrap>Sew Qty</th>
                           <th class="text-center" nowrap>Packing Qty</th>
                           <th class="text-center" nowrap>Shipped Qty</th>
                           <th class="text-center" nowrap>Finishing WIP</th>
                           <th class="text-center" nowrap>Finishing WIP Value</th>
                           <th class="text-center" nowrap>Cut To Pack</th>
                           <th class="text-center" nowrap>Cut to Ship</th>
                        </tr>
                        </thead>
                       <tbody>
                      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>  
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script type="text/javascript">

    function tableData() 
    {
         var currentURL = window.location.href; 
         
      	 $('#tbl').DataTable().clear().destroy();
        
          var table = $('#tbl').DataTable({
            ajax: currentURL,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
              columns: [
                  {data: 'tr_code', name: 'tr_code'},
                  {data: 'vendorName', name: 'vendorName'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'mainstyle_name', name: 'mainstyle_name'},
                  {data: 'order_qty', name: "order_qty"},
                  {data: 'order_rate', name: 'order_rate'}, 
                  {data: 'Cut_Qty', name: 'Cut_Qty'},
                  {data: 'sew_Qty', name: 'sew_Qty'},
                  {data: 'pack_Qty', name: 'pack_Qty'},
                  {data: 'Ship', name: 'Ship'},
                  {data: 'sew_pack', name: 'sew_pack'},
                  {data: 'sew_pack_value', name: 'sew_pack_value'},
                  {data: 'Cut_To_Pack', name: 'Cut_To_Pack'},
                  {data: 'Cut_to_Ship', name: 'Cut_to_Ship'},
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
    
  
</script>                                        
@endsection