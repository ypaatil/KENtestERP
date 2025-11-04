<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 19.5%;
  padding:5px;
  height: 130px; /* Should be removed. Only for demonstration */
 margin-right:2px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
 
h3 {
  
  writing-mode: vertical-rl;
  /*text-orientation: sideways;*/
  transform:scale(-1);
  font-size:20px;
  margin:0px;
  }
  @media print {
  div {
    break-inside: avoid;
  }

#printPageButton {
    display: none;
  }
  
  #printPageButton2
 {
    display: none;
  }
}
  p{
	  margin-left:40px;
	  margin-top:-80px;
  }
  
.dot {
    height: 50px;
    width: 50px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    margin-left: 200px;
    margin-top: -28px;
}
  
.button_niks
{
background-color: #a0c715;
border: none;
color: white;
padding: 15px 32px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 16px;
margin: 4px 2px;
cursor: pointer;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
}  

.button_niks2
{
background-color: #afa715;
border: none;
color: white;
padding: 15px 32px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 16px;
margin: 4px 2px;
cursor: pointer;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
} 
  
  table, th, td {
  border: 1px solid;
}
  .barcode
  {
	margin-left:30px;
	width:120px;
  }
  
</style>
</head>
 <a class="button_niks btn  btn-danger btn-rounded" href="{{route('BundleBarcode.index')}}" id="printPageButton">Back</a>  <button id="printPageButton2" class="button_niks2"  onClick="printDiv('printMe')">Print</button> 

<body id='printMe'>
 
@if(isset($BundleList))
 
<div class="row"  >
@if(count($BundleBarcodeSerialDetailList)>0)


  @foreach($JobPartList  as $part)
@php $no=1; $pc_no=1; @endphp

@foreach($BundleBarcodeSerialDetailList as $List) 
     @php 
                 $results = DB::select( DB::raw("SELECT size_name FROM size_detail WHERE size_id ='".$List->sizes_id."'"));
                @endphp
            
                      
                        <div class="column" style="border-style: solid;  border-width: 1px; height:200px; width:270px; ">
                          <h3>LNo: {{ $List->size_serial_no }}</h3>  <span style="padding:10px;;" class="col-md-6 dot"></span>
                           <p>{{ $List->item_name }}-{{$part->jpart_name}} </br>
                           
                           {{ $List->style_no }}
                          <table style="margin-left:40px;">
                            <thead>
                                <tr>
                                  <th>BNo</th>
                                  <th>Size</th>
                                  <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
    
                                <tr>
                                  <th>{{ $List->bundle_id }}</th>
                                  <th>({{$results[0]->size_name}})</th>
                                  <th>{{ $List->layers }}</th>
                                </tr>
                                <tr>
                                  <th>{{ $pc_no}} To {{ ($pc_no + $List->layers-1) }}</th>
                                </tr>
                            </tbody>
                          </table>
                           </p>
                        </br></br>
                          </br>
                          </br>
                    <div class="col-md-6"><img alt="barcode" class="barcode" src="{{URL('../barcode.php?codetype=Code128&size=32&text=')}}{{$List->bundle_id}}.{{$List->roll_track_code}}&print=true"/> </div>
                            
                        </div>
                        
                        @php 
                          
                            $pc_no=$pc_no + $List->layers;
                            
                         
                        
                        @endphp
                         @endforeach
                         
                         @php $no=1; @endphp
@endforeach
           
@endif
 
</div>
@endif


 


</body>

<script>
		function printDiv(divName){
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;

			window.print();

			document.body.innerHTML = originalContents;

		}
	</script>


</html>