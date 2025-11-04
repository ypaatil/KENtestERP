@extends('layouts.master') 
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
 
<style>
.droppable 
{
  width: 230px;
  height: 50px;
  background: grey;
  font-size: 10px;
  font-family: Helvetical, Arial, sans-serif;
  color: black;
  line-height: 50px;
  font-weight: 600;
  vertical-align: middle;
  text-align: center;
}

.draggable 
{
  width: 180px;
  height: 50px;
  background: #f7f3d9;
}

.placeholder 
{
   background-image: linear-gradient(#ff00008a, #d9ff0566);
}

.hide
{
    dispaly:none;
}
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Daily Operators Line Wise</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Daily Operators Line Wise</li>
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
         <h4 class="card-title mb-4">Daily Operators Line Wise</h4>
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
            <div class="row">
             <form action="{{route('DailyOperatorsLineWise')}}" method="GET">
             <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
                @csrf 
               <div class="row">
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="dopDate" class="form-label">Date</label>
                      <input type="date" name="dopDate" class="form-control" id="dopDate" value="{{date('Y-m-d')}}" required>  
                  </div>
               </div>   
               <div class="col-md-3">
                     <div class="mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <select name="company_id" class="form-control" id="company_id" onchange="GetSubCompanyList(this.value);">
                             <option value="0">--Select--</option> 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sub_company_id" class="form-label">Sub Company</label>
                        <select name="sub_company_id" class="form-select select2"  id="sub_company_id">
                           <option value="">--Select--</option> 
                        </select>
                     </div>
                  </div>  
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-email-input" class="form-label">&nbsp;</label>
                     <button type="submit" class="btn btn-primary w-md">Search</button> 
                  </div>
               </div>  
               </div>  
            </form> 
            <div class="row">
                @php
                
                    $NoOfLineData = DB::SELECT("SELECT * FROM line_master WHERE hrms_company_id='".$company_id."'");
                    $loopCount = count($NoOfLineData)+1;           
                    for($i=1;$i<=$loopCount;$i++)
                    {
                @endphp
                <div class="col-md-2 text-center" style="box-shadow: 10px 5px 5px red;height: fit-content;">
                        @if($i !=$loopCount)
                        <h3>Line-{{$i}}</h3>
                        @else
                        <h3>Other</h3>
                        @endif
                    @php
                    
                        $empData = DB::SELECT("SELECT employeemaster1.firstName,employeemaster1.lastName,employeemaster1.employeeCode FROM employeemaster1  
                                   WHERE employeemaster1.maincompany_id='".$company_id."' 
                                   AND employeemaster1.sub_company_id='".$sub_company_id."' AND employee_status_id IN(1,2)");
                            
                       foreach($empData as $row)
                       {    
                            $presentArr = array();  
                            $lineData = DB::SELECT("SELECT daily_operators_line_wise.*,employeemaster1.firstName,employeemaster1.lastName FROM daily_operators_line_wise 
                                                    INNER JOIN employeemaster1 ON employeemaster1.employeeCode = daily_operators_line_wise.employeeCode
                                                    WHERE daily_operators_line_wise.company_id='".$company_id."' AND daily_operators_line_wise.sub_company_id='".$sub_company_id."'  AND daily_operators_line_wise.dopDate ='".$dopDate."' 
                                                    AND daily_operators_line_wise.line_no=".$i." AND daily_operators_line_wise.employeeCode=".$row->employeeCode." AND employeemaster1.employee_status_id IN(1,2)  GROUP BY daily_operators_line_wise.employeeCode");
                            if(count($lineData) > 0)
                            {
                                foreach($lineData as $lines)
                                { 
                                    @endphp
                                        <div class="droppable" style="width: 100%;" line_no="{{$i}}">  
                                             <div class="draggable" employeeCode="{{$lines->employeeCode}}"  style="width: 100%;">{{$lines->firstName." ".$lines->lastName}} ({{$lines->employeeCode}})</div>
                                        </div> 
                                    @php 
                                }
                            }
                            else if($i==$loopCount)
                            {
                                $checkData = DB::SELECT("SELECT employeeCode FROM daily_operators_line_wise  WHERE company_id='".$company_id."' AND sub_company_id='".$sub_company_id."' AND daily_operators_line_wise.employeeCode=".$row->employeeCode);
                                if(count($checkData) == 0)
                                {
                            @endphp
                                <div class="droppable" style="width: 100%;" line_no="{{$i}}"> 
                                    <div class="draggable" employeeCode="{{$row->employeeCode}}" style="width: 100%;">{{$row->firstName." ".$row->lastName}} ({{$row->employeeCode}})</div>
                                </div> 
                        @php
                                }
                            } 
                      }
                    @endphp
                    <div class="droppable" style="width: 100%;" line_no="{{$i}}">
                            
                    </div> 
                    <div class="droppable" style="width: 100%;" line_no="{{$i}}">
                            
                    </div> 
                    <div class="droppable" style="width: 100%;" line_no="{{$i}}">
                            
                    </div> 
                    <div class="droppable" style="width: 100%;" line_no="{{$i}}">
                            
                    </div> 
                    <div class="droppable" style="width: 100%;" line_no="{{$i}}">
                            
                    </div>   
                </div> 
                @php
                    }
                @endphp
              </div>
            </div>
         <!-- end card body -->
      </div>
      <!-- end card -->
   </div>
   <!-- end col -->
   <!-- end col -->
</div> 
<input type="hidden" id="hidden_company_id" value="{{$company_id}}">
<input type="hidden" id="hidden_sub_company_id" value="{{$sub_company_id}}">
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> 
<!-- end row -->
<script>
    
    function searchDailyOperatorList()
    {
        var dopDate = $("#dopDate").val();
        var company_id = $("#company_id").val();
        var sub_company_id = $("#sub_company_id").val();
        var line_no = $(this).attr('line_no');
        var employeecode = $(this).find('.draggable').attr('employeecode');
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('searchDailyOperatorList') }}",
          data:{'dopDate':dopDate,'company_id':company_id,'sub_company_id':sub_company_id,'line_no':line_no,'employeecode':employeecode},
          success: function(data)
          {  
               $('tbody').html(data.html);  
          }
        });
    }
     
    var dragLastPlace;
    var movedLastPlace;
    
    
    $('.draggable').draggable({
      placeholder: 'placeholder',
      zIndex: 1000,
      containment: 'table',
    //   helper: function(evt) {
    //     var that = $(this).clone().get(0);
    //     $(this).hide();
    //     return that;
    //   },
      start: function(evt, ui) {
        dragLastPlace = $(this).parent();
      },
      cursorAt: {
        top: 20,
        left: 20
      }
    });
    
    $('.droppable').droppable({
      hoverClass: 'placeholder',
      drop: function(evt, ui) {
        var draggable = ui.draggable;
        var droppable = this;
        $(droppable).text("");
        if ($(droppable).children('.draggable:visible:not(.ui-draggable-dragging)').length > 0) 
        {
          $(droppable).children('.draggable:visible:not(.ui-draggable-dragging)').detach().prependTo(dragLastPlace);
        }
       //  var dragedDiv = $(this).parent('td').find('.draggable')[0];
          //console.log($(dragedDiv).attr('employeecode'));
        $(draggable).detach().css({
          top: 0,
          left: 0
        }).prependTo($(droppable)).show();
    
        var dopDate = $("#dopDate").val();
        var company_id = $("#company_id").val();
        var sub_company_id = $("#sub_company_id").val();
        var line_no = $(this).attr('line_no');
        var employeecode = $(this).find('.draggable').attr('employeecode');
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('SetDailyOperator') }}",
          data:{'dopDate':dopDate,'company_id':company_id,'line_no':line_no,'employeecode':employeecode,'sub_company_id':sub_company_id},
          success: function(data)
          { 
               console.log("Operator Added...!");
          }
        });
        
        movedLastPlace = undefined;
      },
      over: function(evt, ui) {
        var draggable = ui.draggable;
        var droppable = this;
    
        // if (movedLastPlace) {
        //   $(dragLastPlace).children().not(draggable).detach().prependTo(movedLastPlace);
        // }
    
        // if ($(droppable).children('.draggable:visible:not(.ui-draggable-dragging)').length > 0) {
        //   $(droppable).children('.draggable:visible').detach().prependTo(dragLastPlace);
        //   movedLastPlace = $(droppable);
        // }
      }
    });

   $(function()
   {
       $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetHRMSCompanyList') }}", 
          success: function(data)
          {  
               $('#company_id').html(data.html);  
               $('#company_id').val($('#hidden_company_id').val()).trigger('change');
          }
        });
   });
   
   function GetSubCompanyList(company_id)
   {
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetHRMSSubCompanyList') }}",
          data:{'maincompany_id':company_id},
          success: function(data)
          {  
               $('#sub_company_id').html(data.html);  
               $("#sub_company_id").val($("#hidden_sub_company_id").val()).trigger('change');    
          }
        });
   }
 
</script>

@endsection