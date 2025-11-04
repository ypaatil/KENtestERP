@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Get Item Ledger REPORT</h4>
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
            <form action="{{route('rptItemLedger')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fdate" class="form-label">From Date</label>
                        <input type="date" name="fdate" class="form-control" value="2000-01-01">
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tdate" class="form-label">To Date</label>
                        <input type="date" name="tdate" class="form-control" value="{{date('Y-m-d')}}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cat_id" class="form-label">Category</label>
                        <select name="cat_id" class="form-control select2" id="cat_id" onchange="GetClassifictionData()" >
                           <option value="All">--All--</option>
                           @foreach($Categorylist as $categ)
                             <option value="{{$categ->cat_id}}">{{$categ->cat_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="line_id" class="form-label">Classification</label>
                        <select name="class_id" class="form-control select2" id="class_id" required onchange="GetItemData()" >
                              <option value="All">--All--</option>
                          
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="item_code" class="form-label">Item</label>
                        <select name="item_code" class="form-control select2" id="item_code" required>
                             <option value="All">--All--</option>
                          
                        </select>
                     </div>
                  </div>
               </div>
               <div class="col-sm-2">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
                  </div>
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
<!-- end row -->
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
      
    GetClassifictionData();
    GetItemData();
    
    function GetClassifictionData()
    {
         var cat_id =  $('#cat_id').val();
         var class_id =  $('#class_id').val();
         
            $.ajax({
                dataType: "json",
                url: "{{ route('GetClassifictionData') }}",
                data:{'cat_id':cat_id},
                success: function(data){
                $('#class_id').html(data.html);
               }
            });
    }
   
    function GetItemData()
    {
        var cat_id =  $('#cat_id').val();
        var class_id =  $('#class_id').val();
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetItemData') }}",
            data:{'cat_id':cat_id,class_id:class_id},
            success: function(data)
            {
                 $("#item_code").html(data.html);
            }
        });
    }
</script>
<!-- end row -->
@endsection