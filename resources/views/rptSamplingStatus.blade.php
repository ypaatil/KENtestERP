@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<style>
    .hide
    {
        display:none;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sampling Status Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Sampling Status Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body">
          <form action="/rptSamplingStatus" method="GET">
              <div class="row">  
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="from_date" class="form-label">Sample Request From Date</label>
                        <input type="date" class="form-control" name="from_date" id="from_date" value="{{ isset($from_date) ? $from_date : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="to_date" class="form-label">Sample Request To Date</label>
                        <input type="date" class="form-control" name="to_date" id="to_date" value="{{ isset($to_date) ? $to_date : date('Y-m-d')}}">
                     </div>
                   </div>
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer</label>
                        <select name="Ac_code" id="Ac_code" class="form-control select2">
                            <option value="0">--Select--</option>  
                             @foreach($Buyerlist as $row)
                                <option value="{{$row->ac_code}}"  {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }} >{{$row->ac_name}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div>  
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/rptSamplingStatus" class="btn btn-warning">Clear</a>
                  </div>
              </div>
          </form>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th nowrap>SR.NO.</th>
                        <th nowrap>INDENT NO.</th>
                        <th nowrap>REQUESTED DATE</th>
                        <th nowrap>BUYER NAME</th> 
                        <th nowrap>STYLE CATEGORY</th> 
                        <th nowrap>STYLE</th> 
                        <th nowrap>NO OF SAMPLES</th>
                        <th nowrap>DEPARTMENT</th>
                        <th>MERCHANT</th>
                        <th nowrap>SAMPLE TYPE</th>
                        <th nowrap>SAMPLE REQ. DATE</th>
                        <th nowrap>COMMITTED TO ETD</th>
                        <th nowrap>MATERIAL AVAILABLE DATE</th>
                        <th nowrap>ACTUAL ETD (COMPLETE DATE)</th>
                        <th nowrap>TAT (DAYS)</th>
                        <th nowrap>FABRIC STATUS</th>
                        <th nowrap>SEWING TRIMS STATUS</th>
                        <th nowrap>PACKING TRIMS STATUS</th>
                        <th nowrap>STATUS</th>
                        <th nowrap>REMARK</th>
                        <th nowrap>BUYER REMARK</th> 
                     </tr> 
                  </thead>
                  <tbody>
                     @php
                        $srno = 1;
                     @endphp 
                     @foreach($SampleDetailList as $row)    
                     @php 
                        
                     @endphp
                     <tr>
                        <td style="white-space:nowrap"> {{ $srno++  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->sample_indent_code  }} </td> 
                        <td style="white-space:nowrap"> @if($row->sample_indent_date!= ''){{ date("d-m-Y", strtotime($row->sample_indent_date)) }}@endif  </td> 
                        <td style="white-space:nowrap"> {{ $row->ac_short_name  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->mainstyle_name  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->substyle_name  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->no_of_sample  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->dept_type_name  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->username  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->sample_type_name  }} </td>  
                        <td style="white-space:nowrap"> @if($row->sample_required_date!= ''){{ date("d-m-Y", strtotime($row->sample_required_date))  }}@endif  </td> 
                        <td style="white-space:nowrap"> @if($row->delivery_date!= ''){{ date("d-m-Y", strtotime($row->delivery_date))  }} @endif </td>  
                        <td style="white-space:nowrap"> @if($row->material_avaliable_date!= ''){{ date("d-m-Y", strtotime($row->material_avaliable_date))  }} @endif </td>  
                        <td style="white-space:nowrap">  @if($row->actual_etd!= '') {{ date("d-m-Y", strtotime($row->actual_etd))  }} @endif </td>
                        <td style="white-space:nowrap"> 
                        @php
                            $materialAvailableDate = new DateTime($row->material_avaliable_date);
                            $actualEtd = new DateTime($row->actual_etd);
                             
                            $diff = $materialAvailableDate->diff($actualEtd);
                            $dateDifference = $diff->days;
                             
                            if ($diff->invert) {
                                $dateDifference = -$dateDifference + 1;
                            } else {
                                $dateDifference = $dateDifference + 1;
                            }
                        @endphp
                        {{$dateDifference}}
                        </td> 
                        <td style="white-space:nowrap"> {{ $row->fabric_material_received_status  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->sewing_trims_material_received_status  }} </td> 
                        <td style="white-space:nowrap"> {{ $row->packing_trims_material_received_status  }} </td> 
                        <td style="white-space:nowrap"> </td> 
                        <td style="white-space:nowrap"> </td> 
                        <td style="white-space:nowrap"> {{ $row->cust_comments  }} </td> 
                     </tr> 
                     @endforeach 
                  </tbody> 
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script> 
    
      
</script>
@endsection