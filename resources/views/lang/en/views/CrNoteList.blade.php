
@extends('layouts.master') 

@section('content')   

<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Data Tables</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
<li class="breadcrumb-item active">Cr Note List</li>
</ol>
</div>

</div>
</div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)

<div class="row">
<div class="col-md-6">
<a href="{{ Route('CrNote.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a>
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
<th>Firm</th>
<th>Dr Note No</th>
<th>Date</th>
<th>Tax Type</th>
<th>DR A/c</th>
<th>Cr To Party</th>
<th>GST No</th>
<th>Party Ref No</th>
<th>Ag Bill No</th>
<th>Bill Date</th>
<th>HSN No</th>
<th>Basic Amount</th> 
<th>CGST%</th>
<th>CGST Amount</th>
<th>SGST%</th>
<th>SGST Amount</th>
<th>IGST%</th>
<th>IGST Amount</th>
<th>GST Amount</th> 
<th>Cr Amount</th>
<th>Agent Name</th>
<th>Narration</th>
<th>Created By</th>
<th>EDIT</th>
<th>DELETE</th>
</tr>
</thead>

<tbody>

@foreach($data as $row)    
<tr>
<td>{{ $row->firm_name }}</td>
<td>{{ $row->CrNote_Code }}</td>
<td>{{ $row->date }}</td>
<td>{{ $row->tax_type_name }}</td>
<td>{{ $row->drname }}</td>
<td>{{ $row->crname }}</td>
<td>{{ $row->gst_no }}</td>
<td>{{ $row->party_ref_no }}</td>
<td>{{ $row->ag_bill_no }}</td>
<td>{{ $row->bill_date }}</td>
<td>{{ $row->hsn_no }}</td>
<td>{{ $row->basic_amount }}</td>
<td>{{ $row->cgst_per }}</td>
<td>{{ $row->cgst_amount }}</td>
<td>{{ $row->sgst_per }}</td>
<td>{{ $row->sgst_amount }}</td>
<td>{{ $row->igst_per }}</td>
<td>{{ $row->igst_amount }}</td>
<td>{{ $row->gst_amount }}</td>
<td>{{ $row->cr_amount }}</td>
<td>{{ $row->br_name }}</td>
<td>{{ $row->narration }}</td>
<td>{{ $row->username }}</td>



@if($chekform->edit_access==1)
<td>
<a class="btn btn-outline-secondary btn-sm edit" href="{{route('CrNote.edit',$row->sr_no)}}" title="Edit">
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
<form action="{{route('CrNote.destroy', $row->CrNote_Code )}}" method="POST">
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