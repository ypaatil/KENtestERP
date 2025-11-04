@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Form Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Form List</li>
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
      <a href="{{ Route('Form.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                     <th>Form Id</th>
                     <th>Form Label</th>
                     <th>Form Route</th>
                     <th>Head Id</th>
                     <th>Sequence No.</th>
                     <th>User</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($forms as $row)    
                  <tr>
                     <td>{{ $row->form_code }}</td>
                     <td>{{ $row->form_label }}</td>
                     <td>{{ $row->form_name }}</td>
                     <td class="text-center">{{ $row->head_id }}</td>
                     <td class="text-center">{{ $row->seq_no }}</td>
                     <td>{{ $row->username }}</td>
                     @if($chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Form.edit', $row->form_code)}}" title="Edit">
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
                        <form action="{{route('Form.destroy', $row->form_code)}}" method="POST">
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
   </div>
   <!-- end col -->
</div>
<!-- end row -->
@endsection