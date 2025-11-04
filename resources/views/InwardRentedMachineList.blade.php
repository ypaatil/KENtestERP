@extends('layouts.master')

@section('content')
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Inward Rented Machine List</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                    <li class="breadcrumb-item active">Inward Rented Machine List</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
    <div class="col-md-6">
        <a href="{{ Route('InwardRentedMachine.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New
                Record</button></a>
    </div>
</div>
@endif




<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Entry Date</th>
                            <th>Inward Type</th>
                            <th>Vendor Name</th>
                            <th>Purchase/Rented Date</th>
                            <th>Machine Photo</th>
                            <th>Total Amount</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $no= 1;
                        @endphp
                        @foreach($InwardRentedMachines as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->pureDate }}</td>
                            <td>{{ $row->inwardtypeName }}</td>
                            <td>{{ $row->ac_name }}</td>
                            <td>{{ $row->rentedDate }}</td>
                            @if($row->machineimage == '')
                            <td><img src="./uploads/noimgup.png"
                                    style="max-width:100px!important;height:80px!important"></td>
                            @else
                            <td><img src="./uploads/machineImage/{{$row->machineimage}}"
                                    style="max-width:100px!important;height:80px!important"></td>
                            @endif



                            <td>{{ $row->totalAmount }}</td>
                            @if($chekform->edit_access==1)
                            <td>
                                <a class="btn btn-outline-secondary btn-sm edit"
                                    href="{{route('InwardRentedMachine.edit', $row->purId)}}" title="Edit">
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
                                <button class="btn   btn-sm delete" data-placement="top" id="DeleteRecord"
                                    data-token="{{ csrf_token() }}" data-id="{{ $row->purId }}"
                                    data-route="{{route('InwardRentedMachine.destroy', $row->purId )}}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>


                            @else
                            <td>
                                <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                                    <i class="fas fa-lock"></i>
                                </a>
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
$(document).on('click', '#DeleteRecord', function(e) {

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

            success: function(data) {

                //alert(data);
                location.reload();

            }
        });
    }

});
</script>
@endsection