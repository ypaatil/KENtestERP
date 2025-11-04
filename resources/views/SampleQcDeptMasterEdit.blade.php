@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sample QC Department Edit</h4>
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
            @if(isset($SampleQcDept))
            <form action="{{ route('SampleQcDept.update',$SampleQcDept) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_qc_dept_date" class="form-label">Date</label>
                        <input type="date" name="sample_qc_dept_date" class="form-control" id="sample_qc_dept_date" value="{{ $SampleQcDept->sample_qc_dept_date}}">
                        <input type="hidden" name="sample_qc_dept_id" class="form-control" id="sample_qc_dept_id" value="{{ $SampleQcDept->sample_qc_dept_id}}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN Code</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleQcDeptMasterData();" disabled>
                           <option value="0">--Select--</option>
                           @foreach($SampleIndentList as  $row) 
                                <option value="{{ $row->sample_indent_code }}" {{ $row->sample_indent_code == $SampleQcDept->sample_indent_code ? 'selected="selected"' : '' }}  >{{ $row->sample_indent_code }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer Name</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" disabled>
                           <option value="0">--Select--</option>
                           @foreach($Buyerlist as  $row) 
                                <option value="{{ $row->ac_code }}" {{ $row->ac_code == $SampleQcDept->Ac_code ? 'selected="selected"' : '' }}  >{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select select2" id="brand_id" disabled>
                           <option value="0">--Select--</option> 
                           @foreach($BrandList as  $row) 
                                <option value="{{ $row->brand_id }}" {{ $row->brand_id == $SampleQcDept->brand_id ? 'selected="selected"' : '' }}  >{{ $row->brand_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Main Style</label>
                        <select name="mainstyle_id" class="form-select" id="mainstyle_id" disabled>
                           <option value="0">--- Select ---</option>
                           @foreach($MainStylelist as  $row) 
                                <option value="{{ $row->mainstyle_id }}"  {{ $row->mainstyle_id == $SampleQcDept->mainstyle_id ? 'selected="selected"' : '' }} >{{ $row->mainstyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select" id="substyle_id" disabled>
                           <option value="0">--- Select ---</option>
                           @foreach($SubStylelist as  $row) 
                                <option value="{{ $row->substyle_id }}" {{ $row->substyle_id == $SampleQcDept->substyle_id ? 'selected="selected"' : '' }}  >{{ $row->substyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{ $SampleQcDept->style_description}}" readonly >
                        <input type="hidden" name="userId" value="{{ $SampleQcDept->userId}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="{{ $SampleQcDept->sam}}" readonly > 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_type_id" class="form-label">Sample Type</label>
                        <select name="sample_type_id" class="form-select select2" id="sample_type_id" disabled onchange="GetDepartmentType();">
                           <option value="0">--- Select ---</option>
                           @foreach($SampleTypelist as  $row) 
                           <option value="{{ $row->sample_type_id  }}" {{ $row->sample_type_id == $SampleQcDept->sample_type_id ? 'selected="selected"' : '' }}  >{{ $row->sample_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select " id="dept_type_id" disabled>
                           <option value="0">-- Select --</option>
                           @foreach($DepartmentTypelist as  $row)
                                <option value="{{ $row->dept_type_id  }}" {{ $row->dept_type_id == $SampleQcDept->dept_type_id ? 'selected="selected"' : '' }}  >{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select " id="sz_code" onchange="SizeSampleQcDeptList(this.value);" disabled>
                           <option value="0">-- Select --</option>
                           @foreach($SizeGroupList as  $row)
                              <option value="{{ $row->sz_code }}" {{ $row->sz_code == $SampleQcDept->sz_code ? 'selected="selected"' : '' }}  >{{ $row->sz_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="actual_etd" class="form-label">Actual ETD (Complete Date)</label>
                        <input type="date" name="actual_etd" class="form-control" id="actual_etd" value="{{$SampleQcDept->actual_etd}}"> 
                     </div>
                  </div>
               </div>
                <div class="col-md-12">
                  <h4><b>Order Qty</b></h4>
               </div>
                <div class="table-wrap">
                   <div class="table-responsive" id="order_qty">
                        <table id="footable_1" class="table table-bordered table-striped m-b-0 footable_1">
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>Color</th>
                                    @foreach ($SizeDetailList as $sz)
                                        <th>{{$sz->size_name}}</th>
                                    @endforeach
                                    <th>Total Qty</th>
                                </tr>
                            </thead>
                            <tbody id="SampleQcDept">
                                @if(count($SampleIndentDetailList) > 0)
                                    @php $no = 1; $n = 1; @endphp
                                    @foreach($SampleIndentDetailList as $List)
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{$List->color}}</td>
                                            @php   
                                                $n = 1;  
                                                $SizeQtyList = explode(',', $List->size_qty_array); 
                                            @endphp
                                            @foreach($SizeQtyList as $key => $szQty)
                                                <td>{{$szQty}}</td>
                                                @php $n++; @endphp
                                            @endforeach
                                            <td>{{$List->size_qty_total}}</td>
                                        </tr>
                                        @php $no++; @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                   </div> 
                </div> 
               <div class="col-md-12">
                  <h4><b>Stitching Qty</b></h4>
               </div>
               <div class="table-responsive">
                  <table id="footable_2" class="table table-bordered table-striped m-b-0 footable_2">
                        <thead>
                            <tr>
                                <th>SrNo</th>
                                <th>Color</th>
                                @foreach($SizeDetailList as $sz)
                                <th>{{$sz->size_name}}</th>
                                @endforeach
                                <th>Total Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            
                            $SizeDetailList = DB::table('size_detail')->where('size_detail.sz_code','=', $SampleQcDept->sz_code)->get();
            
                            $sizes='';
                            
                            foreach ($SizeDetailList as $sz) 
                            {
                                
                                $sizes=$sizes.$sz->size_id.',';
                            }
                            $sizes=rtrim($sizes,',');
            
                            if(count($SampleStitchingDetailList) > 0)
                            {
                                $no = 1; 
                                $n = 1;
                                foreach($SampleStitchingDetailList as $List)
                                {
                                
                                    $n = 1;  
                                    $SizeQtyList = explode(',', $List->size_qty_array); 
                            @endphp
                                    <tr>
                                        <td><input type="text" name="id[]" value="{{$no}}" id="id{{$no}}" style="width:50px;" /></td>
                                        <td><input type="text" name="color[]" class="color" value="{{$List->color}}" id="color{{$no}}" style="width:150px; height:30px;" readonly /></td>
                                        @foreach($SizeQtyList as $key => $szQty1)
                                        <td><input type="number" name="s{{$n}}[]" class="size_id" value="{{$szQty1}}" id="size_id{{$no}}_{{$n}}"  max="{{$List->size_qty_total}}" style="width:80px; height:30px;" onkeyup="GetSizeQtyArray(this);" /></td>
                                        @php
                                             $n++;
                                        @endphp
                                        @endforeach
                                        <td>
                                            <input type="number" name="order_qty[]" class="QTY" value="{{$List->size_qty_total}}"  min="1" max="{{$List->size_qty_total}}" id="size_qty_total{{$no}}" style="width:80px; height:30px;" readonly />
                                            <input type="hidden" name="size_array[]" class="size_array" value="{{$List->size_array}}" id="size_array{{$no}}" />
                                            <input type="hidden" name="size_qty_array[]" class="size_qty_array" value="{{$List->size_qty_array}}" id="size_qty_array{{$no}}" />
                                        </td>
                                    </tr>
                            @php
                                    $no++; 
                                }
                            }
                            @endphp
                        </tbody>
                    </table>
               </div>
               <div class="table-responsive">
                <table id="footable_3" class="table table-bordered table-striped m-b-0 footable_3">
                    <thead>
                        <tr>
                            <th>Attachment Name</th>
                            <th>Attachment</th>
                            <th>View Attachment</th>
                            <th>Add/Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($SampleStitchingAttachmentList) > 0)
                        @foreach($SampleStitchingAttachmentList as $index => $row)
                        <tr>
                           <td> 
                               <input type="text" name="attachment_name[]" class="form-control" value="{{ $row->attachment_name }}" id="attachment_name_{{ $index }}" style="width:300px;"/>
                           </td>
                            <td> 
                                <input type="file" name="upload_attachment[]" id="upload_attachment_{{ $index }}" style="width:200px;"/>
                            </td>
                            <td>
                                @if(isset($row->upload_attachment))
                                    <a href="{{ asset('public/uploads/Sample/'. $row->upload_attachment) }}" target="_blank">View Attachment</a>
                                @endif 
                            </td>
                            <td>
                                <input type="button" style="width:40px;" id="Abutton{{ $index }}" name="button[]" value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left">
                                <input type="button" id="Bbutton{{ $index }}" class="btn btn-danger pull-left" onclick="deleteRow1(this);" value="X" style="margin-left:5px;">
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                           <td> 
                               <input type="text" name="attachment_name[]" class="form-control" value="" id="attachment_name_0" style="width:300px;"/>
                           </td>
                            <td> 
                                <input type="file" name="upload_attachment[]" id="upload_attachment_0" style="width:200px;"/>
                            </td>
                            <td>
                               -
                            </td>
                            <td>
                                <input type="button" style="width:40px;" id="Abutton0" name="button[]" value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left">
                                <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow1(this);" value="X" style="margin-left:5px;">
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit"  onclick="EnableFeilds();">Submit</button>
                     <a href="{{ Route('SampleQcDept.index') }}" class="btn btn-warning w-md">Cancel</a>
                  </div>
               </div>
         </div>
         </form>
         @endif
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
<script> 
   $(document).ready(function() {
      $('#frmData').submit(function() {
          $('#Submit').prop('disabled', true);
      }); 
      
      OrderQty();
       
   });
   
   function OrderQty()
   {
      var total = 0;  
      $('input[name="order_qty[]"]').each(function()
      {
             total += parseFloat($(this).val());
      });  
      
      if(parseFloat(total) == 0)
      {
           $("#Submit").attr("disabled", true);  
      }
      else
      {
          
           $("#Submit").attr("disabled", false);  
      }
   }
   
    function EnableFeilds()
    {
       $('select').removeAttr('disabled');
    }
    
    function GetSampleIndentMasterData()
    {
        var sample_indent_code = $('#sample_indent_code').val();
        $.ajax({
             dataType: "json",
             contentType: "application/json; charset=utf-8",
             url: "{{ route('GetSampleIndentMasterQCData') }}",
             data:{'sample_indent_code':sample_indent_code},
             success: function(data)
             { 
                var res = data.MasterData;
                $("#order_qty").html(data.DetailHtml);
                $("#stitching_qty").html(data.StitchingHtml);
                $("#style_description").val(res.style_description);
                $("#sam").val(res.sam); 
                $('#Ac_code option[value="' + res.Ac_code + '"]').prop('selected', true).change();
                $('#brand_id option[value="' + res.brand_id + '"]').prop('selected', true).change();
                $('#mainstyle_id option[value="' + res.mainstyle_id + '"]').prop('selected', true).change();
                $('#substyle_id option[value="' + res.substyle_id + '"]').prop('selected', true).change();
                $('#sample_type_id option[value="' + res.sample_type_id + '"]').prop('selected', true).change();
                $('#dept_type_id option[value="' + res.dept_type_id + '"]').prop('selected', true).change();
                $('#sz_code option[value="' + res.sz_code + '"]').prop('selected', true).change(); 
                
                if(data.actual_etd != '')
                {
                     $("#actual_etd").val(data.actual_etd);
                     $("#actual_etd").attr('readonly', true);
                }
             }
        });
    } 
    
    function GetSizeQtyArray(row)
    {
        var size_array = [];
        var total_qty = 0;
        $(row).parent().parent('tr').find('.size_id').each(function()
        {
            var size_id = $(this).val(); 
            size_array.push(size_id); 
            total_qty += parseFloat(size_id);
        });
    
        var unique_size_array = size_array.join(','); 
        $(row).parent().parent('tr').find('input[name="size_qty_array[]"]').val(unique_size_array); 
        var max =  $(row).parent().parent('tr').find('input[name="order_qty[]"]').attr('max');
        if(parseInt(total_qty) > parseInt(max))
        {
            alert("Total qty exceeded...!");
            $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(parseInt(total_qty) - parseInt($(row).val())); 
            $(row).val(0);
            
            var size_array = [];
            var total_qty = 0;
            $(row).parent().parent('tr').find('.size_id').each(function()
            {
                var size_id = $(this).val(); 
                size_array.push(size_id); 
                total_qty += parseFloat(size_id);
            });
        
            var unique_size_array = size_array.join(','); 
            $(row).parent().parent('tr').find('input[name="size_qty_array[]"]').val(unique_size_array); 
        
        }
        else
        {
            $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(total_qty); 
        }
        
        OrderQty();
    }
    
     
     function deleteRow(btn) 
     {
        var row = btn.parentNode.parentNode;
        var table = row.parentNode;
        
        if (table.rows.length > 1) 
        {
            table.removeChild(row);  
            recalcId1(); 
        }
    }

    function deleteRow1(btn) 
    { 
        var row = btn.parentNode.parentNode; 
        var link = $(btn).closest('tr').find('a').attr('href'); 
    
        if (typeof link !== 'undefined' && link) { 
            var filename = link.substring(link.lastIndexOf('/') + 1); 
            var sample_qc_dept_id = $("#sample_qc_dept_id").val(); 
    
            $.ajax({
                type: "POST",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                url: "{{ route('DeleteSampleQcAttachment') }}", 
                data: JSON.stringify({
                    'upload_attachment': filename,
                    'sample_qc_dept_id': sample_qc_dept_id
                }),
                success: function(data) { 
                    row.parentNode.removeChild(row); 
                    recalcId1(); 
                },
                error: function(xhr, status, error) {
                    console.error('Error occurred while deleting:', error);
                }
            });
        } 
        else 
        { 
            var table = row.parentNode;
            if (table.rows.length > 1) 
            {
                row.parentNode.removeChild(row); 
                recalcId1(); 
            }
        } 
    }


   
   function recalcId1()
   {
        $.each($("#footable_1 tr"),function (i,el)
        {
            $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
        });
   }
   

    function addNewRow(row)
    {
        var table = $(row).closest('table');
        var lastRow = table.find('tr:last').clone();  
        
        lastRow.find('input[type="text"]').val('');  
        lastRow.find('input[type="file"]').val(''); 
    
        table.append(lastRow); 
        recalcId1();
    }
</script>
@endsection