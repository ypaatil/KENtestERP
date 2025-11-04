      
   @extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
 
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">BOM</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">BOM</li>
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
                              
                        
                        
                        
                        @if($chekform->write_access==1)    
                        <div class="row">
                        <div class="col-md-12">
                        <a href="{{ Route('BOM.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
                        
                        </div>
                        </div>
                        @endif
                        
                        
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
        
                                    <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 footable_2">
                                          <thead>
                                            <tr style="text-align:center;">
                                                <th>SRNO</th>
                                                <th>Sales Order No</th>
                                                <th>Entry Date</th>
                                                 
                                                <th>Buyer Name</th>
                                                <th>Season</th>  
                                                
                                                <th>Fabric Cost</th> 
                                                <th>Sewing Trims Cost</th>
                                                <th>Packing Trims Cost</th>
                                                <th>Total Cost</th>
                                                <th>User</th>     
                                                <th>BOM</th>
                                                <th>Budget</th>
                                                <th>Edit</th>     
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>
 
                                            @foreach($BOMList as $row)    
                                            <tr>
                                                <td > {{ substr($row->sales_order_no,5,15)  }} </td>
                                                <td style="text-align:center; white-space:nowrap;"> {{ $row->sales_order_no  }} </td>
                                                <td style="text-align:center; white-space:nowrap;">{{ date('d-m-Y', strtotime($row->bom_date)) }}</td>
                                                 
                                                <td style="text-align:center;"> {{ $row->Ac_name  }} </td>
                                                <td style="text-align:center;"> {{ $row->season_name  }} </td>
                                                
                                                <td style="text-align:right;"> {{ $row->fabric_value  }} </td>
                                                <td style="text-align:right;"> {{ $row->sewing_trims_value  }} </td> 
                                                <td style="text-align:right;"> {{ $row->packing_trims_value  }} </td>
                                                <td style="text-align:right;"> {{ $row->total_cost_value  }} </td>
                                                <td style="text-align:center;"> {{ $row->username  }} </td>
                                                @if($chekform->edit_access==1)
                                                 <td>
                                                <a class="btn btn-outline-secondary btn-sm print" target="_blank" href="BOMPrint/{{ $row->bom_code }}" title="print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                </td>
                                                
                                                   <td>
                                                <a class="btn btn-outline-secondary btn-sm print" target="_blank" href="BUDGETPrint/{{ $row->bom_code }}" title="print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                </td>
                                                
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('BOM.edit', $row->bom_code)}}" title="Edit">
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
                                                @if($chekform->delete_access==1) 
                                                <td>
                                                
        <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->bom_code }}"  data-route="{{route('BOM.destroy', $row->bom_code )}}" title="Delete">
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