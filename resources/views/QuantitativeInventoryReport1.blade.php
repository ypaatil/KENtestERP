@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp  
<!-- end page title -->
<style>
    #lblSync
    {
        background: #a8e94269;
        padding: 10px;
        font-weight: 900;
        background-position: left top;
        padding-top:95px;
        margin-bottom:60px;
        -webkit-animation-duration: 10s;animation-duration: 10s;
        -webkit-animation-fill-mode: both;animation-fill-mode: both;
    }
    .hide
    {
        display:none;
    }
    .text-right
    {
        text-align:end;
    }
    th
    {  
        font-weight: 800;font-size: 18px;
    }
    td
    {
        font-weight: 500;font-size: 16px;
    }
    
    .sticky_row
    {
          position: sticky;
    }
    table.scroll {
        width: 100%;
        border-spacing: 0;
        border: 2px solid black;
    }
 
    
    /*table.scroll thead tr {*/
        /* fallback */
    /*    width: 97%;*/
        /* minus scroll bar width */
    /*    width: -webkit-calc(100% - 16px);*/
    /*    width:    -moz-calc(100% - 16px);*/
    /*    width:         calc(100% - 16px);*/
    /*}*/
    
    /*table.scroll tr:after {*/
    /*    content: ' ';*/
    /*    display: block;*/
    /*    visibility: hidden;*/
    /*    clear: both;*/
    /*}*/
    
  /*.table-responsive {*/
  /*      min-height: .01%;*/
  /*      overflow-x: auto;*/
  /*  }*/
    
  /*  table.table-condensed.table-striped {*/
  /*      border-collapse: collapse;*/
  /*      width: 1200px;*/
  /*      overflow-x: scroll;*/
  /*      display: block;*/
  /*  }*/
  /*  .table-condensed.table-striped thead, .table-condensed.table-striped tbody {*/
  /*      display: block;*/
  /*  }*/
  /*  .table-condensed.table-striped tbody {*/
  /*      overflow-y: scroll;*/
  /*      overflow-x: hidden;*/
  /*      height: 400px;*/
  /*  }*/
  /*  .table>thead>tr>th {*/
  /*      vertical-align: bottom;*/
  /*      border-bottom: 2px solid transparent;*/
  /*  }*/
  /*  .table-condensed.table-striped td, .table-condensed.table-striped th {*/
  /*      min-width: 150px;*/
  /*      height: 25px;*/
  /*      overflow:hidden;*/
  /*      text-overflow: ellipsis;*/
  /*      max-width: 150px;*/
  /*  } */
  /*  .tablehead {*/
  /*      background-color: #5e5e60;*/
  /*      color: #fff;*/ 
  /*  }*/
  /*  .table-condensed>thead.tablehead>tr>th {*/
  /*      padding: 20px 10px 20px 20px;*/
  /*      text-transform: uppercase;*/
  /*      font-weight: 400;*/
  /*      font-size: 14px;*/
  /*  }*/
  /*  .table-condensed>tbody.tablebody>tr>td {*/
  /*      padding: 15px 10px 15px 20px;*/
  /*      text-transform: capitalize;*/
  /*      font-weight: 400;*/
  /*      font-size: 14px;*/
  /*      color: #4d4d4f;*/
  /*  }*/
  /*  .table-striped>tbody>tr:nth-of-type(even) {*/
  /*      background-color: #e4e4e5;*/
  /*      min-width: 100%;*/
  /*      display: inline-block;*/
  /*      border-bottom: 2px solid #fff;*/
  /*  }*/
  /*  .table-striped>tbody>tr:nth-of-type(odd) {*/
  /*      background-color: #f6f6f6;*/
  /*      min-width: 100%;*/
  /*      display: inline-block;*/
  /*      border-bottom: 2px solid #fff;*/
  /*  }*/

    .wrapper {
      position: relative;
      overflow: auto;
      border: 1px solid black;
      white-space: nowrap;
    }
    
    .sticky-col {
      position: -webkit-sticky;
      position: sticky;
      background-color: #4d4a45!important;
      color:#fff;
    }
    
    .first-col {
      left: 0px;
      z-index: 999;
    }
    
    .second-col {
      left: 70px;
      z-index: 999;
    }
       
    .third-col {
      left: 200px;
      z-index: 999;
    }
    
    tbody {
        overflow-y: scroll;
        overflow-x: hidden;
        height: auto;
    }
</style>
  <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body"> 
                   <form action="/QuantitativeInventoryReport1" method="GET">
                        <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="fin_year_id" class="form-label">Financial Year</label>
                                    <select name="fin_year_id" id="fin_year_id" class="form-control">
                                        @foreach($Financial_Year1 as $years)
                                            <option value="{{$years->fin_year_id}}" {{ $years->fin_year_id == $fin_year_id ? 'selected="selected"' : '' }} >{{$years->fin_year_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mt-4">
                                   <button type="submit" class="btn btn-primary w-md">Search</button>
                                </div>
                        </div>
                    </form>
                <div class="card-title tablewrapper" style="text-align: center;background: #ff8c0040;"><h1><b>QUANTITATIVE INVENTORY(₹ in lakhs)</b><h1></div>
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
            </div>
        </div>
    </div>  
</div>   
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>

<script type="text/javascript">

    $('table').on('scroll', function () 
    {
      
        $("#"+this.id+" > *").width($(this).width() + $(this).scrollLeft());
    });
    
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
//   let page = 1;

//   function tableData() 
//   {
//          var currentURL = window.location.href; 
                        
//          var totalpacking_qty = 0;
//          var totalcarton_pack_qty = 0;
//          var totaltransfer_qty = 0;
//          var totalstock = 0;
       
         
//       	 //$('#tbl').DataTable().clear().destroy();
        
//         //   var table = $('#tbl').DataTable({
//         //     ajax: currentURL,    
//         //     dom : 'Bfrtip',
//         //     processing: true,
//         //     serverSide: false,
//         //     buttons: [
//         //         { extend: 'copyHtml5', footer: true },
//         //         { extend: 'excelHtml5', footer: true },
//         //         { extend: 'csvHtml5', footer: true },
//         //         { extend: 'pdfHtml5', footer: true }
//         //     ],
//         //      "footerCallback": function (row, data, start, end, display) 
//         //      {   
                  
//         //         // var totalValue = 0;
//         //         // if(data.length > 0)
//         //         // {
//         //         //     for (var i = 0; i < data.length; i++) 
//         //         //     {
//         //         //         // totalpacking_qty += parseFloat(data[i].packing_qty);
//         //         //         // totalcarton_pack_qty += parseFloat(data[i].carton_pack_qty);
//         //         //         // totaltransfer_qty += parseFloat(data[i].transfer_qty);
//         //         //         // totalstock += parseFloat(data[i].stock);
//         //         //         totalValue += parseFloat(data[i].Value);
//         //         //     }
//         //         //     console.log(totalValue);
//         //         // }
              
//         //         //$('#head_packing_grn_qty').html(totalpacking_qty.toLocaleString('en-IN'));
//         //         // $('#head_carton_packing_qty').html(totalcarton_pack_qty.toLocaleString('en-IN'));
//         //         // $('#head_transfered_qty').html(totaltransfer_qty.toLocaleString('en-IN'));
//         //         // $('#head_fg_stock').html(totalstock.toLocaleString('en-IN'));
//         //         // $('#head_value').html(totalSum.toLocaleString('en-IN'));
//         //         // $("#totalFGStock").html('<b>Total Stock : </b><b>'+totalstock/100000+'</b>');
//         //         // $("#totalFGValue").html('<b>Total Value : </b><b>'+totalValue/100000+'</b>');
//         //       },
//         //       columns: [
//         //           {data: 'ac_name', name: 'ac_name'},
//         //           {data: 'sales_order_no', name: 'sales_order_no'},
//         //           {data: 'sam', name: 'sam'},
//         //           {data: 'job_status_name', name: 'job_status_name'},
//         //           {data: 'brand_name', name: "brand_name"},
//         //           {data: 'mainstyle_name', name: 'mainstyle_name'}, 
//         //           {data: 'color_name', name: 'color_name'},
//         //           {data: 'size_name', name: 'size_name'},
//         //           {data: 'packing_qty', name: 'packing_qty'},
//         //           {data: 'carton_pack_qty', name: 'carton_pack_qty'},
//         //           {data: 'transfer_qty', name: 'transfer_qty'},
//         //           {data: 'stock', name: 'stock'},
//         //           {data: 'fob_rate', name: 'fob_rate'},
//         //           {data: 'Value', name: 'Value'},
//         //     ]
//         // });
//         var currentDate = $("#currentDate").val();
//         // $.ajax({
//         //     dataType: "json", 
//         //     url: "{{ route('LoadFGStockReportTrial') }}", 
//         //     data:{'currentDate':currentDate},
//         //     success: function(data)
//         //     {
//         //         console.log(data); 
                    
//         //     } 
//         // });
         
            
//             $.ajax({
//                 url:  "{{ route('LoadFGStockReportTrial') }}",
//                 type: 'GET',
//                 data: { 'page': page,'currentDate':currentDate },
//                 complete: function(data){
//                      var table = $('#tbl').DataTable({  
//                             dom : 'Bfrtip',
//                             processing: true,
//                             serverSide: false,
//                             buttons: [
//                                 { extend: 'copyHtml5', footer: true },
//                                 { extend: 'excelHtml5', footer: true },
//                                 { extend: 'csvHtml5', footer: true },
//                                 { extend: 'pdfHtml5', footer: true }
//                             ],
//                      });
//                 },
//                 success: function(data) 
//                 { 
                   
//                     // if(lastRow != 'undefined')
//                     // {
//                     //     lastRow.after(data); 
//                     //     setTimeout(function() 
//                     //     { 
//                     //         page++;  
//                     //         tableData(); 
                            
//                     //     }, 2500);
                        
//                     // }
//                     // else
//                     // {
                     
//                         $('tbody').append(data.html);
                        
//                         $("#head_packing_grn_qty").html(data.total_packing);
//                         $("#head_carton_packing_qty").html(data.total_carton);
//                         $("#head_transfered_qty").html(data.total_transfer);
//                         $("#head_fg_stock").html(data.total_stock);
//                         $("#head_value").html(data.total_value);
//                         $("#totalFGStock").html('<b>Total Stock(In Lakh): '+data.total_stock+'</b>');
//                         $("#totalFGValue").html('<b>Total Value(In Lakh): '+data.total_value+'</b>');
//                         // setTimeout(function() 
//                         // { 
//                             // page++;  
                           
                            
//                         // }, 2500);
//                     // } 
//                 }
//             });
//     }
    
    
//     $( document ).ready(function() 
//     { 
//       //tableData();
       
//     });
    
//     var xhr;
//     function syncData()
//     {
//          xhr = $.ajax({
//             dataType: "json",
//             url: "{{ route('DumpFGData') }}",
//             beforeSend: function() 
//             {
//                 $("#sync").attr('disabled','disabled');
//             },
//             complete: function(data)
//             {
//                 $("#sync").removeAttr('disabled');
//                 setTimeout(function() 
//                 { 
//                     $(".alert-success").addClass('hide'); 
                    
//                 }, 2500);
//             },
//             success: function(data)
//             {
//                 tableData();
//                 $(".alert-success").removeClass('hide'); 
                    
//             },
//             error: function (error) 
//             {
//             }
//         });
//     }
   
//     function abort()
//     {
//         console.log("abort");
//         xhr.abort();
//     }
  
</script>                                        
@endsection