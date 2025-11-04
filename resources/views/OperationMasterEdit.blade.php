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
         @if(isset($OperationList))
          <form action="{{ route('Operation.update',$OperationList) }}" method="POST" enctype="multipart/form-data">  
            <input type="hidden" name="operationId" class="form-control" id="operationId" value="{{ $OperationList->operationId}}"> 
            <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input"> 
           @method('put')
           @csrf  
            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="formrow-sales_order_no" class="form-label">KDPL</label>
                     <select name="sales_order_no" class="form-select" id="sales_order_no"  onchange="GetMainstyleFromKDPL();" disabled >
                        <option value="">--KDPL--</option>
                        @foreach($SalesOrderList as  $row)
                        <option value="{{ $row->tr_code }}"  {{ $row->tr_code == $OperationList->sales_order_no ? 'selected="selected"' : '' }} >{{ $row->tr_code }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="formrow-main_style_id" class="form-label">Main Style</label>
                     <select name="main_style_id" class="form-select" id="main_style_id" onchange="GetOperationList(this.value);" disabled >
                        <option value="">--Main Style--</option>
                        @foreach($MainStyleList as  $row)
                        <option value="{{ $row->mainstyle_id }}" {{ $row->mainstyle_id == $OperationList->main_style_id ? 'selected="selected"' : '' }}>{{ $row->mainstyle_name }}</option>
                        @endforeach
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
                       @php
                            $srno = 1;
                            $operationFilter = "";
                       @endphp
                       @foreach($OperationDetailList as $row)
                       @php
                            //DB::enableQueryLog();
                            if($row->operationNameId != "")
                            {
                                $operationFilter = "AND operation_details.operationNameId=".$row->operationNameId;
                            }
                                
                            $EntryData = DB::SELECT("SELECT count(*) as total_count FROM cutting_entry_master 
                                        INNER JOIN cutting_entry_details ON cutting_entry_details.cuttingEntryId = cutting_entry_master.cuttingEntryId
                                        INNER JOIN operation_master ON operation_master.sales_order_no = cutting_entry_master.sales_order_no
                                        INNER JOIN operation_details ON operation_details.operationId = operation_master.operationId
                                        WHERE cutting_entry_master.sales_order_no='".$OperationList->sales_order_no."' ".$operationFilter);
                               
                        
                            //dd(DB::getQueryLog());
                            $total_count = isset($EntryData[0]->total_count) ? $EntryData[0]->total_count : 0;
                            
                            if($total_count > 0)
                            {
                                $btn = 'disabled';
                            }
                            else
                            {
                                $btn ='';
                            }
                       @endphp
                       <tr>
                            <td> 
                                 <input type="text" name="srno" class="form-control" id="formrow-srno-input" value="{{$srno++}}" style="width:80px;" readonly>  
                            </td>
                            <td>   
                                 <div class="highlight-container"></div>
                                 <br>
                                 <select name="operationNameId[]" class="form-select" id="operationNameId"  style="width:300px;" onchange="checkDuplicates(this);" disabled>
                                    <option value="">--Select--</option>  
                                    @foreach($OperationNameList as $op)
                                          <option value="{{$op->operationNameId}}" {{ $op->operationNameId == $row->operationNameId ? 'selected="selected"' : '' }} >{{$op->operation_name}}</option>  
                                    @endforeach
                                 </select>
                                 <div class="highlight-container"></div>
                            </td>
                            <td>
                                 <input type="text" name="operation_rate[]" class="form-control" id="formrow-operation_rate-input" value="{{$row->operation_rate}}"  style="width:200px;" >  
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-warning" onclick="AddNewRow(this);">+</a>
                                <button type="button" class="btn btn-danger" onclick="removeRow(this);" {{$btn}}> X </button>
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
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFeilds();">Submit</button>
                     <a href="{{ Route('Operation.index') }}"  class="btn btn-warning w-md">Cancel</a>
                  </div>
               </div>
         </div>
         </form>
         @endif
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
        var currentIndex = -1;
        var highlightedElements = [];

        function highlightAndCollectElements(searchText) {
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
                }

                // Display highlighted text in the container next to the select box
                $(this).closest('select').next('.highlight-container').html(highlightedText);
            });
        }

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
    });

    function checkDuplicates(row)
    {
        var elemArr = [];
        $('select[name="operationNameId[]"]').not(row).find(":selected").each(function()
        { 
            elemArr.push($(this).val()); 
        });
        
        if ($.inArray($(row).val(), elemArr) !== -1) 
        {
            
           $(row).select2('destroy'); 
           alert("Already Exists...!");
           $(row).val("");
           $(row).select2();  
        }   
        
    }


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
               $('select[name="operationNameId[]"]').html(data.html); 
          }
        });
   }
   
   function AddNewRow(row)
   {  
        $("select").select2('destroy'); 
        var tr = $(row).closest('tr');
        var clone = tr.clone(); 
        clone.find('select').val("");
        tr.after(clone); 
        tr.find('select').attr('disabled',true);
        clone.find('select').removeAttr('disabled');
        clone.find('select').select2(); 
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