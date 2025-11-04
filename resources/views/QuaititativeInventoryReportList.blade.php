<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js?v=1"></script>

<!-- Include DataTables plugin -->
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>

<!-- Apply CSS and jQuery -->
<style>
    /* Define your CSS classes here */
    .text-right {
        text-align: right;
    }
</style>
    
  <div class="table-responsive">
        <table id="tbl" class="table-condensed table-striped nowrap w-100">
          <thead class="tablehead"> 
          <tr style="text-align:center; white-space:nowrap">
			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col first-col"></th>
			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col second-col">MONTHS</th>
			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col third-col">Units</th> 
			     @php
			        $colorCtr = 0;
			        
                    foreach($period as $key1=>$dates)
                    {  
                      $yrdata= strtotime($dates."-01");
                      $monthName = date('F', $yrdata);  
                  
                @endphp
			    <th colspan="2" style="background:{{$colorArr[0]}};border-top: 3px solid black;">{{$monthName}}</th>
			    @php  
                   }   
                @endphp
            </tr>
            <tr style="text-align:center; white-space:nowrap"> 
			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col first-col">ITEMS</th>
			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col second-col">Headers</th>
			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col third-col"></th>
			    @php
			      $colorCtr1 = 0;
                    foreach($period as $key=>$dates)
                    {  
                @endphp
			    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;" class="sticky_row">Qty.</th> 
			    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;" class="sticky_row">Value</th>
			    @php 
			      $colorCtr1++;
                   }   
                @endphp
            </tr>
            </thead>  
            <tbody id="tablebody"></tbody> 
        </table>
    </div>
	
<script>
      
    $(function()
    {
         LoadFabricQuantitiveReport();
         LoadTrimsQuantitiveReport();
         LoadWIPQuantitiveReport();
         LoadFGQuantitiveReport();
    });
    
    function LoadFabricQuantitiveReport()
    {
        var fin_year_id = $("#fin_year_id").val();
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFabricQuantitiveReport') }}",
            beforeSend: function() 
            {
                //$("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                // $("#sync").removeAttr('disabled');
                // setTimeout(function() 
                // { 
                //     $(".alert-success").addClass('hide'); 
                    
                // }, 2500);
            },
            success: function(data)
            {
                 $("#tablebody").append(data.html);
            },
            error: function (error) 
            {
            }
        });
    }
    
    
    
    function LoadTrimsQuantitiveReport()
    {
        var fin_year_id = $("#fin_year_id").val();
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadTrimsQuantitiveReport') }}",
            beforeSend: function() 
            {
                //$("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                // $("#sync").removeAttr('disabled');
                // setTimeout(function() 
                // { 
                //     $(".alert-success").addClass('hide'); 
                    
                // }, 2500);
            },
            success: function(data)
            {
                 $("#tablebody").append(data.html);
            },
            error: function (error) 
            {
            }
        });
    }
      
    
    function LoadWIPQuantitiveReport()
    {
        var fin_year_id = $("#fin_year_id").val();
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadWIPQuantitiveReport') }}",
            beforeSend: function() 
            {
                //$("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                // $("#sync").removeAttr('disabled');
                // setTimeout(function() 
                // { 
                //     $(".alert-success").addClass('hide'); 
                    
                // }, 2500);
            },
            success: function(data)
            {
                 $("#tablebody").append(data.html);
            },
            error: function (error) 
            {
            }
        });
    }
     
    function LoadFGQuantitiveReport()
    {
        var fin_year_id = $("#fin_year_id").val();
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFGQuantitiveReport') }}",
            beforeSend: function() 
            {
                //$("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                // $("#sync").removeAttr('disabled');
                // setTimeout(function() 
                // { 
                //     $(".alert-success").addClass('hide'); 
                    
                // }, 2500);
            },
            success: function(data)
            {
                 $("#tablebody").append(data.html);
            },
            error: function (error) 
            {
            }
        });
    }
</script>