
@extends('layouts.master') 

@section('content')   

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                            <li class="breadcrumb-item active">Sale Transaction List</li>
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
    <p class="  fw-medium" style="color:#fff;">No. of Sale</p>
    <h4 class="mb-0" style="color:#fff;"> </h4>
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
    <p class="  fw-medium" style="color:#fff;" >Sale Total (Lacs)</p>
    <h4 class="mb-0" style="color:#fff;" ></h4>
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
    <p class="  fw-medium" style="color:#fff;">Sale Total Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;"> </h4>
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
    <p class="  fw-medium" style="color:#fff;">Sale Pending</p>
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
<a href="{{ Route('SaleTransaction.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Sale</button></a> &nbsp; &nbsp; &nbsp;
 
</div>
</div>
@endif
</br>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                            <tr>
                                 <th>SrNo</th>
                                <th>Invoice No</th>
                                <th>Invoice Date</th>
                                <th>Buyer</th>
                                <th>Carton Packing Nos</th>
                                <th>GST</th>  
                                <th>Total Qty</th>
                                <th>Gross Amount</th> 
                                <th>Freight Amount</th>
                                <th>GST Amount</th> 
                                <th>Net Amount</th> 
                                <th>Narration</th> 
                                <th>Username</th>
                                 <th>Action</th>
                              
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($SaleTransactionMasterList as $row)    
                            <tr>
                                 @php $number=intval(substr($row->sale_code,15,50)); @endphp
                                <th>{{$number }}</th>
                                <td>{{ $row->sale_code }}</td>
                                <td>{{ $row->sale_date }}</td>
                                <td>{{ $row->ac_name1 }}</td>
                                <td>{{ $row->carton_packing_nos }}</td>
                                <td>{{ $row->tax_type_name }}</td>
                                <td>{{ $row->total_qty }}</td>
                                <td>{{ $row->Gross_amount }}</td>
                                <td>{{ $row->freight_charges }}</td>
                                <td>{{ $row->Gst_amount }}</td>
                                <td>{{ $row->Net_amount }}</td>
                                <td>{{ $row->narration }}</td>
                                <td>{{ $row->username }}</td>
                               <td>
                                <a class="btn   btn-sm edit" href="PrintSaleTransaction/{{ $row->sale_code }}" title="Print">
                                             <i class="fas fa-print"></i>
                                </a>
                                 
                                @if($chekform->edit_access==1)
                                
                                <a class="btn   btn-sm edit" href="{{route('SaleTransaction.edit',   base64_encode($row->sale_code) )}}" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                
                                @else
                           
                               
                                            <a class="btn   btn-sm edit" href="" title="Edit">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                

                                @endif

                                @if($chekform->delete_access==1)
                                                                
                                        <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ base64_encode($row->sale_code) }}"  data-route="{{route('SaleTransaction.destroy', base64_encode($row->sale_code))}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        </button>
                                 
                                     @else
                                
                                                           
                                    <button class="btn   btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                                    <i class="fas fa-lock"></i>
                                    </button>
                               
                                
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