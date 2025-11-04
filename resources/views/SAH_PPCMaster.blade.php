@extends('layouts.master') 
@section('content')
<style>
    .select2 
    {
        width: 140px!important;
    }
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">SAH-PPC Master</h4>
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
            @if(isset($SAHPPCEditData))
              <form action="{{ route('SAH_PPCMasterUpdate',["id" => $SAHPPCEditData->sah_ppc_master_id] ) }}" method="POST" enctype="multipart/form-data" id="frmData">
                  @method('put')
                   @csrf 
                   <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
                   <div class="row">
                      <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                      <div class="table-wrap">
                         <div class="table-responsive">
                            <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                               <thead>
                                  <tr>
                                     <th class="text-center">Vendor Name</th>
                                     <th class="text-center">Line No</th>
                                     <th class="text-center">Sales Order</th>
                                     <th class="text-center">SAM</th>
                                     <th class="text-center">No. Of Days</th>
                                     <th class="text-center">Total Available Minutes</th>
                                     <th class="text-center">Month</th>
                                     <th class="text-center">Month Value</th>
                                     <th class="text-center">Booked Min</th>
                                     <th class="text-center">Open Min</th> 
                                  </tr>
                               </thead>
                               <tbody id="endData">
                                  <tr>
                                     <td>
                                        <select name="vendorId[]"  id="vendorId" class="select2" style="width:250px;" onchange="GetLineList(this);">
                                           <option value="">--Vendors--</option>
                                           @foreach($Ledger as  $rowvendor)
                                           {
                                           <option value="{{ $rowvendor->ac_code }}"  {{ $rowvendor->ac_code == $SAHPPCEditData->vendorId ? 'selected="selected"' : '' }} >{{ $rowvendor->ac_name }}</option>
                                           }
                                           @endforeach
                                        </select>
                                     </td>
                                     
                                     <td>
                                        <select name="line_id[]"  id="line_id" class="select2" style="width:100px;">
                                           <option value="">--Line No.--</option>
                                           @foreach($LineList as  $rowline)
                                           {
                                           <option value="{{ $rowline->line_id }}" {{ $rowline->line_id == $SAHPPCEditData->line_id ? 'selected="selected"' : '' }}  >{{ $rowline->line_name }}</option>
                                           }
                                           @endforeach
                                           @php 
                                                $class= "startDate";
                                           @endphp
                                        </select>
                                     </td>
                                     <td>
                                        <select name="sales_order_no[]"  id="sales_order_no" class="select2" style="width:100px;" onchange="GetPPCData(this,this.value);" >
                                           <option value="">--Sales Order No.--</option>
                                           @foreach($SalesList as  $rowSale)
                                           {
                                           <option value="{{ $rowSale->sales_order_no }}" {{ $rowSale->sales_order_no == $SAHPPCEditData->sales_order_no ? 'selected="selected"' : '' }} >{{ $rowSale->sales_order_no }}</option>
                                           }
                                           @endforeach
                                        </select>
                                     </td>
                                     <td><input type="number" min="0" step="any" name="sam[]" value="{{$SAHPPCEditData->sam}}" class="form-control" id="sam" style="width:120px;" readonly /></td>
                                     <td><input type="number" min="0" step="any" name="noOfDays[]" value="{{$SAHPPCEditData->noOfDays}}" class="form-control" id="noOfDays" onchange="GetTotalAvaliableMin(this,this.value);" style="width:120px;" /></td>
                                     <td><input type="number" min="0" step="any" name="totalAvaliableMin[]" value="{{$SAHPPCEditData->totalAvaliableMin}}" class="form-control" id="totalAvaliableMin" style="width:120px;" readonly /></td>
                                     <td>
                                        <select name="month[]"  id="month" class="select2" style="width:100px;" >
                                           <option value="">--Month--</option>
                                           @foreach($getMonth as  $rowMonth => $key)
                                           {
                                           
                                           <option value="{{ $rowMonth }}"  {{ $rowMonth == $SAHPPCEditData->month ? 'selected="selected"' : '' }} >{{$key}}</option>
                                           }
                                           @endforeach
                                        </select>
                                     </td>
                                     <td><input type="number" min="0" step="any" name="monthValue[]" value="{{$SAHPPCEditData->monthValue}}" class="form-control" id="monthValue" onchange="GetMinData(this,this.value);" style="width:120px;" /></td>
                                     <td><input type="number" min="0" step="any" name="bookedMin[]" value="{{$SAHPPCEditData->bookedMin}}" class="form-control" id="bookedMin" style="width:120px;" readonly /></td>
                                     <td><input type="number" min="0" step="any" name="openMin[]" value="{{$SAHPPCEditData->openMin}}" class="form-control" id="openMin" style="width:120px;" readonly /></td> 
                                    
                                </tr>
                               </tbody>
                            </table>
                         </div>
                      </div>
                   </div>
                   <div class="col-sm-6">
                      <label for="formrow-inputState" class="form-label"></label>
                      <div class="form-group">
                         <button type="submit" class="btn btn-primary w-md">Submit</button>
                         <a href="{{ Route('rptSAH_PPC') }}" class="btn btn-info w-md">Go to SAH-PPC Report</a>
                         <a href="{{ Route('PPCMaster.index') }}" class="btn btn-warning w-md">Go to PPC Master</a>
                      </div>
             </div>
             </form>
            
            @else
            <form action="{{route('SAHPPC')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
               <div class="row">
                  <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>
                                 <th class="text-center">Vendor Name</th>
                                 <th class="text-center">Line No</th>
                                 <th class="text-center">Sales Order</th>
                                 <th class="text-center">SAM</th>
                                 <th class="text-center">No. Of Days</th>
                                 <th class="text-center">Total Available Minutes</th>
                                 <th class="text-center">Month</th>
                                 <th class="text-center">Month Value</th>
                                 <th class="text-center">Booked Min</th>
                                 <th class="text-center">Open Min</th>
                                 <th class="text-center">Action</th>  
                              </tr>
                           </thead>
                           <tbody id="endData">
                              <tr>
                                 <td>
                                    <select name="vendorId[]"  id="vendorId" class="select2" style="width:250px;" onchange="GetLineList(this);">
                                       <option value="">--Vendors--</option>
                                       @foreach($Ledger as  $rowvendor)
                                       {
                                       <option value="{{ $rowvendor->ac_code }}" >{{ $rowvendor->ac_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 
                                 <td>
                                    <select name="line_id[]"  id="line_id" class="select2" style="width:100px;">
                                       <option value="">--Line No.--</option>
                                       @foreach($LineList as  $rowline)
                                       {
                                       <option value="{{ $rowline->line_id }}">{{ $rowline->line_name }}</option>
                                       }
                                       @endforeach
                                       @php 
                                            $class= "startDate";
                                       @endphp
                                    </select>
                                 </td>
                                 <td>
                                    <select name="sales_order_no[]"  id="sales_order_no" class="select2" style="width:100px;" onchange="GetPPCData(this,this.value);" >
                                       <option value="">--Sales Order No.--</option>
                                       @foreach($SalesList as  $rowSale)
                                       {
                                       <option value="{{ $rowSale->sales_order_no }}">{{ $rowSale->sales_order_no }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="number" min="0" step="any" name="sam[]" value="" class="form-control" id="sam" style="width:120px;" readonly /></td>
                                 <td><input type="number" min="0" step="any" name="noOfDays[]" value="" class="form-control" id="noOfDays" onchange="GetTotalAvaliableMin(this,this.value);" style="width:120px;" /></td>
                                 <td><input type="number" min="0" step="any" name="totalAvaliableMin[]" value="" class="form-control" id="totalAvaliableMin" style="width:120px;" readonly /></td>
                                 <td>
                                    <select name="month[]"  id="month" class="select2" style="width:100px;" >
                                       <option value="">--Month--</option>
                                       @foreach($getMonth as  $rowMonth => $key)
                                       {
                                       <option value="{{ $rowMonth }}">{{$key}}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="number" min="0" step="any" name="monthValue[]" value="" class="form-control" id="monthValue" onchange="GetMinData(this,this.value);" style="width:120px;" /></td>
                                 <td><input type="number" min="0" step="any" name="bookedMin[]" value="" class="form-control" id="bookedMin" style="width:120px;" readonly /></td>
                                 <td><input type="number" min="0" step="any" name="openMin[]" value="" class="form-control" id="openMin" style="width:120px;" readonly /></td> 
                                 <td>
                                     <input type="button" class="btn btn-warning pull-left" onclick="insertcone(this);" value="+" > 
                                     <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" >
                                 </td>
                            </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit">Submit</button>
                     <a href="{{ Route('rptSAH_PPC') }}" class="btn btn-info w-md">Go to SAH-PPC Report</a>
                     <a href="{{ Route('PPCMaster.index') }}" class="btn btn-warning w-md">Go to PPC Master</a>
                  </div>
         </div>
         </form>
        @endif
         <div class="row">
           <div class="col-12">
              <div class="card">
                 <div class="card-body">
                    <div class="table-responsive">
                       <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                          <thead>
                             <th>Vendor Name</th>
                             <th class="text-center">Line No.</th>
                             <th class="text-center">Order No.</th>
                             <th class="text-center">SAM</th>
                             <th class="text-center">Available Min</th>
                             <th class="text-center">Month</th>
                             <th class="text-center">Value</th>
                             <th class="text-center">Booked Min</th>
                             <th class="text-center">Open Min</th>
                             <th class="text-center" colspan="2">Action</th>
                          </thead>
                          <tbody>
                              @foreach($SAHPPCList as $ppc)
                              @php 
                                     $month = date('F', mktime(0, 0, 0, $ppc->month, 1));
                              @endphp
                                <tr>
                                    <td>{{$ppc->ac_name}}</td>
                                    <td class="text-center">{{$ppc->line_name}}</td>
                                    <td class="text-center">{{$ppc->sales_order_no}}</td>
                                    <td class="text-center">{{$ppc->sam}}</td>
                                    <td class="text-center">{{$ppc->totalAvaliableMin}}</td>
                                    <td class="text-center">{{$month}}</td>
                                    <td class="text-center">{{$ppc->monthValue}}</td>
                                    <td class="text-center">{{$ppc->bookedMin}}</td>
                                    <td class="text-center">{{$ppc->openMin}}</td>
                                    <td>
                                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('SAHPPCEdit', $ppc->sah_ppc_master_id)}}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{$ppc->sah_ppc_master_id}}" 
                                            data-route="{{route('SAHPPCDelete', $ppc->sah_ppc_master_id )}}" title="Delete">
                                              <i class="fas fa-trash"></i>
                                        </button>       
                                    </td>
                                </tr>
                              @endforeach
                          </tbody>
                          </tbody>
                       </table>
                    </div>
                 </div>
              </div>
           </div>
           <!-- end col -->
        </div>
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
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });  
    $(document).on('click','#DeleteRecord',function(e) 
    {
        var Route = $(this).attr("data-route");
        var id = $(this).data("id");
        var token = $(this).data("token");
        
        if (confirm("Are you sure you want to Delete this Record?") == true) 
        {
            $.ajax({
                 url: Route,
                 type: "DELETE",
                  data: {
                  "id": id,
                  "_method": 'DELETE',
                   "_token": token,
                   },
                 
                 success: function(data)
                 {
                    location.reload();
                 }
            });
        }

    });
    function GetPPCData(row, val)
    {
        var vendorId=  $(row).closest("tr").find('td:nth-child(1) select').val();
        var line_id= $(row).closest("tr").find('td:nth-child(2) select').val();
        $.ajax({
            dataType: "json",
            url: "{{ route('GetPPCData') }}",
            data:{'vendorId':vendorId, 'line_id':line_id, 'sales_order_no':val},
            success: function(data)
            {
               $(row).closest("tr").find('td:nth-child(4) input').val(data.ppc[0].sam);
            }
        });
    }
    
    
    function GetTotalAvaliableMin(row,val)
    {   
        var vendorId=  $(row).closest("tr").find('td:nth-child(1) select').val();
        var line_id= $(row).closest("tr").find('td:nth-child(2) select').val();
        var sales_order_no= $(row).closest("tr").find('td:nth-child(3) select').val();
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetPPCData') }}",
            data:{'vendorId':vendorId, 'line_id':line_id, 'sales_order_no':sales_order_no},
            success: function(data)
            {
               var totalAvaliableMin =  data.ppc[0].machine_count * data.ppc[0].available_mins * data.ppc[0].line_efficiency * val;
               $(row).closest("tr").find('td:nth-child(6) input').val(totalAvaliableMin);
            }
        });
    }
    
    function GetMinData(row,val)
    {
        var totalAvaliableMin = 0;
        var vendorId=  $(row).closest("tr").find('td:nth-child(1) select').val();
        var line_id= $(row).closest("tr").find('td:nth-child(2) select').val();
        var sales_order_no= $(row).closest("tr").find('td:nth-child(3) select').val();
        var sam = $(row).closest("tr").find('td:nth-child(4) input').val();
        var totalAvaliableMin = $(row).closest("tr").find('td:nth-child(6) input').val();
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetPPCData') }}",
            data:{'vendorId':vendorId, 'line_id':line_id, 'sales_order_no':sales_order_no},
            success: function(data)
            {
               var totalBookedMin = val*sam;
               $(row).closest("tr").find('td:nth-child(9) input').val(totalBookedMin);
               $(row).closest("tr").find('td:nth-child(10) input').val(parseInt(totalAvaliableMin) - parseInt(totalBookedMin));
            }
        });
    }
    function GetLineData(ele)
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('GetLineList') }}",
            data:{'Ac_code':ele},
            success: function(data){
            $('#searchLineId').html(data.html);
           }
        });
    }
 
    
   
   function CalculateQtyRowPro(row)
   {   
     
    var machine_count=+row.find('input[name^="machine_count[]"]').val();
    var available_mins=+row.find('input[name^="available_mins[]"]').val();
    var line_efficiency=+row.find('input[name^="line_efficiency[]"]').val();
    var sam=+row.find('input[name^="sam[]"]').val();
    var production_capacity=parseFloat((((parseFloat(machine_count) * parseFloat(available_mins) * parseFloat(line_efficiency))/sam)/100)).toFixed(0);
    row.find('input[name^="production_capacity[]"]').val(production_capacity);
   }
    
   function GetLineList(row)
   {    
        var vendorId = $(row).val();
        var row = $(row).closest('tr'); 
        $.ajax({
            dataType: "json",
            url: "{{ route('GetLineList') }}",
            data:{'Ac_code':vendorId},
            success: function(data){
            row.find('select[name^="line_id[]"]').html(data.html);
           }
        });
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetSalesOrderList') }}",
            data:{'vendorId':vendorId},
            success: function(data){
            row.find('select[name^="sales_order_no[]"]').html(data.html);
           }
        });
   }
  
   function selselect()
   {
     setTimeout(function(){
     $("#footable_3 tr td  select[name='vendorId[]']").each(function() {
          $(this).closest("tr").find('select[name="vendorId[]"]').select2();
          $(this).closest("tr").find('select[name="line_id[]"]').select2();
          $(this).closest("tr").find('select[name="sales_order_no[]"]').select2();
          $(this).closest("tr").find('select[name="month[]"]').select2();
         });
     }, 1000);
   }
   
   var indexcone = 2;
   function insertcone(obj)
   {
       var available_mins = $(obj).closest("tr").find('td:nth-child(5) input').val();
       var endDate = $(obj).closest("td").prev("td").find('input').val();
       console.log(available_mins);
       $("#vendorId").select2("destroy");
       $("#line_id").select2("destroy");
       $("#sales_order_no").select2("destroy");
       $("#month").select2("destroy");
   
       var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
       var row=table.insertRow(table.rows.length);
       
       var cell2 = row.insertCell(0);
       var t2=document.createElement("select");
       var x = $("#vendorId"),
       y = x.clone();
       y.attr("id","vendorId"+indexcone);
       y.attr("name","vendorId[]");
       y.width(250);
       y.appendTo(cell2);
       
       var cell5 = row.insertCell(1);
       var t5=document.createElement("select");
       var x = $("#line_id"),
       y = x.clone();
       y.attr("id","line_id"+indexcone);
       y.attr("name","line_id[]");
       y.width(100);
       y.appendTo(cell5);
       
       var cell3 = row.insertCell(2);
       var t3=document.createElement("select");
       var x = $("#sales_order_no"),
       y = x.clone();
       y.attr("id","sales_order_no"+indexcone);
       y.attr("name","sales_order_no[]");
       y.width(100);
       y.appendTo(cell3);
       
       var cell6 = row.insertCell(3);
       var t6=document.createElement("input");
       t6.style="display: table-cell; width:120px;";
       t6.type="text";
       t6.id = "sam"+indexcone;
       t6.name="sam[]";
       t6.value="0";
       t6.setAttribute('readonly','readonly');
       t6.className="form-control";
       cell6.appendChild(t6);
       
       var cell7 = row.insertCell(4);
       var t7=document.createElement("input");
       t7.style="display: table-cell; width:120px;";
       t7.type="text";
       t7.id = "noOfDays"+indexcone;
       t7.name="noOfDays[]";
       t7.setAttribute('onchange','GetTotalAvaliableMin(this,this.value);');
       t7.className="form-control";
       cell7.appendChild(t7);
       
       var cell8 = row.insertCell(5);
       var t8=document.createElement("input");
       t8.style="display: table-cell; width:120px;";
       t8.type="text";
       t8.id = "totalAvaliableMin"+indexcone;
       t8.name="totalAvaliableMin[]";
       t8.className="form-control";
       t8.setAttribute('readonly','readonly');
       cell8.appendChild(t8);
       
       
       var cell3 = row.insertCell(6);
       var t3=document.createElement("select");
       var x = $("#month"),
       y = x.clone();
       y.attr("id","month"+indexcone);
       y.attr("name","month[]");
       y.width(100);
       y.appendTo(cell3);
       
       
       var cell9 = row.insertCell(7);
       var t9=document.createElement("input");
       t9.style="display: table-cell; width:120px;";
       t9.type="text";
       t9.className="form-control";
       t9.id = "monthValue"+indexcone;
       t9.name="monthValue[]";
       t9.setAttribute('onchange','GetMinData(this,this.value);');
       cell9.appendChild(t9);
       
       var cell10 = row.insertCell(8);
       var t10=document.createElement("input");
       t10.style="display: table-cell; width:120px;";
       t10.type="text";
       t10.className="form-control";
       t10.id = "bookedMin"+indexcone;
       t10.name="bookedMin[]";
       t10.setAttribute('readonly','readonly');
       cell10.appendChild(t10);
       
       var cell11 = row.insertCell(9);
       var t11=document.createElement("input");
       t11.style="display: table-cell; width:120px;";
       t11.type="text";
       t11.id = "openMin"+indexcone;
       t11.name="openMin[]";
       t11.className="form-control";
       t11.value="0";
       t11.setAttribute('readonly','readonly');
       cell11.appendChild(t11);
       
       var cell14=row.insertCell(10);
       var btnAdd = document.createElement("INPUT");
       btnAdd.id = "Abutton";
       btnAdd.type = "button";
       btnAdd.className="btn btn-warning pull-left";
       btnAdd.value = "+";
       btnAdd.setAttribute("onclick", "insertcone(this)");
       cell14.appendChild(btnAdd);
       
       
       var btnRemove = document.createElement("INPUT");
       btnRemove.id = "Dbutton";
       btnRemove.type = "button";
       btnRemove.className="btn btn-danger pull-left";
       btnRemove.value = "X";
       btnRemove.setAttribute("onclick", "deleteRowcone(this)");
       cell14.appendChild(btnRemove);
    
       document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
       
       indexcone++;
       recalcIdcone();
       selselect();
   }
   
   
   
   function deleteRowcone(btn) 
   {
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       
       document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
       recalcIdcone();
       
       if($("#cntrr").val()<=0)
       {		
         document.getElementById('Submit').disabled=true;
       }
   }
   
   function recalcIdcone()
   {
       $.each($("#footable_3 tr"),function (i,el){
       $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
       })
   }
   
</script>
<!-- end row -->
@endsection