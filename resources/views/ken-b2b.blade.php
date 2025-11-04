<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ken - B2B</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body, html {
        height: 100%;
        font-family: 'Arial', sans-serif;
        overflow: hidden;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        background: url({{ asset('assets/images/Untitleddesign-ezgif.com-video-to-gif-converter.gif') }}) no-repeat center center/cover;
        animation: backgroundAnimation 30s infinite linear;
    }

    @keyframes backgroundAnimation {
        0% { background-position: 0 0; }
        100% { background-position: 100% 100%; }
    }

    .intro-section {
        text-align: center;
        color: white;
        padding: 30px;
        background: rgba(0, 0, 0, 0.6); 
        border-radius: 15px;
        max-width: 90%;
        box-sizing: border-box;
        transition: transform 0.5s ease-in-out;
        transform-style: preserve-3d;
    }

    .intro-section:hover {
        transform: scale(1.05);
    }

    .intro-section h1 {
        font-size: 3.5em;
        margin-bottom: 20px;
        animation: fadeIn 2s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .intro-section p {
        font-size: 1.5em;
        margin-bottom: 30px;
        animation: fadeInText 2s ease-out;
    }

    @keyframes fadeInText {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .enter-btn {
        padding: 15px 30px;
        font-size: 1.4em;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .enter-btn:hover {
        background-color: #0056b3;
    }

    .enter-btn:active {
        transform: scale(0.98);
    }

    .logo-img {
        width: 60%!important;
        height: auto;
        margin-bottom: 20px;
        animation: bounceLogo 2s infinite alternate;
    }

    @keyframes bounceLogo {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(-10px);
        }
    }

    .options-container {
        display: none;
        justify-content: center;
        gap: 30px;
        margin-top: 30px;
        transform: rotateY(180deg);
        transition: transform 1s ease-in-out;
        flex-wrap: wrap;
    }

    .option-btn {
        width: 240px;
        height: 200px;
        border-radius: 5%;
        background-color: #28a745;
        color: white;
        font-size: 1.2em;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        cursor: pointer;
        position: relative;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transform: scale(0);
        animation: showOptions 1s ease forwards;
    }

    .option-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .option-text {
        font-size: 0.6em;
        font-weight: bold;
        margin-top: 20px;
        text-align: center;
        color: white;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-family: 'Arial', sans-serif;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px 20px;
        border-radius: 10px;
        position: relative;
        z-index: 2;
        animation: fadeInText 1.5s ease-in-out;
    }

    @keyframes fadeInText {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes showOptions {
        0% { transform: scale(0); }
        100% { transform: scale(1); }
    }

    .option-btn:nth-child(1) {
        animation-delay: 0.3s;
    }

    .option-btn:nth-child(2) {
        animation-delay: 0.6s;
    }

    .intro-section.flipped .enter-btn {
        display: none;
    }

    .intro-section.flipped .options-container {
        display: flex;
        transform: rotateY(0deg);
    }

    .category-container {
        display: none;
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 250px;
        background: #333;
        overflow-y: auto;
        padding: 20px;
        transition: transform 1s ease-in-out;
    }

    .category-container.active {
        display: block;
        transform: translateX(0);
    }

    .category-btn {
        margin: 15px 0;
        padding: 15px;
        background-color: #f39c12;
        color: white;
        font-size: 1.2em;
        cursor: pointer;
        border: none;
        width: 100%;
        text-align: left;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .category-btn:hover {
        background-color: #e67e22;
    }

    .category-list {
        display: flex;
        margin-top: 10%;
        padding: 10px;
        border-radius: 5px;
        transition: transform 0.5s ease;
        position: absolute;
        margin-left: 5%;
        width:30%;
    }

    .category-list.active {
        display: flex;
        transform: scale(3);
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .option-btn {
            width: 250px;
            height: 250px;
        }
        .option-text {
            font-size: 1.2em;
        }
    }

    @media (max-width: 480px) {
        .option-btn {
            width: 200px;
            height: 200px;
        }
        .option-text {
            font-size: 1em;
        }
        
        .intro-section { 
            max-width: 20%; 
        }

    }

    .hide {
        display: none;
    }
    /* Fullscreen effect for the intro section */
    .intro-section.fullscreen {
        position: fixed;
        top: 0;
        left: 13%;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;   /* Ensure no margin */
        transform: none; /* No scaling distortion */
        border-radius: 0;
        background: rgba(0, 0, 0, 0.8); /* Optional: adjust background for fullscreen */
        overflow: hidden; /* Prevent overflow */
        box-sizing: border-box; /* Ensure padding/margin doesn't affect width/height */
    }

    . 
    
    .card {
        flex: 1 1 calc(25% - 20px); /* 4 cards in a row, adjust as needed */
        box-sizing: border-box;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .col-3
    {
        margin-left: 10%;
        box-shadow: 3px 5px 5px #fff;
    }
    .card-title,.card-text
    {
        font-size:10px!important;
    }
    .enter-btn {
        display: inline-block;
        width: 300px; /* Adjust for desired size */
        height: 300px; /* Keep width and height equal for a circle */
        border-radius: 50%; /* Makes it circular */
        background: linear-gradient(145deg, #87CEFA, #32CD32); /* Blue and green gradient */
        box-shadow: 5px 5px 15px #228B22, -5px -5px 15px #00BFFF; /* Adjusted shadow colors */
        border: none;
        color: #333;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        line-height: 40px; /* Aligns text vertically */
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        font-weight:900;
      }
    
      .enter-btn:hover {
        background: linear-gradient(145deg, #d1d1d1, #ffffff);
        box-shadow: 5px 5px 10px #a1a1a1, -5px -5px 10px #ffffff;
        transform: translateY(-3px); /* Slight lift effect */
      }
    
      .enter-btn:active {
        background: linear-gradient(145deg, #c1c1c1, #e1e1e1);
        box-shadow: inset 5px 5px 10px #b1b1b1, inset -5px -5px 10px #ffffff;
        transform: translateY(2px); /* Pushdown effect */
      }
      
       
        .slider {
            width: 100%;
            overflow: hidden;
            position: relative;
            background: #fff;
        }
        
        .slides {
            display: flex;
            transition: transform 1s ease;
        }
        
        .slide {
            width: 33.3333%;  /* Still showing 3 images at once */
            padding: 0 10px;  /* Optional: space between images */
            box-sizing: border-box;  /* Ensure padding is accounted for within the width */
        }
        
        .slide img {
            width: 100%;
            height: auto; 
            object-fit: cover; 
        }
 
        /* Large screens (desktops, above 1024px) */
        @media screen and (min-width: 1025px) {
            .intro-section { 
                    max-width: 30%; 
            }
            
            .intro-section h1 {
                font-size: 2.5em; 
            }
                
            .enter_btn_mg
            {
                width: 60%!important;
            }

        }
        /* Small screens (phones, up to 600px) */
        @media screen and (max-width: 600px) {
            
                .intro-section { 
                    max-width: 80%; 
                }
                
                .intro-section h1 {
                    font-size: 2.0em; 
                }
                
                .enter_btn_mg
                {
                    width: 80%!important;
                }
        }
        
        /* Medium screens (tablets, 601px to 1024px) */
        @media screen and (min-width: 601px) and (max-width: 1024px) {
           
            .intro-section { 
                max-width: 30%; 
            }
                
            .intro-section h1 {
                font-size: 2.5em; 
            }
                
            .enter_btn_mg
            {
                width: 60%!important;
            }
        }
        

    </style>
</head>
<body>
    <div class="container">
        <div class="intro-section">
            <h1 id="main_title">Welcome</h1>
            <img src="{{ asset('assets/images/ken_ex_logo.svg') }}" class="logo-img" alt="KEN Logo" style="width: 225px;margin-bottom: 40px;"> 
            <hr/>
            <a href="javascript:void(0);" id="enterButton"><img src="{{ asset('assets/images/Black Simple Business Youtube Banner.gif') }}" class="logo-img enter_btn_mg" alt="KEN Logo"  style="width: 100%;"></a>  
             <div class="slider">
                <div class="slides">
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/1_2b622b0d-741d-4853-bc30-f8d1876d0245.png?v=1714732058" alt="Image 1"></div>
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/17.png?v=1714732065" alt="Image 2"></div>
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/5_79b384aa-c1ae-4f26-ba5d-b97f2d99aeac.png?v=1714732065" alt="Image 3"></div>
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/Made_with_100_Organically_Grown_Material_Certified_by_GCL_License_No._GCL-300062_9.png?v=1714733645" alt="Image 4"></div>
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/Made_with_100_Organically_Grown_Material_Certified_by_GCL_License_No._GCL-300062_13.png?v=1714734358" alt="Image 5"></div>
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/Made_with_100_Organically_Grown_Material_Certified_by_GCL_License_No._GCL-300062_11.png?v=1714733773" alt="Image 6"></div>
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/Made_with_100_Organically_Grown_Material_Certified_by_GCL_License_No._GCL-300062_12.png?v=1714734090" alt="Image 5"></div>
                    <div class="slide"><img src="https://ken-b2b.com/cdn/shop/files/Made_with_100_Organically_Grown_Material_Certified_by_GCL_License_No._GCL-300062_6.png?v=1714733116" alt="Image 6"></div>
                </div>
            </div>
            <!-- Circle options hidden initially -->
            <div class="options-container">
                <a href="/ken-b2bProduct/1">
                    <div class="option-btn" id="greigeBtn">
                        <div class="option-text">Ready Greige Fabric</div>
                        <img src="https://ken-b2b.com/cdn/shop/files/rigiKIwK.webp?v=1714018589&width=200" class="img-fluid">
                    </div>
                </a>
                <a href="/ken-b2bProduct/31">
                    <div class="option-btn" id="finishedBtn">
                        <div class="option-text">Our Product <br/>Development</div>
                        <img src="https://ken-b2b.com/cdn/shop/files/Untitled_design.png?v=1736661806&width=200" class="img-fluid">
                    </div>
                </a>
            </div>
        </div> 
    </div>

    <!-- Category Container (Left Side) -->
    <div class="category-container" id="categoryContainer">
        <button class="category-btn" id="category1Btn">Category 1</button>
        <button class="category-btn" id="category2Btn">Category 2</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
    <script>
    
        $(document).ready(function() 
        {
            let currentIndex = 0;
            const slides = $('.slides');
            const totalSlides = $('.slide').length;
            const slidesToShow = 3;  // Number of images to show at once
        
            // Ensure the slides container width is correctly set based on total slides
            $('.slides').css('width', (totalSlides * 33.3333) + '%');
        
            // Function to slide the images
            function slideImages() {
                // Increase index until reaching the last slide, then reset to the first set
                if (currentIndex < totalSlides - slidesToShow) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                    // Reset to the first slide smoothly without showing a blank slide
                    slides.css('transition', 'none');
                    slides.css('transform', 'translateX(0)');
                    setTimeout(function() {
                        slides.css('transition', 'transform 1s ease'); // Restore smooth transition
                    }, 50);  // Delay for the transition to reset
                }
                slides.css('transform', 'translateX(' + (-33.3333 * currentIndex) + '%)');
            }
        
            // Set interval for auto-sliding
            setInterval(slideImages, 3000);
            
            $('#enterButton').click(function() {
                $("#main_title").addClass("hide");
                $("#enterButton").addClass("hide");
                $("#sub_title").addClass("hide");
                $(".slider").addClass("hide");
                $(".logo-img").addClass("hide");
                $("hr").addClass("hide");
                
                $('.intro-section').addClass('flipped');
                setTimeout(function() {
                    $('.options-container').fadeIn();
                }, 1000);
            });
         
        });
    </script>
</body>
</html>
