
@extends('layouts.master') 

@section('content')   

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">KDPL Wise Set Percentage</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                            <li class="breadcrumb-item active">KDPL Wise Set Percentage List</li>
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

@if($chekform->write_access==1)

<div class="row">
<div class="col-lg-6">
<a href="{{ Route('KDPLWiseSetPercentage.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New</button></a> &nbsp; &nbsp; &nbsp;
 
</div>
</div>
@endif
</br>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table  data-page-length='25' data-ordering="false" id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                            <tr>
                                <th>Sr No.</th>
                                <th>Sales Order No.</th>
                                <th>Left Over Fabric Value(%)</th>
                                <th>Left Over Trims Value(%)</th>
                                <th>Left Pcs Value(%)</th>
                                <th>Rejection Pcs Value(%)</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                                @php
                                    $srno = 1;
                                @endphp
                                @foreach($KDPLWiseList as $row)    
                                <tr>
                                    <td class="text-center">{{ $srno++ }}</td>
                                    <td class="text-center">{{ $row->sales_order_no }}</td>
                                    <td class="text-center">{{ $row->leftover_fabric_value }}</td>
                                    <td class="text-center">{{ $row->leftover_trims_value }}</td>
                                    <td class="text-center">{{ $row->left_pcs_value }}</td>
                                    <td class="text-center">{{ $row->rejection_pcs_value }}</td> 
                                    <td class="text-center">
                                        <button  class="btn  btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->kwspId }}"  data-route="{{route('KDPLWiseSetPercentage.destroy',  $row->kwspId)}}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
        
                    //alert(data);
                 location.reload();
        
                 }
            });
        }
    
     });
    </script>




        @endsection