@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sample Customer Feedback Edit</h4>
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
            @if(isset($SampleCustomerFeedback))
            <form action="{{ route('SampleCustomerFeedback.update',$SampleCustomerFeedback) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_cust_feed_date" class="form-label">Date</label>
                        <input type="date" name="sample_cust_feed_date" class="form-control" id="sample_cust_feed_date" value="{{ $SampleCustomerFeedback->sample_cust_feed_date}}">
                        <input type="hidden" name="sample_cust_feed_id" class="form-control" id="sample_cust_feed_id" value="{{ $SampleCustomerFeedback->sample_cust_feed_id}}">
                        <input type="hidden" name="sample_cad_dept_id" class="form-control" id="sample_cad_dept_id" value="{{ $SampleCustomerFeedback->sample_cad_dept_id}}">
                        <input type="hidden" name="sample_qc_dept_id" class="form-control" id="sample_qc_dept_id" value="{{ $SampleCustomerFeedback->sample_qc_dept_id}}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer Name</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" disabled>
                           <option value="0">--Select--</option>
                           @foreach($Buyerlist as  $row) 
                                <option value="{{ $row->ac_code }}" {{ $row->ac_code == $SampleCustomerFeedback->Ac_code ? 'selected="selected"' : '' }}  >{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN Code</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleCustomerFeedbackMasterData();" disabled >
                           <option value="0">--Select--</option>
                           @foreach($SampleIndentList as  $row) 
                                <option value="{{ $row->sample_indent_code }}" {{ $row->sample_indent_code == $SampleCustomerFeedback->sample_indent_code ? 'selected="selected"' : '' }}  >{{ $row->sample_indent_code }}</option>
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
                                <option value="{{ $row->mainstyle_id }}"  {{ $row->mainstyle_id == $SampleCustomerFeedback->mainstyle_id ? 'selected="selected"' : '' }} >{{ $row->mainstyle_name }}</option>
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
                                <option value="{{ $row->substyle_id }}" {{ $row->substyle_id == $SampleCustomerFeedback->substyle_id ? 'selected="selected"' : '' }}  >{{ $row->substyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{ $SampleCustomerFeedback->style_description}}" readonly >
                        <input type="hidden" name="userId" value="{{ $SampleCustomerFeedback->userId}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="{{ $SampleCustomerFeedback->sam}}" readonly > 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_type_id" class="form-label">Sample Type</label>
                        <select name="sample_type_id" class="form-select select2" id="sample_type_id" onchange="GetDepartmentType();" disabled>
                           <option value="0">--- Select ---</option>
                           @foreach($SampleTypelist as  $row) 
                           <option value="{{ $row->sample_type_id  }}" {{ $row->sample_type_id == $SampleCustomerFeedback->sample_type_id ? 'selected="selected"' : '' }}  >{{ $row->sample_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select " id="dept_type_id" disabled >
                           <option value="0">-- Select --</option>
                           @foreach($DepartmentTypelist as  $row)
                                <option value="{{ $row->dept_type_id  }}" {{ $row->dept_type_id == $SampleCustomerFeedback->dept_type_id ? 'selected="selected"' : '' }}  >{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select " id="sz_code" onchange="SizeSampleCustomerFeedbackList(this.value);" disabled >
                           <option value="0">-- Select --</option>
                           @foreach($SizeGroupList as  $row)
                              <option value="{{ $row->sz_code }}" {{ $row->sz_code == $SampleCustomerFeedback->sz_code ? 'selected="selected"' : '' }}  >{{ $row->sz_name }}</option>
                           @endforeach
                        </select>
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
                            <tbody id="SampleCustomerFeedback">
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
                            
                            $SizeDetailList = DB::table('size_detail')->where('size_detail.sz_code','=', $SampleCustomerFeedback->sz_code)->get();
            
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
                                        <td><input type="text" name="id[]" value="{{$no}}" id="id{{$no}}" style="width:50px;" readonly /></td>
                                        <td><input type="text" name="color[]" class="color" value="{{$List->color}}" id="color{{$no}}" style="width:150px; height:30px;"  readonly /></td>
                                        @foreach($SizeQtyList as $key => $szQty)
                                        <td><input type="number" name="s{{$n}}[]" class="size_id" value="{{$szQty}}" id="size_id{{$no}}_{{$n}}" style="width:80px; height:30px;" onchange="GetSizeQtyArray(this);"  readonly/></td>
                                        @php
                                             $n++;
                                        @endphp
                                        @endforeach
                                        <td>
                                            <input type="number" name="order_qty[]" class="QTY" value="{{$List->size_qty_total}}" id="size_qty_total{{$no}}" style="width:80px; height:30px;" readonly />
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
               
               <div class="row">
                   <div class="col-md-8">
                       <div class="table-responsive" id="BOMTbl">
                           <table id="footable_4" class="table table-bordered table-striped m-b-0 footable_4">
                            <thead>
                                <tr>
                                    <th>Bom Type</th>
                                    <th>Material Received Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr> 
                                       <td>
                                           Fabric
                                       </td>
                                       <td>
                                            @php
                                                $detail1 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 1 AND sample_indent_code='".$SampleCustomerFeedback->sample_indent_code."'");
                                                $m1 = isset($detail1[0]->material_received_status_id) ? $detail1[0]->material_received_status_id : 0;
                                            @endphp
                                            <select name="material_received_status_id[]" class="form-select " id="material_received_status_id"  style="width:250px;" disabled>
                                               <option value="0">-- Select --</option>
                                               @php
                                               foreach($MaterialReceivedList as  $row)
                                               {
                                                     $selected1 = '';
                                                     if($row->material_received_status_id == $m1)
                                                     {
                                                         $selected1 = 'selected';
                                                     }
                                                @endphp
                                                     <option value="{{$row->material_received_status_id}}"  {{$selected1}} >{{$row->material_received_status_name}}</option>
                                               
                                               @php
                                               }
                                                @endphp
                                            </select>
                                       </td>
                                    </tr>
                                    <tr> 
                                       <td>
                                           Sewing Trims 
                                       </td>
                                       <td>
                                           @php
                                                $detail2 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 2 AND sample_indent_code='".$SampleCustomerFeedback->sample_indent_code."'");
                                                
                                                $m2 = isset($detail2[0]->material_received_status_id) ? $detail2[0]->material_received_status_id : 0;
                                           @endphp
                                            <select name="material_received_status_id[]" class="form-select " id="material_received_status_id"  style="width:250px;" disabled>
                                               <option value="0">-- Select --</option>
                                             @php
                                               foreach($MaterialReceivedList as  $row)
                                               {
                                                     $selected2 = '';
                                                     if($row->material_received_status_id == $m2)
                                                     {
                                                         $selected2 = 'selected';
                                                     }
                                            @endphp  
                                                   <option value="{{$row->material_received_status_id}}" {{$selected2}}  >{{$row->material_received_status_name}}</option>
                                            @php
                                               }
                                            @endphp  
                                            </select>
                                       </td>
                                    </tr>
                                    <tr> 
                                       <td>
                                           Packing Trims
                                           <input type="hidden" name="bom_type_id[]" value="3" id="bom_type_id" style="width:50px;"/>
                                       </td>
                                       <td>
                                           @php
                                                $detail3 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 3 AND sample_indent_code='".$SampleCustomerFeedback->sample_indent_code."'");
                                                
                                                $m3 = isset($detail3[0]->material_received_status_id) ? $detail3[0]->material_received_status_id : 0;
                                           @endphp
                                           <select name="material_received_status_id[]" class="form-select " id="material_received_status_id"  style="width:250px;" disabled>
                                               <option value="0">-- Select --</option>
                                                @php
                                                foreach($MaterialReceivedList as  $row)
                                                {
                                                     $selected3 = '';
                                                     if($row->material_received_status_id == $m3)
                                                     {
                                                         $selected3 = 'selected';
                                                     }
                                                @endphp  
                                                   <option value="{{$row->material_received_status_id}}"  {{$selected3}} >{{$row->material_received_status_name}}</option>
                                               @php
                                                }
                                               @endphp  
                                            </select>
                                       </td>
                                    </tr>
                                </tbody>
                            </table>
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="table-responsive" id="AttachmentTbl">
                           <table id="footable_3" class="table table-bordered table-striped m-b-0 footable_3">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Sr.No.</th>
                                    <th style="text-align: center;">Attachment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $AttachData = DB::table('sample_qc_dept_attachment')->select('*')->where('sample_qc_dept_id', '=', $SampleCustomerFeedback->sample_qc_dept_id)->get();
                                if(count($AttachData) > 0)
                                {
                                    $no = 1; 
                                    foreach($AttachData as $List1)
                                    {
                                        $upload_attachment = '../../uploads/Sample/'.$List1->upload_attachment;
                                        @endphp
                                        <tr>
                                            <td style="vertical-align: middle;text-align: center;">{{$no}}</td>
                                            <td style="text-align: center;"><img src="{{$upload_attachment}}" width="100" height="80" alt=""></td>
                                        </tr>
                                @php
                                        $no++; 
                                    }
                                }
                                @endphp
                            </tbody>
                        </table>
                       </div>
                   </div>
               </div> 
               <div class="row">
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cust_feed_status_id" class="form-label">Customer Feedback</label>
                        <select name="cust_feed_status_id" class="form-select" id="cust_feed_status_id" required>
                           <option value="0">-- Select --</option>
                           @foreach($CustFeedList as  $row)
                                <option value="{{ $row->cust_feed_status_id  }}"  {{ $row->cust_feed_status_id == $SampleCustomerFeedback->cust_feed_status_id ? 'selected="selected"' : '' }}  >{{ $row->cust_feed_status_name }}</option>
                           @endforeach
                        </select>
                     </div>
                   </div>
                      <div class="col-md-6">
                         <div class="mb-3">
                            <label for="cust_comments" class="form-label">Customer Comments</label>
                            <input type="text" name="cust_comments" class="form-control" id="cust_comments" value="{{ $SampleCustomerFeedback->cust_comments  }}" >
                            <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                         </div>
                      </div>
                </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFeilds();">Submit</button>
                     <a href="{{ Route('SampleCustomerFeedback.index') }}" class="btn btn-warning w-md">Cancel</a>
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
      
        let colorGroups = {};
    
        // Group rows by color
        $('#footable_2 tbody tr').each(function() {
            const $row = $(this);
            const color = $row.find('.color').val();
    
            if (!colorGroups[color]) {
                colorGroups[color] = [];
            }
    
            colorGroups[color].push($row);
        });
    
        // Merge rows and sum quantities
        $.each(colorGroups, function(color, rows) {
            if (rows.length > 1) {
                let $firstRow = rows[0];
                let sizeQtySums = [];
    
                // Initialize the sizeQtySums array with zeros
                $firstRow.find('.size_id').each(function(index) {
                    sizeQtySums[index] = 0;
                });
    
                let totalQty = 0;
    
                // Iterate over each row of the same color
                rows.forEach(function($row, index) {
                    $row.find('.size_id').each(function(i) {
                        sizeQtySums[i] += parseInt($(this).val()) || 0;
                    });
    
                    totalQty += parseInt($row.find('.QTY').val()) || 0;
    
                    if (index !== 0) {
                        $row.remove(); // Remove all rows except the first one
                    }
                });
    
                // Set the summed quantities in the first row
                $firstRow.find('.size_id').each(function(index) {
                    $(this).val(sizeQtySums[index]);
                });
    
                $firstRow.find('.QTY').val(totalQty);
            }
        });
        
   });
   
    function EnableFeilds()
    {
       $('select,input').removeAttr('disabled');
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
                $('#mainstyle_id option[value="' + res.mainstyle_id + '"]').prop('selected', true).change();
                $('#substyle_id option[value="' + res.substyle_id + '"]').prop('selected', true).change();
                $('#sample_type_id option[value="' + res.sample_type_id + '"]').prop('selected', true).change();
                $('#dept_type_id option[value="' + res.dept_type_id + '"]').prop('selected', true).change();
                $('#sz_code option[value="' + res.sz_code + '"]').prop('selected', true).change(); 
                
                let colorGroups = {};

                // Group rows by color
                $('#footable_2 tbody tr').each(function() {
                    const $row = $(this);
                    const color = $row.find('.color').val();
            
                    if (!colorGroups[color]) {
                        colorGroups[color] = [];
                    }
            
                    colorGroups[color].push($row);
                });
            
                // Merge rows and sum quantities
                $.each(colorGroups, function(color, rows) {
                    if (rows.length > 1) {
                        let $firstRow = rows[0];
                        let sizeQtySums = [];
            
                        // Initialize the sizeQtySums array with zeros
                        $firstRow.find('.size_id').each(function(index) {
                            sizeQtySums[index] = 0;
                        });
            
                        let totalQty = 0;
            
                        // Iterate over each row of the same color
                        rows.forEach(function($row, index) {
                            $row.find('.size_id').each(function(i) {
                                sizeQtySums[i] += parseInt($(this).val()) || 0;
                            });
            
                            totalQty += parseInt($row.find('.QTY').val()) || 0;
            
                            if (index !== 0) {
                                $row.remove(); // Remove all rows except the first one
                            }
                        });
            
                        // Set the summed quantities in the first row
                        $firstRow.find('.size_id').each(function(index) {
                            $(this).val(sizeQtySums[index]);
                        });
            
                        $firstRow.find('.QTY').val(totalQty);
                    }
                });
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
        $(row).parent().parent('tr').find('input[name="order_qty[]"]').val(total_qty); 
    }
    
    function deleteRow(btn) 
    { 
      var row = btn.parentNode.parentNode;
      row.parentNode.removeChild(row);  
      recalcId1(); 
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
      table.append(lastRow); 
      recalcId1();
   }
</script>
@endsection