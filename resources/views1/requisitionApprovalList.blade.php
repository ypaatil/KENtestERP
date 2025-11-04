
@extends('layouts.master') 

@section('content')   

<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Requisition</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
<li class="breadcrumb-item active">Requisition</li>
</ol>
</div>

</div>
</div>
</div>
<!-- end page title -->

@if($chekform->write_access==1)
<div class="row">
<div class="col-md-6">
<a href="{{ Route('Requisition.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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

<th>Requisition No</th>
<th>Date</th>
<th>Issue To</th> 
<th>User</th>
<th>Created At</th>
<th>Updated At</th> 
</tr>
</thead>

<tbody>

@foreach($ReqList as $row)    
<tr>

<td>{{ $row->requisitionNo  }}</td>
<td>{{ $row->requisitionDate }}</td>
<td>{{ $row->issueTo }}</td>
<td>{{ $row->username }}</td>
<td>{{ $row->created_at }}</td>
<td>{{ $row->updated_at }}</td>

</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div> <!-- end col -->
</div> <!-- end row -->
@endsection