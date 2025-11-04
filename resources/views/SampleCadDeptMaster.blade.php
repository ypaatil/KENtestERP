@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sample CAD Department</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sample CAD Department</li>
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
            <h4 class="card-title mb-4">Sample CAD Department</h4>
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
            <form action="{{route('SampleCadDept.store')}}" method="POST" id="frmData"  enctype="multipart/form-data"> 
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sample_cad_dept_date" class="form-label">Date</label>
                        <input type="date" name="sample_cad_dept_date" class="form-control" id="sample_cad_dept_date" value="{{date('Y-m-d')}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN Code</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleIndentMasterData();" required>
                           <option value="0">--Select--</option>
                           @foreach($SampleIndentMasterList as  $row) 
                                <option value="{{ $row->sample_indent_code }}">{{ $row->sample_indent_code }}</option>
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
                                <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
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
                                <option value="{{ $row->brand_id }}">{{ $row->brand_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Main Style</label>
                        <select name="mainstyle_id" class="form-select" id="mainstyle_id"  disabled>
                           <option value="0">--- Select ---</option>
                           @foreach($MainStylelist as  $row) 
                                <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select" id="substyle_id"  disabled>
                           <option value="0">--- Select ---</option>
                           @foreach($SubStylelist as  $row) 
                                <option value="{{ $row->substyle_id }}">{{ $row->substyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" readonly>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="" readonly> 
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_type_id" class="form-label">Sample Type</label>
                        <select name="sample_type_id" class="form-select select2" id="sample_type_id"  disabled >
                           <option value="0">--- Select ---</option>
                           @foreach($SampleTypelist as  $row) 
                           <option value="{{ $row->sample_type_id  }}">{{ $row->sample_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select " id="dept_type_id"  disabled >
                           <option value="">-- Select --</option>
                           @foreach($DepartmentTypelist as  $row)
                                <option value="{{ $row->dept_type_id  }}">{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select " id="sz_code"  disabled>
                           <option value="0">-- Select --</option>
                           @foreach($SizeGroupList as  $row)
                              <option value="{{ $row->sz_code }}">{{ $row->sz_name }}</option>
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
               <div class="table-wrap">
                  <div class="table-responsive" id="order_qty">  
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
                                <select name="material_received_status_id[]" class="form-select B1"  onchange="CheckStatus();"  id="material_received_status_id"  style="width:250px;" required>
                                   <option value="0">-- Select --</option>
                                   @foreach($MaterialReceivedList as  $row)
                                        <option value="{{ $row->material_received_status_id  }}">{{ $row->material_received_status_name }}</option>
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
                                <select name="material_received_status_id[]" class="form-select B2"   onchange="CheckStatus();"   id="material_received_status_id"  style="width:250px;" required>
                                   <option value="">-- Select --</option>
                                   @foreach($MaterialReceivedList as  $row)
                                        <option value="{{ $row->material_received_status_id  }}">{{ $row->material_received_status_name }}</option>
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
                                <select name="material_received_status_id[]" class="form-select B3"   onchange="CheckStatus();"   id="material_received_status_id"  style="width:250px;" required>
                                   <option value="0">-- Select --</option>
                                   @foreach($MaterialReceivedList as  $row)
                                        <option value="{{ $row->material_received_status_id  }}">{{ $row->material_received_status_name }}</option>
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
                        <input type="date" name="material_avaliable_date" class="form-control" id="material_avaliable_date" value="" readonly>
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-success w-md" id="Submit" onclick="EnableFeilds();">Save</button>
                  <a href="{{ Route('SampleCadDept.index') }}" class="btn btn-warning w-md">Cancel</a>
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