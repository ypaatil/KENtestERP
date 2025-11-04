@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sample QC Department List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sample QC Department List</li>
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
      <a href="{{ Route('SampleQcDept.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a>
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
                     <th>Sr.No.</th>
                     <th>SIN Code</th>
                     <th>QC Date</th>
                     <th>Material Available Date</th>
                     <th>Actual ETD (Complete Date)</th>
                     <th>TAT (Days)</th>
                     <th>Buyer Name</th>
                     <th>Buyer Brand</th>
                     <th>Sample Type</th>
                     <th>Main Style</th>
                     <th>Sub Style</th>  
                     <th>Stitching Qty</th> 
                     <th>User Name</th> 
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1;
                  @endphp
                  @foreach($data as $row)    
                  @php
                        
                        $checkData = DB::SELECT("SELECT count(*) as count FROM sample_customer_feedback_master where sample_qc_dept_id=".$row->sample_qc_dept_id);
                        
                        $count = isset($checkData[0]->count) ? $checkData[0]->count : 0;
                  @endphp
                  <tr>
                     <td>{{ $srno++ }}</td>
                     <td>{{ $row->sample_indent_code }}</td>
                     <td>{{ date("d-m-Y", strtotime($row->sample_qc_dept_date)) }}</td>
                     <td>{{ date("d-m-Y", strtotime($row->material_avaliable_date)) }}</td>
                     <td>{{ date("d-m-Y", strtotime($row->actual_etd)) }}</td>
                     <td>
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
                        {{ $dateDifference }}
                     </td>
                     <td>{{ $row->ac_short_name }}</td>
                     <td>{{ $row->brand_name }}</td>
                     <td>{{ $row->sample_type_name }}</td>
                     <td>{{ $row->mainstyle_name }}</td>
                     <td>{{ $row->substyle_name }}</td> 
                     <td>{{ $row->total_qty }}</td>
                     <td>{{ $row->username }}</td>
                     @if( $row->userId == Session::get('userId') && $chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('SampleQcDept.edit', $row->sample_qc_dept_id )}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td>
                     @else
                      <td>
                            <a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>
                    </td>
                     @endif
                     @if($count == 0 && $chekform->delete_access==1)
                     <td>
                        <form action="{{route('SampleQcDept.destroy', $row->sample_qc_dept_id )}}" method="POST">
                           <input type="hidden" name="_method" value="DELETE">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           <button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                           <i class="fas fa-trash"></i>
                           </button>
                        </form>
                     </td> 
                     @else
                      <td>
                            <a class="btn btn-primary btn-icon btn-sm">
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
   </div>
   <!-- end col -->
</div>
<!-- end row -->
@endsection