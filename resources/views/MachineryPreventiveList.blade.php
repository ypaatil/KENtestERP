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
            <h4 class="mb-sm-0 font-size-18">Machinery Preventive List</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                    <li class="breadcrumb-item active">Machinery Preventive List</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
    <div class="col-md-6">
        <a href="{{ Route('MachineryPreventive.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New
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
                            <th>Preventive Date</th>
                            <th>Preventive Name</th>
                            <th>Preventive Duration</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $no= 1;
                        @endphp
                        @foreach($MachineryPreventives as $row)
                        @php
                        if($row->status == 1)
                        {
                        $status = '<button status=1 class="btn-sm btn-warning">On Progress</button>';
                        }
                        else if($row->status == 2)
                        {
                        $status = '<button status=2 class="btn-sm btn-danger">On Hold</button>';
                        }
                        else if($row->status == 3)
                        {
                        $status = '<button status=3 class="btn-sm btn-success">Completed</button>';
                        }
                        else if($row->status == 0)
                        {
                        $status = '<button status=0 class="btn-sm btn-info">Pending</button>';
                        }
                        else
                        {
                        $status = "";
                        }
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->preDate }}</td>
                            <td>{{ $row->preventive_name }}</td>
                            <td>{{ $row->preDuration }}</td>
                            <td>{!! html_entity_decode($status) !!} </td>
                            @if($chekform->edit_access==1)
                            <td>
                                <a class="btn btn-outline-secondary btn-sm edit"
                                    href="{{route('MachineryPreventive.edit', $row->preId)}}" title="Edit">
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
                                    data-token="{{ csrf_token() }}" data-id="{{ $row->preId }}"
                                    data-route="{{route('MachineryPreventive.destroy', $row->preId )}}" title="Delete">
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