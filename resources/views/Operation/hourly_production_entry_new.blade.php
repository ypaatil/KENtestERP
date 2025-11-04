@extends('layouts.operationapp')
@section('styles')

<!-- INTERNAL Fancy File Upload css -->
		<link href="{{URL::asset('operation/assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />

		<!-- INTERNAL Time picker css -->
		<link href="{{URL::asset('operation/assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

		<!-- INTERNAL Date Picker css -->
		<link href="{{URL::asset('operation/assets/plugins/date-picker/date-picker.css')}}" rel="stylesheet" />

		<!-- INTERNAL File Uploads css-->
        <link href="{{URL::asset('operation/assets/plugins/fileupload/css/fileupload.css')}}" rel="stylesheet" type="text/css" />

		<!-- INTERNAL Mutipleselect css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/multipleselect/multiple-select.css')}}">

		<!-- INTERNAL Sumoselect css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/sumoselect/sumoselect.css')}}">

		<!--INTERNAL IntlTelInput css-->

		<!-- INTERNAL Jquerytransfer css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/jQuerytransfer/jquery.transfer.css')}}">
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/jQuerytransfer/icon_font/icon_font.css')}}">

		<!-- INTERNAL multi css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/multi/multi.min.css')}}">

		<!-- INTERNAL Bootstrap DatePicker css-->
		<link rel="stylesheet" href="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">
		 <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
		
		<style>
		    
		   .modal-confirm {		
	color: #636363;
	width: 325px;
	font-size: 14px;
}
.modal-confirm .modal-content {
	padding: 20px;
	border-radius: 5px;
	border: none;
}
.modal-confirm .modal-header {
	border-bottom: none;   
	position: relative;
}
.modal-confirm h4 {
	text-align: center;
	font-size: 26px;
	margin: 30px 0 -15px;
}
.modal-confirm .form-control, .modal-confirm .btn {
	min-height: 40px;
	border-radius: 3px; 
}
.modal-confirm .close {
	position: absolute;
	top: -5px;
	right: -5px;
}	
.modal-confirm .modal-footer {
	border: none;
	text-align: center;
	border-radius: 5px;
	font-size: 13px;
}	
.modal-confirm .icon-box {
	color: #fff;		
	position: absolute;
	margin: 0 auto;
	left: 0;
	right: 0;
	top: -70px;
	width: 95px;
	height: 95px;
	border-radius: 50%;
	z-index: 9;
	background: #FF0000;
	padding: 15px;
	text-align: center;
	box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
}
.modal-confirm .icon-box i {
	font-size: 58px;
	position: relative;
	top: 3px;
}
.modal-confirm.modal-dialog {
	margin-top: 80px;
}
.modal-confirm .btn {
	color: #fff;
	border-radius: 4px;
	background: #FF0000;
	text-decoration: none;
	transition: all 0.4s;
	line-height: normal;
	border: none;
}
.modal-confirm .btn:hover, .modal-confirm .btn:focus {
	background: #FF0000;
	outline: none;
}
.trigger-btn {
	display: inline-block;
	margin: 100px auto;
}  

      /*.table-responsive {*/
      /*      overflow-y: scroll;*/
      /*      overflow-x: scroll;*/
      /*      height: fit-content;*/
      /*      max-height: 70.4vh;*/
      /*      margin-top: 22px;*/
      /*      margin: 15px;*/
      /*      padding-bottom: 20px;*/
      /*  }*/
       

      /*  .card-body{*/
            
      /*  position: sticky;*/
      /*      top: 0;*/
      /*      left: 0;*/
      /*      z-index: 2;*/
              
            
      /*  }*/
      
.card{
 max-height: 80vh;  /* Adjust based on your needs */
}

/* Remove sticky behavior and make the form section scrollable */
.card-body .row:first-child {
 position: sticky;
top: 0;
left: 0;
       
}

/* Make the table body scrollable with sticky headers */
.table-responsive {
  max-height: 70vh;  /* Adjust based on your needs */
 
}

  .SelectDrop
{
    
    width:300px;
}

  table th {
            position: sticky;
            top: 0;
            left: 0;
            background-color: #FFF;
            color: rgb(241, 245, 179);
            text-align: center;
            font-weight: normal;
           font-size: 14px; /* Default font size */
            outline: 0.7px solid black;
            border: 1.5px solid black;
            z-index: 2;
             border:1px solid black;
        }

        table th:first-child {
            z-index: 3;
        }

        table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #FFF;
            color: rgb(241, 245, 179);
        }
        
                 td:nth-of-type(2){
        position: -webkit-sticky;
        position: sticky;
        left: 60px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                   td:nth-of-type(3){
        position: -webkit-sticky;
        position: sticky;
        left: 375px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                        td:nth-of-type(4){
        position: -webkit-sticky;
        position: sticky;
        left: 690px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
        
        
        
        
      thead tr th:nth-of-type(2){
        z-index: 5;
        left:60px;
        } 
        
         thead tr th:nth-of-type(3){
        z-index: 5;
        left:375px;
        }   
             thead tr th:nth-of-type(4){
        z-index: 5;
        left:690px;
        }   
        
        
                .app-content {
    margin-top: 0px;
    overflow: hidden;
    transition: margin-left .2s ease;
}
        
        
          #status {
            padding: 10px;
            color: white;
            display: none;
            font-size: 16px;
        }
        .online {
            background-color: green;
        }
        .offline {
            background-color: red;
        }    
        
        
        /*  Mobile Responsive CSS Start    */
        
          @media (max-width: 768px) {
    .table-responsive table,
    .table-responsive thead,
    .table-responsive tbody,
    .table-responsive th,
    .table-responsive td,
    .table-responsive tr {
        display: block;
        width: 100%;
    }

    .table-responsive thead {
        display: none;
    }

    .table-responsive tr {
        margin-bottom: 6px;
        border: 1px solid #ccc;
        padding: 6px;
        border-radius: 4px;
        background: #f9f9f9;
    }

    .table-responsive td {
        position: relative;
        padding: 4px 0 4px 32%;
        min-height: 36px;
    }

                  td:nth-of-type(2){
        position: -webkit-sticky;
        position: sticky;
        left: 60px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                   td:nth-of-type(3){
        position: -webkit-sticky;
        position: sticky;
        left: 10px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                               td:nth-of-type(4){
        position: -webkit-sticky;
        position: sticky;
        left:0;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }


    .table-responsive td::before {
        position: absolute;
        top: 4px;
        left: 8px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: 600;
        font-size: 13px;
        color: #444;
    }

    .table-responsive input,
    .table-responsive select {
        font-size: 13px;
        padding: 4px 6px;
        height: 30px;
    }

    .table-responsive .btn {
        padding: 2px 8px;
        font-size: 12px;
        height: 30px;
    }
    
  .SelectDrop
{
    
    width:270px;
}

    .table-responsive td:nth-of-type(1)::before { content: "Sr No"; }
    .table-responsive td:nth-of-type(2)::before { content: "Operator"; }
    .table-responsive td:nth-of-type(3)::before { content: "Operation"; }
     .table-responsive td:nth-of-type(4)::before { content: "Operation Type"; }
    .table-responsive td:nth-of-type(5)::before { content: "9-10"; }
    .table-responsive td:nth-of-type(6)::before { content: "10-11"; }
    .table-responsive td:nth-of-type(7)::before { content: "11-12"; }
     .table-responsive td:nth-of-type(8)::before { content: "12-1"; }
     .table-responsive td:nth-of-type(9)::before { content: "1.30-2.30"; }  
   .table-responsive td:nth-of-type(10)::before { content: "2.30-3.30"; }  
   .table-responsive td:nth-of-type(11)::before { content: "3.30-4.40"; }  
  .table-responsive td:nth-of-type(12)::before { content: "4.40-5.40"; }   
   .table-responsive td:nth-of-type(13)::before { content: "Total"; }  
  .table-responsive td:nth-of-type(14)::before { content: "Remark"; }  
   .table-responsive td:nth-of-type(15)::before { content: "Other Remark"; }
}      


  @media (max-width: 480px) {
    .table-responsive table,
    .table-responsive thead,
    .table-responsive tbody,
    .table-responsive th,
    .table-responsive td,
    .table-responsive tr {
        display: block;
        width: 100%;
    }

    .table-responsive thead {
        display: none;
    }

    .table-responsive tr {
        margin-bottom: 6px;
        border: 1px solid #ccc;
        padding: 6px;
        border-radius: 4px;
        background: #f9f9f9;
    }

    .table-responsive td {
        position: relative;
        padding: 4px 0 4px 35%;
        min-height: 36px;
    }

                  td:nth-of-type(2){
        position: -webkit-sticky;
        position: sticky;
        left: 60px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                   td:nth-of-type(3){
        position: -webkit-sticky;
        position: sticky;
        left: 375px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                         td:nth-of-type(4){
        position: -webkit-sticky;
        position: sticky;
        left:0;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }


    .table-responsive td::before {
        position: absolute;
        top: 4px;
        left: 8px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: 600;
        font-size: 13px;
        color: #444;
    }

    .table-responsive input,
    .table-responsive select {
        font-size: 13px;
        padding: 4px 6px;
        height: 30px;
    }

    .table-responsive .btn {
        padding: 2px 8px;
        font-size: 12px;
        height: 30px;
    }
    
  .SelectDrop
{
    
    width:170px;
}

    .table-responsive td:nth-of-type(1)::before { content: "Sr No"; }
    .table-responsive td:nth-of-type(2)::before { content: "Operator"; }
    .table-responsive td:nth-of-type(3)::before { content: "Operation"; }
       .table-responsive td:nth-of-type(4)::before { content: "Machine Type"; } 
    .table-responsive td:nth-of-type(5)::before { content: "9-10"; }
    .table-responsive td:nth-of-type(6)::before { content: "10-11"; }
    .table-responsive td:nth-of-type(7)::before { content: "11-12"; }
     .table-responsive td:nth-of-type(8)::before { content: "12-1"; }
     .table-responsive td:nth-of-type(9)::before { content: "1.30-2.30"; }  
   .table-responsive td:nth-of-type(10)::before { content: "2.30-3.30"; }  
   .table-responsive td:nth-of-type(11)::before { content: "3.30-4.40"; }  
  .table-responsive td:nth-of-type(12)::before { content: "4.40-5.40"; }   
   .table-responsive td:nth-of-type(13)::before { content: "Total"; }  
   .table-responsive td:nth-of-type(14)::before { content: "Operation Type"; }     
  .table-responsive td:nth-of-type(15)::before { content: "Remark"; }  
   .table-responsive td:nth-of-type(16)::before { content: "Other Remark"; }
}  
        
        
        
          @media (max-width:360px) {
    .table-responsive table,
    .table-responsive thead,
    .table-responsive tbody,
    .table-responsive th,
    .table-responsive td,
    .table-responsive tr {
        display: block;
        width: 100%;
    }

    .table-responsive thead {
        display: none;
    }

    .table-responsive tr {
        margin-bottom: 6px;
        border: 1px solid #ccc;
        padding: 6px;
        border-radius: 4px;
        background: #f9f9f9;
    }

    .table-responsive td {
        position: relative;
        padding: 4px 0 4px 35%;
        min-height: 36px;
    }

                  td:nth-of-type(2){
        position: -webkit-sticky;
        position: sticky;
        left: 60px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                   td:nth-of-type(3){
        position: -webkit-sticky;
        position: sticky;
        left: 375px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }
        
                               td:nth-of-type(4){
        position: -webkit-sticky;
        position: sticky;
        left: 0px;
        z-index: 2;
        background-color: #FFF;
         color: rgb(241, 245, 179);
        }


    .table-responsive td::before {
        position: absolute;
        top: 4px;
        left: 8px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: 600;
        font-size: 13px;
        color: #444;
    }

    .table-responsive input,
    .table-responsive select {
        font-size: 13px;
        padding: 4px 6px;
        height: 30px;
    }

    .table-responsive .btn {
        padding: 2px 8px;
        font-size: 12px;
        height: 30px;
    }
    
  .SelectDrop
{
    
    width:170px;
}

    .table-responsive td:nth-of-type(1)::before { content: "Sr No"; }
    .table-responsive td:nth-of-type(2)::before { content: "Operator"; }
    .table-responsive td:nth-of-type(3)::before { content: "Operation"; }
     .table-responsive td:nth-of-type(4)::before { content: "Machine Type"; } 
    .table-responsive td:nth-of-type(5)::before { content: "9-10"; }
    .table-responsive td:nth-of-type(6)::before { content: "10-11"; }
    .table-responsive td:nth-of-type(7)::before { content: "11-12"; }
     .table-responsive td:nth-of-type(8)::before { content: "12-1"; }
     .table-responsive td:nth-of-type(9)::before { content: "1.30-2.30"; }  
   .table-responsive td:nth-of-type(10)::before { content: "2.30-3.30"; }  
   .table-responsive td:nth-of-type(11)::before { content: "3.30-4.40"; }  
  .table-responsive td:nth-of-type(12)::before { content: "4.40-5.40"; }   
   .table-responsive td:nth-of-type(13)::before { content: "Total"; }  
     .table-responsive td:nth-of-type(14)::before { content: "Operation Type"; }   
  .table-responsive td:nth-of-type(15)::before { content: "Remark"; }  
   .table-responsive td:nth-of-type(16)::before { content: "Other Remark"; }
}  
        /*  Mobile Responsive CSS End    */
        
   .footerTotalRow {
  position: sticky;
  left: 30px; 
  bottom: 0;
  background: #fff;
  z-index: 1;
}


.input-wrapper {
    position: relative;
    display: inline-block;
}

.input-wrapper input {
    padding-right: 25px; /* space for the icon */
    width: 80px;
}

.input-wrapper .add-icon {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    color: #007bff;
    cursor: pointer;
}
		</style>
		
		

@endsection
@section('content')
<!--Page header-->
<!--End Page header-->
<!-- Row -->



<form action="@if(isset($hourlyFetch)) {{ route('hourly_production.store',array('id'=>$hourlyFetch->hourlyProductionId )) }} @else {{ route('hourly_production.store') }} @endif" method="POST" id="insertform">
   @csrf 	
   <div class="row">
      <div class="col-xl-12 col-lg-12">
         <div class="card">
            <div class="card-body">
              
               <div class="row">
                  <div class="col-md-2">
                     <div class="form-group">
                        
                        <input type="date" name="hourlyEntryDate" id="hourlyEntryDate"  preDate="{{isset($hourlyFetch->hourlyEntryDate) ? $hourlyFetch->hourlyEntryDate: date('Y-m-d')}}"  value="{{ isset($hourlyFetch->hourlyEntryDate) ? $hourlyFetch->hourlyEntryDate: date('Y-m-d')}}"    class="form-control"  required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                         <input type="hidden" name="sub_company_id"  value="{{ Session::get('vendorId')}}" class="form-control" id="sub_company_id">
                        <div id="feild4"></div>
                     </div>
                  </div>
                                <div class="col-md-4">
                    <div class="form-group">
                        <!--<label class="form-label">Style</label>-->
                      <select name="mainstyle_id"  id="mainstyle_id" @if(isset($hourlyFetch))    disabled  @endif  class="form-control" >
                          <option value="">--- Select Style---</option>  
                           @foreach($styleList as $rowStyle)
                           <option value="{{$rowStyle->mainstyle_id}}"
                           
                           
                            @if(isset($hourlyFetch))  {{  $rowStyle->mainstyle_id== $hourlyFetch->mainstyle_id ? "selected='selected'" : ""; }} @endif
                           
                           >{{$rowStyle->mainstyle_name}}</option>
                           @endforeach
                        </select>
                   </div>
                  </div>   
                  
                   <div class="col-md-3">
                    <div class="form-group">
              
                      <select class="form-control"  name="dept_id" id="dept_id"  @if(isset($hourlyFetch))    disabled  @endif   data-parsley-errors-container="#feild1"  onChange="get_hourly_production_table_by_operator(this.value);">
                           <option value="">--- Select Line---</option>  
                           @foreach($dept_list as $dept)
                            <option value="{{$dept->line_id}}"
                            
                             @if(isset($hourlyFetch)) {{  $dept->line_id== $hourlyFetch->dept_id ? "selected='selected'" : ""; }} @endif
                            
                            >{{$dept->line_name}}</option>
                           @endforeach
                        </select>   
                   </div>
                  </div>  
                  
           <div class="card-footer text-right">
               <!--<button class="btn  btn-primary" type="submit" id="SubmitLine">@if(isset($hourlyFetch)) Update @else Save @endif</button>-->
               <!--<a href="{{ route('hourly_production.index') }}" class="btn  btn-danger" id="cancelBtn">Cancel</a>-->
               <div id="status"></div>
            </div>
                  
                 </div>
                 
              		<div class="row">
					<div class="col-md-12">    
				   <input type="number" value="{{isset($hourlyFetchDetail) ? count($hourlyFetchDetail): 1}}" name="cnt" id="cnt" readonly="" hidden="true"  />
                    <div class="table-wrap">
                       <div class="table-responsive" id="tbl">
                        <table  class="table  table-vcenter text-nowrap table-bordered border-bottom footable_2" id="footable_2">
                           <thead class="sticky">
                              <tr>
                                <th class="text-center">Sr.No.</th>     
                                 <th class="text-center">Operator</th>   
                                    <th class="text-center">Operation</th>
                                   <th class="text-center">Machine Type</th>    
                                 <th class="text-center">9-10</th>    
                                <th class="text-center">10-11</th>    
                               <th class="text-center">11-12</th>    
                               <th class="text-center">12-1</th>     
                             <th class="text-center">1.30-2.30</th>    
                               <th class="text-center">2.30-3.30</th>      
                             <th class="text-center">3.30-4.40</th>   
                             <th class="text-center">4.40-5.40</th>   
                               <th class="text-center">Total</th>  
                                   <th class="text-center">Operation Type</th>      
                              <th class="text-center">Remark</th>  
                               <th class="text-center" >Other Remark</th>   
                                 <th class="text-center">Add</th>
                                <th class="text-center">Remove</th>  
                              </tr>
                           </thead>
                           <tbody id="tbodyData">

                           @php  if(!isset($hourlyFetchDetail)) { @endphp   
                              <tr class="rowcheck">
                                <td>
                                    <input type="text" step="any" min="0"  name="sr_no[]"  class="form-control" id="sr_no"  value="1" style="width:70px;">
                                 </td>      
                                  
                                 <td>
                             <select class="form-control SelectDrop"  name="employeeCode[]" style="width:400px"  id="employeeCode"  required data-parsley-errors-container="#feild1" onChange="previousData(this,0);">
                           <option value="">--- Select---</option>  
                            @foreach($employeelist as $rowemp)
                           <option value="{{ $rowemp->employeeCode }}">{{$rowemp->fullName}}({{ $rowemp->employeeCode }})</option>
                           @endforeach
                        </select>             
                          </td>
                                 
                               <td>
                            <select class="form-control CAT SelectDrop"   name="operation_id[]" id="operation_id" required onChange="get_detail(this,this.value);previousData(this,this.value);">
                            <option value="">Select The Operation</option>
                            @foreach($operationList as $operation)
                            <option value="{{$operation->operation_id}}" 
                            
                            
                            >{{$operation->operation_name}} - {{$operation->operation_id}}</option>
                            @endforeach
                            </select>   
                                 </td>
                                           <td>
                            <select class="form-control"  required style="width:200"  name="machine_type_id[]" id="machine_type_id">
                            <option value="">Select The Machine Type</option>
                            @foreach($machineTypeList as $rowMachine)
                           <option value="{{ $rowMachine->machine_type_id }}">{{$rowMachine->machine_type_name}}</option>
                           @endforeach
                            </select>   
                                 </td>     
                                 
                                   <td>
                                       
                                    <input type="text" step="any" min="0"   class="form-control  nine_ten"    name="nine_ten[]" id="nine_ten"  value="0" style="width:80px;">
                                 </td>  
                             
                                 <td>
                                    <input type="text" step="any" min="0"   class="form-control  ten_eleven"   name="ten_eleven[]" id="ten_eleven"  value="0" style="width:80px;" >
                                 </td>  
                                 
                                
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  eleven_twelve"   name="eleven_twelve[]" id="eleven_twelve"  value="0" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  twelve_one"   name="twelve_one[]" id="twelve_one"  value="0" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  oneThirty_twoThirty"   name="oneThirty_twoThirty[]" id="oneThirty_twoThirty"  value="0" style="width:90px;">
                                 </td> 
                           
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  twoThirty_threeThirty"   name="twoThirty_threeThirty[]" id="twoThirty_threeThirty"  value="0" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  threeThirty_fourefourty"   name="threeThirty_fourefourty[]" id="threeThirty_fourefourty"  value="0" style="width:90px;">
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  fourefourty_fiveFourty"   name="fourefourty_fiveFourty[]" id="fourefourty_fiveFourty"  value="0" style="width:90px;">
                                 </td> 
                                         <td>
                                    <input type="text" step="any" min="0"   class="form-control  total_output"   name="total_output[]" id="total_output"  value="0" style="width:90px;">
                                 </td> 
                                   <td>
                            <select class="form-control"   name="operation_type[]" id="operation_type" required>
                            <option value="">Operation Type</option>
                            <option value="1">Fixed</option>
                            <option value="2">Piece</option>
                            </select>   
                               </td>   
                                 
                                   <td>
                                <select name="remark[]" id="remark"  class="form-control  REMARK SelectDrop"  onChange="otherRemarkData(this)">
                                <option value="">Select</option>          
                                <option value="Feeding Problem">Feeding Problem</option>
                                <option value="Machine Problem">Machine Problem</option>
                                <option value="Half Day">Half Day</option>
                                <option value="Change Over">Change Over</option>
                                 <option value="Change Over">Input Delays</option>
                                 <option value="Other">Other</option>   
                                 </select>
                                 </td> 
                                <td>
                                <input type="text" name="other_remark[]" placeholder="Please specify..."  disabled  class="form-control SelectDrop" />
                                </td>
                                  <td>
                                    <input type="button" style="width:40px;"  onclick="AddNewRow(this);" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                  </td>
                                  <td>
                                  <input type="button" class="btn btn-danger deleteOperation" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>
                              @php } else { @endphp
  @php $nonew=1; @endphp
@foreach($hourlyFetchDetail as $row)


 <tr class="rowcheck">
     
     
                                    <td>
                                    <input type="text" step="any" min="0"   class="form-control" name="sr_no[]" id="sr_no"  value="{{ $nonew++ }}" style="width:70px;">
                                 </td>
                                 <td>
                                  <select class="form-control"  name="employeeCode[]" style="width:400px" id="employeeCode" required  data-parsley-errors-container="#feild1" onChange="previousData(this);">
                           <option value="">--- Select---</option>  
                           @foreach($employeelist as $rowemp)
                           <option value="{{ $rowemp->employeeCode }}"
                           
                           {{  $rowemp->employeeCode== $row->employeeCode ? "selected='selected'" : ""; }} 
                           
                           >{{$rowemp->fullName}}({{ $rowemp->employeeCode }})</option>
                           @endforeach
                        </select>     
                                     
                                 </td>
                        
                               <td>
                            <select class="form-control CAT"  style="width:300px"  name="operation_id[]" required id="operation_id" onChange="get_detail(this,this.value);previousData(this);">
                            <option value="">Select The Operation</option>
                            @foreach($operationList as $operation)
                            <option value="{{$operation->operation_id}}" 
                            
                             {{  $row->operationNameId ==$operation->operation_id ? "selected='selected'" : ""; }}  
                            
                            
                            >{{$operation->operation_name}} - {{$operation->operation_id}}</option>
                            @endforeach
                            </select>   
                                 </td>
                                 
                                   <td>
                            <select class="form-control"  required style="width:180"  name="machine_type_id[]" id="machine_type_id">
                            <option value="">Select The Machine Type</option>
                            @foreach($machineTypeList as $rowMachine)
                           <option value="{{ $rowMachine->machine_type_id }}"
                           
                            {{  $row->machine_type_id== $rowMachine->machine_type_id ? "selected='selected'" : ""; }}    
                           
                           >{{$rowMachine->machine_type_name}}</option>
                           @endforeach
                            </select>   
                                 </td>        
                                 
                                 

                                  <td>
                                         <div class="input-wrapper">
                                    <input type="text" step="any" min="0"   class="form-control  nine_ten"    name="nine_ten[]" id="nine_ten"  value="{{ $row->nine_ten }}" style="width:80px;">
                                      <span class="add-icon" onclick="openmodel(this,'nine_ten_down_time_min')">&#x2795;</span>
                                   </div>
                                 </td>  
                             
                                 <td>
                                     <div class="input-wrapper">
                                    <input type="text" step="any" min="0"   class="form-control  ten_eleven"   name="ten_eleven[]" id="ten_eleven"  value="{{ $row->ten_eleven }}" style="width:80px;" >
                                      <span class="add-icon" onclick="openmodel(this,'ten_eleven_down_time_min')">&#x2795;</span>
                                    </div>
                                    </td>  
                                 
                                   <td>
                                        <div class="input-wrapper">
                                    <input type="text" step="any" min="0"   class="form-control  eleven_twelve"   name="eleven_twelve[]" id="eleven_twelve"  value="{{ $row->eleven_twelve }}" style="width:90px;">
                                   <span class="add-icon" onclick="openmodel(this,'eleven_twelve_down_time_min')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                   <div class="input-wrapper">     
                                    <input type="text" step="any" min="0"   class="form-control  twelve_one"   name="twelve_one[]" id="twelve_one"  value="{{ $row->twelve_one }}" style="width:90px;">
                                   <span class="add-icon" onclick="openmodel(this,'twelve_one_dtm')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                     <div class="input-wrapper">        
                                    <input type="text" step="any" min="0"   class="form-control  oneThirty_twoThirty"   name="oneThirty_twoThirty[]" id="oneThirty_twoThirty"  value="{{ $row->oneThirty_twoThirty }}" style="width:90px;">
                                  <span class="add-icon" onclick="openmodel(this,'oneThirty_twoThirty_dtm')">&#x2795;</span>
                                    </div>
                                 </td> 
                           
                                   <td>
                                     <div class="input-wrapper">             
                                    <input type="text" step="any" min="0"   class="form-control  twoThirty_threeThirty"   name="twoThirty_threeThirty[]" id="twoThirty_threeThirty"  value="{{ $row->twoThirty_threeThirty }}" style="width:90px;">
                                 <span class="add-icon" onclick="openmodel(this,'twoThirty_threeThirty_dtm')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                     <div class="input-wrapper">        
                                    <input type="text" step="any" min="0"   class="form-control  threeThirty_fourefourty"   name="threeThirty_fourefourty[]" id="threeThirty_fourefourty"  value="{{ $row->threeThirty_fourefourty }}" style="width:90px;">
                                <span class="add-icon" onclick="openmodel(this,'threeThirty_fourefourty_dtm')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                      <div class="input-wrapper">               
                                    <input type="text" step="any" min="0"   class="form-control  fourefourty_fiveFourty"   name="fourefourty_fiveFourty[]" id="fourefourty_fiveFourty"  value="{{ $row->fourefourty_fiveFourty }}" style="width:90px;">
                                  <span class="add-icon" onclick="openmodel(this,'fourefourty_fiveFourty_dtm')">&#x2795;</span>
                                    </div>
                                 </td> 
                                   <td>
                                    <input type="text" step="any" min="0"   class="form-control  total_output"   name="total_output[]" id="total_output"  value="{{ $row->total_output }}" style="width:90px;">
                                 </td> 
                                  <td>
                            <select class="form-control"   name="operation_type[]" id="operation_type" required>
                            <option value="">Operation Type</option>
                            <option value="1"  {{  $row->operation_type ==1 ? "selected='selected'" : ""; }}  >Fixed</option>
                            <option value="2"  {{  $row->operation_type ==2 ? "selected='selected'" : ""; }}  >Piece</option>
                            </select>   
                               </td> 
                                 
                                   <td>
                                    <select name="remark[]" id="remark" class="form-control REMARK" style="width:200px;" onChange="otherRemarkData(this)">
                                    <option value="" {{ $row->remark == '' ? 'selected' : ''; }}>Select</option>       
                                    <option value="Feeding Problem" {{ $row->remark == 'Feeding Problem' ? 'selected' : ''; }}>Feeding Problem</option>
                                    <option value="Machine Problem" {{  $row->remark == 'Machine Problem' ? 'selected' : ''; }}>Machine Problem</option>
                                    <option value="Half Day" {{ $row->remark == 'Half Day' ? 'selected' : ''; }}>Half Day</option>
                                    <option value="Change Over" {{ $row->remark == 'Change Over' ? 'selected' : ''; }}>Change Over</option>
                                    <option value="Input Delays" {{ $row->remark == 'Input Delays' ? 'selected' : ''; }}>Input Delays</option>
                                    <option value="Other" {{ $row->remark == 'Other' ? 'selected' : ''; }}>Other</option>   
                                    </select>
                                 </td> 
                                 
                                 <td>
                                <input type="text" name="other_remark[]" placeholder="Please specify..."  @if(!isset($row->other_remark)) disabled @endif    value="{{ $row->other_remark }}" style="width:200px;"   class="form-control" />
                                </td>  
                                 <td>
                                    <input type="button" style="width:40px;" id="Abutton"  name="Abutton[]" value="+" class="btn btn-warning mr-2 Abutton"> 
                                 </td>
                                 <td>
                                  <input type="button" class="btn btn-danger" onclick="deleteRow(this);" value="X" >
                                 </td> 
                                 
                              </tr>



@endforeach
@php } @endphp

                           </tbody>
                           
                         
                         </table>
                         
                          <div class="col-sm-3" style="display:none">
                           <label for="formrow-inputState" class="form-label">Total:</label>
                           <div class="form-group">
                               <input type="number"  name="total_production" id="total_production" class="form-control totalAmt" value="{{isset($hourlyFetch->total_production) ? $hourlyFetch->total_production: 0}}">
                           </div>
                        </div>
                
                     </div>
                  </div>
                  
                  	  </div>
					  </div>   
         
            </div>
            
         
    
         </div>
         
            <div class="card-footer text-right">
               <button class="btn  btn-primary" type="submit" id="SubmitLine">@if(isset($hourlyFetch)) Update @else Save @endif</button>
               <a href="{{ route('hourly_production.index') }}" class="btn  btn-danger" id="cancelBtn">Cancel</a>
            </div>
      </div>
   </div>
   
</form>
<!-- End Row-->
@endsection('content')
@section('scripts')

      	<script src="{{URL::asset('operation/assets/js/select2.js')}}"></script>
	<script src="{{URL::asset('operation/assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/time-picker/toggles.min.js')}}"></script>

		<!-- INTERNAL Datepicker js -->
		<script src="{{URL::asset('operation/assets/plugins/date-picker/date-picker.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/date-picker/jquery-ui.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

		<!-- INTERNAL File-Uploads Js-->
		<script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
        <script src="{{URL::asset('operation/assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>

		<!-- INTERNAL File uploads js -->
        <script src="{{URL::asset('operation/assets/plugins/fileupload/js/dropify.js')}}"></script>
		<script src="{{URL::asset('operation/assets/js/filupload.js')}}"></script>

		<!-- INTERNAL Multiple select js -->
		<script src="{{URL::asset('operation/assets/plugins/multipleselect/multiple-select.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/multipleselect/multi-select.js')}}"></script>

		<!-- INTERNAL Sumoselect js-->
		<script src="{{URL::asset('operation/assets/plugins/sumoselect/jquery.sumoselect.js')}}"></script>

		<!-- INTERNAL intlTelInput js-->
		<script src="{{URL::asset('operation/assets/plugins/intl-tel-input-master/intlTelInput.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/intl-tel-input-master/country-select.js')}}"></script>
		<script src="{{URL::asset('operation/assets/plugins/intl-tel-input-master/utils.js')}}"></script>

		<!-- INTERNAL jquery transfer js-->
		<script src="{{URL::asset('operation/assets/plugins/jQuerytransfer/jquery.transfer.js')}}"></script>

		<!-- INTERNAL multi js-->
		<script src="{{URL::asset('operation/assets/plugins/multi/multi.min.js')}}"></script>

		<!-- INTERNAL Bootstrap-Datepicker js-->
		<script src="{{URL::asset('operation/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

		<!-- INTERNAL Form Advanced Element -->
		<script src="{{URL::asset('operation/assets/js/formelementadvnced.js')}}"></script>
		<script src="{{URL::asset('operation/assets/js/form-elements.js')}}"></script>
	
  

  <div id="errorModal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header">
				<div class="icon-box">
			<i class="material-icons">error</i>
				</div>	
			<button type="button" class="close btn"  data-dismiss="modal" aria-label="Close" style="background-color:#FFF;color:#000">  <span aria-hidden="true">&times;</span></button>
				
				<h4 class="modal-title w-100">error!</h4>	
			</div>
			<div class="modal-body">
				<p class="text-center">Already exist record.!</p>
			</div>
			<div class="modal-footer">
	     	<button class="btn btn-danger btn-block" data-dismiss="modal">cancel</button>
			</div>
		</div>
	</div>
</div>  
		
	
	<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" onclick="closeModal()">&times;</button>
         <div class="success_msg" id="partymsg"></div>
        </div>
   
        <form id="formParty">
        <div class="modal-body" id="formSpecificationData">
        <div class="form-group">
        <div class="row">
        <div class="col-md-6">
        <div class="form-group">
        <label>Down Time Minutes</label>
        <input type="number" id="down_time_min"  value="" placeholder="Enter Down Time Minutes Here...!" class="form-control">
           <input type="hidden" id="employeeCodeSubmit"  value=""  class="form-control">
           <input type="hidden" id="operation_idSubmit"  value=""  class="form-control">  
           <input type="hidden" id="hoursSubmit"  value=""  class="form-control">   
           
        </div>
        </div>
       <div class="col-md-6">
        <div class="form-group">
        <label>Down Time Reason</label>
            <select id="down_time_reason" name="down_time_reason" class="form-control">
            <option value="absent">Absent</option>
            <option value="mechanical_breakdown">Mechanical Breakdown</option>
            <option value="trims_unavailability">Trims Unavailability</option>
            <option value="changeover">Changeover</option>
            <option value="electricity">Electricity</option>
            <option value="cutting_unavailability">Cutting Unavailability</option>
            <option value="pcd_not_made">PCD not made</option>
            <option value="others">Others</option>
            </select>
        
        </div>
        </div>
        </div>
        </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <button type="button" id="submitDownTime" class="btn btn-success">Submit</button>
        </div>
      </form>
      
      </div>
      
    </div>
  </div>
	   
<script>
   
   function calAmount()
   {
       var qty = $('#qty').val() ? $('#qty').val() : 0;
       var rate = $('#rate').val() ? $('#rate').val() : 0;
       
       $('#amount').val(parseInt(qty) * parseFloat(rate));
   }
   @if(isset($CreditSalesEntry))
        GetEmployeeData();
   @endif
   function GetEmployeeData()
   {
        var employeeCode = $("#employeeCode").val();
        $.ajax({
            type:"GET",
            url:"{{ route('GetEmpDetailFromEmpCode') }}",
            dataType:"json",
            data:{'employeeCode':employeeCode},
            success:function(response)
            {
                $("#emp_name").val(response.fullName);
                $("#dept_id").html('<option value="'+response.dept_id+'">'+response.dept_name+'</option>').attr('selected','selected');
                $("#sub_company_id").html('<option value="'+response.sub_company_id+'">'+response.sub_company_name+'</option>').attr('selected','selected');
            }
        });
   }
   
   
   
   $("#no_of_station").keyup(function(){

    var no_of_station=$('#no_of_station').val();

   $.ajax({
   type:"POST",
   url:"{{ route('get_selected_operator') }}",
   //dataType:"json",
   data:{no_of_station:no_of_station,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   $("#footable_2").html(response.html);
   
   }
   });


  });
  
  function get_detail(row,operation_id){
      
      var mainstyle_id=$('#mainstyle_id').val();
      
     $.ajax({
   type:"POST",
   url:"{{ route('get_operation_detail') }}",
   //dataType:"json",
   data:{operation_id:operation_id,mainstyle_id:mainstyle_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   
   
   
    $(row).closest('tr').find('input[name^="sam[]"]').val(response.sam);
    
     closetRow($(row).closest('tr'));
   
   
   }
   });    
      
  }
  
  
    function checkExist(row,pieces){
        
        
     var dept_id =$('#dept_id').val();
     var daily_pr_date=   $('#daily_pr_date').val(); 
     var operation_id=  $(row).closest('tr').find('select[name^="operation_id[]"]').val();    
     var employeeCode= $(row).closest('tr').find('select[name^="employeeCode[]"]').val();        
        
     
      
     $.ajax({
   type:"POST",
   url:"{{ route('check_exists_production') }}",
   dataType:"json",
   data:{dept_id:dept_id,daily_pr_date:daily_pr_date,operation_id:operation_id,employeeCode:employeeCode,pieces:pieces,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response.flag);  
   
   if(response.flag==1)
   {
    $(row).closest('tr').find('input[name^="pieces[]"]').val("");
    
    alert('This record already exist.! For Group:-'+response.group_name);
   } 
   
   }
   });    
      
  }
  
  
  
  
  
    
    function get_operators(row,dept_id){
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_operators') }}",
   //dataType:"json",
   data:{dept_id:dept_id,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
     $(row).closest('tr').find('select[name^="employeeCode[]"]').html(response.html);
   
   }
   });      
        
    }
    
    

    
    
    
     
    function get_hourly_production_table_by_operator(dept_id){
        
       
        
        var hourlyEntryDate=$('#hourlyEntryDate').val();
        var mainstyle_id=$('#mainstyle_id').val();
        
        
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_hourly_production_table_by_operator_new') }}",
   //dataType:"json",
   data:{dept_id:dept_id,mainstyle_id:mainstyle_id,hourlyEntryDate:hourlyEntryDate,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response); 
   
       $("#footable_2").html(response);
       
     updateColumnTotals();
   }
   });      
        
    }
    

    
       function get_details_by_operators(row,employeeCode){
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_operator_detail') }}",
   //dataType:"json",
   data:{employeeCode:employeeCode,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
   
      var select2 = $(row).closest("tr").find('select').select2();


if (select2) {
 
$(row).closest("tr").find('select').select2('destroy');
} else {
  
}
   
    $(row).closest('tr').find('select[name^="mainstyle_id[]"]').val(response.mainstyle_id);
    //$(row).closest('tr').find('select[name^="operation_id[]"]').val(response.operation_id);
    //$(row).closest('tr').find('input[name^="sam[]"]').val(response.sam);
  
   
   }
   });      
        
    }
  
  
  
  
    function get_operation_ids(row,employeeCode){
        
          $.ajax({
   type:"POST",
   url:"{{ route('get_operation_ids_by_operator') }}",
   //dataType:"json",
   data:{employeeCode:employeeCode,"_token":"{{ csrf_token() }}"},
   success:function(response){
   console.log(response);  
 
    $(row).closest('tr').find('select[name^="operation_id[]"]').html(response.html);
   
   }
   });      
        
        
    }
  
 
  
  
      $(document).on("mouseover", 'select', function (event) {
          
            $(this).not('.noSelect2').select2('');
          
      });
      
      

   

    
    
//     $(function(){
//     $("#footable_2").on('click', '.Abutton', function() {
        
//       var select2 = $(this).closest("tr").find('select').select2();


// if (select2) {
 
// $(this).closest("tr").find('select').select2('destroy');
// } else {
  
// }
   
        
        
//       var $tableBody = $('#footable_2').find("tbody"),
//         $trLast = $tableBody.find("tr:last"),
        
//           $trNew = $trLast.clone();
        
        
//          var currentSrNo = $tableBody.find('input[name="sr_no[]"]').last().val();
//           currentSrNo = currentSrNo ? parseInt(currentSrNo) + 1 : 1;
          

          
//             $trNew.find('input[name="sr_no[]"]').val(currentSrNo);
//         	$trNew.find('input[name="nine_ten[]"]').val(0);
//         	$trNew.find('input[name="ten_eleven[]"]').val(0);
//         	$trNew.find('input[name="eleven_twelve[]"]').val(0);
//         	$trNew.find('input[name="twelve_one[]"]').val("");	
//         	$trNew.find('input[name="oneThirty_twoThirty[]"]').val(0);		
//         	$trNew.find('input[name="twoThirty_threeThirty[]"]').val(0);			
//           	$trNew.find('input[name="threeThirty_fourefourty[]"]').val(0);	
//           	$trNew.find('input[name="fourefourty_fiveFourty[]"]').val(0);		
//          	$trNew.find('input[name="total_output[]"]').val(0);			
        	
        	
		
//     $trLast.after($trNew);
//     });
    
//   mycalc();
// });


   function AddNewRow(row)
   { 
  
  
                var select2 = $(row).closest('tr').find('select').select2();
                
                
                if (select2) {
                
               $(row).closest('tr').find('select').select2('destroy');
                } else {
                
                }
   
        
        
        
        var tr = $(row).closest('tr');
        var clone = tr.clone(); 
        
        
         var currentSrNo = clone.find('input[name="sr_no[]"]').last().val();
          currentSrNo = currentSrNo ? parseInt(currentSrNo) + 1 : 1;
          
                if (clone.find('select[name="operation_id[]"]').is(':disabled')) {
                clone.find('select[name="operation_id[]"]').removeAttr('disabled');
                }
                   if (clone.find('select[name="employeeCode[]"]').is(':disabled')) {
                clone.find('select[name="employeeCode[]"]').removeAttr('disabled');
                }

       
         clone.find('select[name="employeeCode[]"]').val(tr.find('select[name="employeeCode[]"]').val());
         clone.find('select[name="operation_id[]"]').val("");
    
            clone.find('input[name="sr_no[]"]').val(currentSrNo);
        	clone.find('input[name="nine_ten[]"]').val(0);
        	clone.find('input[name="ten_eleven[]"]').val(0);
        	clone.find('input[name="eleven_twelve[]"]').val(0);
        	clone.find('input[name="twelve_one[]"]').val("");	
        	clone.find('input[name="oneThirty_twoThirty[]"]').val(0);		
        	clone.find('input[name="twoThirty_threeThirty[]"]').val(0);			
          	clone.find('input[name="threeThirty_fourefourty[]"]').val(0);	
           	clone.find('input[name="fourefourty_fiveFourty[]"]').val(0);		
         	clone.find('input[name="total_output[]"]').val(0);	
        
        
        tr.after(clone);

       mycalc();
   }




$('#SubmitLine').on("click", function() {
    // Check required fields before submission
   // if (checkRequiredFields()) {
        $("#SubmitLine").text('Please wait...');
        $('#SubmitLine').prop('disabled', true); 
        $('#cancelBtn').prop('disabled', true);  
        $('input,select').prop('disabled', false); 
        $("#insertform").submit();
    // } else {
    //     // Show lightbox popup indicating missing fields
    //     alert('select neccessary fields');
    // }
});


// function checkRequiredFields() {
//     var allFieldsValid = true;
//     // Reset border colors
//     $('#insertform .required').css('border-color', '');

//     // Check required fields in the form
//     $('#insertform .required').each(function() {
//         if ($(this).is('input,select') && $(this).val().trim() === '') {
//             allFieldsValid = false;
//             $(this).css('border-color', 'red'); // Add red border to empty input or textarea
//             return false; // Exit loop if any required field is empty
//         }
//         if ($(this).is('select') && $(this).val() === '') {
//             allFieldsValid = false;
//             $(this).css('border-color', 'red'); // Add red border to empty select box
//             return false; // Exit loop if any required field is empty
//         }
//     });
//     return allFieldsValid;
// }

    
    
    
        $(document).on("keyup", 'input[name^="nine_ten[]"],input[name^="ten_eleven[]"],input[name^="eleven_twelve[]"],input[name^="twelve_one[]"],input[name^="oneThirty_twoThirty[]"],input[name^="twoThirty_threeThirty[]"],input[name^="threeThirty_fourefourty[]"],input[name^="fourefourty_fiveFourty[]"]', function (event) {
      
        closetRow($(this).closest("tr"));
        mycalc();
      });  
      
      
      
      
      
      
      
      
      
      
      
      function closetRow(row)
      {
          
 var nine_ten = parseFloat(row.find('input[name^="nine_ten[]"]').val()) || 0;
var ten_eleven = parseFloat(row.find('input[name^="ten_eleven[]"]').val()) || 0;
var eleven_twelve = parseFloat(row.find('input[name^="eleven_twelve[]"]').val()) || 0;  
var twelve_one = parseFloat(row.find('input[name^="twelve_one[]"]').val()) || 0;    
var oneThirty_twoThirty = parseFloat(row.find('input[name^="oneThirty_twoThirty[]"]').val()) || 0;   
var twoThirty_threeThirty = parseFloat(row.find('input[name^="twoThirty_threeThirty[]"]').val()) || 0;  
var threeThirty_fourefourty = parseFloat(row.find('input[name^="threeThirty_fourefourty[]"]').val()) || 0;    
var fourefourty_fiveFourty = parseFloat(row.find('input[name^="fourefourty_fiveFourty[]"]').val()) || 0;      

var totalProduction = (nine_ten + ten_eleven + eleven_twelve + twelve_one + oneThirty_twoThirty + twoThirty_threeThirty + threeThirty_fourefourty + fourefourty_fiveFourty);

row.find('input[name^="total_output[]"]').val(totalProduction);

        
          
      }
      
      
      
      
      
    
    
function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('nine_ten');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}


sum2 = 0.0;
var amountsOD = document.getElementsByClassName('ten_eleven');
//alert("value="+amounts[0].value);
for(var i=0; i<amountsOD .length; i++)
{ 
var a1 = +amountsOD[i].value;
sum2 += parseFloat(a1);
}

sum3 = 0.0;
var amountsEFF = document.getElementsByClassName('eleven_twelve');
//alert("value="+amounts[0].value);
for(var i=0; i<amountsEFF .length; i++)
{ 
var a3 = +amountsEFF[i].value;
sum3 += parseFloat(a3);
}


sum4 = 0.0;
var amounts4= document.getElementsByClassName('twelve_one');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts4 .length; i++)
{ 
var a4 = +amounts4[i].value;
sum4 += parseFloat(a4);
}


sum5 = 0.0;
var amounts5= document.getElementsByClassName('oneThirty_twoThirty');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts5 .length; i++)
{ 
var a5 = +amounts4[i].value;
sum5 += parseFloat(a5);
}

sum6 = 0.0;
var amounts6= document.getElementsByClassName('twoThirty_threeThirty');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts6 .length; i++)
{ 
var a6 = +amounts6[i].value;
sum6 += parseFloat(a6);
}


sum7 = 0.0;
var amounts7= document.getElementsByClassName('threeThirty_fourefourty');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts7 .length; i++)
{ 
var a7 = +amounts7[i].value;
sum7 += parseFloat(a7);
}


sum8 = 0.0;
var amounts8= document.getElementsByClassName('fourefourty_fiveFourty');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts8 .length; i++)
{ 
var a8 = +amounts8[i].value;
sum8 += parseFloat(a8);
}




document.getElementById("total_production").value = (sum1 + sum2 + sum3 +sum4 +sum5 + sum6+ sum7+ sum8);


}


        function calculateEff()
      {
          
        var sam=$('#overall_sam').val();
        var overall_output=$('#overall_output').val();
        var total_present=$('#total_present').val();

        
        var totalEFF=((((parseFloat(sam) * parseFloat(overall_output)) / (total_present * 480)) * (100)));
        
        $('#overall_efficiency').val(totalEFF.toFixed(2));
        
          
      }
      
      
           function calculateOverallSam()
      {
          
  
        var total_present=$('#total_present').val();
        var sam_1=$('#sam_1').val();
        var sam_2=$('#sam_2').val();  
        var output_1=$('#output_1').val();  
        var output_2=$('#output_2').val();  
        
        
        var overallSam=(((parseFloat(sam_1) * parseFloat(output_1)) +  (parseFloat(sam_2) * parseFloat(output_2))) / (parseFloat(output_1) + parseFloat(output_2)));
        var overall_output=(parseFloat(output_1) + parseFloat(output_2));
        
        $('#overall_output').val(overall_output);
        
        var totalEFF=((((parseFloat(overallSam) * parseFloat(overall_output)) / (total_present * 480)) * (100)));
        
        
          $('#overall_sam').val(overallSam.toFixed(2));
        
         $('#overall_efficiency').val(totalEFF.toFixed(2));
        
          
      }
      
      
      
       function showheads(str)
      {
          
         
   
      if($('#is_style_change').is(':checked'))
      {
     
      $('.hideInput').show();
   
      } 
      else{
      
        $('.hideInput').hide();
   
      }
      
      }
      
      
      
        function checkExistRecord()
        {
        
        var daily_pr_date=$('#daily_pr_date').val();
        var dept_id=$('#dept_id').val();
        var mainstyle_id=$('#mainstyle_id').val();
        
        $.ajax({
        type:"POST",
        url:"{{ route('check_exist_record') }}",
        dataType:"json",
        data:{dept_id:dept_id,daily_pr_date:daily_pr_date,mainstyle_id:mainstyle_id,"_token":"{{ csrf_token() }}"},
        success:function(response){
        console.log(response);  
        
         if(response > 0)
         {
          $('#daily_pr_date').val($("#daily_pr_date").attr('preDate'));   
         $('#errorModal').modal('show');
        
         } else{
             
             
         }
        
        }
        });   
        
        }
      
      
      

      
      
      
      
      
      
      
      
      
function previousData(obj) {
    let cnt = 0;
    


    // Get the selected employee code and operation from the current row
    const selectedEmployeeCode = $(obj).closest('tr').find("select[name='employeeCode[]']").val();
    const selectedOperation = $(obj).closest('tr').find("select[name='operation_id[]']").val();  // The current operation selected in this row 

    // Loop through all the rows and check for duplicate employee code and operation combinations
    $("#footable_2 tr").each(function() {
        const employeeCode = $(this).find("select[name='employeeCode[]']").val();
        const operation = $(this).find("select[name='operation_id[]']").val();
        
      

        // Check if both the employee code and operation match the selected ones
        if (selectedEmployeeCode === employeeCode && selectedOperation === operation) {
            cnt++;
        }
    });

    // If the same combination of employee code and operation is selected more than once
    if (cnt > 1) {
        alert("This combination of employee and operation is already selected, you should choose another.");
        $(obj).val("");  // Clear the currently selected operation
        
        $(obj).closest('tr').find("select[name='employeeCode[]']").val("--Select--");
        $(obj).closest('tr').find("select[name='operation_id[]']").val("--Select--");  
        $(obj).closest('tr').find("input[name='sam[]']").val("0");    
    }
}


$(document).ready(function() {
    $('#dept_id').change(function() {
        if ($(this).val()) {
            $(this).prop('disabled', true);
        }
    });
    
        $('#mainstyle_id').change(function() {
        if ($(this).val()) {
            $(this).prop('disabled', true);
        }
    });
    
           $('#group_id').change(function() {
        if ($(this).val()) {
            $(this).prop('disabled', true);
        }
    });
});



function otherRemarkData(obj) {
    var currentVal = $(obj).closest('tr').find("select[name='remark[]']").val();
    
    if (currentVal == 'Other') {
        // Enable the input field
        $(obj).closest('tr').find("input[name='other_remark[]']").removeAttr('disabled');
    } else {
        // Disable the input field
        $(obj).closest('tr').find("input[name='other_remark[]']").attr('disabled', 'disabled');
    }
}





let debounceTimer;
function auto_save(obj) {
    
    //   clearTimeout(debounceTimer);
    // debounceTimer = setTimeout(function() {
   

    var hourlyEntryDate=$('#hourlyEntryDate').val();
    var userId=$('#userId').val();
    var sub_company_id=$('#sub_company_id').val();
    var mainstyle_id=$('#mainstyle_id').val();
    var dept_id=$('#dept_id').val();
    var total_production=$('#total_production').val();
    
    var employeeCode= $(obj).closest('tr').find("select[name='employeeCode[]']").val();
    var operation_id= $(obj).closest('tr').find("select[name='operation_id[]']").val();
    
    var nine_ten= ($(obj).closest('tr').find("input[name='nine_ten[]']").val()) ?? 0;  
    var ten_eleven= $(obj).closest('tr').find("input[name='ten_eleven[]']").val();    
    var eleven_twelve= $(obj).closest('tr').find("input[name='eleven_twelve[]']").val();    
    var twelve_one= $(obj).closest('tr').find("input[name='twelve_one[]']").val();   
    var oneThirty_twoThirty= $(obj).closest('tr').find("input[name='oneThirty_twoThirty[]']").val();    
    var twoThirty_threeThirty= $(obj).closest('tr').find("input[name='twoThirty_threeThirty[]']").val();     
    var threeThirty_fourefourty= $(obj).closest('tr').find("input[name='threeThirty_fourefourty[]']").val(); 
    var fourefourty_fiveFourty= $(obj).closest('tr').find("input[name='fourefourty_fiveFourty[]']").val();     
    var total_output= $(obj).closest('tr').find("input[name='total_output[]']").val();            
    var remark= $(obj).closest('tr').find("select[name='remark[]']").val();       
    var other_remark= $(obj).closest('tr').find("input[name='other_remark[]']").val();
    var operation_type= $(obj).closest('tr').find("select[name='operation_type[]']").val(); 
    
    
    
     if(operation_id!="")
     {
        $.ajax({
        type:"POST",
        url:"{{ route('store_update_hourly_production') }}",
        dataType:"json",
        data:{hourlyEntryDate:hourlyEntryDate,userId:userId,sub_company_id:sub_company_id,mainstyle_id:mainstyle_id,dept_id:dept_id,
        employeeCode:employeeCode,operation_id:operation_id,nine_ten:nine_ten,ten_eleven:ten_eleven,eleven_twelve:eleven_twelve,twelve_one:twelve_one,
        oneThirty_twoThirty:oneThirty_twoThirty,twoThirty_threeThirty:twoThirty_threeThirty,threeThirty_fourefourty:threeThirty_fourefourty,fourefourty_fiveFourty:fourefourty_fiveFourty,
        total_output:total_output,remark:remark,other_remark:other_remark,total_production:total_production,operation_type:operation_type,"_token":"{{ csrf_token() }}"},
        success:function(response){
        console.log(response);  
        
        
        },
        complete: function(response) {
        console.log(response);  
        
        
         if(response='ok')
         {
        $(obj).css("background-color", "#4CBB17");
        $(obj).css("color", "white");
         }
        
        
        
            $(window).on('offline', function() {
                $(obj).css("background-color", "#FFF");
            });
        
        
    }
        }); 
     } else{
         
         alert('Please select correct operation first.!');
         $(obj).closest('tr').find("input").val("");
     }
        
// }, 500);

}


   $(document).ready(function(){
     $(document).on('click','.deleteOperation',function(e) {
   
     
       var employeeCode = $(this).data("employeecode");
       var operationNameId = $(this).data("operationnameid");
      
   
   
           //pop up
           swal({
               title: "Are you sure?",
               text: "It will permanently deleted !", 
               icon: "warning",
               buttons: true,
               dangerMode: true,
           })
           .then((willDelete) => {
   
        if (willDelete) {
            
          
            
            var RowCount= $('#footable_2 tr').length;
            var row = $(this).closest('tr');
            
            row.remove();
            mycalc();
            
            
   
     $.ajax({
            url:'{{ route("delete_operator") }}',
            type: "POST",
             data: {
             "employeeCode": employeeCode,
             "operationNameId":operationNameId,
              "_token": "{{ csrf_token(); }}"
              },
            
            success: function(data){
                
                
   
            }
   });
   
   	swal({
   				title: "Success",
   				text: "Production has been deleted",
   				icon: "success",
   			});
   
             
             } else {
               
             }
   
           });
       });
   });
   
 
 
  $(document).ready(function() {
            // Function to update the connection status
            function updateConnectionStatus() {
                console.log("Checking connection...");  // Debugging: log to check when the status is updated
                if (navigator.onLine) {
                    console.log("You are online."); // Debugging: log online status
                    $('#status').text('You are online').removeClass('offline').addClass('online').show();
                } else {
                    console.log("You are offline."); // Debugging: log offline status
                    $('#status').text('You are offline').removeClass('online').addClass('offline').show();
                }
            }

            // Initial check when the page loads
            updateConnectionStatus();

            // Listen for the 'online' event
            $(window).on('online', function() {
                console.log("Connection restored (online event).");  // Debugging: log when online event is triggered
                updateConnectionStatus();
            });

            // Listen for the 'offline' event
            $(window).on('offline', function() {
                console.log("Connection lost (offline event).");  // Debugging: log when offline event is triggered
                updateConnectionStatus();
            });
        });  
        
        
        
    function updateColumnTotals() {
    // Array to hold totals for each column index [4 to 11] (8 columns)
    let totals = Array(8).fill(0);
    let grandTotal=0;
    // Go through each row
    $('#footable_2 tbody tr').each(function() {
      // For each time slot column (from 4th index to 11th in the <td>s)
      $(this).find('td').each(function(index) {
        if (index >= 4 && index <= 11) {
          const input = $(this).find('input');
          const value = parseFloat(input.val()) || 0;
          totals[index - 4] += value;
           grandTotal += value;
        }
      });
    });

    // Update footer cells with totals
    for (let i = 0; i < totals.length; i++) {
      $('#total' + (i + 1)).text(totals[i].toFixed(2));
    }
    
     $('#GrandTotal').text(grandTotal.toFixed(2));
  }

  $(document).ready(function() {
    updateColumnTotals(); // Initial call

    // When any input changes, recalculate
    $('#footable_2').on('input', 'input', function() {
      updateColumnTotals();
    });
  });
  
  
  
  
   $(document).on('click','#submitDownTime',function(e) {

  e.preventDefault();
//var data = $("#formParty").serialize();

var down_time_min=$('#down_time_min').val();
var down_time_reason=$('#down_time_reason').val();
var operation_idSubmit=$('#operation_idSubmit').val();
var employeeCodeSubmit=$('#employeeCodeSubmit').val();
var hourlyEntryDate=$('#hourlyEntryDate').val();
var mainstyle_id=$('#mainstyle_id').val();
var dept_id= $('#dept_id').val();
var  hoursSubmit= $('#hoursSubmit').val();

if(down_time_min!="" && down_time_reason!="")
{

$.ajax({
data: {down_time_min:down_time_min,down_time_reason:down_time_reason,operation_idSubmit:operation_idSubmit,employeeCodeSubmit:employeeCodeSubmit,
hourlyEntryDate:hourlyEntryDate,mainstyle_id:mainstyle_id,dept_id:dept_id,hoursSubmit:hoursSubmit,"_token":"{{ csrf_token(); }}"},
type:"POST",
url:"{{ route('update_hourly_production_down_time') }}",
dataType:"json",
success: function(data){
// alert("Data Save: " + data);

$('#partymsg').html('Down Time Added.!').css({"color": "#98d973","font-size": "130%","text-align":"center"});

$('#down_time_min').val("");      
$('#down_time_reason').val("");    


}
});

} else{
    
    alert('Please select necessary fields')
    
}


});
  
      
        
function openmodel(row,selectedhours) {
    // Get the closest <tr> element to the clicked row
    var selectedEmployeeCodess = $(row).closest('tr').find('select[name^="employeeCode[]"]').val();
    var selectedoperation_id = $(row).closest('tr').find('select[name^="operation_id[]"]').val();
    
     console.log(selectedhours);
      $('#employeeCodeSubmit').val(selectedEmployeeCodess);
      $('#operation_idSubmit').val(selectedoperation_id);
      $('#hoursSubmit').val(selectedhours);
     
    // Show the Bootstrap modal
    $('#myModal').modal('show');
}

     function closeModal()
    {
        
     $('#down_time_min').val("");      
     $('#down_time_reason').val("");   
    
     $('#operation_idSubmit').val("");
     $('#selectedEmployeeCode').val("");
     $('#hoursSubmit').val("");
        
      $('#myModal').modal('hide');     
    }  
        
</script>


@endsection