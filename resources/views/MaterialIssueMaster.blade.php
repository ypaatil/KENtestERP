@extends('layouts.master') 
@section('content')  
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Material Issue Status Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Material Issue Status Master</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title --> 
<div class="row" style="justify-content: center;">
    @if(Session::get('user_type') == 1 || Session::get('user_type') == 3 || Session::get('user_type') == 10) 
    <div class="col-md-4 text-center" style="padding: 10px;background: #2ed78987;"><h2><b>Fabric</b></h2></div>
    @endif
    @if(Session::get('user_type') == 1 || Session::get('user_type') == 3 || Session::get('user_type') == 11) 
    <div class="col-md-4 text-center" style="padding: 10px;background: #2e36d787;color: #fff;"><h2><b>Sewing Trims</b></h2></div>
    <div class="col-md-4 text-center" style="padding: 10px;background: #c2d72e87;"><h2><b>Packing Trims</b></h2></div>
    @endif
</div>
<div class="row" style="justify-content: center;">   
@if(Session::get('user_type') == 1 || Session::get('user_type') == 3 || Session::get('user_type') == 10) 
<div class="col-md-4">   
<div class="row">   
    <div class="col-md-4" style="padding: 10px;background: #2ed78987;">
      <a href="javascript:void(0);" onclick="GetTotalCount(3,1);">
      <div class="card mini-stats-wid" style="background-color:#FD5D70;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Pending</p>
                  <h4 class="mb-0" style="color:#fff;">{{$FPendingCount}}</h4>
               </div>
            </div>
         </div>
      </div>
      </a>
   </div>
   <div class="col-md-4" style="padding: 10px;background: #2ed78987;">
      <a href="javascript:void(0);" onclick="GetTotalCount(2,1);">
          <div class="card mini-stats-wid" style="background-color:#FF9C1F;">
             <div class="card-body">
                <div class="d-flex">
                   <div class="flex-grow-1">
                      <p class="  fw-medium" style="color:#fff;" >Partial</p>
                      <h4 class="mb-0" style="color:#fff;" >{{$FPartial_count}}</h4>
                   </div>
                </div>
             </div>
          </div>
      </a>
   </div>
   <div class="col-md-4" style="padding: 10px;background: #2ed78987;">
      <a href="javascript:void(0);" onclick="GetTotalCount(1,1);">
          <div class="card mini-stats-wid" style="background-color:#21AD75;">
             <div class="card-body">
                <div class="d-flex">
                   <div class="flex-grow-1">
                      <p class="  fw-medium" style="color:#fff;">Completed</p>
                      <h4 class="mb-0" style="color:#fff;">{{$Fcompleted_count}}</h4>
                   </div>
                </div>
             </div>
          </div>
      </a>
   </div>
 </div>
 </div>
@endif
@if(Session::get('user_type') == 1 || Session::get('user_type') == 3 || Session::get('user_type') == 11) 
<div class="col-md-4">   
<div class="row">   
   <div class="col-md-4" style="padding: 10px;background: #2e36d787;">
      <a href="javascript:void(0);" onclick="GetTotalCount(3,0);">
      <div class="card mini-stats-wid" style="background-color:#FD5D70;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Pending</p>
                  <h4 class="mb-0" style="color:#fff;">{{$SPending_count}}</h4>
               </div>
            </div>
         </div>
      </div>
      </a>
   </div>
   <div class="col-md-4" style="padding: 10px;background: #2e36d787;">
      <a href="javascript:void(0);" onclick="GetTotalCount(2,0);">
          <div class="card mini-stats-wid" style="background-color:#FF9C1F;">
             <div class="card-body">
                <div class="d-flex">
                   <div class="flex-grow-1">
                      <p class="  fw-medium" style="color:#fff;" >Partial</p>
                      <h4 class="mb-0" style="color:#fff;" >{{$SPartial_count}}</h4>
                   </div>
                </div>
             </div>
          </div>
      </a>
   </div>
   <div class="col-md-4" style="padding: 10px;background: #2e36d787;">
      <a href="javascript:void(0);" onclick="GetTotalCount(1,0);">
          <div class="card mini-stats-wid" style="background-color:#21AD75;">
             <div class="card-body">
                <div class="d-flex">
                   <div class="flex-grow-1">
                      <p class="  fw-medium" style="color:#fff;">Completed</p>
                      <h4 class="mb-0" style="color:#fff;">{{$SCompleted_count}}</h4>
                   </div>
                </div>
             </div>
          </div>
      </a>
   </div>
 </div>
 </div>
@endif
@if(Session::get('user_type') == 1 || Session::get('user_type') == 3 || Session::get('user_type') == 11) 
<div class="col-md-4">   
<div class="row"> 
   <div class="col-md-4" style="padding: 10px;background: #c2d72e87;">
      <a href="javascript:void(0);" onclick="GetTotalCount(3,3);">
      <div class="card mini-stats-wid" style="background-color:#FD5D70;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Pending</p>
                  <h4 class="mb-0" style="color:#fff;">{{$PPending_count}}</h4>
               </div>
            </div>
         </div>
      </div>
      </a>
   </div>
   <div class="col-md-4" style="padding: 10px;background: #c2d72e87;">
      <a href="javascript:void(0);" onclick="GetTotalCount(2,3);">
          <div class="card mini-stats-wid" style="background-color:#FF9C1F;">
             <div class="card-body">
                <div class="d-flex">
                   <div class="flex-grow-1">
                      <p class="  fw-medium" style="color:#fff;" >Partial</p>
                      <h4 class="mb-0" style="color:#fff;" >{{$PPartial_count}}</h4>
                   </div>
                </div>
             </div>
          </div>
      </a>
   </div>
   <div class="col-md-4" style="padding: 10px;background: #c2d72e87;">
      <a href="javascript:void(0);" onclick="GetTotalCount(1,3);">
          <div class="card mini-stats-wid" style="background-color:#21AD75;">
             <div class="card-body">
                <div class="d-flex">
                   <div class="flex-grow-1">
                      <p class="  fw-medium" style="color:#fff;">Completed</p>
                      <h4 class="mb-0" style="color:#fff;">{{$PCompleted_count}}</h4>
                   </div>
                </div>
             </div>
          </div>
      </a>
   </div>
</div>
</div>
@endif
</div>
<div class="row">
<div class="col-xl-12">
   <div class="card">
      <div class="card-body"> 
         @if ($errors->any())
         <div class="col-md-6">
            <div class="alert alert-danger">
               <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
               </ul>
            </div>
         </div>
         @endif
          <div id="overlay"></div>
         <form action="{{route('MaterialIssue.store')}}" method="POST"> 
            @csrf 
            <div class="row"> 
                  <table id="opertionTbl" class="table table-bordered dt-responsive nowrap w-100">
                   <thead>
                       <tr>
                           <th>Sr No.</th> 
                           <th>Date</th>
                           <th>Order No.</th> 
                           <th>Material Type</th> 
                           <th>Process No.</th> 
                           <th>Vendor Name</th> 
                           <th>Order Qty</th> 
                           <th>Material Issue Status</th> 
                           <th>Remark</th> 
                           @if(Session::get('user_type') != 3)<th>Action</th>@endif
                       </tr>
                   </thead>
                   <tbody>
                       @php
                            $srno = 1;
                       @endphp
                       @foreach($VendorOrderList as  $row)
                       @php
                            if($row->process_id == 1)
                            {
                                $material_type_name = "Fabric";
                            }
                            else if($row->process_id == 3)
                            { 
                                $material_type_name = "Packing - Trims";
                            }
                            else  
                            {
                                $material_type_name = 'Sewing - Trims';
                            }
                             
                       @endphp
                       @if($row->process_id == $packing_type && $row->issue_status_id == $status_id  && $row->job_status_id == 1)
                       <tr>
                            <td>{{$srno++}}</td> 
                            <td> 
                                 {{$row->process_date}}
                            </td>
                            <td> 
                                {{$row->sales_order_no}}  
                            </td>
                            <td> 
                                 {{$material_type_name}}
                            </td>
                            <td> 
                                {{$row->process_no}}
                            </td>  
                            <td> 
                                {{$row->vendorName}}
                            </td> 
                            <td> 
                                 {{$row->final_bom_qty}}
                            </td> 
                            <td> 
                                <span class="display-value">{{  $row->issue_status_name ? $row->issue_status_name : 'Pending' }}</span>
                                <select name="issue_status_id" class="form-select d-none">
                                    <option value="0">--Select--</option>
                                    @foreach($issueStatusList as $list)
                                    <option value="{{$list->issue_status_id}}" {{ $list->issue_status_id == $row->issue_status_id ? 'selected="selected"' : '' }}>{{$list->issue_status_name}}</option>
                                    @endforeach
                                </select>
                            </td>  
                            <td> 
                                <span class="display-value">{{ $row->remark }}</span>
                                 <input type="text" name="remark" class="form-control d-none" value="{{ $row->remark}}" >   
                            </td> 
                            @if(Session::get('user_type') != 3)
                            <td>   
                                <a class="btn btn-outline-success edit" title="Edit" vendorId="{{$row->vendorId}}" 
                                        process_date="{{$row->process_date}}" sales_order_no="{{$row->sales_order_no}}" 
                                        material_type_name="{{$material_type_name}}" process_no="{{$row->process_no}}"
                                        order_qty="{{$row->final_bom_qty}}" onclick="SaveMaterialIssue(this);">
                                    <i class="fas fa-edit" title="Edit"></i>
                                </a>
                            </td>
                            @endif
                       </tr>  
                       @endif
                       @endforeach
                   </tbody>
               </table>
            </div> 
         </form>
         </div>
         <!-- end card body -->
      </div>
      <!-- end card -->
   </div>
   <!-- end col --> 
</div>

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- end row -->
<script>
   function SaveMaterialIssue(row)
   { 
        var $row = $(row).closest('tr');
        var vendorId = $(row).attr("vendorId");
        var process_date = $(row).attr("process_date");
        var sales_order_no = $(row).attr("sales_order_no");
        var material_type_name = $(row).attr("material_type_name");
        var process_no = $(row).attr("process_no");
        var order_qty =  $(row).attr("order_qty");
        var issue_status_id = $row.find('select[name="issue_status_id"]').val();
        var remark = $row.find('input[name="remark"]').val();
        
        if ($(row).find('i').hasClass('fa-edit')) {
            // Switch to edit mode
            $row.find('select[name="issue_status_id"]').removeClass('d-none');
            $row.find('input[name="remark"]').removeClass('d-none');
            $row.find('.display-value').addClass('d-none');
            $(row).find('i').toggleClass('fa-edit fa-save');
            $(row).attr('title', 'Save');
        } else {
            // Save the changes
            $.ajax({
               dataType: "json",
               url: "{{ route('SaveMaterialIssue') }}",
               data:{
                   'vendorId':vendorId,
                   'process_date':process_date,
                   'sales_order_no':sales_order_no,
                   'material_type_name':material_type_name,
                   'process_no':process_no,
                   'order_qty':order_qty,
                   'issue_status_id':issue_status_id,
                   'remark':remark
               },
               success: function(data)
               {
                   toastr.success('Data Stored Successfully!');
                   $row.find('select[name="issue_status_id"]').addClass('d-none');
                   $row.find('input[name="remark"]').addClass('d-none');
                   $row.find('.display-value').removeClass('d-none');
                   $row.find('.display-value').eq(0).text($row.find('select[name="issue_status_id"] option:selected').text());
                   $row.find('.display-value').eq(1).text(remark);
                   $(row).find('i').toggleClass('fa-save fa-edit');
                   $(row).attr('title', 'Edit');
               },
               error: function(xhr, status, error){
                   toastr.error('An error occurred while storing data.');
               }
            }); 
        }
   }
   
   function GetTotalCount(status_id,packing_type)
   {
       window.location.href = 'https://kenerp.com/MaterialIssue?status_id='+status_id+'&packing_type='+packing_type; 
   }
   
</script>
@endsection
