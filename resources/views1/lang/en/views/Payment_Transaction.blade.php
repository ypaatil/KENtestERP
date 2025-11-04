@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Payment</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Payment</li>
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
<h4 class="card-title mb-4">Payment</h4>
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

@if(isset($Receiptfetch))

<form action="{{route('Payment_Transaction.update',$Receiptfetch)}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@method('put')
@csrf 
<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Firm</label>
<select name="firm_id" class="form-select" id="firm_id">
<option value="">--- Select Firm ---</option>
@foreach($firmlist as  $row)
{
<option value="{{ $row->firm_id }}"

{{ $row->firm_id == $Receiptfetch->firm_id ? 'selected="selected"' : '' }}

    >{{ $row->firm_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Payment No</label>
 <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PAYMENT' ?>" /> 
    <input type="text" id="TrNo" name="TrNo" class="form-control" placeholder="Transaction No" value="{{ $Receiptfetch->TrNo }}" readonly />
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">

<input type="hidden" name="TrType" value="83" />
<input type="hidden" name="c_code" value="{{ $Receiptfetch->c_code }}" />

</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Payment Date</label>
<input type="date" name="Date" class="form-control" value="<?php echo date('Y-m-d'); ?>" value="{{ $Receiptfetch->Date }}"/>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Ref Code</label>
<input type="text" id="TrNo" name="ref_no" class="form-control" placeholder="Transaction No" value="{{ $Receiptfetch->ref_no }}"  />
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Ref Date</label>
<input type="date" name="ref_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" value="{{ $Receiptfetch->ref_date }}"/>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Payment Mode</label><br>
<input type="radio" name="Pay_mode" value="1" @if($Receiptfetch->Pay_mode==1) checked="Checked" @endif>BY CASH  
<input type="radio" name="Pay_mode" value="2" @if($Receiptfetch->Pay_mode==2) checked="Checked" @endif>BY CHEQUE  
<input type="radio" name="Pay_mode" value="3" @if($Receiptfetch->Pay_mode==3) checked="Checked" @endif>BY NEFT/RTGS  
<input type="radio" name="Pay_mode" value="4" @if($Receiptfetch->Pay_mode==4) checked="Checked" @endif>OTHER
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Cash/Bank AC</label>
<select name="CrCode" class="form-select" id="CrCode">
<option value="">--Select Cash/Bank AC--</option>
@foreach($cashbank as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}"

{{ $rowledger->ac_code == $Receiptfetch->CrCode ? 'selected="selected"' : '' }}

    >{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-4"> <!-- onchange="GetUnpaidBills(this.value) -->
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Party AC/Name</label>
<select name="DrCode" class="form-select" id="DrCode" >
<option value="">--- Select Party AC/Name ---</option>
@foreach($ledgerlist as  $rowledger11)
{
<option value="{{ $rowledger11->ac_code  }}"

{{ $rowledger11->ac_code == $Receiptfetch->DrCode ? 'selected="selected"' : '' }}

    >{{ $rowledger11->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>

</div>

<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Amount</label>
<input type="text" name="Amount" class="form-control" id="BillAmount" onkeyup="disc_calculatess();" value="{{ $Receiptfetch->Amount }}">
</div>
</div>
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="Naration" class="form-control" id="Narration" onkeyup="disc_calculatess();" value="{{ $Receiptfetch->Naration }}">
</div>
</div>
</div>

<div>
<button type="submit" class="btn btn-success w-md">Save</button>
</div>
</form>


@else

<form action="{{route('Payment_Transaction.store')}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@csrf 
<div class="row">
<div class="col-md-4">
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
<label for="formrow-email-input" class="form-label">Payment No</label>
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PAYMENT' ?>" /> 
<input type="text" id="TrNo" name="TrNo" class="form-control" placeholder="Transaction No" value="" readonly />
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">

<input type="hidden" name="TrType" value="83" />
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Payment Date</label>
<input type="date" name="Date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Ref Code</label>
<input type="text" id="TrNo" name="ref_no" class="form-control" placeholder="Transaction No" value=""  />
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Ref Date</label>
<input type="date" name="ref_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Payment Mode</label><br>
<input type="radio" name="Pay_mode" value="1" >BY CASH  
<input type="radio" name="Pay_mode" value="2">BY CHEQUE  
<input type="radio" name="Pay_mode" value="3">BY NEFT/RTGS  
<input type="radio" name="Pay_mode" value="4">OTHER
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Cash/Bank AC</label>
<select name="CrCode" class="form-select" id="CrCode">
<option value="">--Select Cash/Bank AC--</option>
@foreach($cashbank as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-4"> <!-- onchange="GetUnpaidBills(this.value) -->
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Party AC/Name</label>
<select name="DrCode" class="form-select" id="DrCode" >
<option value="">--- Select Party AC/Name ---</option>
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
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Amount</label>
<input type="text" name="Amount" class="form-control" id="BillAmount" onkeyup="disc_calculatess();">
</div>
</div>
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="Naration" class="form-control" id="Narration" onkeyup="disc_calculatess();" value="">
</div>
</div>
</div>

<div>
<button type="submit" class="btn btn-success w-md">Save</button>
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


<!-- end row -->



@endsection