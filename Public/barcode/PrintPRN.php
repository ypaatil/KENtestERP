<?php
    session_start();
 
    include 'WebClientPrint.php';
    use Neodynamic\SDK\Web\WebClientPrint;
    use Neodynamic\SDK\Web\DefaultPrinter;
    use Neodynamic\SDK\Web\InstalledPrinter;
    use Neodynamic\SDK\Web\UserSelectedPrinter;
     $id=$_GET['id'];
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>How to directly Print PRN Commands without Preview or Printer Dialog</title>
    <style>
    
    
    
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
    background-color: #FF4500;
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

.button_niks3
{
    background-color: #DC143C;
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
        
    </style>
    <link href="../dist/css/style.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color:white;">
    
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="form-wrap">
    
                                    <!-- Store User's SessionId -->
                                    <input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />
                                   	<div  class="col-sm-4 form-group">  
                                    <h4>Use Default Printer</h4>
                                    
                                        <input class="form-control" type="checkbox" id="useDefaultPrinter" checked="checked" /> <strong></strong> 
                                   
                                      </div>
                                    <div id="loadPrinters" class="col-sm-4">
                                    
                                   <h4> Get List of Installed Printers</h4>
                                    <br />
                                    <input class="btn btn-danger" type="button" onclick="javascript:jsWebClientPrint.getPrinters();" value="Load installed printers..." />
                                                     
                                   
                                    </div>
                                    <div id="installedPrinters" style="visibility:hidden" class="col-sm-4 form-group">
                                   
                                    <label for="installedPrinterName"><h4>Select an installed Printer:</h4></label>
                                    <select class="form-control" name="installedPrinterName" id="installedPrinterName"></select>
                                    </div>
                                             
                                    
                                    
                                    	 
                                    <input type="button" class="button_niks" onclick="javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val());" value="Print Barcode..." />
                                        
                                          
                                    <script type="text/javascript">
                                        var wcppGetPrintersTimeout_ms = 10000; //10 sec
                                        var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
                                 
                                        function wcpGetPrintersOnSuccess(){
                                            // Display client installed printers
                                            if(arguments[0].length > 0){
                                                var p=arguments[0].split("|");
                                                var options = '';
                                                for (var i = 0; i < p.length; i++) {
                                                    options += '<option>' + p[i] + '</option>';
                                                }
                                                $('#installedPrinters').css('visibility','visible');
                                                $('#installedPrinterName').html(options);
                                                $('#installedPrinterName').focus();
                                                $('#loadPrinters').hide();                                                        
                                            }else{
                                                alert("No printers are installed in your system.");
                                            }
                                        }
                                 
                                        function wcpGetPrintersOnFailure() {
                                            // Do something if printers cannot be got from the client
                                            alert("No printers are installed in your system.");
                                        }
                                    </script>
                                     
                                    <!-- Add Reference to jQuery at Google CDN -->
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
                                 
                                    <?php
                                    //Get Absolute URL of this page
                                    $currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
                                    $currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
                                    if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
                                    {
                                        $currentAbsoluteURL .= ":".$_SERVER["SERVER_PORT"];
                                    } 
                                    $currentAbsoluteURL .= $_SERVER["REQUEST_URI"];
                                     
                                    //WebClientPrinController.php is at the same page level as WebClientPrint.php
                                    $webClientPrintControllerAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')).'/WebClientPrintController.php';
                                     
                                    //PrintPRNController.php is at the same page level as WebClientPrint.php
                                    $printPRNControllerAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')).'/PrintPRNController.php?id='.$id;
                                     
                                    //Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object
                                    echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $printPRNControllerAbsoluteURL, session_id());
                                    ?>
                                    
                                    
                                    
	                          </div>
	                	</div>
                	</div>
            	</div>
        	</div>
         </div>
  </div>
</div>
</body>
</html>