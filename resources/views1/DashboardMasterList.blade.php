      
@extends('layouts.master') 

@section('content')   

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Dashboard List</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
    <div class="col-md-6">
         @foreach($dashboard_master1 as $row)
        <a href="{{route('DashboardMaster.edit', $row->db_id)}}"><button type="buuton" class="btn btn-primary w-md">Edit Record</button></a>
        @endforeach
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
                            <th>Particular</th>
                            <th>Today Plan</th>
                            <th>Month To Date Plan</th>    
                            <th>Year To Date Plan</th>
                        </tr>
                    </thead>
                    
                    <tbody>

                        @foreach($dashboard_master1 as $row)    
                        <tr>
                            @if($chekform->edit_access==1)

                            <tr>
                            <td>Booking Volume</td>
                            <td>{{ $row->BK_VOL_TD_P}}</td>
                            <td>{{ $row->BK_VOL_M_TO_Dt_P}}</td>
                            <td>{{ $row->BK_VOL_Yr_TO_Dt_P}}</td>
                        </tr>
                        <tr>
                            <td>Booking Value</td>
                            <td>{{ $row->BK_VAL_TD_P}}</td>
                            <td>{{ $row->BK_VAL_M_TO_Dt_P}}</td>
                            <td>{{ $row->BK_VAL_Yr_TO_Dt_P}}</td>
                        </tr>
                        <tr>
                            <td>Sales Volume</td>
                            <td>{{ $row->SAL_VOL_TD_P}}</td>
                            <td>{{ $row->SAL_VOL_M_TO_Dt_P}}</td>
                            <td>{{ $row->SAL_VOL_Yr_TO_Dt_P}}</td>
                        </tr>
                        <tr>
                            <td>Sales Value</td>
                            <td>{{ $row->SAL_VAL_TD_P}}</td>
                            <td>{{ $row->SAL_VAL_M_TO_Dt_P}}</td>
                            <td>{{ $row->SAL_VAL_Yr_TO_Dt_P}}</td>
                        </tr>
                        <tr>
                            <td>Booking SAH</td>
                            <td>{{ $row->BOK_SAH_TD_P}}</td>
                            <td>{{ $row->BOK_SAH_M_TO_Dt_P}}</td>
                            <td>{{ $row->BOK_SAH_Y_TO_Dt_P}}</td>
                        </tr>
                        <tr>
                            <td>Sales SAH</td>
                            <td>{{ $row->SAL_SAH_TD_P}}</td>
                            <td>{{ $row->SAL_SAH_M_TO_Dt_P}}</td>
                            <td>{{ $row->SAL_SAH_Yr_TO_Dt_P}}</td>
                        </tr>

                            
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