@extends('layouts.master') 
@section('content')   
<style>
    .text-right
    {
        text-align:right;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">OB Pending List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">OB Pending List< </li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!--<div class="row">-->
<!--    <div class="col-md-3">-->
<!--    <div class="card mini-stats-wid" style="background-color:#152d9f;" >-->
<!--    <div class="card-body">-->
<!--    <div class="d-flex">-->
<!--    <div class="flex-grow-1">-->
<!--    <p class="  fw-medium" style="color:#fff;">No. of Orders</p>-->
<!--<h4 class="mb-0" style="color:#fff;">  </h4>-->
<!--    </div>-->
<!--    <div class="flex-shrink-0 align-self-center">-->
<!--    <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">-->
<!--    <span class="avatar-title" style="background-color:#152d9f;">-->
<!--    <i class="bx bx-copy-alt font-size-24"></i>-->
<!--    </span>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    <div class="col-md-3">-->
<!--    <div class="card mini-stats-wid" style="background-color:#556ee6;">-->
<!--    <div class="card-body">-->
<!--    <div class="d-flex">-->
<!--    <div class="flex-grow-1">-->
<!--    <p class="  fw-medium" style="color:#fff;" >Order Qty</p>-->
<!--<h4 class="mb-0" style="color:#fff;" >  </h4>-->
<!--    </div>-->
<!--    <div class="flex-shrink-0 align-self-center ">-->
<!--    <div class="avatar-sm rounded-circle bg-primary  ">-->
<!--    <span class="avatar-title  " style="background-color:#556ee6;" >-->
<!--   <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
<!--    </span>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    <div class="col-md-3">-->
<!--    <div class="card mini-stats-wid" style="background-color:#f79733;">-->
<!--    <div class="card-body">-->
<!--    <div class="d-flex">-->
<!--    <div class="flex-grow-1">-->
<!--    <p class="  fw-medium" style="color:#fff;">Order Value</p>-->
<!--<h4 class="mb-0" style="color:#fff;">  </h4>-->
<!--    </div>-->
<!--   <div class="flex-shrink-0 align-self-center">-->
<!--    <div class="avatar-sm rounded-circle bg-primary  " >-->
<!--    <span class="avatar-title  " style="background-color:#f79733;">-->
<!--    <i class="bx bx-archive-in font-size-24"></i>-->
<!--    </span>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>  -->

@if(session()->has('message'))
<div class="col-md-6">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('messagedelete'))
<div class="col-md-6">
   <div class="alert alert-danger">
      {{ session()->get('messagedelete') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
<div class="row d-flex align-items-center">
    <form action="/ob_pending_list" method="GET" enctype="multipart/form-data" class="d-flex">
        <div class="mb-3 me-2">
            <label for="fromDate" class="form-label">From date</label>
            <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ (request('fromDate')) ? request('fromDate') : date('Y-m-d',strtotime('-30 days')) }}" required> 
        </div> 
        <div class="mb-3 me-2">
            <label for="toDate" class="form-label">To Date</label>
            <input type="date" name="toDate" class="form-control" id="toDate" value="{{ (request('toDate')) ? request('toDate') : date('Y-m-d') }}" required>
        </div> 

        <div class="mb-3 me-2 mt-4">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>

        <div class="mb-3 me-2 mt-4">
            <a href="/ob_pending_list" class="btn btn-danger">Cancel</a>
        </div>
    </form>

    <!-- Filter button outside the form, inside the same row -->
    <div class="mb-3 ">
        <button id="filterButton" class="btn btn-success">Filter Rows</button>
    </div>
</div>




             
             
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>SrNo</th>
                     <th>Cutting PO</th>
                      <th>PO Date</th>
                     <th>Unit</th>
                     <th>Sales Order No</th>
                     <th>Buyer Name</th>
                      <th>Brand</th>  
                     <th>Main Style</th>
                     <th>Style No.</th>  
                     <th>ERP SAM</th>
                    <th>IE SAM</th>  
                      <th>IE Style No.</th> 
                  </tr>
               </thead>
               <tbody>
                  @foreach($VendorPurchaseOrderList as $row) 

                  <tr>
                     <td > {{ substr($row->vpo_code,4,15)  }} </td>
                     <td > {{ $row->vpo_code }} </td>
                      <td nowrap> {{ $row->vpo_date  }} </td>
                     <td> {{ $row->vendorName  }} </td>
                     <td> {{ $row->sales_order_no  }} </td>
                     <td> {{ $row->Ac_name  }} </td> 
                     <td> {{ $row->brand_name  }} </td> 
                     <td> {{ $row->styleERP  }} </td>
                      <td> {{ $row->style_no  }} </td> 
                     
                     <td>{{ $row->sam }}  </td>
                    <td>{{ $row->opSam }}  </td>
                      <td class="IEST"> {{ $row->styleOP  }} </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript"> 
   function closeOrder(row)
     {
         $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });   
          var vpo_code = $(row).attr('vpo_code');
          Swal.fire({
            title: 'Are you sure?',
            text: "This order will close",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
          }).then((result) => {
            if (result.isConfirmed) 
            {
                 $.ajax({
                 type: "POST", 
                 url: "{{ route('PurchaseOrderClose') }}",
                 data:{'vpo_code':vpo_code},
                 success: function(data)
                 {
                     Swal.fire(
                        'Closed!',
                        'Your Order has been closed.',
                        'success'
                      )
                  }
                  }); 
            }
          })
     }
   $(document).on('click','#DeleteRecord',function(e) 
   {
   
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
    
      if (confirm("Are you sure you want to Delete this Record?") == true) 
      {
          $.ajax({
               url: Route,
               type: "DELETE",
                data: {
                "id": id,
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
   
   $(document).ready(function() {
  // Bind click event to the filter button
  $('#filterButton').click(function() {
    $('table tr').each(function() {
      var isRowEmpty = true;
      
      // Check if any td within the row is not blank
      $(this).find('td.IEST').each(function() {
        if ($(this).text().trim() !== '') {
          isRowEmpty = false;
        }
      });

      // Hide the row if it is not entirely empty
      if (!isRowEmpty) {
        $(this).hide();
      } else {
        $(this).show();
      }
    });
  });
});

</script>  
@endsection