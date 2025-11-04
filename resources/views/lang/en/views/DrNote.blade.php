@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">DR Note</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">DR Note</li>
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
<h4 class="card-title mb-4">DR Note</h4>
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

@if(isset($drnotefetch))
<form action="{{ route('DrNote.update',$drnotefetch) }}" method="POST">
@method('put')

@csrf 

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Firm</label>
<select name="firm_id" class="form-select" id="firm_id">
<option value="">--- Select Firm ---</option>
@foreach($firmlist as  $row)
{
<option value="{{ $row->firm_id }}"

{{ $row->firm_id == $drnotefetch->firm_id ? 'selected="selected"' : '' }}

	>{{ $row->firm_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">DR Note No</label>
<input type="text" name="DrNote_Code" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->DrNote_Code }}">
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
<input type="hidden" name="c_code" value="{{ $drnotefetch->c_code }}" class="form-control" id="c_code">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Date</label>
<input type="date" name="date" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->date }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Type</label>
<select name="tax_type_id" class="form-select" id="tax_type_id" onChange="taxTypeChnage(this.value);">
<option value="">--- Select Gst---</option>
@foreach($gstlist as  $rowgst)
{
<option value="{{ $rowgst->tax_type_id  }}"

{{ $rowgst->tax_type_id == $drnotefetch->tax_type_id ? 'selected="selected"' : '' }}

	>{{ $rowgst->tax_type_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CR A/C</label>
<select name="CrCode" class="form-select" id="CrCode">
<option value="">--- Select Party Name ---</option>
@foreach($ledgerlist as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}"

{{ $rowledger->ac_code == $drnotefetch->CrCode ? 'selected="selected"' : '' }}


	>{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Dr To Party</label>
<select name="DrCode" class="form-select" id="DrCode" onChange="GetPartyDetails(this.value);">
<option value="">--- Select Party Name ---</option>
@foreach($ledgerlist as  $rowledger11)
{
<option value="{{ $rowledger11->ac_code  }}"

{{ $rowledger11->ac_code == $drnotefetch->DrCode ? 'selected="selected"' : '' }}


	>{{ $rowledger11->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>
</div>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST No</label>
<input type="text" name="gst_no"  id="gst_no" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->gst_no }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Party Ref No</label>
<input type="text" name="party_ref_no" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->party_ref_no }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Ag Bill No</label>
<input type="text" name="ag_bill_no" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->ag_bill_no }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Bill Date</label>
<input type="date" name="bill_date" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->bill_date }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">HSN No</label>
<input type="text" name="hsn_no" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->hsn_no }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Basic Amount</label>
<input type="text" name="basic_amount" id="basic_amount" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->basic_amount }}">
</div>
</div>
</div>
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CGST %</label>
<input type="text" name="cgst_per" class="form-control" id="cgst_per" onkeyup="calculateGst();" value="{{ $drnotefetch->cgst_per }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CGST Amount</label>
<input type="text" name="cgst_amount" class="form-control" id="cgst_amount" value="{{ $drnotefetch->cgst_amount }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">SGST %</label>
<input type="text" name="sgst_per" class="form-control" id="sgst_per" onkeyup="calculateGst();" value="{{ $drnotefetch->sgst_per }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">SGST Amount</label>
<input type="text" name="sgst_amount" class="form-control" id="sgst_amount" value="{{ $drnotefetch->sgst_amount }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">IGST %</label>
<input type="text" name="igst_per" class="form-control" id="igst_per" value="{{ $drnotefetch->igst_per }}" onkeyup="calculateGst();">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">IGST Amount</label>
<input type="text" name="igst_amount" class="form-control" id="igst_amount" value="{{ $drnotefetch->igst_amount }}">
</div>
</div>
</div>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Amount</label>
<input type="text" name="gst_amount" class="form-control" id="gst_amount" value="{{ $drnotefetch->gst_amount }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Dr Amount</label>
<input type="text" name="dr_amount" class="form-control" id="dr_amount" value="{{ $drnotefetch->dr_amount }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Broker Name</label>
<select name="br_id" class="form-select" id="br_id">
<option value="">--- Broker Name ---</option>
@foreach($brokerlist as  $rowbroker)
{
<option value="{{ $rowbroker->br_id  }}"

{{ $rowbroker->br_id == $drnotefetch->br_id ? 'selected="selected"' : '' }}


	>{{ $rowbroker->br_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="narration" class="form-control" id="formrow-email-input" value="{{ $drnotefetch->narration }}">
</div>
</div>
</div>


<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>

@else
<form action="{{route('DrNote.store')}}" method="POST">
@csrf 
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Firm</label>
<select name="firm_id" class="form-select" id="firm_id">
<option value="">--- Select Firm ---</option>
@foreach($firmlist as  $row)
{
<option value="{{ $row->firm_id }}">{{ $row->firm_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">DR Note No</label>
<input type="text" name="DrNote_Code" class="form-control" id="formrow-email-input">
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Date</label>
<input type="date" name="date" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Type</label>
<select name="tax_type_id" class="form-select" id="tax_type_id" onChange="taxTypeChnage(this.value);">
<option value="">--- Select Gst---</option>
@foreach($gstlist as  $rowgst)
{
<option value="{{ $rowgst->tax_type_id  }}">{{ $rowgst->tax_type_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CR A/C</label>
<select name="CrCode" class="form-select" id="CrCode">
<option value="">--- Select Party Name ---</option>
@foreach($ledgerlist as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Dr To Party</label>
<select name="DrCode" class="form-select" id="DrCode" onChange="GetPartyDetails(this.value);">
<option value="">--- Select Party Name ---</option>
@foreach($ledgerlist as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>
</div>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST No</label>
<input type="text" name="gst_no"  id="gst_no" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Party Ref No</label>
<input type="text" name="party_ref_no" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Ag Bill No</label>
<input type="text" name="ag_bill_no" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Bill Date</label>
<input type="date" name="bill_date" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">HSN No</label>
<input type="text" name="hsn_no" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Basic Amount</label>
<input type="text" name="basic_amount" id="basic_amount" class="form-control" id="formrow-email-input">
</div>
</div>
</div>
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CGST %</label>
<input type="text" name="cgst_per" class="form-control" id="cgst_per" onkeyup="calculateGst();">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CGST Amount</label>
<input type="text" name="cgst_amount" class="form-control" id="cgst_amount">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">SGST %</label>
<input type="text" name="sgst_per" class="form-control" id="sgst_per" onkeyup="calculateGst();">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">SGST Amount</label>
<input type="text" name="sgst_amount" class="form-control" id="sgst_amount">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">IGST %</label>
<input type="text" name="igst_per" class="form-control" id="igst_per" onkeyup="calculateGst();">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">IGST Amount</label>
<input type="text" name="igst_amount" class="form-control" id="igst_amount">
</div>
</div>
</div>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Amount</label>
<input type="text" name="gst_amount" class="form-control" id="gst_amount">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Dr Amount</label>
<input type="text" name="dr_amount" class="form-control" id="dr_amount">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Broker Name</label>
<select name="br_id" class="form-select" id="br_id">
<option value="">--- Broker Name ---</option>
@foreach($brokerlist as  $rowbroker)
{
<option value="{{ $rowbroker->br_id  }}">{{ $rowbroker->br_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="narration" class="form-control" id="formrow-email-input">
</div>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
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

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script type="text/javascript">
function taxTypeChnage(tax_type_id)
{
	if(tax_type_id ==2)
{

 document.getElementById('cgst_per').readOnly=true;
 document.getElementById('cgst_amount').readOnly=true;
 document.getElementById('sgst_per').readOnly=true;
 document.getElementById('sgst_amount').readOnly=true;
 document.getElementById('igst_per').readOnly=false;
 document.getElementById('igst_amount').readOnly=false;

  
}
else{
 
  document.getElementById('cgst_per').readOnly=false;
 document.getElementById('cgst_amount').readOnly=false;
 document.getElementById('sgst_per').readOnly=false;
 document.getElementById('sgst_amount').readOnly=false;
 document.getElementById('igst_per').readOnly=true;
 document.getElementById('igst_amount').readOnly=true;

}
calculateGst();
}



function calculateGst()
{
var amount=document.getElementById('basic_amount').value;
var cgst_per=document.getElementById('cgst_per').value;
var sgst_per=document.getElementById('sgst_per').value;
var igst_per=document.getElementById('igst_per').value;
 
var tax_type_id1=document.getElementById('tax_type_id').value;
if(tax_type_id1==2)
{
var igst_amount=  parseFloat(( amount*(igst_per/100))).toFixed(2);
$('#igst_amount').val(igst_amount);
$('#cgst_per').val(0);
$('#sgst_per').val(0);
$('#cgst_amount').val(0);
$('#sgst_amount').val(0);
var total_amount=(parseFloat(amount) + parseFloat(igst_amount)).toFixed(2);
var gst_amount=(parseFloat(igst_amount)).toFixed(2);
 $('#gst_amount').val(gst_amount);
$('#dr_amount').val(total_amount);

  
}
else{
var cgst_amount=  parseFloat(( amount*(cgst_per/100))).toFixed(2);
$('#cgst_amount').val(cgst_amount);
var sgst_amount= parseFloat(( amount*(sgst_per/100))).toFixed(2);
$('#sgst_amount').val(sgst_amount);
$('#igst_amount').val(0);
$('#igst_per').val(0);
var total_amount=(parseFloat(amount) + parseFloat(cgst_amount) + parseFloat(sgst_amount)).toFixed(2);
var gst_amount=(parseFloat(cgst_amount) + parseFloat(sgst_amount)).toFixed(2);
 $('#gst_amount').val(gst_amount);
$('#dr_amount').val(total_amount);
  

}
}

	

function GetPartyDetails(DrCode){
    
//alert(firm_id);
$.ajax({
type:"GET",
url: "{{ route('PartyDetail') }}",
dataType:"json",
data:{id:DrCode},
success:function(response){
console.log(response);	

$("#gst_no").val(response[0].gst_no);
 
}
});
} 


</script>




<!-- end row -->


<!-- end row -->
@endsection