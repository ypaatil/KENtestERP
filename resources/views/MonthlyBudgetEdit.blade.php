@extends('layouts.master') 
@section('content')
<style>
   .hide
   {
   display:none;
   }
   
    input[type="number"], .value, .per {
      text-align: right!important;
    } 
    
    input[type="text"] {
      text-align: left!important;
    }
       
    @media (max-width: 600px) 
    {
        .breadcumbCls 
        {
            display: none;
        }
        
        .navbar-header
        {
            background: #703eb385;
        }
        .titleCls
        { 
            text-align: center;
        }
        
        #vertical-menu-btn
        {
            display: none;
        }
    }
    
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Monthly Budget</h4>
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
            <form action="{{route('monthly_budget.update',$MonthlyMasterList)}}" method="POST"  enctype="multipart/form-data" id="frmData">
                
                @method('put')
               @csrf 
               
                <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" >
                           <input type="hidden" name="monthly_budget_id" value="{{ $MonthlyMasterList->monthly_budget_id }}" class="form-control" >  
               <div class="row">
                      
                              <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cur_id" class="form-label">Year</label>
                            <select name="year" class="form-control select2" id="year" required>
                            <option value="0">--Select--</option>
                            @php 
                           
                            $currentYear = date('Y');
                            for($i = 2022; $i <= $currentYear; $i++) { 
                            @endphp
                            <option value="{{ $i }}" {{ $i == $MonthlyMasterList->year ? 'selected="selected"' : '' }}>{{ $i }}</option>
                            @php 
                            } 
                            @endphp
                            </select>
                        
                         
                     </div>
                  </div>      
                      
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cur_id" class="form-label">Month</label>
                        <select name="monthId" class="form-control select2" id="monthId" required>
                            <option value="0">--Select--</option>
                            @foreach($monthlyList as $rowMonth)
                                <option value="{{ $rowMonth->monthId }}"
                                
                                {{ $MonthlyMasterList->monthId==$rowMonth->monthId ? 'selected="selected"' : '';   }}
                                
                                >{{$rowMonth->MonthName}}</option>
                            @endforeach
                        </select> 
                     </div>
                  </div>
           
                   
   
               </div>
               <div class="row">
                  <label class="form-label">SALES: </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Buyer</th>
                                 <th>LPC</th>
                                 <th>FOB</th>
                                 <th>RS CR</th>
                                 <th>L MIN</th>
                                 <th>CMOHP</th>
                                 <th>Remark</th>   
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                               
                               
                                  @php
                                $srno1 = 1;
                              @endphp
                              @foreach($sales as $rowsale) 
                              <tr>
                                 <td><input type="text" name="id" value="{{ $srno1++ }}" id="id" style="width:50px;" readonly/></td>
                                 <td>
                            <select name="Ac_code_sale[]" class="form-control select2" id="Ac_code" onChange="previousData1(this,this.value);">
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           
                           {{ $row->ac_code==$rowsale->Ac_code ? 'selected="selected"' : '';   }} 
                           
                           >{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select> 
                                 </td>
                                 <td><input type="number" step="any" step="any" name="lpc_sale[]" value="{{ $rowsale->lpc }}" id="lpc_sale" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="fob_sale[]" value="{{ $rowsale->fob }}" id="fob_sale" style="width:80px;height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="rs_cr_sale[]" value="{{ $rowsale->rs_cr }}" onkeyup="calculateFOB(this,this.value);" id="rs_cr_sale" style="width:80px;height:30px;"  /></td>
                                  <td><input type="number" step="any" step="any" name="l_min_sale[]" value="{{ $rowsale->l_min }}" id="l_min_sale" style="width:80px;height:30px;"  /></td>
                                  <td><input type="number" step="any" step="any" class="FABRIC"   name="cmohp_sale[]" value="{{ $rowsale->cmohp }}" id="cmohp_sale" style="width:80px;height:30px;"  /></td>
                                 <td><input type="text" step="any" step="any" name="remark_sale[]" value="{{ $rowsale->remark }}" id="remark_sale"  /></td>
                                 <td><button type="button" onclick="insertcone1(this);" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
                              </tr>
                                @endforeach
                              
                           </tbody>
                           
                        <tfoot>
                        <tr>
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_lpc_sale }}" style="width:80px; height:30px;" name="total_lpc_sale"  readOnly id="totalLPC"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_fob_sale }}" style="width:80px; height:30px;" name="total_fob_sale"  readOnly id="totalFOB"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_rs_cr_sale }}" style="width:80px; height:30px;" name="total_rs_cr_sale"  readOnly id="totalRSCR"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_lmin_sale }}" style="width:80px; height:30px;" name="total_lmin_sale"  readOnly id="totalLMIN"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_cmohp }}" style="width:80px; height:30px;" name="total_cmohp"  readOnly id="totalCMOHP"></td>
                        <td></td>
                        </tr>
                        </tfoot>
                        </table>
                        
                     </div>
                  </div>
               </div>
               </br>
               <div class="row">
                  <label class="form-label">PRODUCTION: </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>
                              <th>Sr No</th>
                                 <th>Buyer</th>
                                 <th>LPC</th>
                                 <th>FOB</th>
                                 <th>RS CR</th>
                                 <th>L MIN</th>
                                 <th>CMOHP</th>   
                                <th>Remark</th>       
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                               
                              @php
                              $srno2 = 1;
                              @endphp
                              @foreach($productionData as $rowproductions)    
                              <tr>
                                 <td><input type="text" name="ids" value="{{ $srno2++ }}" id="id" style="width:50px;" readonly/></td>
                                 <td>
                            <select name="Ac_code_production[]" class="form-control" id="Ac_code" onChange="previousData2(this,this.value);">
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $rowLedgerProduction)
                           
                           <option value="{{ $rowLedgerProduction->ac_code }}"
                           
                              {{ $rowLedgerProduction->ac_code==$rowproductions->Ac_code_production ? 'selected="selected"' : '';   }} 
                           
                           >{{ $rowLedgerProduction->ac_name }}</option>
                           
                           @endforeach
                        </select>         
                                 </td>
                                 <td><input type="number" step="any" step="any" name="lpc_production[]" value="{{ $rowproductions->lpc }}" id="lpc_production" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="fob_production[]" value="{{ $rowproductions->fob }}" id="fob_production" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="rs_cr_production[]" value="{{ $rowproductions->rs_cr }}" onkeyup="calculateFOBProduction(this,this.value);" id="wastages" style="width:80px; height:30px;"  /></td>
                                  <td><input type="number" step="any" step="any" name="l_min_production[]" value="{{ $rowproductions->l_min }}" id="wastages" style="width:80px; height:30px;"  /></td> 
                                 <td><input type="number" step="any" step="any"  class="SEWING"  name="cmohp_production[]" value="{{ $rowproductions->cmohp }}" id="total_amounts" style="width:80px; height:30px;" required /></td>
                               
                                 <td><input type="text" step="any" step="any" name="remark_production[]" value="{{ $rowproductions->remark }}" id="remark_production"  /></td>
                                 <td><button type="button" onclick="insertcone2(this);" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
                              
                             
                              </tr>
                              
                              @endforeach
                           </tbody>
                              <tfoot>
                        <tr>
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_lpc_production }}" style="width:80px; height:30px;" name="total_lpc_production"  readOnly id="totalLPCPRODUCTION"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_fob_production }}" style="width:80px; height:30px;" name="total_fob_production"  readOnly id="totalFOBPRODUCTION"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_rs_cr_production }}" style="width:80px; height:30px;" name="total_rs_cr_production"  readOnly id="totalRSCRPRODUCTION"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_l_min_production }}" style="width:80px; height:30px;" name="total_l_min_production"  readOnly id="totalLMINPRODUCTION"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_cmohp_production }}" style="width:80px; height:30px;" name="total_cmohp_production"   readOnly id="totalCMOHPPRODUCTION"></td>
                        <td></td>
                        </tr>
                        </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
               </br>
               <div class="row">
                  <label class="form-label">PURCHASES FABRIC: </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_4" class="table  table-bordered table-striped m-b-0  footable_4">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Buyer</th>
                                 <th>L MTR</th>
                                 <th>Rate</th>
                                 <th>RS CR</th>
                                 <th>DAYS</th>
                                 <th>Remark</th>  
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                               
                                   @php
                              $srno3 = 1;
                              @endphp
                              @foreach($purchase_fabric as $rowPFabric)      
                              <tr>
                                 <td><input type="text" name="idss" value="{{ $srno3++ }}" id="id" style="width:50px;" readonly/></td>
                                 <td>
                                     
                                   <select name="Ac_code_purchase_fabric[]" class="form-control select2" id="Ac_code"  onChange="previousData3(this,this.value);">
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $rowLedgerPFabric)
                           {
                           <option value="{{ $rowLedgerPFabric->ac_code }}"
                           
                           {{ $rowLedgerPFabric->ac_code==$rowPFabric->Ac_code ? 'selected="selected"' : '';   }} 
                           
                           >{{ $rowLedgerPFabric->ac_name }}</option>
                           }
                           @endforeach
                        </select>               
                                     
                                 </td>
                                 <td><input type="number" step="any" step="any" name="l_mtr_purchase_fabric[]" value="{{ $rowPFabric->l_mtr  }}" id="consumptionss" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="rate_purchase_fabric[]" value="{{ $rowPFabric->rate  }}" id="rate_per_unitss" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="rs_cr_purchase_fabric[]" value="{{ $rowPFabric->rs_cr  }}" onkeyup="calculateRatePurchaseFabric(this,this.value);" id="wastagess" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" class=""  name="days_purchase_fabric[]" value="{{ $rowPFabric->days }}" id="total_amountss" style="width:80px; height:30px;"   /></td>
                                 <td><input type="text" step="any" step="any" class=""  name="remark_purchase_fabric[]" value="{{ $rowPFabric->remark }}" id="remark_purchase_fabric"    /></td>
                                 <td><button type="button" onclick="insertcone3(this);" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
                              </tr>
                              
                              @endforeach
                              
                           </tbody>
                              <tfoot>
                              <tr>
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_l_mtr_purchase_fabric }}" style="width:80px; height:30px;" name="total_l_mtr_purchase_fabric"  readOnly id="totalLMTRPFABRIC"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_rate_purchase_fabric }}" style="width:80px; height:30px;" name="total_rate_purchase_fabric"   readOnly id="totalRATEPFABRIC"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_rs_cr_purchase_fabric }}" style="width:80px; height:30px;" name="total_rs_cr_purchase_fabric"  readOnly id="totalRSCRPFABRIC"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_days_purchase_fabric }}" style="width:80px; height:30px;" name="total_days_purchase_fabric"  readOnly id="totalDAYSPFABRIC"></td>
                        <td></td>
                        </tr>
                          </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
                           </br>

                            <div class="row">
                  <label class="form-label">PRUCHASES TRIMS: </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_5" class="table  table-bordered table-striped m-b-0  footable_5">
                           <thead>
                              <tr>
                                  <th>Sr No</th>
                                 <th>Buyer</th>
                                 <th>RS CR</th>
                                 <th>DAYS</th>
                                 <th>Remark</th>  
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                                   @php
                              $srno4 = 1;
                              @endphp
                              @foreach($purchase_trim as $rowPTrims)    
                               
                              <tr>
                                 <td><input type="text" name="idss" value="{{ $srno4++ }}" id="id" style="width:50px;" readonly/></td>
                                 <td>
                                     
                                   <select name="Ac_code_purchase_trims[]" class="form-control select2" id="Ac_code" required onChange="previousData4(this,this.value);">
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $rowLedgerPTRIMS)
                           {
                           <option value="{{ $rowLedgerPTRIMS->ac_code }}"
                           
                           {{ $rowLedgerPTRIMS->ac_code==$rowPTrims->Ac_code ? 'selected="selected"' : '';   }} 
                           
                           >{{ $rowLedgerPTRIMS->ac_name }}</option>
                           }
                           @endforeach
                        </select>               
                                     
                                 </td>
                                 <td><input type="number" step="any" step="any" name="rs_cr_purchase_trims[]" value="{{ $rowPTrims->rs_cr  }}" id="rs_cr_purchase_trims" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="days_purchase_trims[]" value="{{ $rowPTrims->days  }}" id="days_purchase_trims" style="width:80px; height:30px;"  /></td>
                                 <td><input type="text" step="any" step="any" name="remark_purchase_trims[]" value="{{ $rowPTrims->remark  }}" id="remark_purchase_trims"   /></td>
                               
                                 <td><button type="button" onclick="insertcone4(this);" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone4(this);" value="X" ></td>
                              </tr>
                              
                              @endforeach
                              
                           </tbody>
                              <tfoot>
                              <tr>
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_rs_cr_purchase_trims }}" style="width:80px; height:30px;" name="total_rs_cr_purchase_trims"  readOnly id="totalRSPTRIMS"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_days_purchase_trims }}" style="width:80px; height:30px;" name="total_days_purchase_trims"  readOnly id="totalDAYSPTRIMS"></td>
                        <td></td>
                        </tr>
                          </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
                         
               
               
                                         </br>
               <div class="row">
                  <label class="form-label">PURCHASES JOBWORK </label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_6" class="table  table-bordered table-striped m-b-0  footable_6">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Buyer</th>
                                 <th>L PC</th>
                                 <th>RATE</th>
                                 <th>RS CR</th> 
                                  <th>L MIN</th>  
                                 <th>Remark</th>  
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                               
                               @php
                              $srno5 = 1;
                              @endphp
                              @foreach($purchase_job_work as $rowPJobWork)       
                              <tr>
                                 <td><input type="text" name="idss" value="{{ $srno5++ }}" id="id" style="width:50px;" readonly/></td>
                                 <td>
                                     
                                   <select name="Ac_code_purchase_job_work[]" class="form-control select2" id="Ac_code"  onChange="previousData5(this,this.value);">
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $rowLedgerPJOBWORK)
                           {
                           <option value="{{ $rowLedgerPJOBWORK->ac_code }}"
                           
                            {{ $rowLedgerPJOBWORK->ac_code==$rowPJobWork->Ac_code ? 'selected="selected"' : '';   }} 
                           
                           >{{ $rowLedgerPJOBWORK->ac_name }}</option>
                           }
                           @endforeach
                        </select>               
                                     
                                 </td>
                                 <td><input type="number" step="any" step="any" name="l_pc_purchase_job_work[]" value="{{ $rowPJobWork->lpc  }}" id="consumptionss" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="rate_purchase_job_work[]" value="{{ $rowPJobWork->rate  }}" id="rate_per_unitss" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="rs_cr_job_work[]" value="{{ $rowPJobWork->rs_cr  }}" onkeyup="calculateRatePurchaseJobWork(this,this.value);" id="wastagess" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" class=""  name="l_min_job_work[]" value="{{ $rowPJobWork->l_min  }}" id="total_amountss" style="width:80px; height:30px;"   /></td>
                                  <td><input type="text" step="any" step="any" class=""  name="remark_job_work[]" value="{{ $rowPJobWork->remark  }}" id="remark_job_work"    /></td>
                                 <td><button type="button" onclick="insertcone5(this);" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone5(this);" value="X" ></td>
                              </tr>
                              
                              @endforeach
                              
                           </tbody>
                             <tfoot>
                              <tr>
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_l_pc_purchase_job_work }}" style="width:80px; height:30px;" name="total_l_pc_purchase_job_work"  readOnly id="totalLPCPURCHASEJOBWORK"></td>
                        <td ><input type="number" value="{{ $MonthlyMasterList->total_rate_purchase_job_work }}" style="width:80px; height:30px;" name="total_rate_purchase_job_work"  readOnly id="totalRATEPURCHASEJOBWORK"></td>
                          <td ><input type="number" value="{{ $MonthlyMasterList->total_rs_cr_job_work }}" style="width:80px; height:30px;" name="total_rs_cr_job_work"  readOnly id="totalRSCRJOBWORK"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_l_min_job_work }}" style="width:80px; height:30px;" name="total_l_min_job_work"  readOnly id="totalLIMINJOBWORK"></td>
                        <td></td>
                        </tr>
                          </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
               
               
                                                        </br>
               <div class="row">
                  <label class="form-label">Collection</label> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_7" class="table  table-bordered table-striped m-b-0  footable_6">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Buyer</th>
                                 <th>Total O/S</th>
                                 <th>RS CR</th>
                                 <th>Remark</th>  
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                               @php
                              $srno6 = 1;
                              @endphp
                              @foreach($collection as $rowCollection)         
                               
                              <tr>
                                 <td><input type="text" name="idss" value="{{ $srno6++ }}" id="id" style="width:50px;" readonly/></td>
                                 <td>
                                     
                                   <select name="Ac_code_collection[]" class="form-control select2" id="Ac_code"  onChange="previousData6(this,this.value);">
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $rowLedgerCollection)
                           {
                           <option value="{{ $rowLedgerCollection->ac_code }}"
                           
                           {{ $rowLedgerCollection->ac_code==$rowCollection->Ac_code ? 'selected="selected"' : '';   }} 
                           
                           >{{ $rowLedgerCollection->ac_name }}</option>
                           }
                           @endforeach
                        </select>               
                                     
                                 </td>
                                 <td><input type="number" step="any" step="any" name="total_os[]" value="{{ $rowCollection->total_os }}" id="consumptionss" style="width:80px; height:30px;"  /></td>
                                 <td><input type="number" step="any" step="any" name="rs_cr_collection[]" value="{{ $rowCollection->rs_cr }}" id="rs_cr_collection" style="width:80px; height:30px;"  /></td>
                                 <td><input type="text" step="any" step="any" name="remark_collection[]" value="{{ $rowCollection->remark }}" id="wastagess"   /></td>
                                
                                 <td><button type="button" onclick="insertcone6(this);" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone6(this);" value="X" ></td>
                              </tr>
                              
                                @endforeach
                           </tbody>
                             <tfoot>
                              <tr>
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->grand_total_os }}" style="width:80px; height:30px;" name="grand_total_os"  readOnly id="totalOS"></td>
                        <td><input type="number" value="{{ $MonthlyMasterList->total_rs_cr_collection }}" style="width:80px; height:30px;" name="total_rs_cr_collection"  readOnly id="totalRSCRCOLLECTION"></td>
                        <td></td>
                        </tr>
                          </tfoot>
                           
                        </table>
                     </div>
                  </div>
               </div>
               
               
               <!-- end row -->

               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit">Submit</button>
                     <a href="{{ Route('monthly_budget.index') }}" id="cancel" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
  
  
$(document).on("mouseover", 'select', function (event) {
    $(this).focus();
    $(this).select2(); // Initialize or refresh Select2 here
});

$(document).on('select2:open', () => {
    setTimeout(() => {
        document.querySelector('.select2-search__field').focus();
    }, 1); // Delay to ensure the field is available
});

  
 
   function calOrderRate()
   {
       var exchange_rate=$('#exchange_rate').val();
       var inr_rate=$('#inr_rate').val();
       var order_rate=(parseFloat(inr_rate) * parseFloat(exchange_rate)).toFixed(2);
       $('#fob_rate').val(order_rate);
       calculateGarmentRejectionValue($("#garment_reject_per"));
   }
   
   
   function insertcone1(row)
   {
       
      var select2 = $(row).closest("tr").find('select').select2();
      if (select2) { $(row).closest("tr").find('select').select2('destroy'); } else {}
       
       var $lastRow = $("#footable_2 tbody tr").last();
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input[name="item_name[]"]').val(''); 
        $newRow.appendTo("#footable_2"); // Append the cloned row to the table
        recalcIdcone1();
        recalculateTotals_footable_2();
       
   }
   
   

  

   function recalculateTotals_footable_2() {
    let totalLPC = 0;
    let totalFOB = 0;
    let totalRSCR = 0;
    let totalLMIN = 0;
    let totalCMOHP = 0;

    // Loop through each row in the table
    $("#footable_2 tbody tr").each(function() {
        const lpc = parseFloat($(this).find('input[name="lpc_sale[]"]').val()) || 0;
        const fob = parseFloat($(this).find('input[name="fob_sale[]"]').val()) || 0;
        const rscr = parseFloat($(this).find('input[name="rs_cr_sale[]"]').val()) || 0;
        const lmin = parseFloat($(this).find('input[name="l_min_sale[]"]').val()) || 0;
        const cmohp = parseFloat($(this).find('input[name="cmohp_sale[]"]').val()) || 0;

        totalLPC += lpc; 
        totalFOB += fob; 
        totalRSCR += rscr; 
        totalLMIN += lmin; 
        totalCMOHP += cmohp; 
    });

    // Update the totals in the <tfoot>
    $('#totalLPC').val(totalLPC.toFixed(2));
    $('#totalFOB').val(totalFOB.toFixed(2));
    $('#totalRSCR').val(totalRSCR.toFixed(2));
    $('#totalLMIN').val(totalLMIN.toFixed(2));
    $('#totalCMOHP').val(totalCMOHP.toFixed(2));
}

// Call this function whenever an input value changes to recalculate totals
$(document).on('input', 'input[name="lpc_sale[]"], input[name="fob_sale[]"], input[name="rs_cr_sale[]"], input[name="l_min_sale[]"],input[name="cmohp_sale[]"]', function() {
    recalculateTotals_footable_2();
});


   
   
   
   
   function insertcone2(row)
   {
       
           var select2 = $(row).closest("tr").find('select').select2();
      if (select2) { $(row).closest("tr").find('select').select2('destroy'); } else {}  
       
        var $lastRow = $("#footable_3 tbody tr").last(); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input[name="item_name[]"]').val('');
        $newRow.appendTo("#footable_3"); // Append the cloned row to the table
        recalcIdcone2();
        recalculateTotals_footable_3();
   }


   function recalculateTotals_footable_3() {
    let totalLPC = 0;
    let totalFOB = 0;
    let totalRSCR = 0;
    let totalLMIN = 0;
    let totalCMOHP = 0;

    // Loop through each row in the table
    $("#footable_3 tbody tr").each(function() {
        const lpc = parseFloat($(this).find('input[name="lpc_production[]"]').val()) || 0;
        const fob = parseFloat($(this).find('input[name="fob_production[]"]').val()) || 0;
        const rscr = parseFloat($(this).find('input[name="rs_cr_production[]"]').val()) || 0;
        const lmin = parseFloat($(this).find('input[name="l_min_production[]"]').val()) || 0;
        const cmohp = parseFloat($(this).find('input[name="cmohp_production[]"]').val()) || 0;

        totalLPC += lpc; 
        totalFOB += fob; 
        totalRSCR += rscr; 
        totalLMIN += lmin; 
        totalCMOHP += cmohp; 
    });

    // Update the totals in the <tfoot>
    $('#totalLPCPRODUCTION').val(totalLPC.toFixed(2));
    $('#totalFOBPRODUCTION').val(totalFOB.toFixed(2));
    $('#totalRSCRPRODUCTION').val(totalRSCR.toFixed(2));
    $('#totalLMINPRODUCTION').val(totalLMIN.toFixed(2));
    $('#totalCMOHPPRODUCTION').val(totalCMOHP.toFixed(2));
}

// Call this function whenever an input value changes to recalculate totals
$(document).on('input', 'input[name="lpc_production[]"], input[name="fob_production[]"], input[name="rs_cr_production[]"], input[name="l_min_production[]"],input[name="cmohp_production[]"]', function() {
    recalculateTotals_footable_3();
});
   
   
   
   


   function insertcone3(row)
   {
       
            var select2 = $(row).closest("tr").find('select').select2();
      if (select2) { $(row).closest("tr").find('select').select2('destroy'); } else {} 
       
        var $lastRow = $("#footable_4 tbody tr").last(); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input[name="item_name[]"]').val('');
        $newRow.appendTo("#footable_4"); // Append the cloned row to the table
        recalcIdcone3();
        recalculateTotals_footable_4();
   }
   
   
   
   
      function recalculateTotals_footable_4() {
    let totalLMTRPFABRIC = 0;
    let totalRATEPFABRIC = 0;
    let totalRSCRPFABRIC = 0;
    let totalDAYSPFABRIC = 0;

    // Loop through each row in the table
    $("#footable_4 tbody tr").each(function() {
        const l_mtr = parseFloat($(this).find('input[name="l_mtr_purchase_fabric[]"]').val()) || 0;
        const rate_ = parseFloat($(this).find('input[name="rate_purchase_fabric[]"]').val()) || 0;
        const rs_cr_ = parseFloat($(this).find('input[name="rs_cr_purchase_fabric[]"]').val()) || 0;
        const days_ = parseFloat($(this).find('input[name="days_purchase_fabric[]"]').val()) || 0;
     

        totalLMTRPFABRIC += l_mtr; 
        totalRATEPFABRIC += rate_; 
        totalRSCRPFABRIC += rs_cr_; 
        totalDAYSPFABRIC += days_; 
       
    });
    


    // Update the totals in the <tfoot>
    $('#totalLMTRPFABRIC').val(totalLMTRPFABRIC.toFixed(2));
    $('#totalRATEPFABRIC').val(totalRATEPFABRIC.toFixed(2));
    $('#totalRSCRPFABRIC').val(totalRSCRPFABRIC.toFixed(2));
    $('#totalDAYSPFABRIC').val(totalDAYSPFABRIC.toFixed(2));
    
}

// Call this function whenever an input value changes to recalculate totals
$(document).on('input','input[name="l_mtr_purchase_fabric[]"], input[name="rate_purchase_fabric[]"], input[name="rs_cr_purchase_fabric[]"], input[name="days_purchase_fabric[]"]', function() {
    recalculateTotals_footable_4();
});
   
   
   

   
   
   function insertcone4(row)
   {
       
          var select2 = $(row).closest("tr").find('select').select2();
      if (select2) { $(row).closest("tr").find('select').select2('destroy'); } else {}   
       
        var $lastRow = $("#footable_5 tbody tr").last(); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input').not('.btn-danger').val(''); // Clear the input values
        $newRow.appendTo("#footable_5"); // Append the cloned row to the table
        
         recalcIdcone4();
        recalculateTotals_footable_5();
   }
   
   
   
   
         function recalculateTotals_footable_5() {
             
             
    let totalRSCRPTRIMS = 0;
    let totalDAYSPTRIMS = 0;


    // Loop through each row in the table
    $("#footable_5 tbody tr").each(function() {
        
        const rs_cr_purchase_trims = parseFloat($(this).find('input[name="rs_cr_purchase_trims[]"]').val()) || 0;
        const days_purchase_trims = parseFloat($(this).find('input[name="days_purchase_trims[]"]').val()) || 0;
     
    
        totalRSCRPTRIMS += rs_cr_purchase_trims; 
        totalDAYSPTRIMS += days_purchase_trims; 
       
    });
    


    // Update the totals in the <tfoot>
    $('#totalRSPTRIMS').val(totalRSCRPTRIMS.toFixed(2));
    $('#totalDAYSPTRIMS').val(totalDAYSPTRIMS.toFixed(2));
  
    
}

// Call this function whenever an input value changes to recalculate totals
$(document).on('input','input[name="rs_cr_purchase_trims[]"], input[name="days_purchase_trims[]"]', function() {
    recalculateTotals_footable_5();
});
   
   
   
   
   
   
      function insertcone5(row)
   {
       
          var select2 = $(row).closest("tr").find('select').select2();
      if (select2) { $(row).closest("tr").find('select').select2('destroy'); } else {}   
       
        var $lastRow = $("#footable_6 tbody tr").last(); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input').not('.btn-danger').val(''); // Clear the input values
        $newRow.appendTo("#footable_6"); // Append the cloned row to the table
        
          recalcIdcone5();
        recalculateTotals_footable_6();
   }
   
   
   
            function recalculateTotals_footable_6() {
             
             
    let totalLPCPURCHASEJOBWORK = 0;
    let totalRATEPURCHASEJOBWORK = 0;
    let totalRSCRJOBWORK = 0;
    let totalLMINJOBWORK = 0;
    



    // Loop through each row in the table
    $("#footable_6 tbody tr").each(function() {
        
        
          const l_pc_purchase_job_work = parseFloat($(this).find('input[name="l_pc_purchase_job_work[]"]').val()) || 0;
          const rate_purchase_job_work = parseFloat($(this).find('input[name="rate_purchase_job_work[]"]').val()) || 0;
          const rs_cr_job_work = parseFloat($(this).find('input[name="rs_cr_job_work[]"]').val()) || 0;
          const l_min_job_work = parseFloat($(this).find('input[name="l_min_job_work[]"]').val()) || 0;
     
    
        totalLPCPURCHASEJOBWORK += l_pc_purchase_job_work; 
        totalRATEPURCHASEJOBWORK += rate_purchase_job_work; 
        totalRSCRJOBWORK += rs_cr_job_work; 
        totalLMINJOBWORK += l_min_job_work; 
       
    });
    


    // Update the totals in the <tfoot>
    $('#totalLPCPURCHASEJOBWORK').val(totalLPCPURCHASEJOBWORK.toFixed(2));
    $('#totalRATEPURCHASEJOBWORK').val(totalRATEPURCHASEJOBWORK.toFixed(2));
    $('#totalRSCRJOBWORK').val(totalRSCRJOBWORK.toFixed(2));
   $('#totalLIMINJOBWORK').val(totalLMINJOBWORK.toFixed(2));
    
}

// Call this function whenever an input value changes to recalculate totals
$(document).on('input','input[name="l_pc_purchase_job_work[]"], input[name="rate_purchase_job_work[]"],input[name="rs_cr_job_work[]"],input[name="l_min_job_work[]"]', function() {
    recalculateTotals_footable_6();
});
   
   

   
   
   
   
   
   
   
   
         function insertcone6(row)
   {
       
            var select2 = $(row).closest("tr").find('select').select2();
      if (select2) { $(row).closest("tr").find('select').select2('destroy'); } else {} 
       
        var $lastRow = $("#footable_7 tbody tr").last(); // Select the last row
        var $newRow = $lastRow.clone(); // Clone it
        
        $newRow.find('input').not('.btn-danger').val(''); // Clear the input values
        $newRow.appendTo("#footable_7"); // Append the cloned row to the table
        
        recalcIdcone6();
   }
   
   
   
   
               function recalculateTotals_footable_7() {
             
             
    let totalOS = 0;
    let totalRS = 0;

    


    // Loop through each row in the table
    $("#footable_7 tbody tr").each(function() {
        
        
          const total_os = parseFloat($(this).find('input[name="total_os[]"]').val()) || 0;
          const rs_cr_collection = parseFloat($(this).find('input[name="rs_cr_collection[]"]').val()) || 0;

     
    
        totalOS += total_os; 
        totalRS += rs_cr_collection; 

    });
    
   

    // Update the totals in the <tfoot>
    $('#totalOS').val(totalOS.toFixed(2));
    $('#totalRSCRCOLLECTION').val(totalRS.toFixed(2));
 
    
}

// Call this function whenever an input value changes to recalculate totals
$(document).on('input','input[name="total_os[]"], input[name="rs_cr_collection[]"]', function() {
    recalculateTotals_footable_7();
});
   
   
   
   
   
   
   
   
   function CalTotalValue()
   {
        var fob_rate = $("#fob_rate").val() ? $("#fob_rate").val() : 0;
        var total_qty = $("#total_qty").val() ? $("#total_qty").val() : 0;
       
        $("#total_value").val(parseFloat(total_qty) * parseFloat(fob_rate));
        $('#footabel_2 > tbody > tr').each(function() {
            CalculateQtyRowPro($(this));
        });
       
        $('.per').each(function()
        { 
            calculate_percentage_value($(this)); 
        });
         
       
        GetTotalmakingCost();
        calculateTotalCost();
   }
   
    $(document).ready(function() {
    //   $('#frmData').submit(function() {
    //       $('#Submit').prop('disabled', true);
    //   }); 
       CalFabricSewingPacking();
       setTimeout(function() {
           //calculatepercentage($("#transport_value"));
           calculate_percentage_value(0); 
           GetTotalmakingCost();
           calculateTotalCost();
       }, 500);
   });
   
   function GetTotalmakingCost()
   {
       var total_making_value = 0;
       var total_making_per = 0;
       $(".tmcv").each(function()
       {
           total_making_value += parseFloat($(this).val());
       });
        var value = total_making_value ? total_making_value : 0;
        $("#total_making_value").val(value.toFixed(2));
       
       $(".tmcp").each(function()
       {
           total_making_per += parseFloat($(this).val());
       });
       
        var per = total_making_per ? total_making_per : 0; 
        $("#total_making_per").val(per.toFixed(2));
   }
   
   function calculateTotalCost()
   {
       
         var total_cost_value = 0;
         var total_cost_per = 0;
         
         var fob_rate = $("#fob_rate").val();
         var dbk_value1 = $("#dbk_value1").val();
         var dbk_per1 = $("#dbk_per1").val();
         $(".value1").not("#dbk_value1").each(function()
         {
                total_cost_value += parseFloat($(this).val());   
         });
         
         $(".per1").not("#dbk_per1").each(function()
         {
                total_cost_per += parseFloat($(this).val());   
         });
         var tcp = (parseFloat($("#total_cost_value").val())/parseFloat(fob_rate)) * 100;
         var tcv = parseFloat(total_cost_value) + parseFloat($("#total_making_value").val()) +  parseFloat($("#garment_reject_value").val());
         var tp = tcp ? tcp : 0;
         var tv = tcv ? tcv : 0;
         $("#total_cost_per").val(tp.toFixed(2)); 
         $("#total_cost_value").val(tv.toFixed(2));
         
         var pv = (parseFloat(fob_rate) - parseFloat($("#total_cost_value").val()) + parseFloat(dbk_value1));
         var pp = (parseFloat(100) - parseFloat($("#total_cost_per").val()) + parseFloat(dbk_per1));
         var v = pv ? pv : 0;
         var p = pp ? pp : 0;
         $("#profit_value").val(v.toFixed(2)); 
         $("#profit_per").val(p.toFixed(2));
         
         
   }
   
   function CalFabricSewingPacking()
   {
       var total_fabric_cost = 0;
       var total_sewing_cost = 0;
       var total_packing_cost = 0;
       $(".FABRIC").each(function(){
           total_fabric_cost += parseFloat($(this).val());
       });
       $(".SEWING").each(function()
       {
           total_sewing_cost += parseFloat($(this).val());
       });
       $(".PACKING").each(function(){
           total_packing_cost += parseFloat($(this).val());
       }); 
       $("#fabric_value").val(total_fabric_cost.toFixed(2));
       $("#sewing_trims_value").val(total_sewing_cost.toFixed(2));
       $("#packing_trims_value").val(total_packing_cost.toFixed(2));
   }
   function calculate_percentage_value(row)
   {    
        var fob_rate = $('#fob_rate').val();
        var value = $(row).val();
        var total_value = ((value * fob_rate)/100).toFixed(2);
        $(row).parent().parent('tr').find('.value').val(total_value ? total_value : 0); 
        CalFabricSewingPacking();
        setTimeout(function() 
        {
           calculateGarmentRejectionValue();
        }, 500);
        GetTotalmakingCost();
        calculateTotalCost();
   }
   
      
   function calculatepercentage(row)
   {  
     var fob_rate = $('#fob_rate').val(); 
     var per = $(row).val(); 
     var total_per = ((per/fob_rate) * 100).toFixed(2); 
     $(row).parent().parent('tr').find('.per').val(total_per ? total_per : 0);  
     
     CalFabricSewingPacking();
     setTimeout(function() 
     {
        calculateGarmentRejectionValue();
     }, 500);
     GetTotalmakingCost();
     calculateTotalCost();
   }
   
        
   function calculateGarmentRejectionValue()
   {   
     var value = $("#garment_reject_per").val(); 
     var total_making_value = $('#total_making_value').val();
     
     $("#garment_reject_value").val(((total_making_value * value)/100).toFixed(2) ? ((total_making_value * value)/100).toFixed(2) : 0); 
     calculateTotalCost();
   }
   
   
   function SalesOrderDisable(type)
   {
         if(type==1)
         {
              document.getElementById('sales_order_no').disabled=false;
         }
         else
         {
               document.getElementById('sales_order_no').disabled=true;
         }
   }
 
   
   
   function EnableFields()
   { 
     $("input").removeAttr('disabled');
   }
   
   
   
   
   $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
      
      mycalc();
   
   });
   
   
   
   $('table.footable_2').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"]', function()
   { 
        CalculateQtyRowPro($(this).closest("tr"));
   
        $('.per').each(function()
        { 
            calculateGarmentRejectionValue($(this)); 
        });
   });
   function CalculateQtyRowPro(row)
   {   
       var consumption=+row.find('input[name^="consumption[]"]').val();
       var wastage=+row.find('input[name^="wastage[]"]').val();
       var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
       var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
       var total_price=(bom_qty*rate_per_unit).toFixed(4);
       row.find('input[name^="total_amount[]"]').val(total_price ? total_price : 0);
       mycalc();
       GetTotalmakingCost(); 
       calculateTotalCost();
   }
   
   $('table.footable_3').on('keyup', 'input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"]', function()
   {
       CalculateQtyRowPros($(this).closest("tr"));
       
        $('.per').each(function()
        { 
            calculateGarmentRejectionValue($(this)); 
        });
   });
   function CalculateQtyRowPros(row)
   {   
       var consumption=+row.find('input[name^="consumptions[]"]').val();
       var wastage=+row.find('input[name^="wastages[]"]').val();
       var rate_per_unit=+row.find('input[name^="rate_per_units[]"]').val();
       var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
       var total_price=(bom_qty*rate_per_unit).toFixed(4);
       
       row.find('input[name^="total_amounts[]"]').val(total_price ? total_price : 0);
       mycalc();
       GetTotalmakingCost(); 
       calculateTotalCost();
   }
   
   
   
    
   $('table.footable_4').on("keyup", 'input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"]', function()
   { 
        CalculateQtyRowPross($(this).closest("tr"));
   
        $('.per').each(function()
        { 
            calculateGarmentRejectionValue($(this)); 
        });
   });
   function CalculateQtyRowPross(row)
   {   
       var consumption=+row.find('input[name^="consumptionss[]"]').val();
       var wastage=+row.find('input[name^="wastagess[]"]').val();
       var rate_per_unit=+row.find('input[name^="rate_per_unitss[]"]').val();
       var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
       var total_price=(bom_qty*rate_per_unit).toFixed(4);
       row.find('input[name^="total_amountss[]"]').val(total_price ? total_price : 0);
       mycalc();
       GetTotalmakingCost(); 
       calculateTotalCost();
   }
   
   function mycalc()
   {   
   
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('FABRIC');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
           var a = +amounts[i].value;
           sum1 += parseFloat(a);
       }
       document.getElementById("fabric_value").value = sum1.toFixed(2);
       
       sum2 = 0.0;
       var amounts = document.getElementsByClassName('SEWING');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum2 += parseFloat(a);
       }
       document.getElementById("sewing_trims_value").value = sum2.toFixed(2);
       
       sum3 = 0.0;
       var amounts = document.getElementsByClassName('PACKING');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum3 += parseFloat(a);
       }
       document.getElementById("packing_trims_value").value = sum3.toFixed(2);
       
        var fob_rate=$('#fob_rate').val();  
       
       
       var fabricpercentage= ((sum1.toFixed(2) / fob_rate) * 100).toFixed(2);
       var sewing_trimspercentage= ((sum2.toFixed(2) / fob_rate) * 100).toFixed(2);
       var packing_trimspercentage= ((sum3.toFixed(2) / fob_rate) * 100).toFixed(2);
       
       $('#fabric_per').val(fabricpercentage ? fabricpercentage : 0);
       $('#sewing_trims_per').val(sewing_trimspercentage ? sewing_trimspercentage : 0);
       $('#packing_trims_per').val(packing_trimspercentage ? packing_trimspercentage : 0);
    
   
   }
   
   
   function calculateamount()
   { 
      var prod_qty=document.getElementById('prod_qty').value;
      var rate_per_piece=document.getElementById('rate_per_piece').value;
      
      
      var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
      $('#total_amount').val(total_amount.toFixed(2));
   }
    
   
   function deleteRowcone1(btn) 
   { 
       var tbl_count = $('#footable_2 > tbody > tr').length;
       if(tbl_count > 1)
       {
           var row = btn.parentNode.parentNode;
           row.parentNode.removeChild(row); 
           recalcIdcone1(); 
           
           CalFabricSewingPacking();
           setTimeout(function() {
               calculate_percentage_value(0);
               calculatepercentage(0);
               GetTotalmakingCost();
               calculateTotalCost();
           }, 500); 
           
           recalculateTotals_footable_2()
       } 
   }
    
   function deleteRowcone2(btn)
   { 
       var tbl_count = $('#footable_3 > tbody > tr').length;
       if(tbl_count > 1)
       {
           var row = btn.parentNode.parentNode;
           row.parentNode.removeChild(row);
           recalcIdcone2();
           CalFabricSewingPacking();
           setTimeout(function() {
               calculate_percentage_value(0);
               calculatepercentage(0);
               GetTotalmakingCost();
               calculateTotalCost();
           }, 500); 
       }
       recalculateTotals_footable_3();
       
   }
   
   function deleteRowcone3(btn) 
   {
       var tbl_count = $('#footable_4 > tbody > tr').length;
       if(tbl_count > 1)
       {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            recalcIdcone3();
            // document.getElementById('Submit').disabled=true; 
            CalFabricSewingPacking();
            setTimeout(function() {
               calculate_percentage_value(0);
               calculatepercentage(0);
               GetTotalmakingCost();
               calculateTotalCost();
            }, 500);
       }
   }
   
   function deleteRowcone4(btn) 
   {
       var tbl_count = $('#footable_5 > tbody > tr').length;
       if(tbl_count > 1)
       {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            
             recalcIdcone4();
       }
       
       recalculateTotals_footable_5();
   }
      function deleteRowcone5(btn) 
   {
       var tbl_count = $('#footable_6 > tbody > tr').length;
       if(tbl_count > 1)
       {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            
            recalcIdcone5();
       }
       
        recalculateTotals_footable_6();
   }
   
   
         function deleteRowcone6(btn) 
   {
       var tbl_count = $('#footable_7 > tbody > tr').length;
       if(tbl_count > 1)
       {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            
            recalcIdcone6();
            recalculateTotals_footable_7();
       }
   }
   
   
   
   
   
   function recalcIdcone1()
   {
       $.each($("#footable_2 tr"),function (i,el)
       {
            $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   
   function recalcIdcone2()
   {
       $.each($("#footable_3 tr"),function (i,el)
       {
            $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   
   function recalcIdcone3()
   {
       $.each($("#footable_4 tr"),function (i,el)
       {
          $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   
      function recalcIdcone4()
   {
       $.each($("#footable_5 tr"),function (i,el)
       {
          $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
         function recalcIdcone5()
   {
       $.each($("#footable_6 tr"),function (i,el)
       {
          $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   
            function recalcIdcone6()
   {
       $.each($("#footable_7 tr"),function (i,el)
       {
          $(this).find("td:first input").not('.btn-danger').val(i);
       })
   }
   

    
    
       function selselect()
   {
       setTimeout(
    function() 
    {
   
    $("#footable_2 tr td  select[name='Ac_code[]']").each(function() {
   
       $(this).closest("tr").find('select[name="Ac_code[]"]').select2();
   
      });
   }, 2000);
   }
   
   
   
   $('#Submit').on("click", function() {

     if($('#monthId').val()!=0 && $('#year').val()!=0)
    {
        $("#Submit").text('Please wait...');
        $('#Submit').prop('disabled', true); 
        $('#cancel').prop('disabled', true);  
        
        $("#frmData").submit();
    } else{
        
        alert('Please Select Neccessary Fields')
    }
   
});
   
   
   
     function calculateFOB(row,rsCr)
   { 
       
    var lpc_sale= $(row).closest('tr').find('input[name^="lpc_sale[]"]').val();
    
    var fob=((parseFloat(rsCr) / parseFloat(lpc_sale)) * (100));
    
    $(row).closest('tr').find('input[name^="fob_sale[]"]').val(Math.round(fob));

    
    
       
   
   }
   
       function calculateFOBProduction(row,rsCr)
   { 
       
    var lpc_production= $(row).closest('tr').find('input[name^="lpc_production[]"]').val();
    
    var fob=((parseFloat(rsCr) / parseFloat(lpc_production)) * (100));
    
      $(row).closest('tr').find('input[name^="fob_production[]"]').val(Math.round(fob)); 
    
    
       
   
   }
   
   
     function calculateRatePurchaseFabric(row,rsCr)
   { 
       
    var l_mtr_purchase_fabric= $(row).closest('tr').find('input[name^="l_mtr_purchase_fabric[]"]').val();
    
    var Rate=((parseFloat(rsCr) / parseFloat(l_mtr_purchase_fabric)) * (100));
    
      $(row).closest('tr').find('input[name^="rate_purchase_fabric[]"]').val(Rate.toFixed(2)); 
    
    
       
   
   }
   
   
        function calculateRatePurchaseJobWork(row,rsCr)
   { 
       
    var l_pc_purchase_job_work= $(row).closest('tr').find('input[name^="l_pc_purchase_job_work[]"]').val();
    
    var Rate=((parseFloat(rsCr) / parseFloat(l_pc_purchase_job_work)) * (100));
    
      $(row).closest('tr').find('input[name^="rate_purchase_job_work[]"]').val(Rate.toFixed(2)); 
    
    
       
   
   }
   
   
   
   
   
   
   function previousData1(obj, ele) {
    let cnt = 0;
    $("select[name='Ac_code_sale[]'] > option:selected").each(function() {
        if (ele === $(this).val()) {
            cnt++;
        }
    });
   // console.log("Duplicate count for value '" + ele + "':", cnt);

    if (cnt > 1) {
        
          
        $(obj).val('');

        
        alert("Already selected, you should choose again");
        
          
         
           
    }
}

function previousData2(obj, ele) {
    let cnt = 0;
    $("select[name='Ac_code_production[]'] > option:selected").each(function() {
        if (ele === $(this).val()) {
            cnt++;
        }
    });
   // console.log("Duplicate count for value '" + ele + "':", cnt);

    if (cnt > 1) {
        
          
        $(obj).val('');

        
        alert("Already selected, you should choose again");
        
          
         
           
    }
}
function previousData3(obj, ele) {
    let cnt = 0;
    $("select[name='Ac_code_purchase_fabric[]'] > option:selected").each(function() {
        if (ele === $(this).val()) {
            cnt++;
        }
    });
   // console.log("Duplicate count for value '" + ele + "':", cnt);

    if (cnt > 1) {
        
          
        $(obj).val('');

        
        alert("Already selected, you should choose again");
        
          
         
           
    }
}

function previousData4(obj, ele) {
    let cnt = 0;
    $("select[name='Ac_code_purchase_trims[]'] > option:selected").each(function() {
        if (ele === $(this).val()) {
            cnt++;
        }
    });
   // console.log("Duplicate count for value '" + ele + "':", cnt);

    if (cnt > 1) {
        
          
        $(obj).val('');

        
        alert("Already selected, you should choose again");
        
          
         
           
    }
}

function previousData5(obj, ele) {
    let cnt = 0;
    $("select[name='Ac_code_purchase_job_work[]'] > option:selected").each(function() {
        if (ele === $(this).val()) {
            cnt++;
        }
    });
   // console.log("Duplicate count for value '" + ele + "':", cnt);

    if (cnt > 1) {
        
          
        $(obj).val('');

        
        alert("Already selected, you should choose again");
        
          
         
           
    }
}

function previousData6(obj, ele) {
    let cnt = 0;
    $("select[name='Ac_code_collection[]'] > option:selected").each(function() {
        if (ele === $(this).val()) {
            cnt++;
        }
    });
   // console.log("Duplicate count for value '" + ele + "':", cnt);

    if (cnt > 1) {
        
          
        $(obj).val('');

        
        alert("Already selected, you should choose again");
        
          
         
           
    }
}
   
</script>
<!-- end row -->
@endsection