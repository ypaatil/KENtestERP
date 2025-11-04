@extends('layouts.master') 

@section('content')
<style type="text/css">
    .hide{
        display: none;
    }
    .show{
        display: block;
    }
    
    .select2-dropdown.increasezindex {
        z-index:99999;
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
                <form action="{{route('DeliveryChallan.store')}}" method="POST" id="DeliveryChallan">
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
                                                <input tabindex="6" type="date" name="issue_date"  id="issue_date" class="form-control" placeholder="Issue Date"  max="{{date('Y-m-d')}}" required="required" value="{{ date('Y-m-d') }}"  >
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
                                                <select name="product_type" id="product_type"  class="form-control select2" autocomplete="off" data-placeholder="Select" tabindex="1" style="width:200px;" required >
                                                        <option value="">--Select--</option>
                                                        @foreach($item_category_list as $category)
                                                        <option value="{{ $category->cat_id}}">{{ $category->cat_name }}</option>
                                                        @endforeach
                                                </select>
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
                                                <select name="ac_code" id="ac_code"  class="form-control select2" autocomplete="off" data-placeholder="Select" tabindex="1" style="width:200px;" onchange="getAddressForDC(this.value)" required>
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
                                                <input tabindex="13" type="text" name="sent_through"  id="sent_through" class="form-control" placeholder="Sent Through" >
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
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Tax Type</label>
                                                <select name="tax_type_id" class="form-control" id="tax_type_id" required>
                                                    <option value="">--Tax Type--</option>
                                                    @foreach($TaxListing as  $row)
                                                    {
                                                        <option value="{{ $row->tax_type_id }}">{{ $row->tax_type_name }}</option>
                                                    }
                                                    @endforeach
                                                </select>
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
                                                    {{-- <th>Tax Type</th> --}}
                                                    <th>GST %</th>
                                                    <th>Quantity</th>
                                                    <th>Base Rate</th>
                                                    <th>Amount</th>
                                                    <th>GST AMT</th>
                                                    <th>TAMOUNT</th>
                                                    <th>Remark</th>
                                                    <th>Add/Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="id[]" value="1" id="id" style="width:50px;" readonly/></td>
                                                    <td>
                                                        <input type="text" name="item_description[]" id="item_description"  tabindex="17" class="form-control"  required="required" style="width:200px;">
                                                    </td>

                                                    <td>
                                                        <select class="form-control select2" data-placeholder="Choose one" name="unit_id[]" style="width:100px;" tabindex="18" id="unit_id" required data-parsley-errors-container="#field1">

                                                            <option value="">--- Select Unit ---</option>
                                                            @foreach($unitlist as  $rowunit)
                                                            {
                                                                <option value="{{ $rowunit->unit_id }}">{{ $rowunit->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    {{-- <td>
                                                        <select name="tax_type_id[]" class="form-control" style="width:140px;" id="tax_type_id">
                                                            <option value="">--Tax Type--</option>
                                                            @foreach($TaxListing as  $row)
                                                            {
                                                                <option value="{{ $row->tax_type_id }}">{{ $row->tax_type_name }}</option>
                                                            }
                                                            @endforeach
                                                        </select>
                                                    </td> --}}
                                                    <td>
                                                        <select name="gst_per[]" class="form-control" style="width:100px;"  id="gst_per" onchange="setGST(this);">
                                                            <option value="">-</option>
                                                            <option value="5">5 %</option>
                                                            <option value="12">12 %</option>
                                                            <option value="18">18 %</option> 
                                                            <option value="28">28 %</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[]" id="quantity1" class="form-control QTY" tabindex="19" step="any" onkeyup="mycalc();"  required="required" style="width:120px;">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="rate[]" id="rate" class="form-control" onkeyup="mycalc();" tabindex="20" value="0" step="any" required="required" style="width:120px;">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="any" class="form-control AMT"  name="amount[]" onkeyup="mycalc();" value="0" id="amount1" style="width:80px;" required readonly/> 
                                                        <input type="hidden"   name="cgst_per[]" onkeyup="mycalc();" value="0" id="cgst_per" style="width:80px;" required/>
                                                        <input type="hidden"   name="cgst_amt[]" onkeyup="mycalc();" value="0" id="cgst_amt1" style="width:80px;" required/>
                                                        <input type="hidden"   name="sgst_per[]" onkeyup="mycalc();" value="0" id="sgst_per" style="width:80px;" required/>
                                                        <input type="hidden"   name="sgst_amt[]" onkeyup="mycalc();" value="0" id="sgst_amt1" style="width:80px;" required/>
                                                        <input type="hidden"   name="igst_per[]" onkeyup="mycalc();" value="0" id="igst_per" style="width:80px;" required/>
                                                        <input type="hidden"   name="igst_amt[]" onkeyup="mycalc();" value="0" id="igst_amt1" style="width:80px;" required/>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="any" readOnly class="form-control GST" name="gst_amt[]"   value="0" id="gst_amt1" style="width:80px;" required />
                                                    </td>
                                                    <td>
                                                        <input type="number" step="any" class="form-control TAMT"  name="total_amount[]" onkeyup="mycalc();"  id="total_amount1" style="width:80px;" required readonly />
                                                    </td>

                                                    <td>
                                                        <input type="text" name="remark[]" id="remark" class="form-control" tabindex="21"  style="width:120px;">
                                                    </td>
                                                    <td>
                                                        <input type="button"  style="width:40px; margin-right: 5px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left " onclick="deleteRow(this);" value="X" >
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="total_qty" class="form-label">Total Qty</label>
                                            <input type="number" step="any"  name="total_qty" class="form-control" id="total_qty" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="GrossAmount" class="form-label">Gross Amount</label>
                                            <input type="number" step="any"  name="GrossAmount" class="form-control" id="GrossAmount" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="GstAmount" class="form-label">GST Amount</label>
                                            <input type="number" step="any"   name="GstAmount" class="form-control" id="GstAmount" value="0" onkeyup="mycalc();">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="NetAmount" class="form-label">Net Amount</label>
                                            <input type="number" step="any"  name="NetAmount" class="form-control" id="NetAmount" value="0" onkeyup="mycalc();">
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
                                        <button type="submit" onclick="EnableFields();" class="btn btn-primary w-md"  id="Submit" >Submit</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.js" ></script>
<script type="text/javascript">

   $('#Submit').click(function(e)
   {  
        var isValid = true;
        $('#DeliveryChallan input, #DeliveryChallan textarea, #DeliveryChallan select').each(function() 
        {
          if($(this).prop('required'))
          {
              if($(this).val() === '')
              {
                  isValid = false;
              }
          }
        });
     
        if(isValid==true)
        {
            $("#Submit").prop('disabled', true);
            $("#Submit").text("Please wait...");
            $("#DeliveryChallan").submit(); 
        }
        else
        {
            e.preventDefault();
            alert('Fill all the compulsory fields');
        }
   }); 
    
    $( document ).ready(function() 
    {
        $('#non-returnable').attr('checked', 'checked');
        $('#IssueDelivery').attr('checked', 'checked');
        $('#return_date').removeAttr('required');
        
        $('#tax_type_id').val(3);
    });

    function returnableOrNonReturnable() 
    {
        if (document.getElementById('returnable').checked) {
            document.getElementById('Return').style.display = 'block';
        }
        else document.getElementById('Return').style.display = 'none';
    }

    function getData() 
    {

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

        $("#tax_type_id").attr('disabled', true);

        $("#otherBuyerorVendor").attr("readonly", "readonly");

        $("#from_location").attr("readonly", "readonly");
    }




    function recieverChange()
    {
        var radioValue = $("input[name='reciever_type']:checked").val();
    // alert(radioValue);
        if( radioValue == 1){
            $(".Others").removeClass('hide'); 
            $(".BuyerOrVendor").addClass('hide');
            $("#ac_code").removeAttr('required');
        }
        else if(radioValue == 2)
        {
            $(".BuyerOrVendor").removeClass('hide');
            $(".Others").addClass('hide');
            $("#ac_code").attr('required','required');
            
            $.ajax({
                type: "GET",
                dataType:"json",
                url: "{{ route('GetVendorBuyerWiseData') }}",
                data:{'type':4},
                success: function(response)
                {
                    $("#ac_code").html(response.html);
                }
            });
            

        }
        else if(radioValue == 3)
        {
            $(".BuyerOrVendor").removeClass('hide');
            $(".Others").addClass('hide');
            $("#ac_code").attr('required','required');
            
            $.ajax({
                type: "GET",
                dataType:"json",
                url: "{{ route('GetVendorBuyerWiseData') }}",
                data:{'type':2},
                success: function(response)
                {
                    $("#ac_code").html(response.html);
                }
            });
        }
        else
        {
            $(".BuyerOrVendor").addClass('hide');
            $(".Others").addClass('hide');
        }
    }


    function removeAddress() 
    {
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
                // console.log(response);
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
                        setAcCode(response);
                        $("#otherBuyerorVendor").val(response[0]['otherBuyerorVendor']);
                    }
                    else if (response[0]['reciever_type'] ==  2) {
                        $('input:radio[name=reciever_type][id=vendor]').attr('checked', 'checked');
                        recieverChange();
                        setAcCode(response);
                        $("#ac_code").val(response[0]['ac_code']);
                    }
                    else if (response[0]['reciever_type'] ==  3) {
                        $('input:radio[name=reciever_type][id=buyer]').attr('checked', 'checked');
                        recieverChange();
                        setAcCode(response);
                        $("#ac_code").val(response[0]['ac_code']);
                    }

                    $("#sent_through").val(response[0]['sent_through']);
                    $("#dept_id").val(response[0]["dept_id"]);
                    var q=response[0]["dept_name"];
                    $('#select2-dept_id-container').html(q);
                    $("#dept_id").val(response[0]['dept_id']);
                    $("#tax_type_id").val(response[0]["tax_type_id"]);
                    var q=response[0]["tax_type_name"];
                    $('#select2-tax_type_id-container').html(q);
                    $("#tax_type_id").val(response[0]['tax_type_id']);
                    $("#from_location").val(response[0]['from_location']);
                    $("#to_location").val(response[0]['to_location']);
                    $("#total_qty").val(response[0]['total_qty']);
                    $("#GrossAmount").val(response[0]['GrossAmount']);
                    $("#GstAmount").val(response[0]['GstAmount']);
                    $("#NetAmount").val(response[0]['NetAmount']);
                    $("#narration").val(response[0]['narration']);
                   
                }
            });
        }

        getDeliveryChallanDetailsData(issue_no);
        

    }
    function setAcCode(row)
    {
         setTimeout(function() {
            $("#ac_code").val(row[0]['ac_code']).change();
            $("#product_type").val(row[0]['product_type']).change();
            $("#dept_id").val(row[0]['dept_id']).change();
            getAddressForDC(row[0]['ac_code']);
        }, 500);
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
                setTimeout(function() {
                    var html_Data = $('.delivery');
                    $.each(html_Data, function (i) 
                    {
                        var row = $(html_Data).find('td select[name="gst_per[]"]');
                        //console.log(row);
                        var tax_type_id =$("#tax_type_id").val();
                        var per = $(row).val();


                        if(tax_type_id==1)
                        {
                            var perhalf=(per/2).toFixed(2);
                            // $("#cgst_per").val(perhalf);
                            var cgst = $(html_Data).find('td input[name="cgst_per[]"]')[i];
                            var sgst = $(html_Data).find('td input[name="sgst_per[]"]')[i];
                            // console.log(cgst);
                            // console.log(sgst);
                            $(cgst).val(perhalf);
                            $(sgst).val(perhalf);
                           // $("#sgst_per").val(perhalf);

                        }
                        else if(tax_type_id==2)
                        {
                            var igst = $(html_Data).find('td input[name="igst_per[]"]')[i];
                            $(igst).val(per);
                        }
                        else if(tax_type_id==3)
                        {
                            var cgst = $(html_Data).find('td input[name="cgst_per[]"]')[i];
                            var sgst = $(html_Data).find('td input[name="sgst_per[]"]')[i];
                            var igst = $(html_Data).find('td input[name="igst_per[]"]')[i];

                            $(cgst).val(0);
                            $(sgst).val(0);
                            $(igst).val(0);
                        }

                    });
                }, 1000);
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
        $("#tax_type_id").val('');
        $("#tax_type_id").prop("disabled",false);
        $("#from_location").val('');
        $("#from_location").removeAttr("readonly");
        $("#to_location").val('');
        $("#total_qty").val('');
        $("#NetAmount").val('');
        $("#narration").val('');
        getDeliveryChallanDetailsData('');
    }

    $(document).on("click", 'input[name^="Abutton[]"]', function (event) 
    {
        $(".select2").select2('destroy');
        var tr = $(this).closest('tr');
        var clone = tr.clone();
        clone.find('input[type="number"]').val('');
        clone.find('input[type="text"]').val('');
        clone.find('input[type="select"]').val('');
        tr.after(clone);
        recalcId();
        mycalc();
        $(".select2").select2();
    }); 
    
    //var index = 2;
    // function insertRow(row)
    // {
        
    //     var tr = $(this).closest('tr');
    //     var clone = tr.clone();
    //     clone.find('input[type="number"]').val('');
    //     clone.find('input[type="text"]').val('');
    //     clone.find('input[type="select"]').val('');
    //     tr.after(clone);

    //     var rowsx=$(row).closest("tr");

    //     setTimeout(function(){
    //         if ($('select').data('select2')) {
    //           $('.select2').select2('destroy');
    //       }
    //   }, 500);   

    //     var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
    //     var row=table.insertRow(table.rows.length);

    //     var cell1=row.insertCell(0);
    //     var t1=document.createElement("input");
    //     t1.style="display: table-cell; width:50px;";
    //     t1.className = "form-control";
    //     t1.id = "id"+index;
    //     t1.name= "id[]";
    //     t1.value=index;
    //     t1.setAttribute("readonly", "readonly");
    //     cell1.appendChild(t1);

    //     var cell2=row.insertCell(1);
    //     var t2=document.createElement("input");
    //     t2.style="display: table-cell; width:120px;";
    //     t2.className = "form-control";
    //     t2.id = "item_description"+index;
    //     t2.name= "item_description[]";
    //     t2.value="";
    //     cell2.appendChild(t2);


    //     var cell3 = row.insertCell(2);
    //     var t3=document.createElement("select");
    //     t3.className = "form-control";
    //     var x = $("#unit_id"),
    //     y = x.clone();
    //     y.attr("id","unit_id");
    //     y.attr("name","unit_id[]");
    //     y.val();
    //     y.attr("selected","selected"); 
    //     y.width(140);
    //     y.appendTo(cell3);

    //     // var cell4 = row.insertCell(3);
    //     // var t4=document.createElement("select");
    //     // t4.className = "form-control";
    //     // var x = $("#tax_type_id"),
    //     // y = x.clone();
    //     // y.attr("id","tax_type_id");
    //     // y.attr("name","tax_type_id[]");
    //     // y.val();
    //     // y.attr("selected","selected"); 
    //     // y.width(140);
    //     // y.appendTo(cell4);

    //     var cell4 = row.insertCell(3);
    //     var t4=document.createElement("select");
    //     t4.className = "form-control";
    //     var x = $("#gst_per"),
    //     y = x.clone();
    //     y.attr("id","gst_per");
    //     y.attr("name","gst_per[]");
    //     y.val();
    //     y.attr("selected","selected"); 
    //     y.onchange="setGST(this);";
    //     y.width(140);
    //     y.appendTo(cell4);


    //     var cell5 = row.insertCell(4);
    //     var t5=document.createElement("input");
    //     t5.style="display: table-cell; width:120px;";
    //     t5.className = "form-control QTY";
    //     t5.type="number";
    //     t5.id = "quantity"+index;
    //     t5.name="quantity[]";
    //     t5.setAttribute("step","any");
    //     cell5.appendChild(t5);

    //     var cell6= row.insertCell(5);
    //     var t6=document.createElement("input");
    //     t6.style="display: table-cell; width:120px;";
    //     t6.type="number";
    //     t6.className = "form-control";
    //     t6.required="true";
    //     t6.onkeyup="mycalc();";
    //     t6.id = "rate"+index;
    //     t6.name="rate[]";
    //     t6.onkeyup=mycalc();
    //     t6.setAttribute("step","any");
    //     t6.value=+rowsx.find('input[name^="rate[]"]').val();
    //     t6.setAttribute("onkeyup", "mycalc();");
    //     cell6.appendChild(t6);

    //     var cell7 = row.insertCell(6);
    //     var t7=document.createElement("input");
    //     t7.style="display: table-cell; width:80px;";
    //     t7.type="number";
    //     t7.onkeyup="mycalc();";
    //     t7.className="form-control AMT";
    //     t7.id = "amount"+index;
    //     t7.name="amount[]";
    //     t7.onkeyup=mycalc();
    //     t7.setAttribute("step","any");
    //     t7.setAttribute("readonly","readonly");
    //     cell7.appendChild(t7);

    //     var t8=document.createElement("input");
    //     t8.style="display: table-cell; width:80px;";
    //     t8.type="hidden";
    //     t8.id = "cgst_per"+index;
    //     t8.name="cgst_per[]";
    //     t8.value=+rowsx.find('input[name^="cgst_per[]"]').val();
    //     cell7.appendChild(t8);

    //     var t9=document.createElement("input");
    //     t9.style="display: table-cell; width:80px;";
    //     t9.type="hidden";
    //     t9.onkeyup="mycalc();";
    //     t9.id = "cgst_amt"+index;
    //     t9.name="cgst_amt[]";
    //     cell7.appendChild(t9);

    //     var t10=document.createElement("input");
    //     t10.style="display: table-cell; width:80px;";
    //     t10.type="hidden";
    //     t10.id = "sgst_per"+index;
    //     t10.name="sgst_per[]";
    //     t10.value=+rowsx.find('input[name^="sgst_per[]"]').val();
    //     cell7.appendChild(t10);


    //     var t11=document.createElement("input");
    //     t11.style="display: table-cell; width:80px;";
    //     t11.type="hidden";
    //     t11.onkeyup="mycalc();";
    //     t11.id = "sgst_amt"+index;
    //     t11.name="sgst_amt[]";
    //     cell7.appendChild(t11);


    //     var t12=document.createElement("input");
    //     t12.style="display: table-cell; width:80px;";
    //     t12.type="hidden";
    //     t12.id = "igst_per"+index;
    //     t12.name="igst_per[]";
    //     t12.value=+rowsx.find('input[name^="igst_per[]"]').val();
    //     cell7.appendChild(t12);

    //     var t13=document.createElement("input");
    //     t13.style="display: table-cell; width:80px;";
    //     t13.type="hidden";
    //     t13.onkeyup="mycalc();";
    //     t13.id = "igst_amt"+index;
    //     t13.name="igst_amt[]";
    //     cell7.appendChild(t13);

    //     var cell14 = row.insertCell(7);
    //     var t14=document.createElement("input");
    //     t14.style="display: table-cell; width:80px;";
    //     t14.type="text";
    //     t14.onkeyup="mycalc();";
    //     t14.id = "gst_amt"+index;
    //     t14.name="gst_amt[]";
    //     t14.setAttribute("step","any");
    //     t14.className="form-control GST";
    //     t14.setAttribute("readonly", "readonly");
    //     cell14.appendChild(t14);

    //     var cell15 = row.insertCell(8);
    //     var t15=document.createElement("input");
    //     t15.style="display: table-cell; width:80px;";
    //     t15.className = "form-control TAMT";
    //     t15.type="text";
    //     t15.onkeyup="mycalc();";
    //     t15.id = "total_amount"+index;
    //     t15.name="total_amount[]";
    //     t15.setAttribute("step","any");
    //     t15.setAttribute("readonly", "readonly");
    //     cell15.appendChild(t15);

    //     var cell16 = row.insertCell(9);
    //     var t16=document.createElement("input");
    //     t16.style="display: table-cell; width:120px;";
    //     t16.className = "form-control";
    //     t16.type="text";
    //     t16.id = "remark"+index;
    //     t16.name="remark[]";
    //     cell16.appendChild(t16);

    //     var cell19=row.insertCell(10);
    //     var btnAdd = document.createElement("input");
    //     btnAdd.style="display: table-cell; width:40px;";
    //     btnAdd.id = "Abutton";
    //     btnAdd.name = "Abutton[]";
    //     btnAdd.type = "button";
    //     btnAdd.className="btn btn-warning pull-left";
    //     btnAdd.value = "+";
    //     cell19.appendChild(btnAdd);

    //     var btnRemove = document.createElement("INPUT");
    //     btnRemove.style="display: table-cell; margin-left: 5px;";
    //     btnRemove.id = "Dbutton";
    //     btnRemove.type = "button";
    //     btnRemove.className="btn btn-danger pull-left";
    //     btnRemove.value = "X";
    //     btnRemove.setAttribute("onclick", "deleteRow(this)");
    //     cell19.appendChild(btnRemove);

    //     var w = $(window);
    //     var row = $('#footable_2').find('tr').eq( index );

    //     if (row.length){
    //         $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
    //     }

    //     document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;

    //     index++;
    //     recalcId();
    //     selselect();
    //     mycalc();
        
        
        

    // }


    $(document).on("keyup", 'input[name^="quantity[]"],input[name^="return_quantity[]"],input[name^="rate[]"],input[name^="total_qty[]"],select[name^="gst_per[]"],input[name^="cgst_amt[]"],input[name^="sgst_per[]"],input[name^="sgst_amt[]"],input[name^="igst_per[]"],input[name^="igst_amt[]"],input[name^="amount[]"],input[name^="total_amount[]"],input[name^="gst_amt[]"],input[name^="GrossAmount[]"],input[name^="GstAmount[]"],input[name^="NetAmount[]"]', function (event) {
        CalculateRow($(this).closest("tr"));
    });

    function CalculateRow(row)
    { 

        var quantity=0;
        var issue_case_id = $('input[name="issue_case_id"]:checked').val();

    // console.log(issue_case_id);

    // alert(issue_case_id);
        if(issue_case_id == 1)
        {
            quantity=+row.find('input[name^="quantity[]"]').val();
        }
        else
        {
            quantity=+row.find('input[name^="return_quantity[]"]').val();
        }
        var total_qty=+row.find('input[name^="total_qty[]"]').val();
        var rate=+row.find('input[name^="rate[]"]').val();
    // console.log(quantity);
    // console.log(rate);
        var amount=parseFloat(quantity * rate).toFixed(2);
    // var total_amount=0;
    // console.log(amount);
        if(quantity>0)
        {
            total_amount=parseFloat(amount);
            row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
        // console.log(total_amount);
        }

        var total_amount=+row.find('input[name^="total_amount[]"]').val();
        var cgst_per=+row.find('input[name^="cgst_per[]"]').val();
    // console.log(cgst_per);
        var cgst_amt=+row.find('input[name^="cgst_amt[]"]').val();
        var igst_per=+row.find('input[name^="igst_per[]"]').val();
        var igst_amt=+row.find('input[name^="igst_amt[]"]').val();
        var sgst_per=+row.find('input[name^="sgst_per[]"]').val();
    // console.log(sgst_per);
        var sgst_amt=+row.find('input[name^="sgst_amt[]"]').val();
        var GrossAmount= +row.find('input[name^="GrossAmount[]"]').val();
        var GstAmount= +row.find('input[name^="GstAmount[]"]').val();
        var NetAmount=+row.find('input[name^="NetAmount[]"]').val();
    // console.log(amount);
        row.find('input[name^="amount[]"]').val(amount);
        if(quantity>0)
        {
            if(igst_per!=0)
            {
                igst_amt=parseFloat(amount*(igst_per/100)).toFixed(2);
                row.find('input[name^="igst_amt[]"]').val(parseFloat(igst_amt));
                total_amount=parseFloat(amount)+parseFloat(igst_amt);
                row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
                row.find('input[name^="gst_amt[]"]').val(parseFloat(igst_amt));
            // console.log(igst_amt);
                row.find('input[name^="cgst_per[]"]').val(0);
                row.find('input[name^="cgst_amt[]"]').val(0);
                row.find('input[name^="sgst_per[]"]').val(0);
                row.find('input[name^="sgst_amt[]"]').val(0);
            }
            else
            {
                row.find('input[name^="igst_per[]"]').val(0);
                row.find('input[name^="igst_amt[]"]').val(0);
                cgst_amt=parseFloat(amount*(cgst_per/100)).toFixed(2);
            // console.log(cgst_amt);
                row.find('input[name^="cgst_amt[]"]').val(parseFloat(cgst_amt));
                row.find('input[name^="sgst_per[]"]').val(parseFloat(cgst_per));
                row.find('input[name^="sgst_amt[]"]').val(parseFloat(cgst_amt));

                sgst_amt=parseFloat(amount*(cgst_per/100)).toFixed(2);
                row.find('input[name^="sgst_amt[]"]').val(parseFloat(sgst_amt));
            // console.log(sgst_amt);

                total_amount=parseFloat(amount)+parseFloat(cgst_amt)+parseFloat(sgst_amt);
                row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
                row.find('input[name^="gst_amt[]"]').val(parseFloat(cgst_amt)+parseFloat(sgst_amt));
            }
        }

        mycalc();
    }


    function setGST(row)
    {
        var tax_type_id=$("#tax_type_id").val();
        
        var per = $(row).val();
        
        
        if(tax_type_id==1)
        {
            var perhalf=(per/2).toFixed(2);
        // $("#cgst_per").val(perhalf);
            var cgst = $(row).closest('tr').find('td input[name="cgst_per[]"]')[0];
            var sgst = $(row).closest('tr').find('td input[name="sgst_per[]"]')[0];
            // console.log(cgst);
            // console.log(sgst);
            $(cgst).val(perhalf);
            $(sgst).val(perhalf);
       // $("#sgst_per").val(perhalf);

        }
        else if(tax_type_id==2)
        {
            $(row).parent().parent('tr').find('td input[name^="igst_per[]"]').val(parseFloat(per));
        }
        else if(tax_type_id==3)
        {
            $(row).parent().parent('tr').find('td input[name^="cgst_per[]"]').val(0);
            $(row).parent().parent('tr').find('td input[name^="igst_per[]"]').val(0);
            $(row).parent().parent('tr').find('td input[name^="sgst_per[]"]').val(0);
        }
        
    }   

    function selselect()
    {
        setTimeout(
            function() 
            {
                $("#footable_2 tr td  select[name='unit_id[]']").each(function() 
                {
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
        if(issue_case_id == 1)
        {
            amounts = document.getElementsByClassName('QTY');
        }
        else
        {
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
        var amounts = document.getElementsByClassName('AMT');
        // alert("value="+amounts[0].value);
        for(var i=0; i<amounts .length; i++)
        { 
            var a = +amounts[i].value;
            sum1 += parseFloat(a);
        }
        document.getElementById("GrossAmount").value = sum1.toFixed(2);

        sum1 = 0.0;
        var amounts = document.getElementsByClassName('GST');
        // alert("value="+amounts[0].value);
        for(var i=0; i<amounts .length; i++)
        { 
            var a = +amounts[i].value;
            sum1 += parseFloat(a);
        }
        document.getElementById("GstAmount").value = sum1.toFixed(2);

        sum1 = 0.0;
        var amounts = document.getElementsByClassName('TAMT');
        // alert("value="+amounts[0].value);
        for(var i=0; i<amounts .length; i++)
        { 
            var a = +amounts[i].value;
            sum1 += parseFloat(a);
        }
        document.getElementById("NetAmount").value = sum1.toFixed(2);
    }


    function deleteRow(btn) 
    {

        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
        
        recalcId();
        mycalc();
         
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