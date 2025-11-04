
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

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                            <tr>
                                 <th>SrNo</th>
                                <th>PO No</th>
                                <th>PO Date</th>
                                <th>Supplier</th>
                                <th>GST</th>  
                                <th>Gross Amount</th> 
                                <th>GST Amount</th> 
                                <th>Net Amount</th> 
                                <th>Narration</th> 
                                <th>Approval  Status</th> 
                                <th>Username</th>
                                 <th>Action</th>
                              
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($data as $row)    
                            <tr>
                                 <th>{{ $row->sr_no }}</th>
                                <td>{{ $row->pur_code }}</td>
                                <td>{{ $row->pur_date }}</td>
                                <td>{{ $row->ac_name1 }}</td>
                                <td>{{ $row->tax_type_name }}</td>
                                <td>{{ $row->Gross_amount }}</td>
                                <td>{{ $row->Gst_amount }}</td>
                                <td>{{ $row->Net_amount }}</td>
                                <td>{{ $row->narration }}</td>
                                <td>Pending</td>
                                <td>{{ $row->username }}</td>


                            <td>
                                <a class="btn   btn-sm edit" href="print/{{ base64_encode($row->pur_code) }}" title="Print">
                                             <i class="fas fa-print"></i>
                                </a>
                                 
                                @if($chekform->edit_access==1)
                                
                                <a class="btn   btn-sm edit" href="{{route('PurchaseOrder.edit',$row->sr_no)}}" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                
                                @else
                           
                               
                                            <a class="btn   btn-sm edit" href="" title="Edit">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                

                                @endif

                                @if($chekform->delete_access==1)
                                                                
                                        <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ base64_encode($row->pur_code) }}"  data-route="{{route('PurchaseOrder.destroy', base64_encode($row->pur_code))}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        </button>
                                 
                                     @else
                                
                                                           
                                    <button class="btn   btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                                    <i class="fas fa-lock"></i>
                                    </button>
                               
                                
                                @endif
                                 
                                
                                <a class="btn   btn-sm edit" href="{{ Route('FabricInward.create',  ['po_code'=>$row->pur_code,'ac_code'=>$row->ac_code]) }}" title="GRN">
                                             <i class="far fa-sticky-note"></i>
                                </a>
                                
                                
                                 @if($row->bomtype==1)
                                
         <a class="btn   btn-sm edit" href="{{ Route('FabricInward.create',  ['po_code'=> base64_encode($row->pur_code),'ac_code'=>$row->ac_code]) }}" title="GRN">
                                 <i class="fa fa-plus"></i>       
                    </a>
                                
                                 @elseif($row->bomtype==2)
                                
                    <a class="btn   btn-sm edit"  href="{{ Route('TrimsInward.create',  ['po_code'=> base64_encode($row->pur_code),'ac_code'=>$row->ac_code]) }}" title="Trim">
                                            <i class="fa fa-plus"></i>
                          </a>
                                
                              @endif
                                
                                 </td>
                                  </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->


    <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 

 $(document).on('click','#DeleteRecord',function(e) {

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

            //alert(data);
         location.reload();

         }
});
}

 });
</script>




        @endsection