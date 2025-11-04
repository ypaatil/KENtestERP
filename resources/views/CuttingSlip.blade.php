<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Slip</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    /* styles.css */
    * {
    font-family: 'Times New Roman', Times, serif;
}

    /* Setting up the page size for A4 */
    @page {
        size: A4;
        margin: 7mm; /* Small margin around the page */
    }

    /* Body style to remove default margins and padding */


    /* Container holding all the boxes */
    .container {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 boxes in each row */
        grid-template-rows: repeat(4, 1fr); /* 4 rows */
        gap: 9mm; /* Gap between boxes */
        width: 100%;
        height: 100%;
    }

    /* Style for individual boxes */
    .box {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    border: 2px solid #000;
    text-align: center;
    font-size: 13px;
    color: black;
    box-sizing: border-box;
    padding: 10px;
    height: 100%;
      position: relative;
        
    }


  .box::after{
      
   
    width: 1px;
    background-color: black;
    height: 100%;


  }



    .box p {
        margin: 5px 0;
    }

    .row {
        display: flex;
        justify-content: space-between;
    }


     .small-box {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 40px;
        height: 70px;
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;  
      /*  background-color: lightgray; */
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 10px;
        color: black;
    }
    
     
  
  
   @media print {
 .box {
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
  
  
  

/* Common styles for buttons */
.btn {
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  border: none;
  outline: none;
  display: inline-block;
  border-radius: 25px;
  text-decoration: none;
}

.button_niks {
  background-color: #dc3545; /* Red background */
  color: white;
}

#printPageButton2 {
  background-color: #007bff; /* Blue background */
  color: white;
}

.btn:hover {
  transform: scale(1.05);
}

.btn:focus {
  box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
}

/* Specific hover effects for buttons */
.button_niks:hover {
  background-color: #c82333;
}

#printPageButton2:hover {
  background-color: #0056b3;
}

/* Styling for rounded buttons */
.btn-rounded {
  border-radius: 50px; /* Rounded edges */
}


h5{
    
    margin-top:0px;
}



    </style>
</head>

<body id='printSlip'>
    
   
<a class="button_niks btn btn-danger btn-rounded" href="/CuttingEntry" id="printPageButton">Back</a>
<button id="printPageButton2" class="btn btn-primary" onClick="printDiv('printSlip')">Print</button>


    
    
    <div class="container" id="container">
        <!-- Creating 20 boxes -->
        
        @php  
        
             $previousBundleNo = null; 
             $previouslotNo = 0;  
            $previousQty = 0; 
        @endphp
        @foreach($CuttingEntryDetailList as $row)
 
        
              @php  
              
                $currentBundleNo = $row->bundleNo;
                $currentlotNo=$row->lotNo;
                $currentQty = $row->cut_panel_issue_qty;
 
              
                $fetchParts=DB::table('cutting_entry_master')->select('jpart_id','sales_order_no')->where('cuttingEntryId',$row->cuttingEntryId)->first();
        
              $partsArray=explode(",",$fetchParts->jpart_id); 
              
              @endphp
              
              @foreach($partsArray as $rowParts)   
              
                 @php    $fetchPartName=DB::table('job_part_master')->select('jpart_name','jpart_description')->where('jpart_id',$rowParts)->first();
                 
                     
                     
                     if($previouslotNo != $currentlotNo) {
                    
                        $displayQty = 1;
                        $displayQty2 = $row->cut_panel_issue_qty;
                        
                        $previousQty=0;
                        
                    }  else{
                        
                    if (($currentBundleNo == $previousBundleNo)) {
                       
                        $displayQty = 1;
                        $displayQty2 = $row->cut_panel_issue_qty;
                       
                       
                    }  else {
                       
                        $displayQty = $previousQty + 1;
                        $displayQty2 = $row->cut_panel_issue_qty + $previousQty;
                    }
                    }
                    
                    
                    
                    
                    
                    
        $fetchBrand=DB::table('buyer_purchse_order_master')
        ->select('brand_master.brand_name','buyer_purchse_order_master.tr_code')
        ->join('brand_master','brand_master.brand_id','=','buyer_purchse_order_master.brand_id')
        ->where('buyer_purchse_order_master.tr_code',$fetchParts->sales_order_no)->first();
                    
                    
                 
                 @endphp
                 
                
        
        <div class="box" data-jpart-name="{{ $fetchPartName->jpart_name }}">
            
        
            
            <div>
                     <div class="small-box">
              
            </div>
            
                
      <p class="row"><span style="font-size:12px;float:left" class="LOT"></span> <span style="font-size:12px;float:right" class="LOT"><strong>{{ $fetchBrand->tr_code }} / {{ $fetchBrand->brand_name }}</strong> </span></p>           
                
                <p class="row"><span style="font-size:22px;float:left" class="LOT"><strong></strong> </span> <span style="font-size:22px;float:right" class="LOT"><strong>Lot No.</strong> {{ $row->lotNo }}</span></p>
                <p class="row"><span style="font-size:22px;float:left;" class="LOT"><strong></strong> </span><span style="font-size:22px;float:right"><strong>Size - {{ $row->size_name }}</strong></span></p><hr>
                <p class="row" style="font-size:22px"><span style="float:left"><strong>{{ $row->bundleNo }}</strong></span><span style="float:right"><strong>{{ $row->cut_panel_issue_qty }}</strong></span></p>
                <p><strong>Color -</strong> <strong>{{ $row->color_name }}</strong></p>
                <p style="font-size:22px"><strong>{{ $fetchPartName->jpart_description }}</strong></p>
                
              <hr>
                <p class="row"><span style="float:left;font-size:22px"><strong>{{ $displayQty }}</strong></span>
              
                
                <span style="font-size:22px">To</span>
                
                
                <span style="float:right;font-size:22px"><strong>{{ $displayQty2 }}</strong></span></p>
                
            </div>
                
        </div>
  
        
        @endforeach
        
         @php
                    
                    $previousBundleNo = $currentBundleNo;
                    $previouslotNo=$currentlotNo;
                    $previousQty = $row->cut_panel_issue_qty + $previousQty;
                @endphp
        
        @endforeach
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        
<script>
    $(document).ready(function() {
        // Get all the box elements
        var boxes = $('.box').toArray();

        // Sort boxes first by 'lotNo', then by 'data-jpart-name'
        boxes.sort(function(a, b) {
            var lotNoA = $(a).find('p:contains("Lot No.")').text().match(/\d+/)[0]; // Extract lotNo from text
            var lotNoB = $(b).find('p:contains("Lot No.")').text().match(/\d+/)[0]; // Extract lotNo from text
            var nameA = $(a).data('jpart-name').toLowerCase();
            var nameB = $(b).data('jpart-name').toLowerCase();

            // First sort by lotNo
            if (lotNoA < lotNoB) {
                return -1;
            } else if (lotNoA > lotNoB) {
                return 1;
            }

            // If lotNo is the same, then sort by part name
            if (nameA < nameB) {
                return -1;
            } else if (nameA > nameB) {
                return 1;
            }
            return 0;
        });

        // Append the sorted boxes back to the container
        $('#container').empty().append(boxes);
    });
    
    		function printDiv(divName){
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;

			window.print();

			document.body.innerHTML = originalContents;

		}
		
		

</script>

        
    </div>
</body>
</html>
