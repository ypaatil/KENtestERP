@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">DHU Master List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
               <li class="breadcrumb-item active">DHU Master List</li>
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
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('DHU.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 1, "desc" ]]' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th nowrap>Sr.No.</th>
                     <th nowrap>DHU Date</th>
                     <th nowrap>DHU Code</th>
                     <th nowrap>Work Order No</th>
                     <th nowrap>Work Order Status</th>
                     <th nowrap>Vendor Name</th>
                     <th nowrap>Sales Order No</th>
                     <th nowrap>Main Style Category</th>
                     <th nowrap>Style Name</th>
                     <th nowrap>Line No</th>
                     <th nowrap>Buyer Name</th>
                     <th class="text-center" nowrap>Total Defect Qty</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1; 
                  @endphp 
                  @foreach($dhuList as $row)
                  <tr>
                     <td>{{$srno++}}</td>
                     <td nowrap>{{$row->dhu_date}}</td>
                     <td nowrap>{{$row->dhu_code}}</td>
                     <td nowrap>{{$row->vw_code}}</td>
                     <td nowrap>{{$row->job_status_name}}</td>
                     <td nowrap>{{$row->vendor_name}}</td>
                     <td nowrap>{{$row->sales_order_no}}</td>
                     <td nowrap>{{$row->mainstyle_name}}</td>
                     <td nowrap>{{$row->fg_name}}</td>
                     <td nowrap>{{$row->line_name}}</td>
                     <td nowrap>{{$row->buyer_name}}</td> 
                     <td class="text-center">{{$row->total_defect_qty}}</td>
                     <td class="text-center" nowrap>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('DHU.edit', $row->dhu_code)}}" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <button class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->dhu_code }}"  data-route="{{route('DHU.destroy', $row->dhu_code )}}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                     </td>
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
<script type="text/javascript"> 

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
</script>   
@endsection