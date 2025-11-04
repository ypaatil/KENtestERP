@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Spare Item Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Spare Item Master</li>
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
            @if(isset($items))
            <form action="{{ route('SpareItem.update',$items) }}" method="POST" enctype="multipart/form-data">
               @method('put')
               @csrf   
               <h4 class="card-title mb-4">Item Code: {{ $items->spare_item_code }}</h4>
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
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <input type="hidden" name="item_name" class="form-control" id="spare_item_code" value="{{ $items->spare_item_code }}">
                        <input type="hidden" name="userId" class="form-control" id="userId" value="{{Session::get('userId')}}">
                        <label for="formrow-inputState" class="form-label">Category</label>
                        <select name="cat_id" class="form-select select2" id="cat_id" onchange="getClassList(this.value);" disabled>
                           @foreach($Categorylist as  $row) 
                           <option value="{{ $row->cat_id }}"
                           {{ $row->cat_id == $items->cat_id ? 'selected="selected"' : '' }}
                           >{{ $row->cat_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="class_id" class="form-label">Classification</label>
                        <select name="class_id" class="form-select select2" id="class_id" required>
                           <option value="">-Classification-</option>
                           @foreach($Classificationlist as  $row) 
                           <option value="{{ $row->class_id }}"
                           {{ $row->class_id == $items->class_id ? 'selected="selected"' : '' }}
                           >{{ $row->class_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="machinetype_id" class="form-label">Machine Type</label>
                        <select name="machinetype_id" class="form-select select2" id="machinetype_id " required >
                           <option value="">-Select-</option>
                           @foreach($MachineTypelist as  $row) 
                               <option value="{{ $row->machinetype_id }}" {{ $row->machinetype_id == $items->machinetype_id ? 'selected="selected"' : '' }} >{{ $row->machinetype_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mc_make_Id" class="form-label">Machine Make</label>
                        <select name="mc_make_Id" class="form-select select2" id="mc_make_Id " required >
                           <option value="">-Select-</option>
                           @foreach($MachineMakelist as  $row) 
                               <option value="{{ $row->mc_make_Id }}" {{ $row->mc_make_Id == $items->mc_make_Id ? 'selected="selected"' : '' }} >{{ $row->machine_make_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mc_model_id" class="form-label">Machine Model</label>
                        <select name="mc_model_id" class="form-select select2" id="mc_model_id " required >
                           <option value="">-Select-</option>
                           @foreach($MachineModellist as  $row) 
                               <option value="{{ $row->mc_model_id }}" {{ $row->mc_model_id == $items->mc_model_id ? 'selected="selected"' : '' }} >{{ $row->mc_model_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="item_name" class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" id="item_name" value="{{ $items->item_name }}"  oninput="restrictQuotes(this)" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="item_description" class="form-label">Item Description</label>
                        <input type="text" name="item_description" class="form-control" id="item_description" value="{{ $items->item_description }}"  oninput="restrictQuotes(this)">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Part Name/ Dimensions</label>
                        <input type="text" name="dimension" class="form-control" id="formrow-email-input" value="{{ $items->dimension }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="unit_id" class="form-label">UOM</label>
                        <select name="unit_id" class="form-select select2" id="unit_id" required>
                           <option value="">--UOM--</option>
                           @foreach($UnitList as  $row) 
                           <option value="{{ $row->unit_id }}"
                           {{ $row->unit_id == $items->unit_id ? 'selected="selected"' : '' }}
                           >{{ $row->unit_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cgst_per" class="form-label">CGST %</label>
                        <input  type="number" step="any"  name="cgst_per" class="form-control" id="cgst_per" value="{{ $items->cgst_per }}" onkeyup="change(this.value);" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sgst_per" class="form-label">SGST %</label>
                        <input  type="number" step="any"  name="sgst_per" class="form-control" id="sgst_per" value="{{ $items->sgst_per }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="igst_per" class="form-label">IGST %</label>
                        <input type="number" step="any"  name="igst_per" class="form-control" id="igst_per" value="{{ $items->igst_per }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="hsn_code" class="form-label">HSN Code</label>
                        <input type="number" step="any" name="hsn_code" class="form-control" id="hsn_code" value="{{ $items->hsn_code }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="min_qty" class="form-label">Minimum Qty</label>
                        <input type="number" step="any" name="min_qty" class="form-control" id="min_qty" value="{{ $items->min_qty }}">
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();">Submit</button>
                  <a href="{{ Route('SpareItem.index') }}" class="btn btn-warning w-md">Cancel</a>
               </div>
            </form>
            @else
            <form action="{{route('SpareItem.store')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <input type="hidden" name="userId" class="form-control" id="userId" value="{{Session::get('userId')}}">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Category</label>
                        <select name="cat_id" class="form-select select2" id="cat_id" onchange="getClassList(this.value);" disabled>
                           @foreach($Categorylist as  $row)
                                <option value="{{ $row->cat_id }}">{{ $row->cat_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="class_id" class="form-label">Classification</label>
                        <select name="class_id" class="form-select select2" id="class_id " required >
                           <option value="">-Classification-</option>
                           @foreach($Classificationlist as  $row) 
                               <option value="{{ $row->class_id }}" {{ $row->class_id == 159 ? 'selected="selected"' : '' }} >{{ $row->class_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="machinetype_id" class="form-label">Machine Type</label>
                        <select name="machinetype_id" class="form-select select2" id="machinetype_id " required >
                           <option value="">-Select-</option>
                           @foreach($MachineTypelist as  $row) 
                               <option value="{{ $row->machinetype_id }}">{{ $row->machinetype_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mc_make_Id" class="form-label">Machine Make</label>
                        <select name="mc_make_Id" class="form-select select2" id="mc_make_Id " required >
                           <option value="">-Select-</option>
                           @foreach($MachineMakelist as  $row) 
                               <option value="{{ $row->mc_make_Id }}">{{ $row->machine_make_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mc_model_id" class="form-label">Machine Model</label>
                        <select name="mc_model_id" class="form-select select2" id="mc_model_id " required >
                           <option value="">-Select-</option>
                           @foreach($MachineModellist as  $row) 
                               <option value="{{ $row->mc_model_id }}">{{ $row->mc_model_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3" id="itemshow">
                        <label for="item_name" class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" id="item_name"  oninput="restrictQuotes(this)" required>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="item_description" class="form-label">Item Description</label>
                        <input type="text" name="item_description" class="form-control" id="item_description"  oninput="restrictQuotes(this)">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dimension" class="form-label">Part Name/ Dimensions</label>
                        <input type="text" name="dimension" class="form-control" id="dimension" value="" required>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="unit_id" class="form-label">UOM</label>
                        <select name="unit_id" class="form-select select2" id="unit_id" required>
                           <option value="">--UOM--</option>
                           @foreach($UnitList as  $row) 
                                <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
               </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cgst_per" class="form-label">CGST %</label>
                        <input type="number" step="any"  name="cgst_per" class="form-control" id="cgst_per" onkeyup="change(this.value)" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sgst_per" class="form-label">SGST %</label>
                        <input  type="number" step="any"  name="sgst_per" class="form-control" id="sgst_per" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="igst_per" class="form-label">IGST %</label>
                        <input type="number" step="any"  name="igst_per" class="form-control" id="igst_per" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="hsn_code" class="form-label">HSN Code</label>
                        <input type="number" step="any" name="hsn_code" class="form-control" id="hsn_code" required>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="min_qty" class="form-label">Minimum Qty</label>
                        <input type="number" step="any" name="min_qty" class="form-control" id="min_qty" value="0">
                     </div>
                  </div>
               </div>
               <div>
                    <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();">Submit</button>
                    <a href="{{ Route('SpareItem.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script>

   function EnableFields()
   {
        $("select").prop('disabled', false);
        $("input").prop('disabled', false);
   }
   
   function restrictQuotes(input) 
   {
       input.value = input.value.replace(/['"]/g, '');
   }
   
   function change(cgst_per)
   {
       $("#sgst_per").val(cgst_per);
       $("#igst_per").val(cgst_per*2);
   }
   
   
   function itemExist(val) 
   {	
       
       $.ajax({
           type: "GET",
           url: "{{ route('itemexist') }}",
           data:'item_name='+val,
           success: function(data)
           {
                $("#itemshow").html(data.html);
           }
       });
   }   
   
   
   function getClassList(val) 
   { 
       $.ajax({
           type: "GET",
           url: "{{ route('ClassList') }}",
           data:'cat_id='+val,
           success: function(data)
           {
               $("#class_id").html(data.html);
           }
       });
   }  
   
</script>
<!-- end row -->
@endsection