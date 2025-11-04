@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>


.table td, .table th {
    padding: 0.75rem;
    font-size: 26px;
    vertical-align: top;
    border-top: 1px solid #000000;
    font-weight: 600;
}



    .row1{
      padding-bottom:10px;
    }

    .hide
    {
        display:none;
    }
    .show
    {
       display:block; 
    }
    .blurCls
    {
        -webkit-filter: blur(2px);
    }

</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" >
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
               <li class="breadcrumb-item active">Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="col-md-12">
     <label>Synchronization</label><br/>
    <button style="border: navajowhite; background: none;" onclick="refreshData();" id="refreshBtn" ><img src="../images/refresh_img.png" id="refreshImg" width="70" height="80" ></button><br/>
    <span id="last_updated_time" style="margin: 10px;color:green;">Last Synchronization : 00:00</span>
    <div class="progress progress-striped active">
    <div class="progress-bar" id="progressDialog" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
        <span class="sr-only">0% Complete</span>
    </div>
    <div id="starting" style="font-weight: bolder;font-size: 14px;position: absolute;margin: -75px 0px 0px 100px;">Any New Updates in application then please click on Refresh Button</div> 
    <div id="waiting" class="hide" style="font-weight: bolder;font-size: 14px;position: absolute;margin: -75px 0px 0px 100px;">Please Wait......Process started time is <span id="counter"></span>.</div>
    
    </div>
               <table class="table" id="tbl_order_Status">
                  <thead style="background-color:#f79733; color:white; text-align:left;" >
                     <tr >
                        
                        <th >Performance Indicators </th>
                        <th style="text-align:right;">Today(Last Day)</th>
                        <th style="text-align:right;">Month To Date</th>
                        <th style="text-align:right;">Year To Date</th>
                     </tr>
                  </thead>
                  <tbody id="mainData">
                       
                    </tbody>
               </table>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js"></script>
<script>
     $( document ).ready(function() 
    {
        $('#starting').removeClass('hide');
        GetTableData();
       
    });
    
    function GetTableData()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('AllDataMDDashboard1') }}",
            beforeSend: function() {
             $('#loader1').show();
             $('table #tbl1').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader1').hide();
             $('table #tbl1').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#mainData').html(data.html);
            }
        });
    }
    function refreshData()
    {
            
        $.ajax({
            dataType: "json",
            url: "{{ route('refreshData') }}",
            beforeSend: function(e, xhr, settings) 
            {
               var percentage = 0;

                var timer = setInterval(function(){
                   percentage = percentage + 20;
                   //progress_bar_process(percentage, timer);
                }, 1000);
                  
                var counter = 0;
                var timer = 0;
                setInterval(function () {
                  var t = '';
                  if(counter < 60)
                  {
                     t = 'Seconds';
                     $('#counter').html(counter+' '+t);
                     if(timer > 0)
                     {
                          $('#counter').html(timer+' Minutes '+counter+' '+t);
                     }
                  }
                  else
                  {
                     t = 'Minutes';
                     timer++;
                     $('#counter').html(timer+' '+t);
                     counter = 0;
                  }
                  ++counter;
                }, 1000);

            },
            complete: function()
            {
                GetTableData();
                $('#starting').addClass('hide');
                var d = new Date();
                var Hours = "";
                var Minutes = "";
                var Seconds = "";
                
                if(d.getHours() < 10 )
                {
                     Hours = "0"+d.getHours();
                }
                else
                {
                      Hours = d.getHours();
                }
                if(d.getMinutes() < 10)
                {
                     Minutes = "0"+d.getHours();
                }
                else
                {
                     Minutes = d.getMinutes();
                }
                
                if(d.getSeconds() < 10)
                {
                     Seconds = "0"+d.getSeconds();
                }
                else
                {
                     Seconds = d.getSeconds();
                }
                
                var time = Hours + ":"+ Minutes + ":" + Seconds;
             
                const timeString = time;
                const timeString12hr = new Date('1970-01-01T' + timeString + 'Z').toLocaleTimeString('en-US',
                    {
                        timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'
                    }
                  );
  
               $('#last_updated_time').html("Last Synchronization : "+timeString12hr);
            },
            success: function(data)
            {
            }
        });
    }
    



    $(function() {
        window.ajax_loading = false;
        $.hasAjaxRunning = function() 
        {
            return window.ajax_loading;
        };
        $(document).ajaxStart(function() {
            $('#refreshBtn').attr('disabled','disabled');
            $('#refreshImg').addClass('blurCls');
            $('#waiting').removeClass('hide');
            $('#starting').addClass('hide');
            window.ajax_loading = true;
        });
        $(document).ajaxStop(function() 
        {
            $('#refreshBtn').removeAttr('disabled');
            $('#refreshImg').removeClass('blurCls');
            $('#waiting').addClass('hide');
            $('#starting').removeClass('hide');
            window.ajax_loading = false;
        });
    });
</script>
@endsection
@section('script')

@endsection