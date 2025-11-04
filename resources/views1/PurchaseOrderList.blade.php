@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Data Tables</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
               <li class="breadcrumb-item active">Purchase Order List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
@if(session()->has('delete'))
<div class="alert alert-danger">
   {{ session()->get('delete') }}
</div>
@endif
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of PO</p>
                  <h4 class="mb-0" style="color:#fff;">@foreach($InwardFabric as $row) {{$row->noOfPO}} @endforeach</h4>
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
                  <p class="  fw-medium" style="color:#fff;" >PO Total (Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;" >@foreach($InwardFabric as $row)    {{number_format((double)($row->poTotal/100000), 2, '.', '')}} @endforeach</h4>
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
                  <p class="  fw-medium" style="color:#fff;">PO Received Total(Lacs)</p>
                  <h4 class="mb-0" style="color:#fff;">@foreach($InwardFabric as $row)  {{number_format((double)($row->receivedTotal/100000), 2, '.', '')}} @endforeach</h4>
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
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">GRN Total</p>
                  <h4 class="mb-0" style="color:#fff;">0</h4>
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
</div>
@if($chekform->write_access==1)
<div class="row">
   <div class="col-lg-6">
      <a href="{{ Route('PurchaseOrder.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New PO</button></a> &nbsp; &nbsp; &nbsp;
      <a href="{{ Route('POApprovalList') }}"><button type="button" class="btn btn-success w-md float-right">Approval</button></a>&nbsp; &nbsp; &nbsp;
      <a href="{{ Route('PODisApprovalList') }}"><button type="button" class="btn btn-danger w-md float-right">Disapproved</button></a>
   </div>
</div>
@endif
</br>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="purchase_order_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                  <tr>
                     <th>SrNo</th>
                     <th>PO No</th>
                     <th>PO Date</th>
                     <th>Sales Order Nos</th>
                     <th>Order Type</th>
                     <th>Supplier</th>
                     <th>GST</th>
                     <th>Gross Amount</th>
                     <th>GST Amount</th>
                     <th>Net Amount</th>
                     <th>Narration</th>
                     <th>Approval  Status</th>
                     <th>Username</th>
                     <th>Print</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 

      $(function () 
        {
            $('#purchase_order_table').dataTable({
                "bDestroy": true
            }).fnDestroy();
            
           var table = $('#purchase_order_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('PurchaseOrder.index') }}",
            
                 dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            
            columns: [
              {data: 'sr_no', name: 'sr_no'},
              {data: 'pur_code', name: 'pur_code'},
              {data: 'pur_date', name: 'pur_date'},
              {data: 'sono', name: "sono"},
              {data: 'bom_type_wise', name: 'bom_type_wise'},
              {data: 'ac_name1', name: 'ac_name1'},
              {data: 'tax_type_name', name: 'tax_type_name'},
              {data: 'Gross_amount', name: 'Gross_amount'},
              {data: 'Gst_amount', name: 'Gst_amount'},
              {data: 'Net_amount', name: 'Net_amount'},
              {data: 'narration', name: 'narration'},
              {data: 'approved_status', name: 'approved_status'},
              {data: 'username', name: 'username'},
              {data: 'action1', name: 'action1',orderable: false, searchable: false},
              {data: 'action2', name: 'action2',orderable: false, searchable: false},
              {data: 'action3', name: 'action3',orderable: false, searchable: false},
            ]
        });
        
      });
   
   $(document).on('click','.DeleteRecord',function(e) 
   {
      var Route = $(this).attr("data-route");
      var  potype= $(this).data("potype");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
      if (confirm("Are you sure you want to Delete this Record?") == true)
      {
        $.ajax({
           url: Route,
           type: "DELETE",
            data: {
            "id": id,
            "potype":potype,
            "_method": 'DELETE',
             "_token": token,
             },
           success: function(data)
           {
             location.reload();
           }
        });
      }
   
   });
</script>
@endsection