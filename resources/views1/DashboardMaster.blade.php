@extends('layouts.master') 

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard Master</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Dashboard Master</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Dashboard</h4>
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

                @if(isset($dashboard_master1))
                <form action="{{ route('DashboardMaster.update',$dashboard_master1) }}" method="POST">
                    @method('put')

                    @csrf 
                    <div class="row">

                        <table style="width:100%">
                          <tr>
                            <th>Particular</th>
                            <th>Today Plan</th>
                            <th>Month To Date Plan</th>    
                            <th>Year To Date Plan</th>
                        </tr>
                        <tr>
                            <td>Booking Volume</td>
                            <td><input type="text" name="BK_VOL_TD_P" value="{{ $dashboard_master1->BK_VOL_TD_P}}"></td>
                            <td><input type="text" name="BK_VOL_M_TO_Dt_P" value="{{ $dashboard_master1->BK_VOL_M_TO_Dt_P}}"></td>
                            <td><input type="text" name="BK_VOL_Yr_TO_Dt_P" value="{{ $dashboard_master1->BK_VOL_Yr_TO_Dt_P}}"></td>
                        </tr>
                        <tr>
                            <td>Booking Value</td>
                            <td><input type="text" name="BK_VAL_TD_P" value="{{ $dashboard_master1->BK_VAL_TD_P}}"></td>
                            <td><input type="text" name="BK_VAL_M_TO_Dt_P" value="{{ $dashboard_master1->BK_VAL_M_TO_Dt_P}}"></td>
                            <td><input type="text" name="BK_VAL_Yr_TO_Dt_P" value="{{ $dashboard_master1->BK_VAL_Yr_TO_Dt_P}}"></td>
                        </tr>
                        <tr>
                            <td>Sales Volume</td>
                            <td><input type="text" name="SAL_VOL_TD_P" value="{{ $dashboard_master1->SAL_VOL_TD_P}}"></td>
                            <td><input type="text" name="SAL_VOL_M_TO_Dt_P" value="{{ $dashboard_master1->SAL_VOL_M_TO_Dt_P}}"></td>
                            <td><input type="text" name="SAL_VOL_Yr_TO_Dt_P" value="{{ $dashboard_master1->SAL_VOL_Yr_TO_Dt_P}}"></td>
                        </tr>
                        <tr>
                            <td>Sales Value</td>
                            <td><input type="text" name="SAL_VAL_TD_P" value="{{ $dashboard_master1->SAL_VAL_TD_P}}"></td>
                            <td><input type="text" name="SAL_VAL_M_TO_Dt_P" value="{{ $dashboard_master1->SAL_VAL_M_TO_Dt_P}}"></td>
                            <td><input type="text" name="SAL_VAL_Yr_TO_Dt_P" value="{{ $dashboard_master1->SAL_VAL_Yr_TO_Dt_P}}"></td>
                        </tr>
                        <tr>
                            <td>Booking SAH</td>
                            <td><input type="text" name="BOK_SAH_TD_P" value="{{ $dashboard_master1->BOK_SAH_TD_P}}"></td>
                            <td><input type="text" name="BOK_SAH_M_TO_Dt_P" value="{{ $dashboard_master1->BOK_SAH_M_TO_Dt_P}}"></td>
                            <td><input type="text" name="BOK_SAH_Y_TO_Dt_P" value="{{ $dashboard_master1->BOK_SAH_Y_TO_Dt_P}}"></td>
                        </tr>
                        <tr>
                            <td>Sales SAH</td>
                            <td><input type="text" name="SAL_SAH_TD_P" value="{{ $dashboard_master1->SAL_SAH_TD_P}}"></td>
                            <td><input type="text" name="SAL_SAH_M_TO_Dt_P" value="{{ $dashboard_master1->SAL_SAH_M_TO_Dt_P}}"></td>
                            <td><input type="text" name="SAL_SAH_Yr_TO_Dt_P" value="{{ $dashboard_master1->SAL_SAH_Yr_TO_Dt_P}}"></td>
                        </tr>
                    </table>




                    <div class="col-md-6">
                        <div class="mb-3">
                           <!--  <label  class="form-label">Dashboard</label>
                            <input type="text" name="BK_VOL_TD_P" class="form-control" id="formrow-email-input" value="{{ $dashboard_master1->BK_VOL_TD_P}}"> -->
                            <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                            <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $dashboard_master1->created_at }}">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                    </div>
                </div>
            </form>


            @else
            <form action="{{route('DashboardMaster.store')}}" method="POST">
                @csrf 
                <div class="row">



                    <table style="width:100%">
                      <tr>
                        <th>Particular</th>
                        <th>Today Plan</th>
                        <th>Month To Date Plan</th>    
                        <th>Year To Date Plan</th>
                    </tr>
                    <tr>
                        <td>Booking Volume</td>
                        <td><input type="text" name="BK_VOL_TD_P"></td>
                        <td><input type="text" name="BK_VOL_M_TO_Dt_P"></td>
                        <td><input type="text" name="BK_VOL_Yr_TO_Dt_P"></td>
                    </tr>
                    <tr>
                        <td>Booking Value</td>
                        <td><input type="text" name="BK_VAL_TD_P"></td>
                        <td><input type="text" name="BK_VAL_M_TO_Dt_P"></td>
                        <td><input type="text" name="BK_VAL_Yr_TO_Dt_P"></td>
                    </tr>
                    <tr>
                        <td>Sales Volume</td>
                        <td><input type="text" name="SAL_VOL_TD_P"></td>
                        <td><input type="text" name="SAL_VOL_M_TO_Dt_P"></td>
                        <td><input type="text" name="SAL_VOL_Yr_TO_Dt_P"></td>
                    </tr>
                    <tr>
                        <td>Sales Value</td>
                        <td><input type="text" name="SAL_VAL_TD_P"></td>
                        <td><input type="text" name="SAL_VAL_M_TO_Dt_P"></td>
                        <td><input type="text" name="SAL_VAL_Yr_TO_Dt_P"></td>
                    </tr>
                    <tr>
                        <td>Booking SAH</td>
                        <td><input type="text" name="BOK_SAH_TD_P"></td>
                        <td><input type="text" name="BOK_SAH_M_TO_Dt_P"></td>
                        <td><input type="text" name="BOK_SAH_Y_TO_Dt_P"></td>
                    </tr>
                    <tr>
                        <td>Sales SAH</td>
                        <td><input type="text" name="SAL_SAH_TD_P"></td>
                        <td><input type="text" name="SAL_SAH_M_TO_Dt_P"></td>
                        <td><input type="text" name="SAL_SAH_Yr_TO_Dt_P"></td>
                    </tr>
                </table>




                <div class="col-md-6">
                    <div class="mb-3">
                        <!-- <label for="formrow-email-input" class="form-label">Business Type</label> -->
                        <!-- <input type="text" name="Bt_name" class="form-control" id="formrow-email-input"> -->
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary w-md">Submit</button>
            </div>

        </form>



        @endif


    </div>
    <!-- end card body -->
</div>
<!-- end card -->
</div>
<!-- end col -->


<!-- end col -->
</div>
<!-- end row -->


<!-- end row -->


<!-- end row -->
@endsection