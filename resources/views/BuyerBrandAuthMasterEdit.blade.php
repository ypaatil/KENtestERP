@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Buyer Brand Auth Master Edit</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Buyer Brand Auth Master Edit</li>
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
   <form action="{{ route('BuyerBrandAuth.update', $BuyerBrandAuthList) }}" method="POST" enctype="multipart/form-data">  
   @method('put')
   @csrf  
   <div class="row">
       <div class="row">
           <div class="col-md-4">
               <div class="mb-3">    
                     <input type="hidden" name="buyer_brand_auth_id" value="{{$BuyerBrandAuthList->buyer_brand_auth_id}}" >  
                     <label for="formrow-user_Id" class="form-label">User Name</label>
                     <select name="user_Id" class="form-select select2" id="user_Id" required>
                        <option value="">--Select--</option>
                        @foreach($UserMasterList as  $row)
                        <option value="{{ $row->userId }}" {{ $row->userId == $BuyerBrandAuthList->userId ? 'selected="selected"' : '' }}  >{{ $row->username }}</option>
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
               @php
                     $detailData = DB::SELECT("SELECT auth_id FROM buyer_brand_auth_details WHERE brand_id =".$row->brand_id." AND userId=".$BuyerBrandAuthList->userId);
                     $auth_id = isset($detailData[0]->auth_id) ? $detailData[0]->auth_id : 0;
               @endphp
               <tr>
                    <td>{{$srno++}}</td> 
                    <td>
                         {{$row->brand_name}}
                         <input type="hidden" name="brand_id[]" class="form-control" value="{{$row->brand_id}}">  
                    </td>
                    <td> 
                         <input type="checkbox" name="authId[]" class="checkbox" value="{{$row->brand_id}}" {{ $auth_id == 1 ? 'checked="checked"' : '' }} style="width: 202px;height: 31px;" >   
                    </td>  
               </tr>
               @endforeach
           </tbody>
       </table> 
       </div>
       <div class="row"> 
           <div class="col-md-6"> 
               <button type="submit" class="btn btn-primary w-md">Submit</button> 
               <a href="/BuyerBrandAuth" class="btn btn-danger">Cancel</a> 
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
</div>
</div>
<script src="https://code.jquery.com/jquery-1.12.3.js"></script> 
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