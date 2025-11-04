@extends('layouts.master') 
@section('content')
@php 
ini_set('memory_limit', '10G');
@endphp 
<style>
   body {
   font-family: Arial, sans-serif;
   font-size: 13px;
   }
   table {
   border-collapse: collapse;
   width: 100%;
   }
   th, td {
   border: 1px solid #888;
   padding: 6px;
   text-align: center;
   }
   th {
   background-color: #f2f2f2;
   }
   .level-header {
   background-color: #f7c48b;
   font-weight: bold;
   }
   .remarks {
   text-align: left;
   }
   .note {
   font-size: 12px;
   padding-top: 10px;
   }
   .note li {
   margin-bottom: 4px;
   }
   .level2, .level3 {
   background-color: #fcd8b4;
   }
   .highlight {
   background-color: #d9e1f2;
   }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Purchase Order Authority Matrix</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">Purchase Order Authority Matrix</li>
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
            <h4 class="card-title mb-4">Purchase Order Authority Matrix</h4>
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
            <form action="@if(isset($POAMFetch)) {{ route('so_po_authority_matrix.store',array('id'=>$POAMFetch->so_po_authority_id)) }} @else {{ route('so_po_authority_matrix.store') }} @endif" method="POST" id="frmData">
               @csrf 
               <div class="row">
                  <div class="table-responsive">
                     <table>
                        <tr>
                           <th rowspan="2">Sales Order</th>
                           <th rowspan="2">BUYER</th>
                           <th rowspan="2">Brand</th>
                           <th rowspan="2">Category</th>
                           <th rowspan="2">Item</th>
                           <th rowspan="2">Classification</th>
                           <th rowspan="2">BOM Qty</th>
                           <th colspan="2" class="level-header">LEVEL 1</th>
                           <th colspan="2" class="level2">Level 2</th>
                           <th colspan="2" class="level3">Level 3</th>
                        </tr>
                        <tr>
                           <th>%</th>
                           <th>Qty</th>
                           <th>%</th>
                           <th>Qty</th>
                           <th>%</th>
                           <th>Qty</th>
                        </tr>
                        <!-- Example Row (Repeat as needed) -->
                        <tr class="highlight">
                           <td>
                              <select name="sales_order_no"  id="sales_order_no" class="select2" style="width:200px;  height:30px;" required  onchange="getSalesOrderDetails(this.value);" >
                                 <option value="">--Select Sales Order--</option>
                                 @foreach($SalesOrderList as $rowSO)
                                 <option value="{{ $rowSO->tr_code  }}"
                                 @if(isset($POAMFetch)) {{  $rowSO->tr_code== $POAMFetch->sales_order_no ? "selected='selected'" : ""; }} @endif                    
                                 >{{ $rowSO->tr_code }}</option>
                                 @endforeach
                              </select>
                           </td>
                           <td>
                              <select name="ac_code"  id="ac_code" class="" style="width:200px;  height:30px;" disabled onchange="getBrandList(this.value);" >
                                 <option value="">--Select Buyer--</option>
                                 @foreach($BuyerList as $row)
                                 <option value="{{ $row->Ac_code  }}"
                                 @if(isset($POAMFetch)) {{  $row->Ac_code== $POAMFetch->ac_code ? "selected='selected'" : ""; }} @endif                    
                                 >{{ $row->ac_short_name }}</option>
                                 @endforeach
                              </select>
                              <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                           </td>
                           <td>
                              <select name="brand_id"  id="brand_id"  style="width:150px;height:30px;" disabled>
                                 <option value="">--Select Brand--</option>
                                 @foreach($brandList as $rowBrand)
                                 <option value="{{ $rowBrand->brand_id  }}"
                                 @if(isset($POAMFetch)) {{  $rowBrand->brand_id== $POAMFetch->brand_id ? "selected='selected'" : ""; }} @endif                    
                                 >{{ $rowBrand->brand_name }}</option>
                                 @endforeach
                              </select>
                           </td>
                           <td>
                              <select name="cat_id" class="cat_id"  id="cat_id" style="width:200px; height:30px;" required   onchange="getItemList(this.value);">
                                 <option value="">--Category--</option>
                                 <option value="1"  @if(isset($POAMFetch)) {{ $POAMFetch->cat_id==1 ? 'selected="selected"': ''  }} @endif>Packing Trims</option>
                                 <option value="2" @if(isset($POAMFetch)) {{ $POAMFetch->cat_id==2 ? 'selected="selected"': ''  }} @endif>Sewing Trims</option>
                              </select>
                           </td>
                           <td>
                              <select name="item_code" class="select2" id="item_code" style="width:270px; height:30px;" required onchange="getItemDetail(this.value);">
                                 <option value="">--Item List--</option>
                                 @if(isset($POAMFetch))
                                 @foreach($itemList2 as $rowItem)
                                 <option value="{{ $rowItem->item_code  }}"
                                 @if(isset($POAMFetch)) {{  $rowItem->item_code== $POAMFetch->item_code ? "selected='selected'" : ""; }} @endif                    
                                 >{{ $rowItem->item_name }}({{$rowItem->item_code}})</option>
                                 @endforeach
                                 @endif
                              </select>
                           </td>
                           <td>
                              <select name="class_id" class="Classify"  id="class_id" disabled style="width:200px; height:30px;">
                                 <option value="">--Classification--</option>
                                 @foreach($ClassList2 as  $row)
                                 {
                                 <option value="{{ $row->class_id }}"
                                 @if(isset($POAMFetch)) {{  $row->class_id== $POAMFetch->class_id ? "selected='selected'" : ""; }} @endif              
                                 >{{ $row->class_name }}</option>
                                 }
                                 @endforeach
                              </select>
                           </td> 
                           <td><input type="number" step="any" value="{{isset($POAMFetch->bom_qty) ? $POAMFetch->bom_qty: '' }}" disabled  name="bom_qty" id="bom_qty" style="width:80px;  height:30px;"/></td>
                           <td><input type="number" step="any" maxlevel2="{{isset($PoMatrixData[0]->level1) ? $PoMatrixData[0]->level1 : '' }}" value="{{isset($POAMFetch->level1_percentage) ? $POAMFetch->level1_percentage: '' }}"  name="level1_percentage" id="level1_percentage" style="width:80px;  height:30px;" /></td>
                           <td><input type="number" step="any" disabled  value="{{isset($POAMFetch->level1_po_qty) ? $POAMFetch->level1_po_qty: '' }}"  name="level1_po_qty" id="level1_po_qty" style="width:80px;  height:30px;"/></td>
                           <td><input type="number" step="any" maxlevel2="{{isset($PoMatrixData[0]->level2) ? $PoMatrixData[0]->level2 : '' }}"  value="{{isset($POAMFetch->level2_percentage) ? $POAMFetch->level2_percentage: '' }}"   name="level2_percentage" id="level2_percentage" style="width:100px;  height:30px;" /></td>
                           <td><input type="number" step="any" disabled  value="{{isset($POAMFetch->level2_po_qty) ? $POAMFetch->level2_po_qty: '' }}"  name="level2_po_qty" id="level2_po_qty" style="width:80px;  height:30px;"/></td>
                           <td><input type="number" step="any" maxlevel3="{{isset($PoMatrixData[0]->level3) ? $PoMatrixData[0]->level3 : '' }}" value="{{isset($POAMFetch->level3_percentage) ? $POAMFetch->level3_percentage: '' }}"   name="level3_percentage" id="level3_percentage" style="width:80px;  height:30px;" /></td>
                           <td><input type="number" step="any" disabled  value="{{isset($POAMFetch->level3_po_qty) ? $POAMFetch->level3_po_qty: '' }}"  name="level3_po_qty" id="level3_po_qty" style="width:80px;  height:30px;"/></td>
                        </tr>
                     </table>
                  </div>
               </div>
               <div class="mt-4">
                  <button type="submit" id="Submit" class="btn btn-success w-md" onclick="EnableFields();">Save</button>
                  <a href="{{ Route('so_po_authority_matrix.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script> 
   function setCloseDate(row)
   {
       if($(row).val() == 2)
       {
           alert("This process cannot be reversed...!");
           var today = new Date();
           var day = ("0" + today.getDate()).slice(-2);
           var month = ("0" + (today.getMonth() + 1)).slice(-2);
           var year = today.getFullYear();
           var formattedDate = year + '-' + month + '-' + day;
           $("#closeDate").val(formattedDate);
       }
       else
       {
           $("#closeDate").val('');
       }
   
   }
   
   $(document).ready(function() {
       $('#frmData').submit(function() {
           $('#Submit').prop('disabled', true);
       }); 
   });
   
   CKEDITOR.replace('editor1'); 
   
   function closeForm() {
     $('.form-popup-bg').removeClass('is-visible');
   }
   
   
   function calFreightAmt(row)
   {
       var freight_amt = $(row).val() ? $(row).val() : 0;
       var totAmt = $(row).parent().next().find('input[name="total_amounts[]"]').val() ? $(row).parent().next().find('input[name="total_amounts[]"]').val() : 0;
       var total_Amt = parseFloat(freight_amt) + parseFloat(totAmt);
       $(row).parent().next().find('input[name="total_amounts[]"]').val(total_Amt);
   }
   
   function stockPopup(row,item_code)
   {
       var obj = $(row).parent().parent().find('td:nth-child(3) input')[0]; 
      
       var sales_order_no = $(obj).val();
       var bom_type_arr = $("#bom_type").val();
       $.ajax(
          {
          type:"GET",
          dataType:'json',
          url: "{{ route('GetStockDetailPopup') }}",
          data:{item_code:item_code, sales_order_no:sales_order_no,bom_type_arr:bom_type_arr},
          success:function(response)
          {
               $("#stockPopupBody").html(response.html);
               $('.form-popup-bg').addClass('is-visible');
          }
       });
   
   }
   
   $(document).ready(function($) 
   {
        //close popup when clicking x or off popup
        $('.form-popup-bg').on('click', function(event) {
           if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) {
             event.preventDefault();
             $(this).removeClass('is-visible');
           }
        });
    });
   
   $(document).on("change", 'input[class^="ITEMQTY"],input[class^="RATE"]', function (event) 
   {
            var po_type_id=$('#po_type_id').val();
            if(po_type_id!=2)
            {  
                var value = $(this).val();
                var maxLength = parseFloat($(this).attr('max'));
                var minLength = parseFloat($(this).attr('min')); 
                
                if(value>maxLength)
                {
                    alert('Value can not be greater than '+maxLength);
                }
                
                if ((value !== '') && (value.indexOf('.') === -1)) 
                {
                      $(this).val(Math.max(Math.min(value, maxLength), minLength));
                }
           }
   });
   
   function EnableFields()
   {
       $("select").prop('disabled', false);
   }
   
   
   var index = 2;
   function insertRow(){
   $("#item_code").select2("destroy");
   var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   t1.id = "id"+index;
   t1.name= "id[]";
   t1.value=index++;
   cell1.appendChild(t1);
   
   var cell15=row.insertCell(1);
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRow(this)");
   cell15.appendChild(btnRemove);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertRow();mycalc();");
   cell15.appendChild(btnAdd);
   
   
   var btnInfo = document.createElement("INPUT");
   btnInfo.id = "Ibutton";
   btnInfo.type = "button";
   btnInfo.className="btn btn-success pull-left";
   btnInfo.value = "?";
   btnInfo.setAttribute("onclick", "setConversion(this)");
   cell15.appendChild(btnInfo);
   
   
   var cell5=row.insertCell(2);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   
   t5.id = "sales_order_no"+index;
   t5.name= "sales_order_no[]";
   t5.value="0";
   cell5.appendChild(t5); 
   
   var cell2 = row.insertCell(3);
   
   var cell3 = row.insertCell(4);
   var t2=document.createElement("select");
   var x = $("#item_code"),
   y = x.clone();
   var selectedValue="";
   y.attr("id","item_code");
   y.find("option[value = '" + selectedValue + "']").attr("selected", "selected");
   y.attr("name","item_codes[]");
   y.attr("value","");
   y.width(250);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(5); 
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;";
   t3.type="text";
   //t3.className="QTY";
   t3.id = "hsn_code"+index;
   t3.name="hsn_code[]";
   t3.value="";
   cell5.appendChild(t3);
   
   
   var cell6 = row.insertCell(6);
   var t2=document.createElement("select");
   var x = $("#unit_id"),
   y = x.clone();
   y.attr("id","unit_id");
   y.attr("name","unit_id[]");
   y.width(100);
   y.appendTo(cell6);
   
   
   var cell8 = row.insertCell(7);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;";
   t3.type="number";
   t3.id = "bom_qty"+index;
   t3.name="bom_qty[]";
   t3.value="0";
   cell8.appendChild(t3);
   
   
   var cell9 = row.insertCell(8);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;";
   t3.type="number";
   t3.id = "stock"+index;
   t3.name="stock[]";
   t3.value="0";
   cell9.appendChild(t3);
   
   
   
   var cell10 = row.insertCell(9);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;";
   t3.type="number";
   t3.step="any";
   t3.className="ITEMQTY";
   t3.id = "item_qtys"+index;
   t3.name="item_qtys[]";
   t3.value="0";
   cell10.appendChild(t3);
   
   var cell11=row.insertCell(10);
   var t4=document.createElement("input");
   t4.style="display: table-cell; width:80px;";
   t4.type="number";
   t4.step="any";
   t4.id = "item_rates"+index;
   t4.name= "item_rates[]";
   t4.value="0";
   cell11.appendChild(t4);
   
   var cell14=row.insertCell(11);
   var t7=document.createElement("input");
   t7.style="display: table-cell; width:80px;";
   t7.type="number";
   t7.step="any";
   t7.readOnly=true;
   t7.id = "pur_cgsts"+index;
   t7.name= "pur_cgsts[]";
   t7.value="0";
   cell14.appendChild(t7);
   
   
   var cell15=row.insertCell(12);
   var t8=document.createElement("input");
   t8.style="display: table-cell; width:80px;";
   t8.type="number";
   t8.step="any";
   t8.readOnly=true;
   t8.className="GSTAMT";
   t8.id = "camts"+index;
   t8.name= "camts[]";
   t8.value="0";
   cell15.appendChild(t8);
   
   
   var cell16=row.insertCell(13);
   var t9=document.createElement("input");
   t9.style="display: table-cell; width:80px;";
   t9.type="number";
   t9.step="any";
   t9.readOnly=true;
   t9.id = "pur_sgsts"+index;
   t9.name= "pur_sgsts[]";
   t9.value="0";
   cell16.appendChild(t9);
   
   var cell17=row.insertCell(14);
   var t10=document.createElement("input");
   t10.style="display: table-cell; width:80px;";
   t10.type="number";
   t10.step="any";
   t10.readOnly=true;
   t10.className="GSTAMT";
   t10.id = "samts"+index;
   t10.name= "samts[]";
   t10.value="0";
   cell17.appendChild(t10);
   
   
   var cell18=row.insertCell(15);
   var t11=document.createElement("input");
   t11.style="display: table-cell; width:80px;";
   t11.type="number";
   t11.step="any";
   t11.readOnly=true;
   t11.id = "pur_igsts"+index;
   t11.name= "pur_igsts[]";
   t11.value="0";
   cell18.appendChild(t11);
   
   var cell19=row.insertCell(16);
   var t12=document.createElement("input");
   t12.style="display: table-cell; width:80px;";
   t12.type="number";
   t12.step="any";
   t12.readOnly=true;
   t12.className="GSTAMT";
   t12.id = "iamts"+index;
   t12.name= "iamts[]";
   t12.value="0";
   cell19.appendChild(t12);
   
   
   
   var cell12=row.insertCell(17);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "disc_pers"+index;
   t5.name= "disc_pers[]";
   t5.value="0";
   cell12.appendChild(t5);
   
   
   var cell13=row.insertCell(18);
   var t6=document.createElement("input");
   t6.style="display: table-cell; width:80px;";
   t6.type="number";
   t6.step="any";
   t6.readOnly=true;
   t6.id = "disc_amounts"+index;
   t6.name= "disc_amounts[]";
   t6.value="0";
   cell13.appendChild(t6);
   
   var cell20=row.insertCell(19);
   var t13=document.createElement("input");
   t13.style="display: table-cell; width:80px;";
   t13.type="number";
   t13.step="any";
   t13.readOnly=true;
   t13.className="GROSS";
   t13.id = "amounts"+index;
   t13.name= "amounts[]";
   t13.value="0";
   cell20.appendChild(t13);
   document.getElementById("amounts"+index).style.display='value';
   
   var cell7 = row.insertCell(20);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;";
   t3.type="number";
   t3.id = "moq"+index;
   t3.name="moq[]";
   t3.value="0";
   cell7.appendChild(t3);
   
   var cell22=row.insertCell(21);
   var t13=document.createElement("input");
   t13.style="display: table-cell; width:80px;";
   t13.type="text";
   t13.step="any";
   t13.readOnly=true;
   t13.className="FREIGHT";
   t13.id = "freight_amt"+index;
   t13.name= "freight_amt[]";
   t13.value="0";
   cell22.appendChild(t13);
   document.getElementById("freight_amt"+index).style.display='value';
   
   
   var cell23=row.insertCell(22);
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="number";
   t14.step="any";
   t14.readOnly=true;
   t14.className='TOTAMT';
   t14.id = "total_amounts"+index;
   t14.name= "total_amounts[]";
   t14.value="0";
   cell23.appendChild(t14);
   //document.getElementById("total_amounts"+index).style.display='value';  
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "conQtys"+index;
   t14.name= "conQtys[]";
   t14.value="1000";
   cell23.appendChild(t14);
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "unitIdMs"+index;
   t14.name= "unitIdMs[]";
   t14.value="5";
   cell23.appendChild(t14); 
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "priUnitds"+index;
   t14.name= "priUnitds[]";
   t14.value="10";
   cell23.appendChild(t14);  
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "SecConQtys"+index;
   t14.name= "SecConQtys[]";
   t14.value="10";
   cell23.appendChild(t14); 
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "secUnitIds"+index;
   t14.name= "secUnitIds[]";
   t14.value="11";
   cell23.appendChild(t14); 
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "poQtys"+index;
   t14.name= "poQtys[]";
   t14.value="0";
   cell23.appendChild(t14); 
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "poUnitIds"+index;
   t14.name= "poUnitIds[]";
   t14.value="9";
   cell23.appendChild(t14); 
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "rateMs"+index;
   t14.name= "rateMs[]";
   t14.value="0";
   cell23.appendChild(t14); 
   
   
   
   var t14=document.createElement("input");
   t14.style="display: table-cell; width:80px;";
   t14.type="hidden";
   t14.step="any";
   t14.readOnly=true;
   t14.id = "totalQtys"+index;
   t14.name= "totalQtys[]";
   t14.value="0";
   cell23.appendChild(t14); 
   // var w = $(window);
   // var row = $('#footable_2').find('tr').eq( index );
   
   // if (row.length){
   // $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   // }
   
   document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;
   
   index++;
   recalcId();
   
   selselect();
     
   }
   
   
   
   function selselect()
   {
      setTimeout(
   function() 
   {
   
   $("#footable_2 tr td  select[name='item_codes[]']").each(function() {
   
      $(this).closest("tr").find('select[name="item_codes[]"]').select2();
   
   
     });
   }, 2000);
   }
   
   
   function deleteRow(btn) {
   if(document.getElementById('cnt').value > 0){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cnt').value = document.getElementById('cnt').value-1;
   recalcId();
   mycalc();
   if($("#cnt").val()<=0)
   {       
   document.getElementById('Submit').disabled=true;
   }
   
   
   
   }
   }
   
   function recalcId(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   
   
   function mycalc()
   {    
          sum1 = 0.0;
          var amounts = document.getElementsByClassName('GROSS');
          //alert("value="+amounts[0].value);
          for(var i=0; i<amounts .length; i++)
          { 
          var a = +amounts[i].value;
          sum1 += parseFloat(a);
          }
          document.getElementById("Gross_amount").value = sum1.toFixed(4);
          
          
          
          sum1 = 0.0;
          var amounts = document.getElementsByClassName('GSTAMT');
          //alert("value="+amounts[0].value);
          for(var i=0; i<amounts .length; i++)
          { 
          var a = +amounts[i].value;
          sum1 += parseFloat(a);
          }
          document.getElementById("Gst_amount").value = sum1.toFixed(4);
          
          sum1 = 0.0;
          var amounts = document.getElementsByClassName('TOTAMT');
          //alert("value="+amounts[0].value);
          for(var i=0; i<amounts .length; i++)
          { 
          var a = +amounts[i].value;
          sum1 += parseFloat(a);
          }
          document.getElementById("Net_amount").value = sum1.toFixed(0);
          
          
          sum1 = 0.0;
          var amounts = document.getElementsByClassName('FREIGHT');
          //alert("value="+amounts[0].value);
          for(var i=0; i<amounts .length; i++)
          { 
          var a = +amounts[i].value;
          sum1 += parseFloat(a);
          }
          document.getElementById("totFreightAmt").value = sum1.toFixed(0);
          
          
          
          var sum = 0.0;
          var amounts = document.getElementsByClassName('ROWCOUNT');
          for(var i=0; i<amounts .length; i++)
          {
              var a = +amounts[i].value;
              sum += parseFloat(a) || 0;
          }
           document.getElementById("cnt").value = sum;
          
          
          
          sum1 = 0.0;
          var amounts = document.getElementsByClassName('ITEMQTY');
          //alert("value="+amounts[0].value);
          for(var i=0; i<amounts .length; i++)
          { 
          var a = +amounts[i].value;
          sum1 += parseFloat(a);
          }
          document.getElementById("total_qty").value = sum1.toFixed(4); 
          
   }
   
   
   
   
   
   
   function frieght_payable()
   {
      var Net_amount=document.getElementById('Net_amount').value;
      var freight_amt=$('#freight_amt').val();
   
   var payable_amount=(parseFloat(Net_amount) + (parseFloat(freight_amt)));
   $('#payable_amt').val(parseFloat(payable_amount).toFixed(0));
   
   }
   
   
   function disc_calculatess()
   {
   
   var item_qty=document.getElementById('item_qty').value;
   var item_rate=document.getElementById('item_rate').value;
   var disc_per=document.getElementById('disc_per').value;
   var amount= item_qty*item_rate
   
   var disc_amount= parseFloat(parseFloat(amount) * parseFloat(disc_per/100));
   $('#disc_amount').val(disc_amount.toFixed(4));
   
   var amount= parseFloat(parseFloat(amount) - parseFloat(disc_amount)).toFixed(4);
   $('#amount').val(amount);
   calculateGstsss();
   
   }
   
   
   
   
   function calculateGstsss()
   {
   var amount=document.getElementById('amount').value;
   var pur_cgst=document.getElementById('pur_cgst').value;
   var pur_sgst=document.getElementById('pur_sgst').value;
   var pur_igst=document.getElementById('pur_igst').value;
   
   var tax_type_id1=document.getElementById('tax_type_id').value;
   if(tax_type_id1==2)
   {
   var iamt=  parseFloat(( amount*(pur_igst/100))).toFixed(4);
   $('#iamt').val(iamt);
   
   $('#total_amount').val(parseFloat(amount) + parseFloat(iamt));
   
   }
   else{
   var camt=  parseFloat(( amount*(pur_cgst/100))).toFixed(4);
   $('#camt').val(camt);
   var samt= parseFloat(( amount*(pur_sgst/100))).toFixed(4);
   $('#samt').val(samt);
   
   $('#total_amount').val(parseFloat(amount) + parseFloat(camt) + parseFloat(samt));
   
   }
   }
   
   
   function divideBy(str) 
   { 
   // item_code = document.getElementById("item_code").value;  
   
   // calculate_gst(item_code);
   
   }
   
   var tax_type_id =1;
   function calculate_gst(item_code)
   {
   
   
   tax_type_ids =document.getElementById("tax_type_id").value
   
   $.ajax(
   {
   type:"GET",
   dataType:'json',
   url: "{{ route('GSTPER') }}",
   data:{item_code:item_code,tax_type_id:tax_type_ids},
   success:function(response)
   {
   
     if(tax_type_id==1)
              {
   
    $("#pur_cgst").val(response[0].cgst_per); 
    $("#pur_sgst").val(response[0].sgst_per); 
    $("#pur_igst").val(response[0].igst_per);
   } else{
   
   $("#pur_igst").val(response[0].igst_per);
   
   }
   
   }
   
   });
   }
    
       
   
   
   
   
   function firmchange(firm_id){
   
   
   var type=document.getElementById('type').value;
   
   //alert(firm_id);
   
   $.ajax({
   type:"GET",
   url:'getdata.php',
   dataType:"json",
   data:{firm_id:firm_id,type:type, fn:"Firm_change"},
   success:function(response){
   
   $("#pur_code").val(response["code"]+'-'+response["tr_no"]);
   $("#c_code").val(response["c_code"]);
   
   }
   });
   }
      
   function PODisabled()
   {
      $("#po_type_id").attr("disabled", true);   
      var po_type_id = $("#po_type_id").val();
      if(po_type_id != 2)
      {
       $("#bom_code").attr("disabled", false); 
      }
   }
   
   function getPartyDetails()
   {
      var po_type_id = $("#po_type_id").val();
      if(po_type_id == 2)
      {
          $("#tr1").removeClass("hide");   
          $("#bom_code").attr("disabled", true); 
          $("#buyer_id").attr("disabled", false); 
      }
      else
      {
          $("#tr1").addClass("hide");
          $("#buyer_id").attr("disabled", true); 
      }
      
      
      var ac_code=$("#Ac_code").val();
      
       $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('PartyDetail') }}",
              data:{'ac_code':ac_code},
              success: function(data)
              {
                  $("#gstNo").val(data[0]['gst_no']);
                 if(data[0]['state_id']==27){$("#tax_type_id").val(1);}
                 else{$("#tax_type_id").val(2);}
              }
       });
   }
   
   
   
   
       $("table.footable_2").on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="pur_cgsts[]"],input[name^="camts[]"],input[name^="pur_sgsts[]"],input[name^="pur_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="freight_amt[]"],input[name^="total_amounts[]"]', function (event) {
          CalculateRow($(this).closest("tr"));
          
   
          
      });
   
   
   
      function CalculateRow(row)
      {
   
          var item_qtys=+row.find('input[name^="item_qtys[]"]').val();
          var item_rates=+row.find('input[name^="item_rates[]"]').val();
          var disc_pers=+row.find('input[name^="disc_pers[]"]').val();
          var disc_amounts=+row.find('input[name^="disc_amounts[]"]').val();
          var pur_cgsts=  +row.find('input[name^="pur_cgsts[]"]').val();
          var camts= +row.find('input[name^="camts[]"]').val();
          var pur_sgsts= +row.find('input[name^="pur_sgsts[]"]').val();
          var samts= +row.find('input[name^="samts[]"]').val();
          var pur_igsts= +row.find('input[name^="pur_igsts[]"]').val();
          var iamts= +row.find('input[name^="iamts[]"]').val();
          var amounts= +row.find('input[name^="amounts[]"]').val();
          var freight_amt= +row.find('input[name^="freight_amt[]"]').val();
          var total_amounts= +row.find('input[name^="total_amounts[]"]').val();
          var tax_type_id =document.getElementById("tax_type_id").value;
          
          
               
           if(item_qtys>0)
           {
              
                   Amount=item_qtys*item_rates;
                   disc_amt=(Amount*(disc_pers/100));
                   row.find('input[name^="disc_amounts[]"]').val((disc_amt).toFixed(4));
                   Amount=Amount-disc_amt;
                   row.find('input[name^="amounts[]"]').val((Amount).toFixed(4));
                
               if(pur_igsts!=0)
               {
                    Iamt=(Amount*(pur_igsts/100));
                    row.find('input[name^="iamts[]"]').val((Iamt).toFixed(4));
                    TAmount=Amount+Iamt+freight_amt;
                    row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(4));
               }
               else
               {
                    Camt=(Amount*(pur_cgsts/100));
                    row.find('input[name^="camts[]"]').val((Camt).toFixed(4));
                    
                    Samt=(Amount*(pur_sgsts/100));
                    row.find('input[name^="samts[]"]').val((Samt).toFixed(4));
                                    
                    TAmount=Amount+Camt+Samt+freight_amt;
                    row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(4));
                    
               }
               
          }
               
                    mycalc();
   }
   
   
   function GetClassesList()
   {
    //  cat_id=$("#bom_type").val();
      var  bom_types = $("#bom_type option:selected").map(function() {
        return this.value;
      }).get().join(",");
       
       $.ajax({
          dataType: "json",
          url: "{{ route('getClassLists') }}",
          data:{'cat_id':bom_types},
          success: function(data){
          $("#class_id").html(data.html);
          
         }
      });   
   }
   
   
   $(document).ready(function()
   {
       var maxSelection = 0;
       $("#bom_code").change(function () 
       {
           var po_type_id = $("#po_type_id").val();
           var bom_code= $(this).val();
            
           $.ajax({
                  type: "GET",
                  dataType:"json",
                  url: "{{ route('GetBuyerFromBOM') }}",
                  data:{'bom_code':bom_code[0]},
                  success: function(data)
                  {
                      $("#buyer_id").val(data.buyer_id).trigger('change');
                      $("#buyer_id").attr("disabled", true);
                  }
           });
       
           if(parseInt(po_type_id) == 1)
           {
               var selectedOptions = $(this).find("option:selected");
       
               if(selectedOptions.length > maxSelection) 
               {
                   
                   // Prevent further selections by disabling unselected options
                   $("#bom_code option").prop("disabled", false); // Enable all first
                   selectedOptions.each(function () {
                       $("#bom_code option:not(:selected)").prop("disabled", true);
                   });
               } 
               else 
               {
                   $("#bom_code option").prop("disabled", false); // Re-enable if within limit
               }
           }
       }); 
       
         var previousSelection = [];
         
          $('#class_id').change(function(){
          
            
           var currentSelection = $(this).val() || [];
           
           // Compare previous selection with current selection
           $(previousSelection).each(function(index, value){
             if ($.inArray(value, currentSelection) === -1) 
             {
                $(".cls_"+value).remove(); 
                 
                  $.ajax({
                      dataType: "json",
                      url: "{{ route('getItemCodeList') }}",
                      data:{'class_id':value},
                      success: function(data)
                      {
                          
                           $(data.ItemList).each(function()
                           {
                               var item_code = $(this)[0]; 
                               $('.tr_'+item_code).remove(); 
                          });
                     }
                  });  
              
             }
           });
           
           previousSelection = currentSelection;
         });
         
   
   });
      
   
   
   
          
   
     function getBrandList(val) 
   {	//alert(val);
   
     $.ajax({
      type: "GET",
      url: "{{ route('BrandList') }}",
      data:{'Ac_code':val, },
      success: function(data){
      $("#brand_id").html(data.html);
      }
      });
   } 
   
   
   
   
   
   function getSalesOrderDetails(sales_order_no)
   {
   
     $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('SalesOrderDetails') }}",
           data:{'sales_order_no':sales_order_no},
           success: function(data){
          
           $("#ac_code").val(data[0]['Ac_code']);
           $("#brand_id").val(data[0]['brand_id']);
           
           
    
       }
       });
   
   
   
   }
   
   
   function get_po_matrix_detail()
   {
   
   
        var ac_code=$("#ac_code").val();
        var brand_id=$("#brand_id").val();
        var type=$("#cat_id").val();
        
        
           $.ajax({
           type: "POST",
           dataType:"json",
           url: "{{ route('po_matrix_detail') }}",
           data:{'ac_code':ac_code,brand_id:brand_id,"_token":"{{ csrf_token() }}"},
           success: function(data){
               
            handlePOMatrixData(data);
             
       }
       }); 
   
   
   }
   
   
   
   function handlePOMatrixData(data) {
   var type = $("#cat_id").val();
   
   if (type == 1) {
       $("#level1_percentage").val(data.level1_packing_trim_extra_order);
       $("#level2_percentage").val(data.level2_packing_trim_extra_order);
       $("#level3_percentage").val(data.level3_packing_trim_extra_order);
       
       
      $("#level2_percentage").attr('maxlevel2',data.level2_packing_trim_extra_order);
      $("#level3_percentage").attr('maxlevel3',data.level3_packing_trim_extra_order);      
       
   
       calculatePoQty(data.level1_packing_trim_extra_order, '#level1_po_qty');
       calculatePoQty(data.level2_packing_trim_extra_order, '#level2_po_qty');
       calculatePoQty(data.level3_packing_trim_extra_order, '#level3_po_qty');
       
       
       
       
   } else {
       $("#level1_percentage").val(data.level1_sewing_trim_extra_order);
       $("#level2_percentage").val(data.level2_sewing_trim_extra_order);
       $("#level3_percentage").val(data.level3_sewing_trim_extra_order);
       
       
     $("#level2_percentage").attr('maxlevel2',data.level2_sewing_trim_extra_order);
     $("#level3_percentage").attr('maxlevel3',data.level3_sewing_trim_extra_order);   
       
   
       calculatePoQty(data.level1_sewing_trim_extra_order, '#level1_po_qty');
       calculatePoQty(data.level2_sewing_trim_extra_order, '#level2_po_qty');
       calculatePoQty(data.level3_sewing_trim_extra_order, '#level3_po_qty');
   }
   }
   
   
   function calculatePoQty(percentageId, qtyId) {
       
       var bomQty = parseFloat($('#bom_qty').val()) || 0;
       
       var result = ((bomQty * percentageId) / 100);
       
       var finalResult=(result + bomQty);
   
       $(qtyId).val(finalResult.toFixed(2)); 
   }
   
   
     function getItemList(cat_id) 
   {	//alert(val);
   
    var sales_order_no=$('#sales_order_no').val();
   
     $.ajax({
      type: "POST",
      url: "{{ route('get_item_codes') }}",
     data:{'sales_order_no':sales_order_no,cat_id:cat_id,"_token":"{{ csrf_token(); }}"},
      success: function(data){
      $("#item_code").html(data.html);
      }
      });
   } 
   
   
     function getItemDetail(item_code) 
   {	//alert(val);
   
     var sales_order_no=$('#sales_order_no').val();
     var cat_id=$('#cat_id').val();
   
     $.ajax({
      type: "POST",
      url: "{{ route('get_item_details') }}",
     data:{'sales_order_no':sales_order_no,cat_id:cat_id,item_code:item_code,"_token":"{{ csrf_token(); }}"},
      success: function(data){
          
          console.log(data);
          
       $("#bom_qty").val(data.item_qty);
       $("#class_id").val(data.class_id); 
      
      },
        complete: function(res){
      
       get_po_matrix_detail();
      
        }
      });
   } 
   
   
   
   $(document).ready(function(){
   
   // Function to calculate PO qty based on percentage
   function calculateQty(percentageId, qtyId) {
       
       
       var bomQty = parseFloat($('#bom_qty').val()) || 0;
       var percentage = parseFloat($(percentageId).val()) || 0;
       var result = (bomQty * percentage) / 100;
       
        var finalResult=(result + bomQty);
   
       $(qtyId).val(finalResult.toFixed(2)); // rounded to 2 decimals
   }
   
   // Bind event to percentage fields
   $('#level1_percentage').on('input', function() {
       
   
       calculateQty('#level1_percentage', '#level1_po_qty');
   });
   
   $('#level2_percentage').on('input', function() {
       
          let max = parseFloat($(this).attr('maxlevel2'));
          let value = parseFloat($(this).val());
         
   
       if (value > max) {
           alert('Level 2 Percentage cannot exceed the maximum value of ' + max);
           $(this).val(max); 
       }    
       
       
       calculateQty('#level2_percentage', '#level2_po_qty');
   });
   
   $('#level3_percentage').on('input', function() {
       
          let max3 = parseFloat($(this).attr('maxlevel3'));
          let value3 = parseFloat($(this).val());
          
   
       if (value3 > max3) {
           alert('Level 3 Percentage cannot exceed the maximum value of ' + max3);
           $(this).val(max3); 
       }        
       
       
       calculateQty('#level3_percentage', '#level3_po_qty');
   });
   
   // Optional: also recalculate all if bom_qty changes
   $('#bom_qty').on('input', function() {
       $('#level1_percentage').trigger('input');
       $('#level2_percentage').trigger('input');
       $('#level3_percentage').trigger('input');
   });
   
   });
   
   
    $(document).on("mouseover", 'select', function (event) {
         
           $(this).not('.noSelect2').select2('');
         
     });
     
     
   $(document).ready(function() {
   $('#frmData').on('submit', function() {
       // Enable all disabled inputs and selects before form submit
       $(this).find('input:disabled, select:disabled').prop('disabled', false);
   });
   });
   
   
   $(document).ready(function() {
   $('#sales_order_no').change(function() {
       if ($(this).val()) {
           $(this).prop('disabled', true);
       }
   });
   
       $('#cat_id').change(function() {
       if ($(this).val()) {
           $(this).prop('disabled', true);
       }
   });
   
          $('#class_id').change(function() {
       if ($(this).val()) {
           $(this).prop('disabled', true);
       }
   });
   
              $('#item_code').change(function() {
       if ($(this).val()) {
           $(this).prop('disabled', true);
       }
   });
   
   
   });     
</script>
@endsection