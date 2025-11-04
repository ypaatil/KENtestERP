<!DOCTYPE html>
<html>
<head>
    <style>
        .label {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .barcode {
            margin: 20px 0;
        }
        .details {
            margin: 20px 0;
        }
    </style>
    <meta charset="UTF-8">
</head>
<body>
    <div class="label">
        <div class="barcode">
            <!-- Embed the base64-encoded PDF as an image -->
            <embed src="data:application/pdf;base64,{{$generateBarcode}}" type="application/pdf" width="100%" height="1000px" />
        </div>
    </div>
</body>
</html>
