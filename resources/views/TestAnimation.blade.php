<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shirt Flip Animation</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .shirt-container {
            width: 300px;
            height: 400px;
            position: relative;
            perspective: 1000px;
        }

        .shirt {
            width: 100%;
            height: 100%;
            position: absolute;
            transform-style: preserve-3d;
            transition: transform 1s ease-in-out;
        }

        .shirt img {
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            position: absolute;
        }

        .shirt .front {
            transform: rotateY(0deg);
        }

        .shirt .back {
            transform: rotateY(180deg);
        }

        .shirt-container:hover .shirt {
            transform: rotateY(180deg);
        }

    </style>
</head>
<body>

<div class="shirt-container">
    <div class="shirt">
        <!-- Front of the shirt -->
        <img src="https://via.placeholder.com/300x400.png?text=Front+of+Shirt" alt="Front of Shirt" class="front">
        <!-- Back of the shirt -->
        <img src="https://via.placeholder.com/300x400.png?text=Back+of+Shirt" alt="Back of Shirt" class="back">
    </div>
</div>

</body>
</html>
