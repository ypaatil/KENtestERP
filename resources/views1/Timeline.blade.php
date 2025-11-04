      
@extends('layouts.master') 

@section('content')   

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Timeline</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                    <li class="breadcrumb-item active">Timeline</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                  <thead>
                    <tr>
                        <th rowspan="2" align="center" valign="top">Sales Order No</th>
                        <th rowspan="2" align="center" valign="top">Buyer Name</th>
                        <th rowspan="2" align="center" valign="top">Order Recieved Date</th>
                        <th rowspan="2" align="center" valign="top">Style Name</th>
                        @foreach($details as $row1)
                        <th colspan="2">{{$row1-> act_name}}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($details as $row3)
                        <th>Plan</th>
                        <th>Act</th>
                        @endforeach

                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td> {{ $row->tr_code  }} </td>
                            <td nowrap> {{ $row-> ac_name }} </td>
                            <td nowrap> {{ $row-> order_received_date }} </td>
                            <td> {{ $row->fg_name  }} </td>
                            @php
                                 //DB::enableQueryLog();
                                    $detailact = DB::select("Select target_date, actual_date from t_and_a_detail left join t_and_a_master on t_and_a_master.tr_code = t_and_a_detail.tr_code where t_and_a_master.tr_code= '".$row->tr_code."' ");
                                 //dd(DB::getQueryLog());
                            @endphp
                            @foreach($detailact as $row2)
                                @php
                                if(!is_null($row2->actual_date))
                                {
                                    if($row2->target_date < $row2->actual_date)
                                    {
                                        $style = "style=background:#b23a48;"; 
                                        $style .="color:#fff";
                                    }
                                    else
                                    {
                                        $style  = "style=background:#8ac926;";
                                        $style .= "color:#fff";
                                    }
                                }
                                else
                                {
                                    if($row2->target_date < date('Y-m-d'))
                                    {
                                        $style = "style=background:#b23a48;"; 
                                        $style .="color:#fff";
                                    }
                                    else
                                    {
                                        $style  = "style=background:#8ac926;";
                                        $style .= "color:#fff";
                                    }
                                
                                
                                }
                                
                                
                                @endphp
                                <td {{$style}} nowrap> {{ date('d-m-Y', strtotime($row2->target_date))  }} </td>
                                <td {{$style}} nowrap> @if(!is_null($row2->actual_date)) {{ date('d-m-Y', strtotime($row2->actual_date))   }}   @endif</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            </div>
            
        </div>
    </div>
</div> <!-- end col -->
</div> <!-- end row -->        

@endsection