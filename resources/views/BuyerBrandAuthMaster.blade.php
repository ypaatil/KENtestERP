@extends('layouts.master') 
@section('content') 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Buyer Brand Auth Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Buyer Brand Auth Master</li>
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
         <h4 class="card-title mb-4">Buyer Brand Auth Master</h4>
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
          <div id="overlay"></div>
         <form action="{{route('BuyerBrandAuth.store')}}" method="POST"> 
            @csrf 
            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="formrow-user_Id" class="form-label">User Name</label>
                     <select name="user_Id" class="form-select select2" id="user_Id" required>
                        <option value="">--Select--</option>
                        @foreach($UserMasterList as  $row)
                        <option value="{{ $row->userId }}">{{ $row->username }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
                  <table id="opertionTbl" class="table table-bordered dt-responsive nowrap w-100">
                   <thead>
                       <tr>
                           <th>Sr No.</th> 
                           <th>Brand</th>
                           <th>Permission <input type="checkbox" id="all" value="0"  style="width: 183px;height: 30px;vertical-align: bottom;" ></th> 
                       </tr>
                   </thead>
                   <tbody>
                       @php
                            $srno = 1;
                       @endphp
                       @foreach($brandList as  $row)
                       <tr>
                            <td>{{$srno++}}</td> 
                            <td>
                                 {{$row->brand_name}}
                                 <input type="hidden" name="brand_id[]" class="form-control" value="{{$row->brand_id}}"  style="width:200px;" >  
                            </td>
                            <td> 
                                 <input type="checkbox" name="authId[]" class="checkbox" value="{{$row->brand_id}}"  style="width: 202px;height: 31px;" >   
                            </td>  
                       </tr>
                       @endforeach
                   </tbody>
               </table>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="formrow-email-input" class="form-label">&nbsp;</label>
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
                     <a href="{{ Route('BuyerBrandAuth.index') }}"  class="btn btn-warning w-md">Cancel</a>
                  </div>
               </div>
         </form>
         </div>
         <!-- end card body -->
      </div>
      <!-- end card -->
   </div>
   <!-- end col -->
   <!-- end col -->
</div>

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
 
   $(document).ready(function() { 
        $("#all").click(function() 
        { 
            var isChecked = $(this).prop("checked"); 
            $(".checkbox").prop("checked", isChecked);
        });
     
        $(".checkbox").click(function() 
        { 
            var allChecked = $(".checkbox:checked").length === $(".checkbox").length; 
            $("#all").prop("checked", allChecked);
        });
    });

</script>
@endsection