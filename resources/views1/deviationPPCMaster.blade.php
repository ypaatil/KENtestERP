@extends('layouts.master') 
@section('content')
<style>

    .datepicker {
      padding: 4px;
      -webkit-border-radius: 4px;
      -moz-border-radius: 4px;
      border-radius: 4px;
      direction: ltr;
    }
    .datepicker-inline {
      width: 220px;
    }
    .datepicker.datepicker-rtl {
      direction: rtl;
    }
    .datepicker.datepicker-rtl table tr td span {
      float: right;
    }
    .datepicker-dropdown {
      top: 0;
      left: 0;
    }
    .datepicker-dropdown:before {
      content: '';
      display: inline-block;
      border-left: 7px solid transparent;
      border-right: 7px solid transparent;
      border-bottom: 7px solid #999999;
      border-top: 0;
      border-bottom-color: rgba(0, 0, 0, 0.2);
      position: absolute;
    }
    .datepicker-dropdown:after {
      content: '';
      display: inline-block;
      border-left: 6px solid transparent;
      border-right: 6px solid transparent;
      border-bottom: 6px solid #ffffff;
      border-top: 0;
      position: absolute;
    }
    .datepicker-dropdown.datepicker-orient-left:before {
      left: 6px;
    }
    .datepicker-dropdown.datepicker-orient-left:after {
      left: 7px;
    }
    .datepicker-dropdown.datepicker-orient-right:before {
      right: 6px;
    }
    .datepicker-dropdown.datepicker-orient-right:after {
      right: 7px;
    }
    .datepicker-dropdown.datepicker-orient-bottom:before {
      top: -7px;
    }
    .datepicker-dropdown.datepicker-orient-bottom:after {
      top: -6px;
    }
    .datepicker-dropdown.datepicker-orient-top:before {
      bottom: -7px;
      border-bottom: 0;
      border-top: 7px solid #999999;
    }
    .datepicker-dropdown.datepicker-orient-top:after {
      bottom: -6px;
      border-bottom: 0;
      border-top: 6px solid #ffffff;
    }
    .datepicker > div {
      display: none;
    }
    .datepicker table {
      margin: 0;
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }
    .datepicker td,
    .datepicker th {
      text-align: center;
      width: 20px;
      height: 20px;
      -webkit-border-radius: 4px;
      -moz-border-radius: 4px;
      border-radius: 4px;
      border: none;
    }
    .table-striped .datepicker table tr td,
    .table-striped .datepicker table tr th {
      background-color: transparent;
    }
    .datepicker table tr td.day:hover,
    .datepicker table tr td.day.focused {
      background: #eeeeee;
      cursor: pointer;
    }
    .datepicker table tr td.old,
    .datepicker table tr td.new {
      color: #999999;
    }
    .datepicker table tr td.disabled,
    .datepicker table tr td.disabled:hover {
      background: none;
      color: #999999;
      cursor: default;
    }
    .datepicker table tr td.highlighted {
      background: #d9edf7;
      border-radius: 0;
    }
    .datepicker table tr td.today,
    .datepicker table tr td.today:hover,
    .datepicker table tr td.today.disabled,
    .datepicker table tr td.today.disabled:hover {
      background-color: #fde19a;
      background-image: -moz-linear-gradient(to bottom, #fdd49a, #fdf59a);
      background-image: -ms-linear-gradient(to bottom, #fdd49a, #fdf59a);
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fdd49a), to(#fdf59a));
      background-image: -webkit-linear-gradient(to bottom, #fdd49a, #fdf59a);
      background-image: -o-linear-gradient(to bottom, #fdd49a, #fdf59a);
      background-image: linear-gradient(to bottom, #fdd49a, #fdf59a);
      background-repeat: repeat-x;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdd49a', endColorstr='#fdf59a', GradientType=0);
      border-color: #fdf59a #fdf59a #fbed50;
      border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
      filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
      color: #000;
    }
    .datepicker table tr td.today:hover,
    .datepicker table tr td.today:hover:hover,
    .datepicker table tr td.today.disabled:hover,
    .datepicker table tr td.today.disabled:hover:hover,
    .datepicker table tr td.today:active,
    .datepicker table tr td.today:hover:active,
    .datepicker table tr td.today.disabled:active,
    .datepicker table tr td.today.disabled:hover:active,
    .datepicker table tr td.today.active,
    .datepicker table tr td.today:hover.active,
    .datepicker table tr td.today.disabled.active,
    .datepicker table tr td.today.disabled:hover.active,
    .datepicker table tr td.today.disabled,
    .datepicker table tr td.today:hover.disabled,
    .datepicker table tr td.today.disabled.disabled,
    .datepicker table tr td.today.disabled:hover.disabled,
    .datepicker table tr td.today[disabled],
    .datepicker table tr td.today:hover[disabled],
    .datepicker table tr td.today.disabled[disabled],
    .datepicker table tr td.today.disabled:hover[disabled] {
      background-color: #fdf59a;
    }
    .datepicker table tr td.today:active,
    .datepicker table tr td.today:hover:active,
    .datepicker table tr td.today.disabled:active,
    .datepicker table tr td.today.disabled:hover:active,
    .datepicker table tr td.today.active,
    .datepicker table tr td.today:hover.active,
    .datepicker table tr td.today.disabled.active,
    .datepicker table tr td.today.disabled:hover.active {
      background-color: #fbf069 \9;
    }
    .datepicker table tr td.today:hover:hover {
      color: #000;
    }
    .datepicker table tr td.today.active:hover {
      color: #fff;
    }
    .datepicker table tr td.range,
    .datepicker table tr td.range:hover,
    .datepicker table tr td.range.disabled,
    .datepicker table tr td.range.disabled:hover {
      background: #eeeeee;
      -webkit-border-radius: 0;
      -moz-border-radius: 0;
      border-radius: 0;
    }
    .datepicker table tr td.range.today,
    .datepicker table tr td.range.today:hover,
    .datepicker table tr td.range.today.disabled,
    .datepicker table tr td.range.today.disabled:hover {
      background-color: #f3d17a;
      background-image: -moz-linear-gradient(to bottom, #f3c17a, #f3e97a);
      background-image: -ms-linear-gradient(to bottom, #f3c17a, #f3e97a);
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f3c17a), to(#f3e97a));
      background-image: -webkit-linear-gradient(to bottom, #f3c17a, #f3e97a);
      background-image: -o-linear-gradient(to bottom, #f3c17a, #f3e97a);
      background-image: linear-gradient(to bottom, #f3c17a, #f3e97a);
      background-repeat: repeat-x;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f3c17a', endColorstr='#f3e97a', GradientType=0);
      border-color: #f3e97a #f3e97a #edde34;
      border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
      filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
      -webkit-border-radius: 0;
      -moz-border-radius: 0;
      border-radius: 0;
    }
    .datepicker table tr td.range.today:hover,
    .datepicker table tr td.range.today:hover:hover,
    .datepicker table tr td.range.today.disabled:hover,
    .datepicker table tr td.range.today.disabled:hover:hover,
    .datepicker table tr td.range.today:active,
    .datepicker table tr td.range.today:hover:active,
    .datepicker table tr td.range.today.disabled:active,
    .datepicker table tr td.range.today.disabled:hover:active,
    .datepicker table tr td.range.today.active,
    .datepicker table tr td.range.today:hover.active,
    .datepicker table tr td.range.today.disabled.active,
    .datepicker table tr td.range.today.disabled:hover.active,
    .datepicker table tr td.range.today.disabled,
    .datepicker table tr td.range.today:hover.disabled,
    .datepicker table tr td.range.today.disabled.disabled,
    .datepicker table tr td.range.today.disabled:hover.disabled,
    .datepicker table tr td.range.today[disabled],
    .datepicker table tr td.range.today:hover[disabled],
    .datepicker table tr td.range.today.disabled[disabled],
    .datepicker table tr td.range.today.disabled:hover[disabled] {
      background-color: #f3e97a;
    }
    .datepicker table tr td.range.today:active,
    .datepicker table tr td.range.today:hover:active,
    .datepicker table tr td.range.today.disabled:active,
    .datepicker table tr td.range.today.disabled:hover:active,
    .datepicker table tr td.range.today.active,
    .datepicker table tr td.range.today:hover.active,
    .datepicker table tr td.range.today.disabled.active,
    .datepicker table tr td.range.today.disabled:hover.active {
      background-color: #efe24b \9;
    }
    .datepicker table tr td.selected,
    .datepicker table tr td.selected:hover,
    .datepicker table tr td.selected.disabled,
    .datepicker table tr td.selected.disabled:hover {
      background-color: #9e9e9e;
      background-image: -moz-linear-gradient(to bottom, #b3b3b3, #808080);
      background-image: -ms-linear-gradient(to bottom, #b3b3b3, #808080);
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#b3b3b3), to(#808080));
      background-image: -webkit-linear-gradient(to bottom, #b3b3b3, #808080);
      background-image: -o-linear-gradient(to bottom, #b3b3b3, #808080);
      background-image: linear-gradient(to bottom, #b3b3b3, #808080);
      background-repeat: repeat-x;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#b3b3b3', endColorstr='#808080', GradientType=0);
      border-color: #808080 #808080 #595959;
      border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
      filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
      color: #fff;
      text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    }
    .datepicker table tr td.selected:hover,
    .datepicker table tr td.selected:hover:hover,
    .datepicker table tr td.selected.disabled:hover,
    .datepicker table tr td.selected.disabled:hover:hover,
    .datepicker table tr td.selected:active,
    .datepicker table tr td.selected:hover:active,
    .datepicker table tr td.selected.disabled:active,
    .datepicker table tr td.selected.disabled:hover:active,
    .datepicker table tr td.selected.active,
    .datepicker table tr td.selected:hover.active,
    .datepicker table tr td.selected.disabled.active,
    .datepicker table tr td.selected.disabled:hover.active,
    .datepicker table tr td.selected.disabled,
    .datepicker table tr td.selected:hover.disabled,
    .datepicker table tr td.selected.disabled.disabled,
    .datepicker table tr td.selected.disabled:hover.disabled,
    .datepicker table tr td.selected[disabled],
    .datepicker table tr td.selected:hover[disabled],
    .datepicker table tr td.selected.disabled[disabled],
    .datepicker table tr td.selected.disabled:hover[disabled] {
      background-color: #808080;
    }
    .datepicker table tr td.selected:active,
    .datepicker table tr td.selected:hover:active,
    .datepicker table tr td.selected.disabled:active,
    .datepicker table tr td.selected.disabled:hover:active,
    .datepicker table tr td.selected.active,
    .datepicker table tr td.selected:hover.active,
    .datepicker table tr td.selected.disabled.active,
    .datepicker table tr td.selected.disabled:hover.active {
      background-color: #666666 \9;
    }
    .datepicker table tr td.active,
    .datepicker table tr td.active:hover,
    .datepicker table tr td.active.disabled,
    .datepicker table tr td.active.disabled:hover {
      background-color: #006dcc;
      background-image: -moz-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: -ms-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
      background-image: -webkit-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: -o-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: linear-gradient(to bottom, #0088cc, #0044cc);
      background-repeat: repeat-x;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0088cc', endColorstr='#0044cc', GradientType=0);
      border-color: #0044cc #0044cc #002a80;
      border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
      filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
      color: #fff;
      text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    }
    .datepicker table tr td.active:hover,
    .datepicker table tr td.active:hover:hover,
    .datepicker table tr td.active.disabled:hover,
    .datepicker table tr td.active.disabled:hover:hover,
    .datepicker table tr td.active:active,
    .datepicker table tr td.active:hover:active,
    .datepicker table tr td.active.disabled:active,
    .datepicker table tr td.active.disabled:hover:active,
    .datepicker table tr td.active.active,
    .datepicker table tr td.active:hover.active,
    .datepicker table tr td.active.disabled.active,
    .datepicker table tr td.active.disabled:hover.active,
    .datepicker table tr td.active.disabled,
    .datepicker table tr td.active:hover.disabled,
    .datepicker table tr td.active.disabled.disabled,
    .datepicker table tr td.active.disabled:hover.disabled,
    .datepicker table tr td.active[disabled],
    .datepicker table tr td.active:hover[disabled],
    .datepicker table tr td.active.disabled[disabled],
    .datepicker table tr td.active.disabled:hover[disabled] {
      background-color: #0044cc;
    }
    .datepicker table tr td.active:active,
    .datepicker table tr td.active:hover:active,
    .datepicker table tr td.active.disabled:active,
    .datepicker table tr td.active.disabled:hover:active,
    .datepicker table tr td.active.active,
    .datepicker table tr td.active:hover.active,
    .datepicker table tr td.active.disabled.active,
    .datepicker table tr td.active.disabled:hover.active {
      background-color: #003399 \9;
    }
    .datepicker table tr td span {
      display: block;
      width: 23%;
      height: 54px;
      line-height: 54px;
      float: left;
      margin: 1%;
      cursor: pointer;
      -webkit-border-radius: 4px;
      -moz-border-radius: 4px;
      border-radius: 4px;
    }
    .datepicker table tr td span:hover {
      background: #eeeeee;
    }
    .datepicker table tr td span.disabled,
    .datepicker table tr td span.disabled:hover {
      background: none;
      color: #999999;
      cursor: default;
    }
    .datepicker table tr td span.active,
    .datepicker table tr td span.active:hover,
    .datepicker table tr td span.active.disabled,
    .datepicker table tr td span.active.disabled:hover {
      background-color: #006dcc;
      background-image: -moz-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: -ms-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
      background-image: -webkit-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: -o-linear-gradient(to bottom, #0088cc, #0044cc);
      background-image: linear-gradient(to bottom, #0088cc, #0044cc);
      background-repeat: repeat-x;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0088cc', endColorstr='#0044cc', GradientType=0);
      border-color: #0044cc #0044cc #002a80;
      border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
      filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
      color: #fff;
      text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    }
    .datepicker table tr td span.active:hover,
    .datepicker table tr td span.active:hover:hover,
    .datepicker table tr td span.active.disabled:hover,
    .datepicker table tr td span.active.disabled:hover:hover,
    .datepicker table tr td span.active:active,
    .datepicker table tr td span.active:hover:active,
    .datepicker table tr td span.active.disabled:active,
    .datepicker table tr td span.active.disabled:hover:active,
    .datepicker table tr td span.active.active,
    .datepicker table tr td span.active:hover.active,
    .datepicker table tr td span.active.disabled.active,
    .datepicker table tr td span.active.disabled:hover.active,
    .datepicker table tr td span.active.disabled,
    .datepicker table tr td span.active:hover.disabled,
    .datepicker table tr td span.active.disabled.disabled,
    .datepicker table tr td span.active.disabled:hover.disabled,
    .datepicker table tr td span.active[disabled],
    .datepicker table tr td span.active:hover[disabled],
    .datepicker table tr td span.active.disabled[disabled],
    .datepicker table tr td span.active.disabled:hover[disabled] {
      background-color: #0044cc;
    }
    .datepicker table tr td span.active:active,
    .datepicker table tr td span.active:hover:active,
    .datepicker table tr td span.active.disabled:active,
    .datepicker table tr td span.active.disabled:hover:active,
    .datepicker table tr td span.active.active,
    .datepicker table tr td span.active:hover.active,
    .datepicker table tr td span.active.disabled.active,
    .datepicker table tr td span.active.disabled:hover.active {
      background-color: #003399 \9;
    }
    .datepicker table tr td span.old,
    .datepicker table tr td span.new {
      color: #999999;
    }
    .datepicker .datepicker-switch {
      width: 145px;
    }
    .datepicker .datepicker-switch,
    .datepicker .prev,
    .datepicker .next,
    .datepicker tfoot tr th {
      cursor: pointer;
    }
    .datepicker .datepicker-switch:hover,
    .datepicker .prev:hover,
    .datepicker .next:hover,
    .datepicker tfoot tr th:hover {
      background: #eeeeee;
    }
    .datepicker .cw {
      font-size: 10px;
      width: 12px;
      padding: 0 2px 0 5px;
      vertical-align: middle;
    }
    .input-append.date .add-on,
    .input-prepend.date .add-on {
      cursor: pointer;
    }
    .input-append.date .add-on i,
    .input-prepend.date .add-on i {
      margin-top: 3px;
    }
    .input-daterange input {
      text-align: center;
    }
    .input-daterange input:first-child {
      -webkit-border-radius: 3px 0 0 3px;
      -moz-border-radius: 3px 0 0 3px;
      border-radius: 3px 0 0 3px;
    }
    .input-daterange input:last-child {
      -webkit-border-radius: 0 3px 3px 0;
      -moz-border-radius: 0 3px 3px 0;
      border-radius: 0 3px 3px 0;
    }
    .input-daterange .add-on {
      display: inline-block;
      width: auto;
      min-width: 16px;
      height: 18px;
      padding: 4px 5px;
      font-weight: normal;
      line-height: 18px;
      text-align: center;
      text-shadow: 0 1px 0 #ffffff;
      vertical-align: middle;
      background-color: #eeeeee;
      border: 1px solid #ccc;
      margin-left: -5px;
      margin-right: -5px;
    }

</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Deviation-PPC Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">Deviation-PPC Master</li>
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
            @if(isset($DeviationList))
            <form action="{{ route('deviationPPCMasterUpdate',$DeviationList->deviation_PPC_Master_Id) }}" method="GET">
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label  class="form-label">Vendor</label>
                        <select name="vendorId"  id="vendorId" class="select2" style="width:250px;"  onchange="GetPlanLineList(this.value);">
                           <option value="">--Vendors--</option>
                           @foreach($Ledger as  $rowvendor)
                           {
                           <option value="{{ $rowvendor->ac_code }}" {{ $rowvendor->ac_code == $DeviationList->vendorId ? 'selected="selected"' : '' }} >{{ $rowvendor->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                 <div class="col-md-2">
                     <div class="mb-3">
                       <label  class="form-label">Line No.</label><br>
                       <select name="line_id"  id="line_id" class="select2"  lineNo="{{$DeviationList->line_id}}"  >
                           <option value="">--Line No.--</option>
                        </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Efficiency%</label>
                        <input type="text" name="efficiency" class="form-control" id="efficiency" value="{{$DeviationList->efficiency}}" >
                     </div>
                  </div>
                   <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date"  name="fromDate" class="form-control" id="fromDate" value="{{$min_date}}" onchange="SetDateToTable();"  required>
                   </div>
                   <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date"  name="toDate" class="form-control" id="toDate" value="{{$max_date}}" onchange="SetDateToTable();"  required> 
                    </div> 
               </div>
               <div class="col-md-6">
                     <table id="m/c_assign" class="table table-bordered text-1 table-sm" style="height:10vh; ">
                         <thead>
                              <tr>
                               <th>Sr No.</th>
                               <th>Date</th>
                               <th>No of m/cs</th> 
                            </tr> 
                         </thead>
                         <tbody id="assignTbl">
                             @php
                                $sno = 1;
                             @endphp
                             @foreach($DetailDeviation as $row)
                              <tr>
                               <td>{{$sno++}}</td>
                               <td><input type="date" name="monthDate" value="{{$row->monthDate}}" class="form-control"/></td>
                               <td><input type="text" name="noOfMC" value="{{$row->noOfMC}}" class="form-control"/></td> 
                              </tr> 
                            @endforeach
                         </tbody>
                     </table>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
                  <a href="{{route('DeviationPPCList')}}" class="btn btn-danger w-md">Cancel</a>
               </div>
            </form>
            @else
            <form action="{{route('deviationPPCMasterStore')}}" method="POST">
            <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
               @csrf 
               <div class="row">
                   <div class="col-md-3">
                     <div class="mb-3">
                       <label for="formrow-email-input" class="form-label">Vendor</label>
                       <select name="vendorId"  id="vendorId" class="select2" style="width:250px;"  onchange="GetPlanLineList(this.value);">
                           <option value="">--Vendors--</option>
                           @foreach($Ledger as  $rowvendor)
                           {
                           <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                       <label for="formrow-email-input" class="form-label">Line No.</label><br>
                       <select name="line_id"  id="line_id" class="select2" >
                           <option value="">--Line No.--</option>
                        </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Efficiency%</label>
                        <input type="text" name="efficiency" class="form-control" id="efficiency">
                     </div>
                  </div> 
                   <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date"  name="fromDate" class="form-control" id="fromDate" value="" onchange="SetDateToTable();"  required>
                   </div>
                   <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date"  name="toDate" class="form-control" id="toDate" value="" onchange="SetDateToTable();"  required> 
                    </div> 
                  <!-- <div class="col-md-3">-->
                  <!--   <div class="mb-3">-->
                  <!--      <label for="formrow-email-input" class="form-label">Monthly Plan</label>-->
                  <!--      <input type="text"  name="monthlyPlan1" class="form-control" id="monthlyPlan1" onchange="countSelectedDate(this.value);" value="" >-->
                  <!--      <input type="hidden"  name="monthlyPlan" class="form-control" id="monthlyPlan" value="" >-->
                  <!--      <input type="hidden"  name="day_Count" class="form-control" id="day_Count" value="" >-->
                  <!--   </div>-->
                  <!--</div>-->
               </div>  
               <div class="col-md-6">
                     <table id="m/c_assign" class="table table-bordered text-1 table-sm" style="height:10vh; ">
                         <thead>
                              <tr>
                               <th class="text-center">Sr No.</th>
                               <th class="text-center">Date</th>
                               <th class="text-center">No of m/cs</th> 
                            </tr> 
                         </thead>
                         <tbody id="assignTbl">
                              <tr>
                               <td class="text-center">1</td>
                               <td class="text-center"><input type="date" name="monthDate[]" value="" class="form-control"/></td>
                               <td class="text-center"><input type="text" name="noOfMC[]" value="" class="form-control"/></td> 
                            </tr> 
                         </tbody>
                     </table>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
                  <a href="{{route('DeviationPPCList')}}" class="btn btn-danger w-md">Cancel</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>   
    @php
        if(isset($DeviationList))
        {
    @endphp 
           $(function(){
               
                var line_id = $('#line_no').attr('lineno');
                setTimeout(function() {
                      GetPlanLineList($('#vendorId').val()); 
                      $('#line_id option[value="'+line_id+'"]').prop("selected", true);
                }, 1000);
                
           });
    @php
        }
    @endphp 
    
    function SetDateToTable()
    { 
        var fromDate = new Date($("#fromDate").val()).getDate();
        var toDate = new Date($("#toDate").val()).getDate();
        var arr = $("#toDate").val().split('-');
        var month = arr[1]; 
        var year = new Date($("#fromDate").val()).getFullYear();
        var html = "";
        var srno = 1;
        
        for(var i=fromDate;i<=toDate; i++)
        {
           
            if(i < 10)
            {
                i = "0"+i;
            }
            
            var fullDate = year+"-"+month+"-"+i;
            
            html += '<tr><td class="text-center">'+srno+'</td><td><input type="date" name="monthDate[]" value="'+fullDate+'" class="form-control"/></td><td><input type="text" name="noOfMC[]" value="" class="form-control"/></td></tr>'
        
            srno = parseInt(srno) + parseInt(1);
        }
        $("#assignTbl").html(html);
    }
    
    function GetPlanLineList(ele)
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('GetPlanLineList') }}",
            data:{'Ac_code':ele},
            success: function(data){
            $('#line_id').html(data.html);
            var line_no = $('#line_id').attr('lineno');
            $('#line_id option[value='+line_no+']').attr('selected','selected').change();
           }
        });
    }
    $('#monthlyPlan1').datepicker({
        multidate: true,
    	format: 'dd-mm-yyyy'
    });
    
    function countSelectedDate(row)
    {
        var selected_Count = row.split(",").length;
        var ele = row.split(",");
        var spl_date = ele[0].split("-");
        var lastday = new Date(spl_date[2], spl_date[1], 0).getDate();
 
        $('#monthlyPlan').val(selected_Count);
        $('#day_Count').val(lastday);
    }

</script>
@endsection