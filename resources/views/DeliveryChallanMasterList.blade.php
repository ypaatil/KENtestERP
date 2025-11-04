      
@extends('layouts.master') 

@section('content')   

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Delivery Challan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Delivery Challan</a></li>
                    <li class="breadcrumb-item active">Delivery Challan List</li>
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


@if($chekform->write_access==1)
<div class="row">
    <div class="col-md-6">
        <a href="{{ Route('DeliveryChallan.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
    </div>
</div>
@endif



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <table id="tbl" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Delivery Challan Issue No</th>
                            <th>Department Name</th>
                            <th>Sales Order No</th>
                            <th>Returnable/Non-Returnable</th>
                            <th>Return/Delivery</th>
                            <th>Issue Date</th>
                            <th>Return Date</th>
                            <th>Name Of Vendor/Buyer/Other</th>
                            <th>To Location</th>
                            <th>Total Quantity</th>
                            <th>Net Amount</th>
                            <th>Narration</th>
                            <th>Username</th>
                            <th>Updated Date</th>
                            <th>Edit</th>
                            <th>Print</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    
                    <tbody>

                        @foreach($DeliveryChallanList as $DeliveryChallan)    
                        <tr>
                            <td>{{ $DeliveryChallan->issue_no }}</td>
                            <td>{{ $DeliveryChallan->dept_name }}</td>
                            <td>{{ $DeliveryChallan->sales_order_no }}</td>
                            @if($DeliveryChallan->dc_case_id == 1)
                            <td>Returnable</td>
                            @else
                            <td>Non-Returnable</td>
                            @endif
 
                            @if($DeliveryChallan->issue_case_id == 1)
                            <td>Delivery</td>
                            @else
                            <td>Return</td>
                            @endif
                            <td>{{ date('d-m-Y',strtotime($DeliveryChallan->issue_date)) }}</td>
                            @if($DeliveryChallan->return_date !='')
                            <td>{{ date('d-m-Y',strtotime($DeliveryChallan->return_date)) }}</td>
                            @else
                            <td>-</td>
                            @endif

                            @if($DeliveryChallan->reciever_type == 1)
                            <td>{{ $DeliveryChallan->otherBuyerorVendor ? : '' }}</td>
                            @elseif($DeliveryChallan->reciever_type == 2)
                            <td>{{ $DeliveryChallan->ac_name}}</td>
                            @elseif($DeliveryChallan->reciever_type == 3)
                            <td>{{ $DeliveryChallan->ac_name}}</td>
                            @endif
                            <td>{{ $DeliveryChallan->to_location }}</td>
                            <td>{{ $DeliveryChallan->total_qty }}</td>
                            <td>{{ $DeliveryChallan->NetAmount }}</td>
                            <td>{{ $DeliveryChallan->narration }}</td>
                            <td>{{ $DeliveryChallan->username }}</td> 
                            <td>
                                {{ date("d-m-Y", strtotime($DeliveryChallan->updated_at)) }}
                            </td>
                            @if($chekform->edit_access==1 && $DeliveryChallan->username == Session::get('username') || Session::get('user_type') == 1  )
                            <td>
                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('DeliveryChallan.edit', $DeliveryChallan->issue_no)}}" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                            @else
                            <td>
                                <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                                    <i class="fas fa-lock"></i>
                                </a>
                            </td>
                            @endif 
                            <td> 
                                <a class="btn btn-outline-secondary btn-sm print" href="DeliveryChallanPrint/{{ $DeliveryChallan->issue_no }}" title="Print">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td> 
                           @if($chekform->delete_access==1 && $DeliveryChallan->username == Session::get('username') || Session::get('user_type') == 1  ) 
                            <td>
                                <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $DeliveryChallan->issue_no }}"  data-route="{{route('DeliveryChallan.destroy', $DeliveryChallan->issue_no )}}" title="Delete">
                                <i class="fas fa-trash"></i>
                                </button>   
                            </td>              
                            @else
                            <td>                    
                                <button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                                <i class="fas fa-lock"></i>
                                </button>
                            </td>
                            @endif

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

 $(document).ready(function() 
 { 
    $("#tbl").DataTable({
        dom: 'Bfrtip',
        "searchable": false,
        "aaSorting": false,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    }); 
     
    var sortedDir = $('#tbl').dataTable().fnSettings().aaSorting;
 });

 $(document).on('click','.delete',function(e) 
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
             
             success: function(data){
    
             //  console.log(data);
             location.reload();
    
             }
        });
    }

 });
</script> 
@endsection