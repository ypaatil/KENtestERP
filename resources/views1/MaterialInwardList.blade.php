
@extends('layouts.master') 

@section('content')   

<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Material Inward</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
<li class="breadcrumb-item active">Material Inward</li>
</ol>
</div>

</div>
</div>
</div>
<!-- end page title -->

@if($chekform->write_access==1)
<div class="row">
<div class="col-md-6">
<a href="{{ Route('MaterialInward.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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

<table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
<thead>
<tr>

<th>Inward Code</th>
<th>Date</th>
<th>Buyer Name</th>
<th>Job Code</th> 
<th>Lot No</th>
<th>Transaction Type</th>
<th>Total Quantity</th> 
<th>User</th>
<th>Created At</th>
<th>Updated At</th> 
<th>Edit</th>     
<th>Delete</th>
</tr>
</thead>

<tbody>

@foreach($InwardList as $row)    
<tr>

<td>{{ $row->InCode }}</td>
<td>{{ $row->inward_date }}</td>
<td>{{ $row->ac_name }}</td>
<td>{{ $row->job_code }}</td>
<td>{{ $row->lot_no }}</td>
<td>{{ $row->transactionType }}</td>
<td>{{ $row->totalQty }}</td>
<td>{{ $row->username }}</td>
<td>{{ $row->created_at }}</td>
<td>{{ $row->updated_at }}</td>


 @if($chekform->edit_access==1)
<td>
<a class="btn btn-outline-secondary btn-sm edit" href="{{route('MaterialInward.edit', $row->InCode)}}" title="Edit">
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
<form action="{{route('MaterialInward.destroy', $row->InCode)}}" method="POST">
<input type="hidden" name="_method" value="DELETE">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
<i class="fas fa-trash"></i>
</button>
</form>
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
@endsection