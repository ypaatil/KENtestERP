<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Prevents scrolling if the content overflows */
        }
        .label {
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            margin: 0;
        }
        .barcode {
            width: 100%;
            height: auto;
            max-height: 100%; /* Prevents overflow */
        }
        embed {
            width: 100%;
            height: 100%; /* Fits the container's height */
            border: none; /* Remove default border */
        }
    </style>
    <meta charset="UTF-8">
</head>
<body>
    <div class="label">
        <div class="barcode">
            <!-- Embed the base64-encoded PDF as an image -->
            <embed src="data:application/pdf;base64,{{$generateBarcode}}" type="application/pdf" />
        </div>
    </div>
</body>
</html>
