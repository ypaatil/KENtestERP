    <!doctype html>
    <html lang="en">

    <head>

    <meta charset="utf-8" />
    <title>Stitching Garment Inventory Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Stitching Garment Inventory Application" name="description" />
    <style>
     
    .hide{
        display:none;
    }
    #chatgpt-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #107da3;
      color: white;
      border: none;
      border-radius: 12px;
      padding: 10px 16px;
      font-size: 14px;
      cursor: pointer;
      text-align: center;
      box-shadow: 0 0 12px #107da3;
      animation: glowPulse 1.5s infinite ease-in-out;
      z-index: 9999;
      line-height: 1.2;
    }
    
    .chat-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
    }
    
    .chat-icon {
      display: block;
      font-size: 20px; 
    }
    
    /* Glowing animation */
    @keyframes glowPulse {
      0% { box-shadow: 0 0 6px #107da3; }
      50% { box-shadow: 0 0 16px #107da3; }
      100% { box-shadow: 0 0 6px #107da3; }
    }
    

    /* Popup Overlay */
    #chatgpt-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 9998;
    }

    /* Popup Modal */
    #chatgpt-modal {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      width: 500px;
      max-height: 90vh;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
      overflow-y: auto;
    }

    textarea {
      width: 100%;
      height: 100px;
      padding: 10px;
      margin-top: 10px;
    }

    button.send-btn {
      padding: 10px;
      width: 100%;
      margin-top: 10px;
      background: #10a37f;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    #output {
      margin-top: 20px;
    }

    pre code {
      padding: 10px;
      border-radius: 6px;
    }

    #chatgpt-close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      font-weight: bold;
      color: #999;
      cursor: pointer;
    }

    #chatgpt-close:hover {
      color: #000;
    }
    
    
  </style>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    @include('layouts.head-css')
    </head>

    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
    <!-- ========== Left Sidebar Start ========== -->
  @include('layouts.top-bar')
  @include('layouts.sidebar')
    <!--- Sidemenu -->
    <!-- Sidebar -->
    
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

    <div class="page-content">
    <div class="container-fluid">
    <!-- start page title -->
@yield('content')
    <!-- end row -->
    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <button id="chatgpt-button" class="hide">
      <span class="chat-icon">💬</span>
      <span class="chat-label">Chat GPT</span>
    </button>

  @include('layouts.footer')
    </div>
    <!-- end main content-->
    <div id="chatgpt-overlay">
      <div id="chatgpt-modal">
        <span id="chatgpt-close">&times;</span>
        <h3>KEN Chat GPT Assistant</h3>
        <div id="output"></div>
        <textarea id="prompt" placeholder="Ask something...">Welcome In KEN</textarea>
        <button class="send-btn" id="submitBtn">Submit</button>
      </div>
    </div>
    </div>
    <!-- END layout-wrapper -->
    <script>
      $(document).ready(function () {
        // Toggle popup
        $('#chatgpt-button').on('click', function () {
          $('#chatgpt-overlay').fadeIn();
        });
    
        $('#chatgpt-close').on('click', function () {
          $('#chatgpt-overlay').fadeOut();
          $('#output').html("");
        });
    
        // Submit to OpenAI API
        $('#submitBtn').on('click', function () {
          const prompt = $('#prompt').val();
          const apiKey = "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"; // Replace with your API key
          $('#output').html("⏳ Loading...");
    
          $.ajax({
            url: "https://api.openai.com/v1/chat/completions",
            method: "POST",
            headers: {
              "Authorization": "Bearer " + apiKey,
              "Content-Type": "application/json"
            },
            data: JSON.stringify({
              model: "gpt-3.5-turbo",
              messages: [{ role: "user", content: prompt }],
              temperature: 0.7
            }),
            success: function (response) {
              const content = response.choices[0].message.content;
              const html = marked.parse(content);
              $('#output').html(html);
              hljs.highlightAll();
            },
            error: function (xhr) {
              $('#output').html("❌ Error: " + xhr.responseText);
            }
          });
        });
      });
    </script>
    <!-- Right Sidebar -->
     @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
<!-- JAVASCRIPT -->

<!-- JAVASCRIPT -->
@include('layouts.vendor-scripts')


    </body>

    </html>