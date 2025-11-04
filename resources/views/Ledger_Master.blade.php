@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Ledger Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Ledger Master</li>
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
            <h4 class="card-title mb-4">Ledger</h4>
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
            @if(isset($Ledger))
            <form action="{{ route('Ledger.update',$Ledger) }}" method="POST">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="ac_name" class="form-label">Legal Name</label>
                        <input type="text" name="ac_name" class="form-control" id="ac_name" value="{{ $Ledger->ac_name }}" required  onkeyup="convertToUpperCase();">
                        <input type="hidden" name="ac_code" class="form-control" id="ac_code" value="{{ $Ledger->ac_code }}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                        <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $Ledger->created_at }}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="ac_short_name" class="form-label">Account Short Name</label>
                        <input type="text" name="ac_short_name" class="form-control" id="ac_short_name" value="{{ $Ledger->ac_short_name }}" required  onkeyup="convertShortToUpperCase();"> 
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="trade_name" class="form-label">Trade Name</label>
                        <input type="text" name="trade_name" class="form-control" id="trade_name" value="{{ $Ledger->trade_name }}" required> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="group_code" class="form-label">Account Group</label>
                        <select name="group_code" class="form-select" id="group_id" >
                           <option value="">--- Account Group ---</option>
                           @foreach($AcGroup as  $row)
                           {
                           <option value="{{ $row->Group_code }}"
                           {{ $row->Group_code == $Ledger->group_code ? 'selected="selected"' : '' }}
                           >{{ $row->Group_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="op_bal" class="form-label">Balance</label>
                        <input type="text" name="op_bal" class="form-control" id="formrow-email-input" value="{{ $Ledger->op_bal }}">
                        <input type="hidden" name="group_main" class="form-control" id="group_main" value="{{ $Ledger->group_main }}">
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <h6>Credit / Debit</h6>
                     <div class="form-group">
                        <select id="op_dc" name="op_dc" class="form-control" >
                        <option value="Cr" {{ $Ledger->op_dc == 'Cr' ? 'selected="selected"' : '' }}>Cr</option>
                        <option value="Dr"  {{ $Ledger->op_dc == 'Dr' ? 'selected="selected"' : '' }}>Dr</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea rows="3"  class="form-control" cols="20" name="address" id="address">{{ $Ledger->address }}</textarea>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="c_id" class="form-label">Country</label>
                        <select name="c_id" class="form-select" id="c_id">
                           <option value="">--- Select Country ---</option>
                           @foreach($Countrys as  $row)
                           {
                           <option value="{{ $row->c_id }}"
                           {{ $row->c_id == $Ledger->c_id ? 'selected="selected"' : '' }}
                           >{{ $row->c_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="state_id" class="form-label">State</label>
                        <select name="state_id" class="form-select select2" id="state_id">
                           <option value="">--State--</option>
                           @foreach($State as  $row)
                           {
                           <option value="{{ $row->state_id }}"
                           {{ $row->state_id == $Ledger->state_id ? 'selected="selected"' : '' }}
                           >{{ $row->state_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dist_id" class="form-label">District</label>
                        <select name="dist_id" class="form-select select2" id="dist_id">
                           <option value="">--- Select District ---</option>
                           @foreach($District as  $row)
                           {
                           <option value="{{ $row->d_id }}"
                           {{ $row->d_id == $Ledger->dist_id ? 'selected="selected"' : '' }}
                           >{{ $row->d_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="taluka_id" class="form-label">Taluka</label>
                        <select name="taluka_id" class="form-select select2"  id="taluka_id">
                           <option value="">--- Select Taluka ---</option>
                           @foreach($Taluka as  $row)
                           {
                           <option value="{{ $row->tal_id }}"
                           {{ $row->tal_id == $Ledger->taluka_id ? 'selected="selected"' : '' }}
                           >{{ $row->taluka }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="city_name" class="form-label">City Name</label>
                        <input type="text" name="city_name" class="form-control" id="city_name" value="{{ $Ledger->city_name }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" id="formrow-mobile-input" value="{{ $Ledger->mobile }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="status_id" class="form-label">Status</label>
                        <select name="status_id" class="form-select select2" id="status_id">
                           <option value="">--Select--</option>
                           @foreach($statusList as  $row) 
                           <option value="{{ $row->status_id }}"
                           {{ $row->status_id == $Ledger->status_id ? 'selected="selected"' : '' }}
                           >{{ $row->status_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="phone" class="form-label">Landline/Phone</label>
                        <input type="text" name="phone" class="form-control" id="phone" value="{{ $Ledger->phone }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email" value="{{ $Ledger->email }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="pan_no" class="form-label">PAN No.</label>
                        <input type="text" name="pan_no" class="form-control" id="pan_no" maxlength="10" value="{{ $Ledger->pan_no }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="gst_no" class="form-label">GST No.</label>
                        <input type="text" name="gst_no" class="form-control" id="gst_no" maxlength="15" value="{{ $Ledger->gst_no }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="adhar_no" class="form-label">Adhar No.</label>
                        <input type="text" name="adhar_no" class="form-control" id="adhar_no" value="{{ $Ledger->adhar_no }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="pin_code" class="form-label">Pin Code</label>
                        <input type="text" name="pin_code" class="form-control" id="pin_code" value="{{ $Ledger->pin_code }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="msme_code" class="form-label">MSME Code</label>
                        <input type="text" name="msme_code" class="form-control" id="msme_code" value="{{ $Ledger->msme_code }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cin_no" class="form-label">CIN No.</label>
                        <input type="text" name="cin_no" class="form-control" id="cin_no" value="{{ $Ledger->cin_no }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bt_id" class="form-label">Business Type 1</label>
                        <select name="bt_id" class="form-select" id="bt_id">
                           <option value="">--Business--</option>
                           @foreach($BusinessType as  $row)
                           {
                           <option value="{{ $row->Bt_id }}"
                           {{ $row->Bt_id == $Ledger->bt_id ? 'selected="selected"' : '' }}
                           >{{ $row->Bt_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bt_id1" class="form-label">Business Type 2</label>
                        <select name="bt_id1" class="form-select" id="bt_id1">
                           <option value="">--Business--</option>
                           @foreach($BusinessType as  $row)
                           {
                           <option value="{{ $row->Bt_id }}"
                           {{ $row->Bt_id == $Ledger->bt_id1 ? 'selected="selected"' : '' }}
                           >{{ $row->Bt_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bt_id2" class="form-label">Business Type 3</label>
                        <select name="bt_id2" class="form-select" id="bt_id2">
                           <option value="">--Business--</option>
                           @foreach($BusinessType as  $row)
                           {
                           <option value="{{ $row->Bt_id }}"
                           {{ $row->Bt_id == $Ledger->bt_id2 ? 'selected="selected"' : '' }}
                           >{{ $row->Bt_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-3">
                     <label for="bank_name" class="form-label">Bank Name</label>
                     <div class="mb-3">
                        <input type="text" name="bank_name" class="form-control" value="{{ $Ledger->bank_name }} " />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="account_name" class="form-label">Account Name</label>
                     <div class="mb-3">
                        <input type="text" name="account_name" value="{{ $Ledger->account_name }}" class="form-control"  />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="branch_name" class="form-label">Branch Name</label>
                     <div class="mb-3">
                        <input type="text" name="branch_name" value="{{ $Ledger->branch_name }}" class="form-control"  />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="account_no" class="form-label">Account No</label>
                     <div class="mb-3">
                        <input type="text" name="account_no" class="form-control"   value="{{ $Ledger->account_no }}" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ac_id" class="form-label">Account Type</label>
                        <select name="ac_id" class="form-select select2" id="ac_id">
                           <option value="">--Select Type</option>
                           @foreach($Account_Type as  $row)
                           {
                           <option value="{{ $row->Ac_id }}"
                           {{ $row->Ac_id == $Ledger->ac_id ? 'selected="selected"' : '' }}
                           >{{ $row->Ac_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="ifsc_code" class="form-label">IFSC Code</label>
                     <div class="mb-3">
                        <input type="text" name="ifsc_code" value="{{ $Ledger->ifsc_code }}" class="form-control"  />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="tds_type" class="form-label">TDS Type</label>
                     <div class="mb-3">
                        <input type="text" name="tds_type" class="form-control" value="{{ $Ledger->tds_type }}" />
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="tds_per" class="form-label">TDS%</label>
                     <div class="form-group">
                        <input type="number" step="any" name="tds_per" value="{{ $Ledger->tds_per }}" class="form-control" placeholder="Enter TDS % Here..." />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="note" class="form-label">Transporter ID</label>
                     <div class="mb-3">
                        <input type="text" name="note" class="form-control" value="{{ $Ledger->note }}" />
                     </div>
                  </div>
                  <div class="col-sm-3 mb-3" style="display:flex;">
                     <div class="mb-3" style="margin-right: 15px;margin-top: 33px;"> 
                         <input type="checkbox" id="isPackingInward" onchange="chkBox();" {{ $Ledger->isPackingInward == 1 ? 'checked' : '' }}  />
                         <input type="hidden" name="isPackingInwardValue" id="isPackingInwardValue" value="{{ $Ledger->isPackingInward }}" /> 
                     </div>
                     <label for="isPackingInward" class="form-label" style="margin-top: 30px;">Is Auto Packing Inward</label>
                  </div>
               </div>
               <div class="table-wrap">
                  <div class="table-responsive">
                     <input type="number" value="{{ count($LedgerDetailList) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                     <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                        <thead>
                           <tr class="text-center">
                              <th>ID</th>
                              <th>Trade Name</th>
                              <th>Site code</th>
                              <th>PAN No</th>
                              <th>GST No</th> 
                              <th>Address</th>
                              <th>State</th> 
                              <th>Pin Code</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @if(count($LedgerDetailList)>0)
                           @php $no=1; @endphp
                           @foreach($LedgerDetailList as $List) 
                           <tr>
                              <td>
                                 <input type="text" name="id[]"  class="form-control" id="id" value="@php echo $no; @endphp" style="width:50px;" readOnly>
                              </td>
                              <td> 
                                 <input type="text" name="trade_names[]" class="form-control" id="trade_name"  value="{{ $List->trade_name }}">
                              </td>
                              <td>
                                 <input type="hidden" name="sr_no[]"  class="form-control" id="sr_no" value="{{ $List->sr_no }}">
                                 <input type="text" name="site_code[]"  class="form-control" id="site_code" value="{{ $List->site_code }}">
                              </td>
                              <td> 
                                 <input type="text" name="pan_nos[]" class="form-control" id="pan_no"  value="{{ $List->pan_no }}">
                              </td>
                              <td> 
                                 <input type="text" name="gst_nos[]"  class="form-control" id="gst_nos" maxlength="15"  value="{{ $List->gst_no }}">
                              </td>
                              <td> 
                                    <textarea name="addr1[]" id="addr1" class="form-control">{{ $List->addr1 }}</textarea>  
                              </td>
                              <td> 
                                    <select name="state_ids[]" class="form-select select2" id="state_ids">
                                       <option value="">--Select--</option>
                                       @foreach($State as  $row)
                                       <option value="{{ $row->state_id }}" {{ $row->state_id == $List->state_id ? 'selected="selected"' : '' }}>{{ $row->state_name }}</option>
                                       @endforeach
                                    </select>
                              </td> 
                              <td> 
                                 <input type="text" name="pin_codes[]"  class="form-control" id="pin_code" value="{{ $List->pin_code }}">
                              </td>
                              <td nowrap><button type="button" onclick="AddNew(); " class="btn btn-warning pull-left">+</button><button type="button" class="btn btn-danger pull-left" style="margin-left:10px;" onclick="RemoveRow(this);">X</button></td>
                           </tr>
                           @php $no=$no+1;  @endphp
                           @endforeach
                           @else
                           <tr>
                              <td>
                                 <input type="text"   name="id[]"  class="form-control" id="id" value="1" style="width:50px;">
                              </td>
                              <td> 
                                 <input type="text" name="trade_names[]" class="form-control" id="trade_name"  value="">
                              </td>
                              <td>
                                 <input type="text"   name="site_code[]"  class="form-control" id="site_code" value="">
                              </td>
                              <td> 
                                 <input type="text" name="pan_nos[]" class="form-control" id="pan_no"  value="">
                              </td>
                              <td> 
                                 <input type="text"   name="gst_nos[]" maxlength="15"   class="form-control" id="gst_nos" value="">
                              </td>
                              <td> 
                                    <textarea name="addr1[]" id="addr1" class="form-control"></textarea> 
                              </td>
                              <td> 
                                    <select name="state_ids[]" class="form-select select2" id="state_ids">
                                       <option value="">--Select--</option>
                                       @foreach($State as  $row)
                                       <option value="{{ $row->state_id }}">{{ $row->state_name }}</option>
                                       @endforeach
                                    </select>
                              </td> 
                              <td> 
                                 <input type="text" name="pin_codes[]"  class="form-control" id="pin_code" value="">
                              </td>
                              <td nowrap><button type="button" onclick="AddNew(); " class="btn btn-warning pull-left">+</button><button type="button" class="btn btn-danger pull-left" style="margin-left:10px;" onclick="RemoveRow(this);">X</button></td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
               </div>
            </form>
            @else
            <form action="{{route('Ledger.store')}}" method="POST">
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="ac_name" class="form-label">Legal Name</label>
                        <input type="text" name="ac_name" class="form-control" id="ac_name" value="" onkeyup="convertToUpperCase();">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="ac_short_name" class="form-label">Account Short Name</label>
                        <input type="text" name="ac_short_name" class="form-control" id="ac_short_name" value="" required  onkeyup="convertShortToUpperCase();"> 
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="trade_name" class="form-label">Trade Name</label>
                        <input type="text" name="trade_name" class="form-control" id="trade_name" value="" required> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Account Group</label>
                        <select name="group_code" class="form-select" id="group_code">
                           <option value="">--- Account Group ---</option>
                           @foreach($AcGroup as  $row)
                           {
                           <option value="{{ $row->Group_code }}">{{ $row->Group_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Balance</label>
                        <input type="text" name="op_bal" class="form-control" id="formrow-email-input" value="0">
                        <input type="hidden" name="group_main" class="form-control" id="group_main" value="0">
                     </div>
                  </div>
                  <div class="col-sm-1">
                     <h6>Credit / Debit</h6>
                     <div class="form-group">
                        <select id="op_dc" name="op_dc" class="form-control" required>
                           <option value="Cr">Cr</option>
                           <option value="Dr">Dr</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea rows="3"  class="form-control" cols="20" name="address" id="address"></textarea>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Country</label>
                        <select name="c_id" class="form-select" id="c_id" onChange="getState(this.value);">
                           <option value="">--- Select Country ---</option>
                           @foreach($Countrys as  $row)
                           {
                           <option value="{{ $row->c_id }}">{{ $row->c_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">State</label>
                        <select name="state_id" class="form-select select2" id="state_id" onChange="getDistrict(this.value);" >
                           <option value="">--State--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">District</label>
                        <select name="dist_id" class="form-select select2" id="dist_id" onChange="getTaluka(this.value);">
                           <option value="">--- Select District ---</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Taluka</label>
                        <select name="taluka_id" class="form-select select2" id="taluka_id">
                           <option value="">--- Select Taluka ---</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="city_name" class="form-label">City Name</label>
                        <input type="text" name="city_name" class="form-control" id="city_name" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-mobile-input" class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" id="formrow-mobile-input" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="status_id" class="form-label">Status</label>
                        <select name="status_id" class="form-select select2" id="status_id">
                           <option value="">--Select--</option>
                           @foreach($statusList as  $row) 
                           <option value="{{ $row->status_id }}">{{ $row->status_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-phone-input" class="form-label">Landline/Phone</label>
                        <input type="text" name="phone" class="form-control" id="formrow-phone-input" value="">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="formrow-email-input" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-GSpan_noT_no-input" class="form-label">PAN No.</label>
                        <input type="text" name="pan_no" class="form-control" id="formrow-pan_no-input" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-GST_no-input" class="form-label">GST No.</label>
                        <input type="text" name="gst_no" class="form-control" id="formrow-GST_no-input" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-adhar_no-input" class="form-label">Adhar No.</label>
                        <input type="text" name="adhar_no" class="form-control" id="formrow-adhar_no-input" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="pin_code" class="form-label">Pin Code</label>
                        <input type="text" name="pin_code" class="form-control" id="pin_code" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="msme_code" class="form-label">MSME Code</label>
                        <input type="text" name="msme_code" class="form-control" id="msme_code" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cin_no" class="form-label">CIN No.</label>
                        <input type="text" name="cin_no" class="form-control" id="cin_no" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bt_id" class="form-label">Business Type 1</label>
                        <select name="bt_id" class="form-select" id="bt_id">
                           <option value="">Business</option>
                           @foreach($BusinessType as  $row)
                           {
                           <option value="{{ $row->Bt_id }}">{{ $row->Bt_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bt_id1" class="form-label">Business Type 2</label>
                        <select name="bt_id1" class="form-select" id="bt_id1">
                           <option value="">Business</option>
                           @foreach($BusinessType as  $row)
                           {
                           <option value="{{ $row->Bt_id }}">{{ $row->Bt_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bt_id2" class="form-label">Business Type 3</label>
                        <select name="bt_id2" class="form-select" id="bt_id2">
                           <option value="">Business</option>
                           @foreach($BusinessType as  $row)
                           {
                           <option value="{{ $row->Bt_id }}">{{ $row->Bt_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-3">
                     <label for="formrow-inputState" class="form-label">Bank Name</label>
                     <div class="mb-3">
                        <input type="text" name="bank_name" class="form-control"   value="" />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="formrow-inputState" class="form-label">Account Name</label>
                     <div class="mb-3">
                        <input type="text" name="account_name" value="" class="form-control"  />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="branch_name" class="form-label">Branch Name</label>
                     <div class="mb-3">
                        <input type="text" name="branch_name" value="" class="form-control"  />
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <label for="formrow-inputState" class="form-label">Account No</label>
                     <div class="mb-3">
                        <input type="text" name="account_no" class="form-control"   value="" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Account Type</label>
                        <select name="ac_id" class="form-select" id="ac_id">
                           <option value="">--Select Type</option>
                           @foreach($Account_Type as  $row)
                           {
                           <option value="{{ $row->Ac_id }}">{{ $row->Ac_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="formrow-inputState" class="form-label">IFSC Code</label>
                     <div class="mb-3">
                        <input type="text" name="ifsc_code" value="" class="form-control"  />
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="formrow-inputState" class="form-label">TDS Type</label>
                     <div class="mb-3">
                        <input type="text" name="tds_type" class="form-control"   value="" />
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="formrow-inputState" class="form-label">TDS%</label>
                     <div class="form-group">
                        <input type="number" step="any" name="tds_per" value="0" class="form-control" placeholder="Enter TDS % Here..." />
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <label for="formrow-inputState" class="form-label">Transporter ID</label>
                     <div class="mb-3">
                        <input type="text" name="note" class="form-control"  value="" />
                     </div>
                  </div>
                  <div class="col-sm-3 mb-3" style="display:flex;">
                     <div class="mb-3" style="margin-right: 15px;margin-top: 33px;">
                         <input type="checkbox" id="isPackingInward" onchange="chkBox();" />
                         <input type="hidden" name="isPackingInwardValue" id="isPackingInwardValue" value="0" /> 
                     </div>
                     <label for="isPackingInward" class="form-label" style="margin-top: 30px;">Is Auto Packing Inward</label>
                  </div>
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <input type="number" value="1" name="cntrr" id="cntrr"  hidden="true"  />
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr class="text-center">
                                  <th>ID</th>
                                  <th>Trade Name</th>
                                  <th>Site code</th>
                                  <th>PAN No</th>
                                  <th>GST No</th> 
                                  <th>Address</th>
                                  <th>State</th> 
                                  <th>Pin Code</th>
                                  <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>  
                               <tr>
                                  <td>
                                     <input type="text"   name="id[]"  class="form-control" id="id" value="1" style="width:50px;">
                                  </td>
                                  <td> 
                                     <input type="text" name="trade_names[]" class="form-control" id="trade_name"  value="">
                                  </td>
                                  <td>
                                     <input type="text"   name="site_code[]"  class="form-control" id="site_code" value="">
                                  </td>
                                  <td> 
                                     <input type="text" name="pan_nos[]" class="form-control" id="pan_no"  value="">
                                  </td>
                                  <td> 
                                     <input type="text"   name="gst_nos[]" maxlength="15"   class="form-control" id="gst_nos" value="">
                                  </td>
                                  <td> 
                                     <textarea name="addr1[]" id="addr1" class="form-control"></textarea>
                                  </td>
                                  <td> 
                                        <select name="state_ids[]" class="form-select select2" id="state_ids">
                                           <option value="">--Select--</option>
                                           @foreach($State as  $row)
                                           <option value="{{ $row->state_id }}">{{ $row->state_name }}</option>
                                           @endforeach
                                        </select>
                                  </td> 
                                  <td> 
                                     <input type="text" name="pin_codes[]" class="form-control" id="pin_code" value="">
                                  </td>
                                  <td nowrap><button type="button" onclick="AddNew(); " class="btn btn-warning pull-left">+</button><button type="button" class="btn btn-danger pull-left" style="margin-left:10px;" onclick="RemoveRow(this);">X</button></td>
                               </tr>
                           </tbody> 
                        </table>
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" >Submit</button>
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
<script>
   
    function chkBox() 
    {
        var value = $('#isPackingInward').is(':checked') ? 1 : 0;
        $('#isPackingInwardValue').val(value);
    }

   
   function EnableFields()
   {
        $("select").prop('disabled', false);
        $("input").prop('disabled', false);
   }
    function convertToUpperCase() 
    {
        var inputField = document.getElementById("ac_name");
        inputField.value = inputField.value.toUpperCase();
    }
    
    function convertShortToUpperCase() 
    {
        var inputField = document.getElementById("ac_short_name");
        inputField.value = inputField.value.toUpperCase();
    }
    
   function deleteRowcone(btn) {
   if(document.getElementById('cntrr').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
   
   recalcIdcone();
   
   if($("#cntrr").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
   }
   }
   
    function AddNew()
    {
        var $lastRow = $("#footable_3 tbody tr:last");
    
        // Destroy Select2 in the last row before cloning
        $lastRow.find('select.select2').select2('destroy');
    
        var $newRow = $lastRow.clone();
    
        // Optional: clear input and select values 
        $newRow.find('input').not('input[name="id[]"]').val('');
        $newRow.find('select').val('').trigger('change'); 
    
        // Append the new row
        $("#footable_3 tbody").append($newRow);
    
        // Reapply Select2 to all select elements
        $("#footable_3 tbody tr:last").find('select.select2').select2();
        recalcIdcone();
    }

    
    function RemoveRow(btn) 
    {
        var row = btn.closest("tr");
    
        if ($("#footable_3 tbody tr").length > 1) {
            row.remove();
        }
    
        recalcIdcone();
    }

    function recalcIdcone()
    {
        $("#footable_3 tbody tr").each(function(index) 
        {
            $(this).find("input[name='id[]']").val(index + 1);
        });
    }
   
//   var indexcone = 2;
//   function insertcone(){
   
//   var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
//   var row=table.insertRow(table.rows.length);
   
//   var cell1=row.insertCell(0);
//   var t1=document.createElement("input");
//   t1.style="display: table-cell; width:50px;";
//   t1.className="form-control";
//   t1.id = "id"+indexcone;
//   t1.name= "id[]";
//   t1.value=indexcone;
//   cell1.appendChild(t1);
      
//   var cell5 = row.insertCell(1);
//   var t5=document.createElement("input");
//   t5.className="form-control";
//   t5.type="text";
//   t5.id = "trade_name"+indexcone;
//   t5.name="trade_name[]";
//   cell5.appendChild(t5);
   
//   var cell5 = row.insertCell(2);
//   var t5=document.createElement("input");
//   t5.className="form-control";
//   t5.type="text";
//   t5.id = "site_code"+indexcone;
//   t5.name="site_code[]";
//   cell5.appendChild(t5);
   
//   var cell5 = row.insertCell(3);
//   var t5=document.createElement("input");
//   t5.className="form-control";
//   t5.type="text";
//   t5.maxlength="15"
//   t5.id = "pan_no"+indexcone;
//   t5.name="pan_no[]";
//   cell5.appendChild(t5);
   
//   var cell5 = row.insertCell(4);
//   var t5=document.createElement("input");
//   t5.className="form-control";
//   t5.type="text";
//   t5.maxlength="15"
//   t5.id = "gst_nos"+indexcone;
//   t5.name="gst_nos[]";
//   cell5.appendChild(t5);
   
//   var cell5 = row.insertCell(5);
//   var t5=document.createElement("input");
//   t5.className="form-control";
//   t5.type="text";
//   t5.id = "addr1"+indexcone;
//   t5.name="addr1[]";
//   cell5.appendChild(t5);
   
//   var cell5 = row.insertCell(6);
//   var t5=document.createElement("select");
//   t5.className="form-control";
//   t5.id = "state_id"+indexcone;
//   t5.name="state_id[]";
//   cell5.appendChild(t5);
   
//   var cell6=row.insertCell(7);
   
//   var btnAdd = document.createElement("INPUT");
//   btnAdd.id = "Abutton";
//   btnAdd.type = "button";
//   btnAdd.className="btn btn-warning pull-left";
//   btnAdd.value = "+";
//   btnAdd.setAttribute("onclick", "insertcone()");
//   cell6.appendChild(btnAdd);
   
//   var btnRemove = document.createElement("INPUT");
//   btnRemove.id = "Dbutton";
//   btnRemove.type = "button";
//   btnRemove.className="btn btn-danger pull-left";
//   btnRemove.value = "X";
//   btnRemove.setAttribute("onclick", "deleteRowcone(this)");
//   cell6.appendChild(btnRemove);
   
//   var w = $(window);
//   var row = $('#footable_3').find('tr').eq(indexcone);
   
//   if (row.length){
//   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
//   }
   
//   document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
   
//   indexcone++;
    
   
//   recalcIdcone();
//   }
    
   
   function getState(val) 
   {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('StateList') }}",
       data:'country_id='+val,
       success: function(data){
       $("#state_id").html(data.html);
       }
       });
   }
   
   function getDistrict(val) 
   {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('DistrictList') }}",
       data:'state_id='+val,
       success: function(data){
       $("#dist_id").html(data.html);
       }
       });
   }
   
   function getTaluka(val) 
   {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('TalukaList') }}",
       data:'dist_id='+val,
       success: function(data){
       $("#taluka_id").html(data.html);
       }
       });
   }
</script>
<!-- end row -->
@endsection