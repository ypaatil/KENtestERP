@extends('layouts.master') 
@section('content')
<style>
    .highlight {
        background-color: yellow;
    }
    .highlight-container {
        display: inline-block;
        margin-top: 10px;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Operation Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Operation Master</li>
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
         <h4 class="card-title mb-4">Operation Master</h4>
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
         <form action="{{route('Operation.store')}}" method="POST">
         <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
            @csrf 
            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="formrow-sales_order_no" class="form-label">KDPL</label>
                     <select name="sales_order_no" class="form-select select2" id="sales_order_no" onchange="GetMainstyleFromKDPL();" >
                        <option value="">--KDPL--</option>
                        @foreach($SalesOrderList as  $row)
                        <option value="{{ $row->tr_code }}">{{ $row->tr_code }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="formrow-main_style_id" class="form-label">Main Style</label>
                     <select name="main_style_id" class="form-select" id="main_style_id" onchange="GetOperationList(this.value);" >
                        <option value="">--Main Style--</option>
                     </select>
                  </div>
               </div>  
                  <div class="row text-right"> 
                      <div class="col-md-5"></div>
                      <div class="col-md-5"></div>
                      <div class="col-md-2"><input type="text" id="search-box" class="form-control" placeholder="Search..."></div>
                  </div>
                  <table id="opertionTbl" class="table table-bordered dt-responsive nowrap w-100">
                   <thead>
                       <tr>
                           <th>Sr No.</th>
                           <th>Operartion Name</th>
                           <th>Operartion Rate</th>
                           <th>Action</th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                            <td> 
                                 <input type="text" name="srno" class="form-control" id="formrow-srno-input" value="1" style="width:80px;">  
                            </td>
                            <td> 
                                 <div class="highlight-container"></div>
                                 <br>
                                 <select name="operationNameId[]" class="form-select" id="operationNameId"  style="width:300px;" >
                                    <option value="">--Select--</option>  
                                 </select>
                                 <div class="highlight-container"></div>
                            </td>
                            <td>
                                 <input type="text" name="operation_rate[]" class="form-control" id="formrow-operation_rate-input" value=""  style="width:200px;" >  
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" > X </a>
                            </td>
                       </tr>
                   </tbody>
               </table>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="formrow-email-input" class="form-label">&nbsp;</label>
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFeilds();">Submit</button>
                     <a href="{{ Route('Operation.index') }}"  class="btn btn-warning w-md">Cancel</a>
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
 
   function EnableFeilds()
   {
       $('select').removeAttr('disabled');
   }
   
   function GetMainstyleFromKDPL()
   {
       var sales_order_no = $('#sales_order_no').val();
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetMainstyleFromKDPL') }}",
          data:{'sales_order_no':sales_order_no},
          success: function(data)
          { 
               $('#main_style_id').html(data.html);
               var main_style_id =  $('#main_style_id').val();
               GetOperationList(main_style_id);
          }
        });
   }
   function GetOperationList(main_style_id)
   {
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetOperationList') }}",
          data:{'main_style_id':main_style_id},
          success: function(data)
          { 
                $('tbody').html(data.html); 
          }
        });
   }
               
    var currentIndex = -1;
    var highlightedElements = [];
    $('#search-box').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        currentIndex = -1;
        highlightAndCollectElements(searchText);
    });
    
    $('#search-box').on('keypress', function(e) {
        if (e.which == 13) { // Enter key pressed
            e.preventDefault();
            if (highlightedElements.length > 0) {
                currentIndex = (currentIndex + 1) % highlightedElements.length;
                $('html, body').animate({
                    scrollTop: highlightedElements[currentIndex].offset().top
                }, 50);
            }
        }
    });
    
    function highlightAndCollectElements(searchText) 
    {
        highlightedElements = [];
    
        $('select[name="operationNameId[]"]:disabled option:selected').each(function() {
            var optionText = $(this).text();
            var highlightedText = optionText;
  
            // Remove previous highlights
            highlightedText = highlightedText.replace(/<\/?span[^>]*>/g, "");

            // Highlight matching text
            if (searchText && optionText.toLowerCase().includes(searchText)) {
                var regex = new RegExp('(' + searchText + ')', 'gi');
                highlightedText = optionText.replace(regex, '<span class="highlight">$1</span>');
                highlightedElements.push($(this).closest('select'));  
                console.log(highlightedText);
            }

            // Display highlighted text in the container next to the select box
            $(this).closest('select').next('.highlight-container').html(highlightedText);
        });
    }
    
   function AddNewRow(row)
   { 
        var tr = $(row).closest('tr');
        var clone = tr.clone();
        tr.after(clone); 
        recalcIdcone();
   } 
   
   function removeRow(row)
   { 
      $(row).parents('tr').remove(); 
   }
   
   function recalcIdcone()
   {
       $.each($("#opertionTbl tr"),function (i,el)
       {
             $(this).find("td:first input").val(i);  
       })
   }
   
   
</script>

@endsection