@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">From-Table Associate</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Form Association List</li>
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
      <a href="{{ Route('FormTableMaster.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a>
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
                     <th>Sr. No.</th>
                     <th>Date</th>
                     <th>Form Name</th>
                     <th>Form Detail</th>
                     <th>User Name</th>
                     <th>Print</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1;
                  @endphp
                  @foreach($data as $row)    
                  <tr>
                     <td>{{ $srno++ }}</td>
                     <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                     <td>{{ $row->form_label}}</td>
                     <td>{{ $row->form_detail }}</td>
                     <td>{{ $row->username }}</td>
                     
                     @if( $row->userId == Session::get('userId') && $chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('FormTableMaster.edit', $row->form_id )}}" title="Edit">
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
                     @if($chekform->delete_access==1)
                     <td>
                        <form action="{{route('FormTableMaster.destroy', $row->form_id )}}" method="POST">
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