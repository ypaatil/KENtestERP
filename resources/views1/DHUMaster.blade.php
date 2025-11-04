@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">DHU Master</h4>
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
            @if(isset($dhuList))
                 <form action="{{ route('DHU.update',$dhuList) }}" method="POST">
                       @method('put')
                       @csrf 
                       <div class="row">
                          <div class="col-md-3">
                             <div class="mb-3">
                                <label for="dhu_date" class="form-label">DHU Date</label>
                                <input type="date" name="dhu_date" class="form-control" id="dhu_date" value="{{$dhuList->dhu_date}}" required  >
                                @foreach($counter_number as  $row)
                                <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $dhuList->c_code }}">
                                @endforeach
                                <input type="hidden" name="userId" value="{{ $dhuList->userId }}" class="form-control" id="formrow-email-input">
                                <input type="hidden" name="dhu_code" value="{{ $dhuList->dhu_code }}" class="form-control">
                             </div>
                          </div>
                          <div class="col-md-3">
                             <div class="mb-3">
                                <label for="po_date" class="form-label">Work Order No.</label>
                                <select name="vw_code" class="form-control select2" id="vw_code" required onchange="getVendorWorkOrderDetails(this.value)">
                                   <option value="">--Work Order No--</option>
                                   @foreach($VendorWorkOrderList as  $row)
                                   {
                                        <option value="{{ $row->vw_code }}"  {{ $row->vw_code == $dhuList->vw_code ? 'selected="selected"' : '' }}>{{ $row->vw_code }} ({{ $row->sales_order_no }})</option>
                                   }
                                   @endforeach
                                </select>
                             </div>
                          </div>
                          <div class="col-md-3">
                             <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Sales order No</label>
                                <input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="{{$dhuList->sales_order_no}}" readOnly>
                             </div>
                          </div>
                          <div class="col-md-3">
                             <div class="mb-3">
                                <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                                <select name="Ac_code" class="form-control select2" id="Ac_code"  disabled >
                                   <option value="">--Select Buyer--</option>
                                   @foreach($BuyerList as  $row)
                                        <option value="{{ $row->ac_code }}" {{ $row->ac_code == $dhuList->Ac_code ? 'selected="selected"' : '' }} >{{ $row->ac_name }}</option>
                                   @endforeach
                                </select>
                             </div>
                          </div>
                          <div class="col-md-3">
                             <div class="mb-3">
                                <label for="formrow-inputState" class="form-label">Main Style Category</label>
                                <select name="mainstyle_id" class="form-control " id="mainstyle_id"  onchange="getSubStyle(this.value)" disabled>
                                   <option value="">--Main Style--</option>
                                   @foreach($MainStyleList as  $row)
                                         <option value="{{ $row->mainstyle_id }}" {{ $row->mainstyle_id == $dhuList->mainstyle_id ? 'selected="selected"' : '' }}>{{ $row->mainstyle_name }}</option>
                                   @endforeach
                                </select>
                             </div>
                          </div>
                            <div class="col-md-2">
                               <div class="mb-3">
                                  <label for="formrow-inputState" class="form-label">Sub Style Category</label>
                                  <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" disabled>
                                     <option value="">--Sub Style--</option>
                                     @foreach($SubStyleList as  $row)
                                     {
                                     <option value="{{ $row->substyle_id }}"  {{ $row->substyle_id == $dhuList->substyle_id ? 'selected="selected"' : '' }}
                                        >{{ $row->substyle_name }}</option>
                                     }
                                     @endforeach
                                  </select>
                               </div>
                            </div>
                            <div class="col-md-2">
                               <div class="mb-3">
                                  <label for="fg_id" class="form-label">Style Name</label>
                                  <select name="fg_id" class="form-control" id="fg_id" disabled>
                                     <option value="">--Select Style--</option>
                                     @foreach($FGList as  $row)
                                     {
                                     <option value="{{ $row->fg_id }}" {{ $row->fg_id == $dhuList->fg_id ? 'selected="selected"' : '' }}
                                        >{{ $row->fg_name }}</option>
                                     }
                                     @endforeach
                                  </select>
                               </div>
                            </div>
                            <div class="col-md-2">
                               <div class="mb-3">
                                  <label for="formrow-email-input" class="form-label">Style No</label>
                                  <input type="text" name="style_no" class="form-control" id="style_no" value="" readOnly>
                               </div>
                            </div>
                            <div class="col-md-3">
                               <div class="mb-3">
                                  <label for="style_description" class="form-label">Style Description</label>
                                  <input type="text" name="style_description" class="form-control" id="style_description" value="" readOnly>
                               </div>
                            </div>
                            <div class="col-md-3">
                               <div class="mb-3">
                                  <label for="formrow-inputState" class="form-label">Vendor</label>
                                  <select name="vendorId" class="form-control" id="vendorId"  readonly>
                                     <option value="">--Select Vendor--</option>
                                     @foreach($Ledger as  $rowvendor)
                                     {
                                     <option value="{{ $rowvendor->ac_code }}" {{ $rowvendor->ac_code == $dhuList->vendorId ? 'selected="selected"' : '' }} >{{ $rowvendor->ac_name }}</option>
                                     }
                                     @endforeach
                                  </select>
                               </div>
                            </div>
                          <div class="col-md-3">
                             <div class="mb-3">
                                <label for="line_no" class="form-label">Line No.</label>
                                <select name="line_no" class="form-control" id="line_no" required  >
                                   <option value="">--Line--</option>
                                </select>
                             </div>
                          </div>
                       </div>
                       <div class="panel-group" id="accordion">
                          <div class="panel panel-default">
                             <div class="panel-heading">
                                <h4 class="panel-title">
                                   <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">DHU Entry</a>
                                </h4>
                             </div>
                             <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
                                <div class="panel-body">
                                   <div class="row">
                                      <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
                                      <div class="table-wrap">
                                         <div class="table-responsive">
                                            <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                                              <thead>
                                                <tr>
                                                  <th class="text-center">SrNo</th>
                                                  <th>DHU - Stitiching Opration Name</th>
                                                  <th>DHU - Stitiching Defect Type</th>
                                                  <th class="text-center">Defect Qty</th>
                                                  <th class="text-center">Action</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach($dhuDetailList as $details)
                                                  <tr>
                                                    <td class="text-center">
                                                        <input type="number" step="any" class="form-control" value="{{$no++}}" style="width: 60px;">
                                                    </td>
                                                    <td>
                                                        <select name="dhu_so_Id[]" class="form-control select2" onchange="GetDHUDefectList(this);">
                                                            <option>--Select--</option>
                                                            @foreach($DHUStichingOperationList as $op)
                                                                <option value="{{$op->dhu_so_Id}}" {{ $op->dhu_so_Id == $details->dhu_so_Id ? 'selected="selected"' : '' }}>{{$op->dhu_so_Name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="dhu_sdt_Id[]" class="form-control select2">
                                                            <option>--Select--</option>
                                                            @foreach($DHUDefectList as $de)
                                                            <option value="{{$de->dhu_sdt_Id}}" {{ $de->dhu_sdt_Id == $details->dhu_sdt_Id ? 'selected="selected"' : '' }}>{{$de->dhu_sdt_Name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number" step="any" class="form-control defect_qty" name="defect_qty[]" value="{{$details->defect_qty}}" onkeyup="calQty();">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="button" style="width:40px;"  value="+" class="Abutton btn btn-warning pull-left">
                                                        <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" style="margin-left: 15%;" >
                                                    </td>
                                                </tr>
                                                @endforeach
                                              </tbody>
                                            </table>
                                         </div>
                                      </div>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                 </div>
              </div>
           </div>
           </br>
           </br>
           <!-- end row -->
           <div class="row">
               <div class="col-md-2">
                   <div class="mb-3">
                   <label for="total_qty" class="form-label">Total Defect Qty</label>
                   <input type="number" step="any" name="total_defect_qty" class="form-control" id="total_defect_qty" value="{{$dhuList->total_defect_qty}}" required readOnly>
                   </div>
               </div>
           </div>
           <div class="col-sm-6">
               <label for="formrow-inputState" class="form-label"></label>
               <div class="form-group">
               <button type="submit" class="btn btn-primary w-md" onclick="EnabledFeild();" >Update</button>
               <a href="{{ Route('DHU.index') }}" class="btn btn-warning w-md">Cancel</a>
               </div>
           </div>
        </div>
        </form>
        @else
        <form action="{{route('DHU.store')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="dhu_date" class="form-label">DHU Date</label>
                        <input type="date" name="dhu_date" class="form-control" id="dhu_date" value="{{date('Y-m-d')}}" required  >
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Work Order No.</label>
                        <select name="vw_code" class="form-control select2" id="vw_code" required onchange="getVendorWorkOrderDetails(this.value)">
                           <option value="">--Work Order No--</option>
                           @foreach($VendorWorkOrderList as  $row)
                           {
                           <option value="{{ $row->vw_code }}">{{ $row->vw_code }} ({{ $row->sales_order_no }})</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Sales order No</label>
                        <input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" readOnly>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-control select2" id="Ac_code" disabled >
                            <option value="">--Select Buyer--</option>
                            @foreach($BuyerList as  $row)
                                <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                       <div class="mb-3">
                          <label for="mainstyle_id" class="form-label">Main Style Category</label>
                          <select name="mainstyle_id" class="form-control " id="mainstyle_id"  onchange="getSubStyle(this.value)" disabled>
                             <option value="">--Main Style--</option>
                             @foreach($MainStyleList as  $row)
                             {
                             <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                             }
                             @endforeach
                          </select>
                       </div>
                    </div>
                    <div class="col-md-2">
                       <div class="mb-3">
                          <label for="substyle_id" class="form-label">Sub Style Category</label>
                          <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" disabled>
                             <option value="">--Sub Style--</option>
                             @foreach($SubStyleList as  $row)
                             {
                             <option value="{{ $row->substyle_id }}"
                                >{{ $row->substyle_name }}</option>
                             }
                             @endforeach
                          </select>
                       </div>
                    </div>
                    <div class="col-md-2">
                       <div class="mb-3">
                          <label for="fg_id" class="form-label">Style Name</label>
                          <select name="fg_id" class="form-control" id="fg_id" disabled>
                             <option value="">--Select Style--</option>
                             @foreach($FGList as  $row)
                             {
                             <option value="{{ $row->fg_id }}"
                                >{{ $row->fg_name }}</option>
                             }
                             @endforeach
                          </select>
                       </div>
                    </div>
                    <div class="col-md-2">
                       <div class="mb-3">
                          <label for="formrow-email-input" class="form-label">Style No</label>
                          <input type="text" name="style_no" class="form-control" id="style_no" value="" readOnly>
                       </div>
                    </div>
                    <div class="col-md-3">
                       <div class="mb-3">
                          <label for="style_description" class="form-label">Style Description</label>
                          <input type="text" name="style_description" class="form-control" id="style_description" value="" readOnly>
                       </div>
                    </div>
                    <div class="col-md-3">
                       <div class="mb-3">
                          <label for="formrow-inputState" class="form-label">Vendor</label>
                          <select name="vendorId" class="form-control" id="vendorId" disabled>
                             <option value="">--Select Vendor--</option>
                             @foreach($Ledger as  $rowvendor)
                             {
                             <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
                             }
                             @endforeach
                          </select>
                       </div>
                    </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="line_no" class="form-label">Line No.</label>
                        <select name="line_no" class="form-control" id="line_no" required  >
                           <option value="">--Line--</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="panel-group" id="accordion">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h4 class="panel-title">
                           <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">DHU Entry</a>
                        </h4>
                     </div>
                     <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
                        <div class="panel-body">
                           <div class="row">
                              <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
                              <div class="table-wrap">
                                 <div class="table-responsive">
                                    <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                                      <thead>
                                        <tr>
                                          <th class="text-center">SrNo</th>
                                          <th>DHU - Stitiching Opration Name</th>
                                          <th>DHU - Stitiching Defect Type</th>
                                          <th class="text-center">Defect Qty</th>
                                          <th class="text-center">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                            <td class="text-center">
                                                <input type="number" step="any" class="form-control" value="1" style="width: 60px;">
                                            </td>
                                            <td>
                                                <select name="dhu_so_Id[]" class="form-control select2" onchange="GetDHUDefectList(this);">
                                                    <option>--Select--</option>
                                                    @foreach($DHUStichingOperationList as $op)
                                                    <option value="{{$op->dhu_so_Id}}">{{$op->dhu_so_Name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="dhu_sdt_Id[]" class="form-control select2">
                                                    <option>--Select--</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step="any" class="form-control defect_qty" name="defect_qty[]" onkeyup="calQty();">
                                            </td>
                                            <td class="text-center">
                                                <input type="button" style="width:40px;"  value="+" class="Abutton btn btn-warning pull-left">
                                                <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" style="margin-left: 15%;" >
                                            </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                         </div>
                                      </div>
                                   </div>
                                </div>
                             </div>
                          </div>
                        </div>
                     </div>
                  </div>
               </div>
               </br>
               </br>
               <!-- end row -->
               <div class="row">
                   <div class="col-md-2">
                       <div class="mb-3">
                       <label for="total_qty" class="form-label">Total Defect Qty</label>
                       <input type="number" step="any" name="total_defect_qty" class="form-control" id="total_defect_qty" required readOnly>
                       </div>
                   </div>
               </div>
               <div class="col-sm-6">
                   <label for="formrow-inputState" class="form-label"></label>
                   <div class="form-group">
                   <button type="submit" class="btn btn-primary w-md" onclick="EnabledFeild()" >Submit</button>
                   <a href="{{ Route('DHU.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- end row -->
<script>
    @if(isset($dhuList))
            getVendorWorkOrderDetails($('#vw_code').val());
            setTimeout(function() {
                    $("#line_no").val({{$dhuList->line_no}}).attr('selected','selected');
            }, 500);
    @endif

   function EnabledFeild()
   {
        $('select').removeAttr('disabled');    
   }
   
   function getVendorWorkOrderDetails(vw_code)
   {
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('StitchingInhouseDetails') }}",
           data:{'vw_code':vw_code},
           success: function(data)
           {
               $("select[name='Ac_code']").find('option[value="'+data[0]['Ac_code']+'"]').attr('selected','selected').change();
               $("#vendorId").val(data[0]['vendorId']);
               $("#sales_order_no").val(data[0]['sales_order_no']);
               $("#mainstyle_id").val(data[0]['mainstyle_id']);
               $("#substyle_id").val(data[0]['substyle_id']);
               $("#style_no").val(data[0]['style_no']);
               $("#fg_id").val(data[0]['fg_id']);
               $("#style_description").val(data[0]['style_description']);
               
               $.ajax({
                dataType: "json",
                url: "{{ route('GetLineList') }}",
                data:{'Ac_code':data[0]['vendorId']},
                success: function(data){
                $("#line_no").html(data.html);
                }
               });
                @if(!isset($dhuList))
                   $.ajax({
                        dataType: "json",
                        url: "{{ route('GetDHUMainStyleList') }}",
                        data:{'mainstyle_id':data[0]['mainstyle_id']},
                        success: function(data)
                        {
                            //$("#footable_2 > tbody > tr > td select[name='dhu_sdt_Id[]']").html(data.html);
                             $("#footable_2 > tbody > tr > td select[name='dhu_so_Id[]']").html(data.html1);
                        }
                  });
                @endif
            }
        });
   }

    calQty();
    function calQty()
    {
        var total = 0;
        var dq = $(".defect_qty");  
        $.each(dq, function (i, currProgram) 
        {
            total = parseFloat(total) + parseFloat($(this).val()); 
        });
        $('#total_defect_qty').val(total);
    }
  
   $(document).on('click', '.Abutton', function () 
    {
      $('.select2').select2('destroy'); 
       var $tr = $(this).closest('tr');
       var $lastTr = $tr.closest('table').find('tr:last');
       $lastTr.find('.select2-select').select2('destroy');
       var $clone = $lastTr.clone();
       $clone.find('td').each(function() {
           var el = $(this).find(':first-child');
           var id = el.attr('id') || null;
           if (id) {
               var i = id.substr(id.length - 1);
               var prefix = id.substr(0, (id.length - 1));
               el.attr('id', prefix + (+i + 1));
           }
       });
       $tr.closest('tbody').append($clone);
       $lastTr.find('.select2-select').select2();
       $clone.find('td select[name="dhu_so_Id[]"]').attr("onchange","GetDHUDefectList(this)");
       $clone.find('td select[name="dhu_sdt_Id[]"]').html("");
       $clone.find('.defect_qty').val(0);
       $clone.find('.select2-select').select2();
       $('.select2').select2(); 
       recalcIdcone();
    });
   
    function deleteRowcone(btn) 
    {
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       recalcIdcone();
       calQty();
    }
    
    function recalcIdcone()
    {
       $.each($("#footable_2 tr"),function (i,el)
       {
            $(this).find("td:first input").val(i); 
       })
    }
   
    function getSubStyle(val) 
    {	//alert(val);
        $.ajax({
        type: "GET",
        url: "{{ route('SubStyleList') }}",
        data:'mainstyle_id='+val,
        success: function(data){
        $("#substyle_id").html(data.html);
        }
        });
    }   
     
    function getStyle(val) 
    {	//alert(val);
    
       $.ajax({
        type: "GET",
        url: "{{ route('StyleList') }}",
        data:{'substyle_id':val, },
        success: function(data){
        $("#fg_id").html(data.html);
        }
        });
    }  
    
    function GetDHUDefectList(row)
    {
        $(row).parent().parent('tr').find("td select[name='dhu_sdt_Id[]']").html("");; 
        var dhu_so_Id = $(row).val();
        $.ajax({
                dataType: "json",
                url: "{{ route('GetDHUDefectList') }}",
                data:{'dhu_so_Id':dhu_so_Id},
                success: function(data)
                {
                    $(row).parent().parent('tr').find("td select[name='dhu_sdt_Id[]']").html(data.html); 
                }
          });
    }

</script>
@endsection