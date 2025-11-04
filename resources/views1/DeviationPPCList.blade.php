@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Deviation-PPC Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Deviation-PPC Master</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('deviationPPCMaster') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr.No.</th>
                     <th>Vendor</th>
                     <th>Line No.</th>
                     <th>Mo. Of M/c</th>
                     <th>Efficiency%</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1;
                  @endphp
                  @foreach($deviation_ppc_master_list as $row)    
                  <tr>
                     <td>{{ $srno++ }}</td>
                     <td>{{ $row->ac_name }}</td>
                     <td>{{ $row->line_name }}</td>
                     <td>{{ $row->noOfMC }}</td>
                     <td>{{ $row->efficiency }}</td>
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('deviationPPCMasterEdit', $row->deviation_PPC_Master_Id )}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td>
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('deviationPPCMasterDelete', $row->deviation_PPC_Master_Id )}}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                     </td> 
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
<script type="text/javascript"> 
</script>     
@endsection