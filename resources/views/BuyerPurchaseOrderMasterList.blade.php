@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<style>
    .text-right
    {
        text-align:right;
    }
    
    .navbar-brand-box
    {
        width: 266px !important;
    }
</style>
@php
if($job_status_id==0) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales Order: All</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales Order : All</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Order Value(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($total_value/100000), 2, '.', '')}}   </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@php 
}           
elseif($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales Order: Open</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales Order : Open</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($shipped_qty/100000), 2, '.', '')}}  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format((double)($open_qty/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@php 
}
elseif($job_status_id==2) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales Order: Closed</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales Order : Closed</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($shipped_qty/100000), 2, '.', '')}}  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Balance Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format((double)($open_qty/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@php }    
elseif($job_status_id==3) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales Order: Cancelled</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales Order : Cancelled</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@php }
@endphp                           
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('BuyerPurchaseOrder.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
      <a href="javascript:void(0);" data-route="{{ Route('BuyerPurchaseOrder.index') }}" onclick="btnFilter(this);" id="all_Data"><button type="button" class="btn btn-danger w-md">All</button></a>
      <a href="javascript:void(0);" data-route="{{ Route('SalesOrderOpen') }}" onclick="btnFilter(this);" id="open_data"><button type="button" class="btn btn-warning w-md">Open</button></a>
      <a href="javascript:void(0);" data-route="{{ Route('SalesOrderClosed') }}" onclick="btnFilter(this);"><button type="button" class="btn btn-success w-md">Closed</button></a>
      <a href="javascript:void(0);" data-route="{{ Route('SalesOrderCancelled') }}" onclick="btnFilter(this);"><button type="button" class="btn btn-secondary w-md">Cancelled</button></a>
      <a href="javascript:void(0);" data-route="{{ Route('SalesOrderSample') }}" onclick="btnFilter(this);"><button type="button" class="btn btn-info w-md">Samples</button></a>
   </div>
</div>
@endif
@if(session()->has('message'))
<div class="col-md-12">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('error'))
<div class="col-md-12">
   <div class="alert alert-danger">
      {{ session()->get('error') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="salesOrderTable" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center;">
                     <th nowrap>SrNo</th>
                     <th nowrap>Buyer PO No</th>
                     <th nowrap>Order No</th>
                     <th nowrap>Order Received Date</th>
                     <th nowrap>Shipment Date</th>
                     <th nowrap>Buyer Name</th>
                     <th nowrap>Brand</th>
                     <th nowrap>Style Category</th>
                     <th nowrap>Style No</th>
                     <th nowrap>Order Qty</th>
                     <th nowrap>FOB Rate</th> 
                     <th nowrap>Narration</th>
                     <th nowrap>User</th>
                     <th class="text-center">Print</th>
                     <th class="text-center">Edit</th>
                     <th class="text-center">Delete</th>
                  </tr>
               </thead>
               <tbody>
                 
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
<script type="text/javascript"> 
   // $('#footable tfoot th').each( function () {
   //      var title = $(this).text();
   //          $(this).html( '<input type="text" style = "width:70px;" placeholder="'+title+'" />' );
   //  } );
    
   // // DataTable
   //  var otable = $('#footable').DataTable();
    
   //  // Apply the search
   //  otable.columns().every( function () {
     
   //      var that = this;
        
   //      $( 'input', this.footer() ).on( 'keyup change', function () {
            
   //          if ( that.search() !== this.value ) {
   //              // alert(this.value);         
   //              that
   //                  .search( this.value )
   //                  .draw();
   //          }
   //      } );
   //  } );
   $(function(){
      $("#open_data").trigger('click'); 
   });
   function btnFilter(ele)
   {
       var Route = $(ele).attr("data-route");
       
         $('#salesOrderTable').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#salesOrderTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: Route,
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'tr_code1', name: 'tr_code1'},
          {data: 'po_code', name: 'po_code'},
          {data: 'tr_code', name: 'tr_code'},
          {
                data: 'order_received_date',
                name: 'order_received_date',
                render: function(data, type, row) 
                {
                    return moment(data).format('DD/MM/YYYY');
                }
          },
          {
                data: 'shipment_date',
                name: 'shipment_date',
                render: function(data, type, row) 
                { 
                    return moment(data).format('DD/MM/YYYY');
                }
          },
          {data: 'Ac_name', name: 'Ac_name'},
          {data: 'brand_name', name: 'brand_name'},
          {data: 'mainstyle_name', name: 'mainstyle_name'},
          {data: 'style_no', name: 'style_no'},
          {data: 'total_qty', name: 'total_qty', className: 'text-right'},
          {
                data: 'order_rate',
                name: 'order_rate',
                className: 'text-right',
                render: function (data, type, row) {
                    if (!data) return '';
                    return parseFloat(data).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
          },
          {data: 'narration', name: 'narration'},
          {data: 'username', name: 'username'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false, className: 'text-center'},
          {data: 'action2', name: 'action2',orderable: false, searchable: false, className: 'text-center'},
          {data: 'action3', name: 'action3',orderable: false, searchable: false, className: 'text-center'},
        ],
        columnDefs: [
        {
            targets: 9, // index of the total_qty column
            render: function (data, type, row) {
                return Number(data).toLocaleString('en-IN'); // Convert to Indian currency format
            }
        },
        // { targets: [8, 9, 10, 11], render: $.fn.dataTable.render.text() }
        ]
        // drawCallback: function() {
        //     $('#salesOrderTable tbody tr').each(function() {
        //         $(this).find('td:eq(8), td:eq(9), td:eq(10), td:eq(11)').addClass('text-right');
        //     });
        // }
    });
    
   }
   
   
//   $(function () 
//     {
//         $('#salesOrderTable').dataTable({
//             "bDestroy": true
//         }).fnDestroy();
        
//       var table = $('#salesOrderTable').DataTable({
//         processing: true,
//         serverSide: false,
//         ajax: "{{ route('BuyerPurchaseOrder.index') }}",
        
//              dom: 'lBfrtip',
//         buttons: [
//             { extend: 'copyHtml5', footer: true },
//             { extend: 'excel', exportOptions: { modifier: { 
//              order : 'index', 
//              page : 'all', 
//              search : 'none'}}},
//             { extend: 'csvHtml5', footer: true },
//             { extend: 'pdfHtml5', footer: true }
//         ],
//         columns: [
//           {data: 'tr_code1', name: 'tr_code1'},
//           {data: 'po_code', name: 'po_code'},
//           {data: 'tr_code', name: 'tr_code'},
//           {data: 'order_received_date', name: "order_received_date"},
//           {data: 'shipment_date', name: 'shipment_date'},
//           {data: 'Ac_name', name: 'Ac_name'},
//           {data: 'brand_name', name: 'brand_name'},
//           {data: 'mainstyle_name', name: 'mainstyle_name'},
//           {data: 'total_qty', name: 'total_qty'},
//           {data: 'ShippedQty', name: 'ShippedQty'},
//           {data: 'Balance_Qty', name: 'Balance_Qty'},
//           {data: 'narration', name: 'narration'},
//           {data: 'job_status_name', name: 'job_status_name'},
//           {data: 'username', name: 'username'},
//           {data: 'action1', name: 'action1',orderable: false, searchable: false},
//           {data: 'action2', name: 'action2',orderable: false, searchable: false},
//           {data: 'action3', name: 'action3',orderable: false, searchable: false},
//         ]
//     });
    
//   });
   
   
   
   
    $(document).on('click','.DeleteRecord',function(e) {
   
       var Route = $(this).attr("data-route");
       var id = $(this).data("id");
       var token = $(this).data("token");
   
      //alert(Route);
     
     //alert(data);
       if (confirm("Are you sure you want to Delete this Record?") == true) {
     $.ajax({
            url: Route,
            type: "DELETE",
             data: {
             "id": id,
             "_method": 'DELETE',
              "_token": token,
              },
            
            success: function(data){
   
              // console.log(data);
             location.reload();
   
            }
   });
   }
   
    });
</script>  
@endsection