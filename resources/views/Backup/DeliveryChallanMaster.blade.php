@extends('layouts.master') 

@section('content')
<style type="text/css">
    .hide{
        display: none;
    }
    .show{
        display: block;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Delivery Challan Master</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Delivery Challan Master</li>
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
                <h4 class="card-title mb-4">Delivery Challan</h4>
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
                <form action="{{route('DeliveryChallan.store')}}" method="POST" id="formTest">
                    @csrf               
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title">Delivery Challan Master:</div>
                                    <div class="row">
                                        <div class="col-md-1">
                                            <div class="mb-1">
                                                <label class="form-label">Issue No</label>
                                                <input type="text" name="issue_no" class="form-control" id="issue_no"  readonly>
                                                <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                                            </div>
                                        </div>                      
                                        
                                        <div class="col-md-3">
                                            <div class="mb-3" style="display: flex;justify-content: space-evenly;align-content: center;align-items: baseline;flex-wrap: nowrap;flex-direction: row;">

                                                <input type="radio" tabindex="3" id="returnable" name="dc_case_id" value="1" onchange="returnableOrNonReturnable();" required>
                                                <label class="form-label">Returnable</label>

                                                <input type="radio" id="non-returnable" tabindex="4" name="dc_case_id" value="2"  onchange="returnableOrNonReturnable();" required> 
                                                <label class="form-label">Non-Returnable</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-2" style="display: flex;justify-content: space-evenly;align-content: center;align-items: baseline;flex-wrap: nowrap;flex-direction: row;">
                                                <input type="radio" tabindex="5" name="issue_case_id" class="issue_case_id" id="IssueDelivery"   onchange="getData();" value="1" required> 
                                                <label class="form-label">Delivery</label>
                                                <input type="radio" class="issue_case_id"  tabindex="7" name="issue_case_id" id="IssueReturn" value="2" onchange="getData();ReadOnlyFields();" required>
                                                <label class="form-label">Return</label>                                
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="IssueReturnData" style="display:none">
                                            <div class="mb-3">
                                                <label class="form-label">GP Issue No List</label><br/>
                                                <select name="return_issue_no" id="return_issue_no"  class="form-control select2" autocomplete="off" data-placeholder="Select" tabindex="1" style="width:200px;" onchange="getDeliveryChallan(this.value);" >
                                                    <option label="Select" value="0"></option>
                                                    @foreach($IssueList as $rowIssue)
                                                    <option value="{{ $rowIssue->issue_no}}">{{ $rowIssue->issue_no }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Issue Date</label>
                                                <input tabindex="6" type="date" name="issue_date"  id="issue_date" class="form-control" placeholder="Issue Date" required="required" value="{{ date('Y-m-d') }}"  >
                                                <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input" >
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="Return" style="display:none">
                                            <div class="mb-3">
                                                <label class="form-label">Return Date</label>
                                                <input tabindex="7" type="date" name="return_date"  id="return_date" class="form-control" placeholder="Issue Date"  value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Type</label>
                                                <input tabindex="9" type="text" name="product_type"  id="product_type" class="form-control"  placeholder="Type" required="required">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3" style="display: flex;justify-content: space-evenly;align-content: center;align-items: baseline;flex-wrap: nowrap;flex-direction: row; padding-top: 10px;">
                                                <input type="radio" tabindex="10" name="reciever_type" class="reciever_type" value="1" id="others" onchange="removeAddress();recieverChange();" required> 
                                                <label class="form-label">Others</label>
                                                <input type="radio" tabindex="11" name="reciever_type" class="reciever_type" value="2" id="vendor" onchange="recieverChange();" required>
                                                <label class="form-label">Vendor</label>
                                                <input type="radio" tabindex="12" name="reciever_type" class="reciever_type" id="buyer" value="3" onchange="recieverChange();" required>
                                                <label class="form-label">Buyer</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 BuyerOrVendor hide">
                                            <div class="mb-3">
                                                <label class="form-label">Buyer/Vendor</label><br/>
                                                <select name="ac_code" id="ac_code"  class="form-control" autocomplete="off" data-placeholder="Select" tabindex="1" style="width:200px;" onchange="getAddressForDC(this.value)">
                                                    <option label="Select" value=""></option>
                                                    @foreach($Ledger as $rowcompanydetail)
                                                    <option value="{{ $rowcompanydetail->ac_code}}">{{ $rowcompanydetail->ac_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 Others hide">
                                            <div class="mb-3">
                                                <label class="form-label">Buyer/Vendor</label>
                                                <input  type="text" name="otherBuyerorVendor"  id="otherBuyerorVendor" placeholder="Buyer/Vendor" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Sent Through</label>
                                                <input tabindex="13" type="text" name="sent_through"  id="sent_through" class="form-control" placeholder="Sent Through" required="required" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Department</label>
                                                <select name="dept_id" id="dept_id"  class="form-control select2" autocomplete="off" data-placeholder="Select" tabindex="14" required>
                                                    <option label="Select" value=""></option>
                                                    @foreach($departmentlist as $rowdepartmentList)
                                                    <option value="{{ $rowdepartmentList->dept_id }}">{{ $rowdepartmentList->dept_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">To Location</label>
                                                <input tabindex="16" type="text" name="to_location"  id="to_location" class="form-control" placeholder="To Location" required="required">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-wrap" >
                                    <div class="table-responsive" id="Detail">
                                        <table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                                            <thead>
                                                <tr>
                                                    <th>Sr No</th>
                                                    <th>Item Description</th>
                                                    <th>Unit</th>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Total Amount</th>
                                                    <th>Remark</th>
                                                    <th>Add/Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="id[]" value="1" id="id" style="width:50px;" readonly/></td>
                                                    <td>
                                                        <input type="text" name="item_description[]" id="item_description"  tabindex="17" class="form-control"  required="required" style="width:120px;">
                                                    </td>

                                                    <td>
                                                        <select class="form-control select2" data-placeholder="Choose one" name="unit_id[]" style="width:140px;" tabindex="18" id="unit_id" required data-parsley-errors-container="#field1">

                                                            <option value="">--- Select Unit ---</option>
                                                            @foreach($unitlist as  $rowunit)
                                                            {
                                                                <option value="{{ $rowunit->unit_id }}">{{ $rowunit->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[]" id="quantity1" class="form-control QTY" tabindex="19" step="any"   required="required" style="width:120px;">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="rate[]" id="rate" class="form-control"  tabindex="20"  step="any" required="required" style="width:120px;">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control TAMT"  name="total_amount[]" onkeyup="mycalc();"  id="total_amount1" style="width:80px;" required readonly />
                                                    </td>

                                                    <td>
                                                        <input type="text" name="remark[]" id="remark" class="form-control" tabindex="21"  style="width:120px;">
                                                    </td>
                                                    <td>
                                                        <input type="button" style="width:40px; margin-right: 5px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left " onclick="deleteRow(this);" value="X" >
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Total Quantity</label>
                                            <input type="number" step="any" tabindex="22" type="total_qty" name="total_qty" value="0"  id="total_qty" class="form-control" required="required" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="NetAmount" class="form-label">Net Amount</label>
                                            <input type="number" step="any"  name="NetAmount" class="form-control" id="NetAmount" value="0" onkeyup="mycalc();" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Narration</label>
                                            <input tabindex="23" type="text" name="narration"  id="narration" class="form-control" placeholder="Narration">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="formrow-inputState" class="form-label"></label>
                                    <div class="form-group">
                                        <button type="submit" onclick="EnableFields();" class="btn btn-primary w-md"  id="btnSubmit" >Submit</button>
                                        <a href="{{ Route('DeliveryChallan.index') }}" class="btn btn-warning w-md">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script> 
<script src="{{URL::asset('assets/js/select2.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.js" ></script>
<script type="text/javascript">


 $('#formTest').parsley();


function returnableOrNonReturnable() {
    if (document.getElementById('returnable').checked) {
        document.getElementById('Return').style.display = 'block';
    }
    else document.getElementById('Return').style.display = 'none';
}

function getData() {

    if (document.getElementById('IssueReturn').checked) 
    {
        document.getElementById('IssueReturnData').style.display = 'block';

    }
    else 
    {
        document.getElementById('IssueReturnData').style.display = 'none';
        removeData();
    }
}

function EnableFields()
{       
    $("select").prop('disabled', false);

}


function ReadOnlyFields()
{       
    $("#issue_no").attr("readonly", "readonly");

    $("#issue_date").attr("readonly", "readonly");

    $("#product_type").attr("readonly", "readonly");

    $("#sent_through").attr("readonly", "readonly");

    $("#dept_id").attr('disabled', true);

    $("#otherBuyerorVendor").attr("readonly", "readonly");

    $("#from_location").attr("readonly", "readonly");
}




function recieverChange(){
    var radioValue = $("input[name='reciever_type']:checked").val();
    // alert(radioValue);
    if( radioValue == 1){
        $(".Others").removeClass('hide'); 
        $(".BuyerOrVendor").addClass('hide');

    }
    else if(radioValue == 2)
    {
        $(".BuyerOrVendor").removeClass('hide');
        $(".Others").addClass('hide');

    }
    else if(radioValue == 3)
    {
        $(".BuyerOrVendor").removeClass('hide');
        $(".Others").addClass('hide');
    }
    else
    {
        $(".BuyerOrVendor").addClass('hide');
        $(".Others").addClass('hide');
    }
};


$(document).on("click", 'input[name^="Abutton[]"]', function (event) {

    insertRow($(this).closest("tr"));

}); 

function removeAddress() {
 $("#to_location").val('');
}

function getAddressForDC(ac_code)
{ 
    var getVendorOrBuyer = document.getElementsByClassName('reciever_type')[0].value;
    if(getVendorOrBuyer == 1)
    {
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('getAddressForDC') }}",
            //data:'table_id='+table_id,
            data:{ac_code:ac_code},
            success: function(response){
                console.log(response);
                $("#to_location").val(response[0]['address']);
            }
        });
    }
    else{
        $("#to_location").val('');
    }
}

function getDeliveryChallan(issue_no)
{ 
    var getReturnOrDelivery = document.getElementsByClassName('issue_case_id')[0].value;

    if(getReturnOrDelivery == 1)
    {
        $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('getDeliveryChallan') }}",
            //data:'table_id='+table_id,
            data:{issue_no:issue_no},
            success: function(response){
                //console.log(response[0].html);
                $("#issue_no").val(response[0]['issue_no']);
                if(response[0]['dc_case_id'] ==  1){
                    $('input:radio[name=dc_case_id][id=returnable]').attr('checked', 'checked');
                    returnableOrNonReturnable();
                }

                $("#issue_date").val(response[0]['issue_date']);
                $("#return_date").val(response[0]['return_date']);
                $("#product_type").val(response[0]['product_type']);
                if(response[0]['reciever_type'] ==  1){
                    $('input:radio[name=reciever_type][id=others]').attr('checked', 'checked');
                    recieverChange();
                    $("#otherBuyerorVendor").val(response[0]['otherBuyerorVendor']);
                }
                else if (response[0]['reciever_type'] ==  2) {
                    $('input:radio[name=reciever_type][id=vendor]').attr('checked', 'checked');
                    recieverChange();
                    $("#ac_code").val(response[0]['ac_code']);
                }
                else if (response[0]['reciever_type'] ==  3) {
                    $('input:radio[name=reciever_type][id=buyer]').attr('checked', 'checked');
                    recieverChange();
                    $("#ac_code").val(response[0]['ac_code']);
                }

                $("#sent_through").val(response[0]['sent_through']);
                $("#dept_id").val(response[0]['dept_id']);
                $("#from_location").val(response[0]['from_location']);
                $("#to_location").val(response[0]['to_location']);
                $("#total_qty").val(response[0]['total_qty']);
                $("#NetAmount").val(response[0]['NetAmount']);
                $("#narration").val(response[0]['narration']);
            }
        });
    }

    getDeliveryChallanDetailsData(issue_no);

}

function getDeliveryChallanDetailsData(issue_no)
{
    $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('getDeliveryChallanDetailsData') }}",
            //data:'table_id='+table_id,
        data:{issue_no:issue_no},
        success: function(response){
            $("#footable_2").html(response.html);
        }
    });
}
function removeData(){
    $("#issue_no").val('')
    $("#issue_no").attr("readonly","readonly");
    $("#returnable").attr("checked" , false );
    $("#non-returnable").attr("checked" , false );
    $("#issue_date").removeAttr("readonly");
    $("#return_date").val('');
    $("#return_date").removeAttr("readonly");
    $("#product_type").val('');
    $("#product_type").removeAttr("readonly");
    $("#others").attr("checked" , false );
    $("#otherBuyerorVendor").val('');
    $(".Others").addClass('hide');
    $("#otherBuyerorVendor").removeAttr("readonly");
    $("#vendor").attr("checked" , false );
    $("#ac_code").val('');
    $("#buyer").attr("checked" , false );
    $("#sent_through").val('');
    $("#sent_through").removeAttr("readonly");
    $("#dept_id").val('');
    $("#dept_id").prop("disabled",false);
    $("#from_location").val('');
    $("#from_location").removeAttr("readonly");
    $("#to_location").val('');
    $("#total_qty").val('');
    $("#NetAmount").val('');
    $("#narration").val('');
    getDeliveryChallanDetailsData('');
}






var index = 2;
function insertRow(Abutton){    

    var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
    var row=table.insertRow(table.rows.length);

    var cell1=row.insertCell(0);
    var t1=document.createElement("input");
    t1.style="display: table-cell; width:50px;";
    t1.className = "form-control";
    t1.id = "id"+index;
    t1.name= "id[]";
    t1.value=index;
    t1.setAttribute("readonly", "readonly");
    cell1.appendChild(t1);

    var cell2=row.insertCell(1);
    var t2=document.createElement("input");
    t2.style="display: table-cell; width:120px;";
    t2.className = "form-control";
    t2.id = "item_description"+index;
    t2.name= "item_description[]";
    t2.value="";
    cell2.appendChild(t2);


    var cell3 = row.insertCell(2);
    var t3=document.createElement("select");
    t3.className = "form-control";
    var x = $("#unit_id"),
    y = x.clone();
    y.attr("id","unit_id");
    y.attr("name","unit_id[]");
    y.val();
    y.attr("selected","selected"); 
    y.width(140);
    y.appendTo(cell3);


    var cell4 = row.insertCell(3);
    var t4=document.createElement("input");
    t4.style="display: table-cell; width:130px;";
    t4.className = "form-control QTY";
    t4.type="number";
    t4.id = "quantity"+index;
    t4.name="quantity[]";
    cell4.appendChild(t4);

    var cell5 = row.insertCell(4);
    var t5=document.createElement("input");
    t5.style="display: table-cell; width:130px;";
    t5.className = "form-control";
    t5.type="number";
    t5.id = "rate"+index;
    t5.name="rate[]";
    cell5.appendChild(t5);

    var cell6 = row.insertCell(5);
    var t6=document.createElement("input");
    t6.style="display: table-cell; width:80px;";
    t6.className = "form-control TAMT";
    t6.type="text";
    t6.onkeyup="mycalc();";
    t6.id = "total_amount"+index;
    t6.name="total_amount[]";
    t6.setAttribute("readonly", "readonly");
    cell6.appendChild(t6);        

    var cell7 = row.insertCell(6);
    var t7=document.createElement("input");
    t7.style="display: table-cell; width:130px;";
    t7.className = "form-control";
    t7.type="text";
    t7.id = "remark"+index;
    t7.name="remark[]";
    cell7.appendChild(t7);

    var cell8=row.insertCell(7);
    var btnAdd = document.createElement("input");
    btnAdd.style="display: table-cell; width:40px;";
    btnAdd.id = "Abutton";
    btnAdd.name = "Abutton[]";
    btnAdd.type = "button";
    btnAdd.className="btn btn-warning pull-left";
    btnAdd.value = "+";
    cell8.appendChild(btnAdd);

    var btnRemove = document.createElement("INPUT");
    btnRemove.style="display: table-cell; margin-left: 5px;";
    btnRemove.id = "Dbutton";
    btnRemove.type = "button";
    btnRemove.className="btn btn-danger pull-left";
    btnRemove.value = "X";
    btnRemove.setAttribute("onclick", "deleteRow(this)");
    cell8.appendChild(btnRemove);

    var w = $(window);
    var row = $('#footable_2').find('tr').eq( index );

    if (row.length){
        $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
    }

    document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;

    index++;
    recalcId();
    selselect();
    mycalc();

}

$("table.footable_2").on("keyup", 'input[name^="quantity[]"],input[name^="return_quantity[]"],input[name^="rate[]"],input[name^="total_qty[]"],input[name^="total_amount[]"],input[name^="NetAmount[]"]', function (event) {
    CalculateRow($(this).closest("tr"));
});

function CalculateRow(row)
{ 

    var quantity=0;
    var issue_case_id = $('input[name="issue_case_id"]:checked').val();

        // alert(issue_case_id);
    if(issue_case_id == 1){
      quantity=+row.find('input[name^="quantity[]"]').val();
  }
  else{
      quantity=+row.find('input[name^="return_quantity[]"]').val();
  }
  var total_qty=+row.find('input[name^="total_qty[]"]').val();
  var rate=+row.find('input[name^="rate[]"]').val();
  var amount=parseFloat(quantity * rate).toFixed(2);
  var total_amount=0;


  if(quantity>0)
  {

    total_amount=parseFloat(amount);
    row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));

}

mycalc();
}

function selselect()
{
    setTimeout(
        function() 
        {

            $("#footable_2 tr td  select[name='unit_id[]']").each(function() {

                $(this).closest("tr").find('select[name="unit_id[]"]').select2();                   
            });
        }, 100);
}

function mycalc()
{   

    sum1 = 0.0;
    var amounts =0;
    var issue_case_id = $('input[name="issue_case_id"]:checked').val();

        // alert(issue_case_id);
    if(issue_case_id == 1){
       amounts = document.getElementsByClassName('QTY');
   }
   else{

       amounts = document.getElementsByClassName('RTQTY');
   }
        // alert("value="+amounts[0].value);
   for(var i=0; i<amounts.length; i++)
   { 
    var a = +amounts[i].value;
    sum1 += parseFloat(a);
}
        // alert("value="+sum1);
document.getElementById("total_qty").value = sum1.toFixed(2);

sum1 = 0.0;
var amounts = document.getElementsByClassName('TAMT');
        //alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
    var a = +amounts[i].value;
    sum1 += parseFloat(a);
}
document.getElementById("NetAmount").value = sum1.toFixed(2);
}


function deleteRow(btn) {
    if(document.getElementById('cnt').value > 1){
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);

        document.getElementById('cnt').value = document.getElementById('cnt').value-1;
        recalcId();
        mycalc();
        if($("#cnt").val()<=1)
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


$( document ).ready(function() {

    $("#footable_2 tr td  select[name='mainstyle_id[]']").each(function() {

        $(this).closest("tr").find('select[name="mainstyle_id[]"]').select2();
      //$(this).closest("tr").find('select[name="track_code[]"]').select2();

    });
    $("#footable_2 tr td  select[name='deptCostId[]']").each(function() {

        $(this).closest("tr").find('select[name="deptCostId[]"]').select2();
      //$(this).closest("tr").find('select[name="track_code[]"]').select2();

    });


});
$(document).ready(function () {
    $(".select2-selection").on("focus", function () {
        $(this).parent().parent().prev().select2("open");
    });
});

</script>
@endsection