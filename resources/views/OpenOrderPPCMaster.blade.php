@extends('layouts.master') 
@section('content')
<style>
    .select2 
    {
        width: 200px!important;
    }
    td
    {
        text-align:center;
    }
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Open Order PPC Master</h4>
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

            @if(isset($OpenOrderPPCMasterList))
            <form action="{{ route('OpenOrderPPC.update',$OpenOrderPPCMasterList->openOrderPPCDetailId) }}" method="POST" enctype="multipart/form-data">
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
                                     <th class="text-center">Sale Order No.</th>
                                     <th class="text-center">Vendor Name</th>
                                     <th class="text-center" style="width: 110px;">Qty.</th>
                                     <th class="text-center">Action</th>  
                                  </tr>
                               </thead>
                               <tbody id="endData">
                                  <tr>
                                     <td class="text-center">
                                        <select name="sales_order_no[]"  id="sales_order_no" class="select2">
                                           <option value="">--Sales Order No.--</option>
                                           @foreach($salesOrderList as  $sales_order)
                                           {
                                            @php 
                                                if($sales_order->tr_code == $OpenOrderPPCMasterList->sales_order_no)
                                                {
                                                    $selected = "selected";
                                                }
                                                else
                                                {
                                                    $selected = "";
                                                }
                                             @endphp 
                                           <option value="{{ $sales_order->tr_code }}" {{$selected}} >{{ $sales_order->tr_code }}</option>
                                           }
                                           @endforeach
                                        </select>
                                     </td>
                                     <td class="text-center">
                                        <select name="vendorId[]"  id="vendorId" class="select2">
                                           <option value="">--Vendors--</option>
                                           @foreach($Ledger as  $rowvendor)
                                           {
                                             @php 
                                                if($rowvendor->ac_code == $OpenOrderPPCMasterList->vendorId)
                                                {
                                                    $selected = "selected";
                                                }
                                                else
                                                {
                                                    $selected = "";
                                                }
                                             @endphp 
                                           <option value="{{ $rowvendor->ac_code }}" {{$selected}}>{{ $rowvendor->ac_name }}</option>
                                           }
                                           @endforeach
                                        </select>
                                     </td>
                                     <td class="text-center"  style="position: absolute;"><input type="number" min="0" step="any" name="vendorQty[]" value="{{$OpenOrderPPCMasterList->vendorQty}}" style="width: 84px;" class="form-control" id="vendorQty" /></td>
                                     <td class="text-center">
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
                         <button type="submit" class="btn btn-primary w-md">Update</button>
                      </div>
             </div>
             </form>
            @else
            <form action="{{route('OpenOrderPPC.store')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
               <div class="row">
                  <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>
                                 <th class="text-center">Sale Order No.</th>
                                 <th class="text-center">Vendor Name</th>
                                 <th class="text-center" style="width: 110px;">Qty.</th>
                                 <th class="text-center">Action</th>  
                              </tr>
                           </thead>
                           <tbody id="endData">
                              <tr>
                                 <td class="text-center">
                                    <select name="sales_order_no[]"  id="sales_order_no" class="select2">
                                       <option value="">--Sales Order No.--</option>
                                       @foreach($salesOrderList as  $sales_order)
                                       {
                                       <option value="{{ $sales_order->tr_code }}" >{{ $sales_order->tr_code }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td class="text-center">
                                    <select name="vendorId[]"  id="vendorId" class="select2">
                                       <option value="">--Vendors--</option>
                                       @foreach($Ledger as  $rowvendor)
                                       {
                                       <option value="{{ $rowvendor->ac_code }}" >{{ $rowvendor->ac_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td class="text-center"  style="position: absolute;"><input type="number" min="0" step="any" name="vendorQty[]" value="" style="width: 84px;" class="form-control" id="vendorQty" /></td>
                                 <td class="text-center">
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
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
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
<!-- end row -->
<script>
    
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
          $(this).closest("tr").find('select[name="sales_order_no[]"]').select2();
         });
     }, 1000);
   }
   
   var indexcone = 2;
   function insertcone(obj)
   {
    
       $("#vendorId").select2("destroy");
       $("#sales_order_no").select2("destroy");
   
       var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
       var row=table.insertRow(table.rows.length);
       
       var cell2 = row.insertCell(0);
       var t2=document.createElement("select");
       var x = $("#sales_order_no"),
       y = x.clone();
       y.attr("id","sales_order_no"+indexcone);
       y.attr("name","sales_order_no[]");
       y.width(250);
       y.appendTo(cell2);
       
       var cell2 = row.insertCell(1);
       var t2=document.createElement("select");
       var x = $("#vendorId"),
       y = x.clone();
       y.attr("id","vendorId"+indexcone);
       y.attr("name","vendorId[]");
       y.width(250);
       y.appendTo(cell2);
       
       var cell6 = row.insertCell(2);
       var t6=document.createElement("input");
       t6.style="display: table-cell; width:84px;";
       t6.type="text";
       t6.id = "vendorQty"+indexcone;
       t6.name = "vendorQty[]";
       t6.value="0";
       t6.className="form-control";
       cell6.appendChild(t6);
       
       var cell14=row.insertCell(3);
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