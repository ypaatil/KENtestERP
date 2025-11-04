@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>

</style>
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="https://www.datatables.net/rss.xml">
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.jqueryui.min.css">
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
<div class="row">
      <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Sales Target</h4>
                    
                    <div id="column_chart" class="apex-charts" dir="ltr"></div>                                      
                </div>
            </div><!--end card-->
        </div>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}">
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>


<script>

    $.ajax({
        dataType: "json",
        url: "{{ route('GraphicalSaleDashboard') }}",
        success: function(res)
        {
             var target = res.target.replace(/[\[\]']+/g,'');
             var amount = res.amount.replace(/[\[\]']+/g,'');
             
             console.log(target); 
             options = {
            	chart: {
            		height: 350,
            		type: "bar",
            		toolbar: {
            			show: !1
            		}
            	},
            	plotOptions: {
            		bar: {
            			horizontal: !1,
            			columnWidth: "45%",
            			endingShape: "rounded"
            		}
            	},
            	dataLabels: {
            		enabled: !1
            	},
            	stroke: {
            		show: !0,
            		width: 2,
            		colors: ["transparent"]
            	},
            	series: [{
            		name: "Target",
            		data: [target]
            	}, {
            		name: "Amount",
            		data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
            	}, {
            		name: "Remain",
            		data: [20, 42, 38, 26, 47, 50, 54, 55, 43]
            	}],
            	colors: ["#556ee6", "#34c38f", "#f46a6a"],
            	xaxis: {
            		categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"]
            	},
            	yaxis: {
            		title: {
            			text: "Percentage %",
            			style: {
            				fontWeight: "500"
            			},
            		}
            	},
            	grid: {
            		borderColor: "#f1f1f1"
            	},
            	fill: {
            		opacity: 1
            	},
            	
            };
            (chart = new ApexCharts(document.querySelector("#column_chart"), options)).render();
        }
    });
</script>
@endsection
@section('script')

@endsection