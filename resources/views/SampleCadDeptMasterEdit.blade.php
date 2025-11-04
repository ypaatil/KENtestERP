@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sample CAD Department Edit</h4>
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
            @if(isset($SampleCadDept))
            <form action="{{ route('SampleCadDept.update',$SampleCadDept) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row"> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_cad_dept_date" class="form-label">Date</label>
                        <input type="date" name="sample_cad_dept_date" class="form-control" id="sample_cad_dept_date" value="{{ $SampleCadDept->sample_cad_dept_date}}" readonly>
                        <input type="hidden" name="sample_cad_dept_id" class="form-control" id="sample_cad_dept_id" value="{{ $SampleCadDept->sample_cad_dept_id}}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN Code</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleCadDeptMasterData();" disabled>
                           <option value="0">--Select--</option>
                           @foreach($SampleIndentList as  $row) 
                                <option value="{{ $row->sample_indent_code }}" {{ $row->sample_indent_code == $SampleCadDept->sample_indent_code ? 'selected="selected"' : '' }}>{{ $row->sample_indent_code }}</option>
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
                                <option value="{{ $row->ac_code }}" {{ $row->ac_code == $SampleCadDept->Ac_code ? 'selected="selected"' : '' }}  >{{ $row->ac_name }}</option>
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
                                <option value="{{ $row->brand_id }}" {{ $row->brand_id == $SampleCadDept->brand_id ? 'selected="selected"' : '' }}  >{{ $row->brand_name }}</option>
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
                                <option value="{{ $row->mainstyle_id }}"  {{ $row->mainstyle_id == $SampleCadDept->mainstyle_id ? 'selected="selected"' : '' }} >{{ $row->mainstyle_name }}</option>
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
                                <option value="{{ $row->substyle_id }}" {{ $row->substyle_id == $SampleCadDept->substyle_id ? 'selected="selected"' : '' }}  >{{ $row->substyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{ $SampleCadDept->style_description}}" readonly>
                        <input type="hidden" name="userId" value="{{ $SampleCadDept->userId}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="{{ $SampleCadDept->sam}}" readonly> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_type_id" class="form-label">Sample Type</label>
                        <select name="sample_type_id" class="form-select select2" id="sample_type_id" disabled onchange="GetDepartmentType();">
                           <option value="0">--- Select ---</option>
                           @foreach($SampleTypelist as  $row) 
                           <option value="{{ $row->sample_type_id  }}" {{ $row->sample_type_id == $SampleCadDept->sample_type_id ? 'selected="selected"' : '' }}  >{{ $row->sample_type_name }}</option>
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
                                <option value="{{ $row->dept_type_id  }}" {{ $row->dept_type_id == $SampleCadDept->dept_type_id ? 'selected="selected"' : '' }}  >{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select " id="sz_code" onchange="SizeSampleCadDeptList(this.value);" disabled>
                           <option value="0">-- Select --</option>
                           @foreach($SizeGroupList as  $row)
                              <option value="{{ $row->sz_code }}" {{ $row->sz_code == $SampleCadDept->sz_code ? 'selected="selected"' : '' }}  >{{ $row->sz_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="delivery_date" class="form-label">Committed To ETD</label>
                        <input type="date" name="delivery_date" class="form-control" id="delivery_date" value="{{date('Y-m-d', strtotime('+3 days'))}}">
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
                            <tbody id="SampleCadDept">
                                @if(count($SampleIndentDetailList) > 0)
                                    @php $no = 1; $n = 1; @endphp
                                    @foreach($SampleIndentDetailList as $List)
                                        <tr>
                                            <td><input type="text" name="id[]" value="{{$no}}" id="id{{$no}}" style="width:50px;" readonly /></td>
                                            <td><input type="text" name="color[]" class="color" value="{{$List->color}}" id="color{{$no}}" style="width:150px; height:30px;" readonly /></td>
                                            @php   
                                                $n = 1;  
                                                $SizeQtyList = explode(',', $List->size_qty_array); 
                                            @endphp
                                            @foreach($SizeQtyList as $key => $szQty)
                                                <td><input type="number" name="s{{$n}}[]" class="size_id" value="{{$szQty}}" id="size_id{{$no}}_{{$n}}" style="width:80px; height:30px;" onchange="GetSizeQtyArray(this);" readonly /></td>
                                                @php $n++; @endphp
                                            @endforeach
                                            <td>
                                                <input type="number" name="order_qty[]" class="QTY" value="{{$List->size_qty_total}}" id="size_qty_total{{$no}}" style="width:80px; height:30px;" readonly />
                                                <input type="hidden" name="size_array[]" class="size_array" value="{{$List->size_array}}" id="size_array{{$no}}" />
                                                <input type="hidden" name="size_qty_array[]" class="size_qty_array" value="{{$List->size_qty_array}}" id="size_qty_array{{$no}}" />
                                            </td>
                                        </tr>
                                        @php $no++; @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                   </div> 
                </div> 
               <div class="table-responsive">
                  <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                     <thead>
                        <tr>
                           <th>Bom Types</th>
                           <th>Material Received Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr> 
                           <td>
                               Fabric
                               <input type="hidden" name="bom_type_id[]" value="1" id="bom_type_id" style="width:50px;"/>
                           </td>
                           <td>
                               @php
                                    $detail1 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 1 AND sample_cad_dept_id=".$SampleCadDept->sample_cad_dept_id);
                                    
                                    $m1 = isset($detail1[0]->material_received_status_id) ? $detail1[0]->material_received_status_id : 0;
                               @endphp
                                <select name="material_received_status_id[]" class="form-select B1" onchange="CheckStatus();" id="material_received_status_id"  style="width:250px;" required>
                                   <option value="0">-- Select --</option>
                                   @foreach($MaterialReceivedList as  $row)
                                        <option value="{{ $row->material_received_status_id  }}"  {{ $row->material_received_status_id == $m1 ? 'selected="selected"' : '' }}  >{{ $row->material_received_status_name }}</option>
                                   @endforeach
                                </select>
                           </td>
                        </tr>
                        <tr> 
                           <td>
                               Sewing Trims
                               <input type="hidden" name="bom_type_id[]" value="2" id="bom_type_id" style="width:50px;"/>
                           </td>
                           <td>
                               @php
                                    $detail2 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 2 AND sample_cad_dept_id=".$SampleCadDept->sample_cad_dept_id);
                                    
                                    $m2 = isset($detail2[0]->material_received_status_id) ? $detail2[0]->material_received_status_id : 0;
                               @endphp
                                <select name="material_received_status_id[]" class="form-select B2" onchange="CheckStatus();" id="material_received_status_id"  style="width:250px;"  required>
                                   <option value="0">-- Select --</option>
                                   @foreach($MaterialReceivedList as  $row)
                                        <option value="{{ $row->material_received_status_id  }}"  {{ $row->material_received_status_id == $m2 ? 'selected="selected"' : '' }}  >{{ $row->material_received_status_name }}</option>
                                   @endforeach
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
                                    $detail3 = DB::SELECT("SELECT * FROM sample_cad_dept_detail WHERE bom_type_id = 3 AND sample_cad_dept_id=".$SampleCadDept->sample_cad_dept_id);
                                    
                                    $m3 = isset($detail3[0]->material_received_status_id) ? $detail3[0]->material_received_status_id : 0;
                               @endphp
                                <select name="material_received_status_id[]" class="form-select B3"  onchange="CheckStatus();"  id="material_received_status_id"  style="width:250px;"  required>
                                   <option value="0">-- Select --</option>
                                   @foreach($MaterialReceivedList as  $row)
                                        <option value="{{ $row->material_received_status_id  }}"  {{ $row->material_received_status_id == $m3 ? 'selected="selected"' : '' }} >{{ $row->material_received_status_name }}</option>
                                   @endforeach
                                </select>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="material_avaliable_date" class="form-label">Material Available Date</label>
                        <input type="date" name="material_avaliable_date" class="form-control" id="material_avaliable_date" value="{{$SampleCadDept->material_avaliable_date}}" readonly>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFeilds();">Submit</button>
                     <a href="{{ Route('SampleCadDept.index') }}" class="btn btn-warning w-md">Cancel</a>
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
    function CheckStatus()
    {
        var f = $(".B1").val();
        var s = $(".B2").val();
        var p = $(".B3").val();
        
        if(parseInt(f) == 1 && parseInt(s) == 1 && parseInt(p) == 1)
        {
            $("#material_avaliable_date").removeAttr('readonly');     
            $("#material_avaliable_date").attr('required', true);
        }
        else
        {
            $("#material_avaliable_date").attr('readonly', true);            
            $("#material_avaliable_date").attr('required', false);
            $("#material_avaliable_date").val("");
        }
    }
    
    $(document).ready(function() {
      CheckStatus();
      $('#frmData').submit(function() {
          $('#Submit').prop('disabled', true);
      }); 
    });
   
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
             url: "{{ route('GetSampleIndentMasterData') }}",
             data:{'sample_indent_code':sample_indent_code},
             success: function(data)
             { 
                var res = data.MasterData;
                $("#order_qty").html(data.DetailHtml);
                $("#style_description").val(res.style_description);
                $("#sam").val(res.sam); 
                $('#Ac_code option[value="' + res.Ac_code + '"]').prop('selected', true).change();
                $('#brand_id option[value="' + res.brand_id + '"]').prop('selected', true).change();
                $('#mainstyle_id option[value="' + res.mainstyle_id + '"]').prop('selected', true).change();
                $('#substyle_id option[value="' + res.substyle_id + '"]').prop('selected', true).change();
                $('#sample_type_id option[value="' + res.sample_type_id + '"]').prop('selected', true).change();
                $('#dept_type_id option[value="' + res.dept_type_id + '"]').prop('selected', true).change();
                $('#sz_code option[value="' + res.sz_code + '"]').prop('selected', true).change();
             }
        });
    }
   
     
</script>
@endsection