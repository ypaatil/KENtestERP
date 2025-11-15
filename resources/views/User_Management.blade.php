@extends('layouts.master') 
@section('content')
<style>

    ul {
      list-style: none;
    }
     
    li {
      margin-top: 1em;
    }
    
    label {
      font-weight: bold;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">User Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">User Master</li>
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
            <h4 class="card-title mb-4">Form Grid Layout</h4>
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
            @if(isset($permissions))
            <form action="{{ route('User_Management.update',$permissions) }}" method="POST">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Vendor</label>
                        <select name="vendorId" class="form-select" id="vendorId ">
                           <option value="">--- Select Vendor ---</option>
                           @foreach($VendorList as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $permissions->vendorId ? 'selected="selected"' : '' }}
                           >{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Employee</label>
                        <select name="w_id" class="form-select" id="w_id ">
                           <option value="">--- Select Employee ---</option>
                           @foreach($workerlist as  $row)
                           {
                           <option value="{{ $row->w_id }}"
                           {{ $row->w_id == $permissions->w_id ? 'selected="selected"' : '' }}
                           >{{ $row->w_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">User Type</label>
                        <select name="user_type" class="form-select" id="user_type">
                           <option value="">--- User Type ---</option>
                           @foreach($user_typelist as  $user_typerow)
                           {
                           <option value="{{ $user_typerow->utype_id }}"
                           {{ $user_typerow->utype_id == $permissions->user_type ? 'selected="selected"' : '' }}
                           >{{ $user_typerow->user_type }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="gpo_approval_id" class="form-label">GPO Approval</label>
                        <select name="gpo_approval_id" class="form-select" id="gpo_approval_id">
                           <option value="">--- Select ---</option>
                           @foreach($GPOApprovelist as $gpo)
                                <option value="{{ $gpo->gpo_approval_id }}" {{ $gpo->gpo_approval_id == $permissions->gpo_approval_id ? 'selected="selected"' : '' }}>{{ $gpo->gpo_approval_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">User Contact</label>
                        <input type="text" name="contact" class="form-control" id="formrow-email-input" value="{{ $permissions->contact }}">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">User Address</label>
                        <input type="text" name="address" class="form-control" id="formrow-email-input" value="{{ $permissions->address }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">User Name</label>
                        <input type="text" name="username" class="form-control" id="formrow-email-input" value="{{ $permissions->username }}">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" id="formrow-email-input" value="{{ $permissions->password }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                    @foreach($processlist as $process) 
                    @php
                     
                        $process_auth = DB::SELECT("SELECT * FROM process_auth WHERE username='".$permissions->username."' AND process_id=".$process->process_id);
                        
                        $isRead = isset($process_auth[0]->isRead) ? $process_auth[0]->isRead : 0;
                        $isWrite = isset($process_auth[0]->isWrite) ? $process_auth[0]->isWrite : 0;
                        $isEdit = isset($process_auth[0]->isEdit) ? $process_auth[0]->isEdit : 0;
                        $isDelete = isset($process_auth[0]->isDelete) ? $process_auth[0]->isDelete : 0;
               
                    @endphp
                    <div class="col-md-3"> 
                           <ul>
                            <li>
                              <input type="checkbox" id="process_id" name="process_id[]" value="{{$process->process_id}}" {{ $isRead == 1 || $isWrite == 1 || $isEdit == 1 || $isDelete == 1 ? 'checked="checked"' : '' }} ><label for="option">&nbsp;&nbsp;{{$process->process_name}}</label>
                              <ul>
                                <li><label> <input type="checkbox" class="subOption" name="subProcessRead{{$process->process_id}}[]" value="1" {{ $isRead == 1 ? 'checked="checked"' : '' }} >&nbsp;&nbsp;Read</label></li>
                                <li><label> <input type="checkbox" class="subOption" name="subProcessWrite{{$process->process_id}}[]" value="1" {{ $isWrite == 1 ? 'checked="checked"' : '' }} >&nbsp;&nbsp;Write</label></li>
                                <li><label> <input type="checkbox" class="subOption" name="subProcessEdit{{$process->process_id}}[]" value="1" {{ $isEdit == 1 ? 'checked="checked"' : '' }} >&nbsp;&nbsp;Edit</label></li>
                                <li><label> <input type="checkbox" class="subOption" name="subProcessDelete{{$process->process_id}}[]" value="1" {{ $isDelete == 1 ? 'checked="checked"' : '' }} >&nbsp;&nbsp;Delete</label></li>
                              </ul>
                            </li>
                          </ul> 
                   </div>
                   @endforeach
               </div> 
               <div class="row">
               <div class="col-md-4" style="background: blanchedalmond;padding: 3px;">  
                 <table id="table2" class="table table-hover display  pb-30">
                  <thead>
                     <tr>
                        <th>Type</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Edit</th>
                        <th>Delete</th>
                     </tr>
                  </thead>
                  <tbody>  
                      @php
                            $packingTypeData = DB::SELECT("SELECT * FROM packing_type_master WHERE delflag=0");
                            $srno1 = 1;      
                      @endphp
                      @foreach($packingTypeData as $packingType)
                      @php 
                            $packing_auth = DB::SELECT("SELECT * FROM packing_auth WHERE username='".$permissions->username."' AND packing_type_auth_id=".$srno1);
                        
                            $isRead = isset($packing_auth[0]->isRead) ? $packing_auth[0]->isRead: 0;
                            $isWrite = isset($packing_auth[0]->isWrite) ? $packing_auth[0]->isWrite : 0;
                            $isEdit = isset($packing_auth[0]->isEdit) ? $packing_auth[0]->isEdit : 0;
                            $isDelete = isset($packing_auth[0]->isDelete) ? $packing_auth[0]->isDelete : 0;   
                      @endphp
                      <tr>
                          <td>{{$packingType->packing_short_name}}</td>
                          <td><input type="checkbox" name="packingRead{{$srno1}}"  {{ $isRead == 1 ? 'checked="checked"' : '' }}  value="1"></td>
                          <td><input type="checkbox" name="packingWrite{{$srno1}}"  {{ $isWrite == 1 ? 'checked="checked"' : '' }}  value="1"></td>
                          <td><input type="checkbox" name="packingEdit{{$srno1}}" {{ $isEdit == 1 ? 'checked="checked"' : '' }}  value="1"></td>
                          <td><input type="checkbox" name="packingDelete{{$srno1}}" {{ $isDelete == 1 ? 'checked="checked"' : '' }}  value="1"></td>
                      </tr>
                      @php
                        $srno1++;
                      @endphp
                      @endforeach 
                 </tbody>
                 </table> 
               </div>
               <div class="col-md-3" style="background: #22d381;padding: 3px;">  
                  <h3 class="m-3"><b>Order Group</b></h3>
                 <table id="table3" class="table table-hover display  pb-30">
                      @php
                            $orderGroupData = DB::SELECT("SELECT * FROM order_group_master WHERE delflag=0");    
                      @endphp
                      @foreach($orderGroupData as $orderGroup) 
                      @php 
                            $og_auth = DB::SELECT("SELECT count(*) as total_count FROM order_group_auth WHERE username='".$permissions->username."' AND og_id=".$orderGroup->og_id);
                         
                            $total_count = isset($og_auth[0]->total_count) ? $og_auth[0]->total_count : 0;
                      @endphp
                      <tr>
                          <th>{{$orderGroup->order_group_name}}</th>
                          <td><input type="checkbox" name="og_id[]"  value="{{$orderGroup->og_id}}"  {{ $total_count > 0 ?  'checked="checked"' : ''  }}></td> 
                      </tr> 
                      @endforeach 
                 </table> 
                 <h3 class="m-3"><b>PO Type Access</b></h3>
                 <table id="table3" class="table table-hover display  pb-30">
                      @php
                            $poTypeData = DB::SELECT("SELECT * FROM po_type_master WHERE delflag=0");      
                      @endphp
                      @foreach($poTypeData as $potype) 
                      @php 
                            $po_type_auth = DB::SELECT("SELECT count(*) as total_count FROM po_type_auth WHERE username='".$permissions->username."' AND po_type_id=".$potype->po_type_id);
                         
                            $total_count = isset($po_type_auth[0]->total_count) ? $po_type_auth[0]->total_count : 0;
                      @endphp
                      <tr>
                          <th>{{$potype->po_type_name}}</th>
                          <td><input type="checkbox" name="po_type_id[]"  value="{{$potype->po_type_id}}"  {{ $total_count > 0 ?  'checked="checked"' : ''  }}></td> 
                      </tr> 
                      @endforeach 
                 </table> 
               </div>
               <div class="col-md-4" style="background: #d3a922;padding: 3px;">  
                  <h3 class="m-3"><b>Sales Head</b></h3>
                 <table id="table3" class="table table-hover display  pb-30">
                      @php
                            $salesHeadData = DB::SELECT("SELECT * FROM sales_head_master WHERE delflag=0");    
                      @endphp
                      @foreach($salesHeadData as $saleHead) 
                      @php 
                            $sale_auth = DB::SELECT("SELECT count(*) as total_count FROM sales_head_auth WHERE username='".$permissions->username."' AND sales_head_id=".$saleHead->sales_head_id);
                         
                            $total_count1 = isset($sale_auth[0]->total_count) ? $sale_auth[0]->total_count : 0;
                      @endphp
                      <tr>
                          <th>{{$saleHead->sales_head_name}}</th>
                          <td><input type="checkbox" name="sales_head_id[]"  value="{{$saleHead->sales_head_id}}"  {{ $total_count1 > 0 ?  'checked="checked"' : ''  }} ></td> 
                      </tr> 
                      @endforeach 
                 </table> 
               </div>
               </div>
               <table id="myTable1" class="table table-hover display  pb-30">
                  <thead>
                     <tr>
                        <th>SrNo</th>
                        <th>Form Name</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Edit</th>
                        <th>Delete</th>
                     </tr>
                  </thead>
                  <tfoot>
                     <tr>
                        <th>SrNo</th>
                        <th>Form Name</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Edit</th>
                        <th>Delete</th>
                     </tr>
                  </tfoot>
                  <tbody>
                     <input type="hidden" name="userId" class="form-control"  value="{{ $permissions->userId }}" />
                     <input type="hidden" name="row" value="{{ count($formlist) }}">
                     @php $no=1; @endphp
                     @foreach($formlist as $row)   
                     <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $row->form_label }}</td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}">
                           <input type="checkbox" name="chk{{ $no }}" value=""
                           @foreach($formlistbyuser as $rowc) 
                           {{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}
                           @endforeach
                           >  
                        </td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}">
                           <input type="checkbox" name="chkw{{ $no }}" value="" 
                           @foreach($formlistbyuser as $rowc) 
                           @if($rowc->write_access == 1)
                           {{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}
                           @endif
                           @endforeach                   
                           >
                        </td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chke{{ $no }}" value="" 
                           @foreach($formlistbyuser as $rowc) 
                           @if($rowc->edit_access==1)
                           {{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}
                           @endif
                           @endforeach  
                           >
                        </td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}">
                           <input type="checkbox" name="chkd{{ $no }}" value="" 
                           @foreach($formlistbyuser as $rowc) 
                           @if($rowc->delete_access==1)
                           {{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}
                           @endif
                           @endforeach  
                           >
                        </td>
                     </tr>
                     @php $no=$no+1;  @endphp
                     @endforeach
                  </tbody>
               </table>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
               </div>
            </form>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script> 
                $(document).on('change',".subOption",function (e) 
                {
                    if($(this).is(":checked") == true)
                    {
                        $(this).parent().parent().parent().parent().find('input[name="process_id[]"]').attr('checked', true);
                    }
                    else
                    {
                        $(this).parent().parent().parent().parent().find('input[name="process_id[]"]').attr('checked', false);
                    } 
                });
            </script>
            @else
            <form action="{{route('User_Management.store')}}" method="POST">
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Vendor</label>
                        <select name="vendorId" class="form-select" id="vendorId ">
                           <option value="">--- Select Vendor ---</option>
                           @foreach($VendorList as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                              >{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Employee</label>
                        <select name="w_id" class="form-select" id="w_id ">
                           <option value="">--- Select Employee ---</option>
                           @foreach($workerlist as  $row)
                           {
                           <option value="{{ $row->w_id }}">{{ $row->w_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">User Type</label>
                        <select name="user_type" class="form-select" id="user_type">
                           <option value="">--- User Type ---</option>
                           @foreach($user_typelist as  $user_typerow)
                           {
                           <option value="{{ $user_typerow->utype_id }}">{{ $user_typerow->user_type }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="gpo_approval_id" class="form-label">GPO Approval</label>
                        <select name="gpo_approval_id" class="form-select" id="gpo_approval_id">
                           <option value="">--- Select ---</option>
                           @foreach($GPOApprovelist as $gpo)
                                <option value="{{ $gpo->gpo_approval_id }}">{{ $gpo->gpo_approval_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">User Contact</label>
                        <input type="text" name="contact" class="form-control" id="formrow-email-input" value="">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">User Address</label>
                        <input type="text" name="address" class="form-control" id="formrow-email-input" value="">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">User Name</label>
                        <input type="text" name="username" class="form-control" id="formrow-email-input" value="">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" id="formrow-email-input" value="">
                     </div>
                  </div>
               </div> 
               <div class="row">
                    @foreach($processlist as $process) 
                    <div class="col-md-3"> 
                           <ul>
                            <li>
                              <input type="checkbox" id="process_id" name="process_id[]" value="{{$process->process_id}}"><label for="option">&nbsp;&nbsp;{{$process->process_name}}</label>
                              <ul>
                                <li><label><input type="checkbox" class="subOption" name="subProcessRead{{$process->process_id}}[]" value="1">&nbsp;&nbsp;Read</label></li>
                                <li><label><input type="checkbox" class="subOption" name="subProcessWrite{{$process->process_id}}[]" value="1">&nbsp;&nbsp;Write</label></li>
                                <li><label><input type="checkbox" class="subOption" name="subProcessEdit{{$process->process_id}}[]" value="1">&nbsp;&nbsp;Edit</label></li>
                                <li><label><input type="checkbox" class="subOption" name="subProcessDelete{{$process->process_id}}[]" value="1">&nbsp;&nbsp;Delete</label></li>
                              </ul>
                            </li>
                          </ul>
                   </div>
                   @endforeach
               </div> 
               <div class="row"> 
               <div class="col-md-4" style="background: blanchedalmond;padding: 3px;">  
                 <table id="table2" class="table table-hover display  pb-30">
                  <thead>
                     <tr>
                        <th>Type</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Edit</th>
                        <th>Delete</th>
                     </tr>
                  </thead>
                  <tbody>  
                       @php
                            $packingTypeData = DB::SELECT("SELECT * FROM packing_type_master WHERE delflag=0");
                            $srno1 = 1;      
                      @endphp
                      @foreach($packingTypeData as $packingType) 
                      <tr>
                          <td>{{$packingType->packing_short_name}}</td>
                          <td><input type="checkbox" name="packingRead{{$srno1}}" value="1"></td>
                          <td><input type="checkbox" name="packingWrite{{$srno1}}" value="1"></td>
                          <td><input type="checkbox" name="packingEdit{{$srno1}}" value="1"></td>
                          <td><input type="checkbox" name="packingDelete{{$srno1}}" value="1"></td>
                      </tr>
                      @php
                        $srno1++;
                      @endphp
                      @endforeach 
                 </tbody>
                 </table> 
               </div>
               <div class="col-md-3" style="background: #22d381;padding: 3px;">  
                  <h3 class="m-3"><b>Order Group</b></h3>
                 <table id="table3" class="table table-hover display  pb-30">
                      @php
                            $orderGroupData = DB::SELECT("SELECT * FROM order_group_master WHERE delflag=0");    
                      @endphp
                      @foreach($orderGroupData as $orderGroup) 
                      <tr>
                          <th>{{$orderGroup->order_group_name}}</th>
                          <td><input type="checkbox" name="og_id[]"  value="{{$orderGroup->og_id}}"></td> 
                      </tr> 
                      @endforeach 
                 </table> 
                 <br/>
                 <br/>
                 <br/>
                 <h3 class="m-3"><b>PO Type Access</b></h3>
                 <table id="table3" class="table table-hover display  pb-30">
                      @php
                            $poTypeData = DB::SELECT("SELECT * FROM po_type_master WHERE delflag=0");    
                      @endphp
                      @foreach($poTypeData as $potype) 
                      <tr>
                          <th>{{$potype->po_type_name}}</th>
                          <td><input type="checkbox" name="po_type_id[]"  value="{{$potype->po_type_id}}" ></td> 
                      </tr> 
                      @endforeach 
                 </table> 
               </div>
               <div class="col-md-4" style="background: #d3a922;padding: 3px;">  
                  <h3 class="m-3"><b>Sales Head</b></h3>
                 <table id="table3" class="table table-hover display  pb-30">
                      @php
                            $salesHeadData = DB::SELECT("SELECT * FROM sales_head_master WHERE delflag=0");    
                      @endphp
                      @foreach($salesHeadData as $saleHead) 
                      @php 
                            
                      @endphp
                      <tr>
                          <th>{{$saleHead->sales_head_name}}</th>
                          <td><input type="checkbox" name="sales_head_id[]"  value="{{$saleHead->sales_head_id}}"></td> 
                      </tr> 
                      @endforeach 
                 </table> 
               </div>
               </div>
               <table id="myTable1" class="table table-hover display  pb-30">
                  <thead>
                     <tr>
                        <th>SrNo</th>
                        <th>Form Name</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Edit</th>
                        <th>Delete</th>
                     </tr>
                  </thead>
                  <tfoot>
                     <tr>
                        <th>SrNo</th>
                        <th>Form Name</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Edit</th>
                        <th>Delete</th>
                     </tr>
                  </tfoot>
                  <tbody>
                     @foreach($maxuserid as  $max)
                     <input type="hidden" name="userId" class="form-control"  value="{{ $max->userId }}" />
                     @endforeach
                     <input type="hidden" name="row" value="{{ count($formlist) }}">
                     @php $no=1; @endphp
                     @foreach($formlist as $row)   
                     <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $row->form_label }}</td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chk{{ $no }}"></td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chkw{{ $no }}"></td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chke{{ $no }}"></td>
                        <td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chkd{{ $no }}"></td>
                     </tr>
                     @php $no=$no+1;  @endphp
                     @endforeach
                  </tbody>
               </table>
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
<!-- end row -->
<!-- end row -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).on('change',".subOption",function (e) 
    {
        if($(this).is(":checked") == true)
        {
            $(this).parent().parent().parent().parent().find('input[name="process_id[]"]').attr('checked', true);
        }
        else
        {
            $(this).parent().parent().parent().parent().find('input[name="process_id[]"]').attr('checked', false);
        } 
    });
</script>
@endsection