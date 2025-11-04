@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/6035/grid.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    @import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900");
    
    *,
    *:before,
    *:after {
      box-sizing: border-box;
    }
    table{ 
        margin-top: 2%; 
    }
    .hide{
        display:none;
    }
    .div_title
    {
        font-size: 24px;
        font-weight: 700;
    }
    img {
      max-width: 100%;
      height: auto;
      vertical-align: sub;
    }
    
    body {
      font-family: "Montserrat", sans-serif;
      background: #fbf0ef;
      padding-top: 20px;
    }
    
    .btn {
      display: inline-block;
      padding: 6px 14px;
      background: #ff9999;
      border-radius: 3px;
      color: #7a7a7a;
      font-size: 0.8125em;
      transition: background 0.3s ease, color 0.3s ease;
      text-decoration: none;
    }
    
     .span_menu{
        font-size: 12pt;
        margin-bottom: 5px;
        display: inline-block;
        text-shadow: 1.5px 1px 1px rgba(255, 255, 255, 0.98);
        background: #dde1e7;
        box-shadow: -3px -3px 7px #ffffff73, 3px 3px 5px rgba(94, 104, 121, 0.29);
        padding: 10pt;
        border-radius: 50%;
        color: #646c7d;
        font-weight:800;
    }
    
    .fa-duotone:hover,
    .fa-regular:hover,
    .fa-brands:hover,
    .locations .btn:hover {
      background: aliceblue !important;
    }
    
    .audio .actions .btn:hover {
      background: pink !important;
    }
    .btn.btn-large {
      padding: 12px 28px;
    }
    
    .btn.btn-block {
      display: block;
      width: 100%;
      text-align: center;
    }
    
    input[type="text"] {
      width: 100%;
      border: 1px solid #cfcfcf;
      background: #ebebeb;
      height: 28px;
      font-size: 0.75em;
      padding: 5px;
      outline: none;
      border-radius: 3px;
      margin-bottom: 7px;
      transition: background 0.3s ease;
    }
    
    input[type="text"]:focus {
      background: #f0f0f0;
    }
    
    .checkbox {
      color: #ccc;
      text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.4);
      font-size: 0.6875em;
      margin-bottom: 7px;
      display: inline-block;
    }
    
    .box {
      background: #f9f9f9;
      box-shadow: 0 0 1px rgba(0, 0, 0, 0.2), 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      margin-bottom: 20px;
      text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
    }
    .box h2 {
      color: #a4c9d8;
      font-family: "CircularBook";
      font-weight: 400;
      font-size: 12pt;
      text-shadow: 0.25px 0.25px 0.85px #1a1a1a;
      letter-spacing: 0.25pt;
    }
    
    .box.widget.locations h2 {
        content: url(https://assets.codepen.io/4927073/myname.svg);
        width: 100%;
        height: 100%;
        object-fit: contain;
        background-repeat: no-repeat;
        filter: drop-shadow(1px 1px 1px black) saturate(1.025);
    }
    
    .box p {
      color: #7d7d7d;
      font-family: "CircularLight";
      font-size: 11pt !important;
      
      
    }
    
    .locations {
      padding: 30px;
      text-align: center;
    }
    .locations .avatar {
      border-radius: 50%;
    }
    .locations .avatar img {
      border-radius: 50%;
      z-index: 2 !important;
      position: relative;
      box-shadow: 0px -3px 2px 0px #212428 inset, 0px 2px 2px #535d65 inset,
        0px 2px 2px -1px #050606, 0px 7px 4px -1px #1a1d1f;
      text-shadow: 0px 1px 1px #33393e, 0 0 0 black, 0px 1px 1px #33393e;
      border: 1px solid #15181a;
      background: linear-gradient(#3c4349, #252a2d);
      padding: 8% !important;
      width: 95% !important;
      margin-bottom: -10pt;
    }
    @media (max-width: 768px) {
      .locations .avatar img {
        width: 40% !important;
        padding: 2% !important;
      }
    }
    .locations .details {
      margin-top: 30px;
    }
    .locations .btn {
      font-size: 8pt;
      letter-spacing: 0.75pt;
      text-transform: uppercase;
      background: linear-gradient(
        132deg,
        hsl(41deg 100% 60%) 0%,
        hsl(0deg 78% 56%) 50%,
        hsl(286deg 100% 62%) 100%
      );
      background: linear-gradient(33deg, #99e5a2, #d4fc78);
      box-shadow: inset -0.1em -0.2em 0.2em 0.1em rgb(0 0 0 / 40%),
        inset 0.4em 0.4em 0.4em 0.1em rgb(255 255 255 / 25%),
        0 0 0 2.25px hsl(0deg 0% 12% / 30%), 4px 1px 4px -2px hsl(0deg 0% 0% / 75%),
        0 3px 6px 0px hsl(0deg 0% 0% / 6%), 0 4px 4px rgb(0 0 0 / 34%);
      width: 70%;
      margin-left: auto;
      margin-right: auto;
      font-family: "CircularBlack";
      color: white;
      text-shadow: 0.975px 0.5px 0.95px #1d1e22;
      margin-top: 18pt;
      display: flex;
      justify-content: center;
      border-radius: 6pt;
    }
    
    .calendar {
      text-align: center;
    }
    .calendar .header {
      background: #f673a4;
      padding: 26px 0 12px 0;
      background-color: #000;
      background-image: url(https://images.unsplash.com/photo-1495429789562-21d697f29b02?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2250&q=80);
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      mix-blend-mode: multiply;
    }
    .calendar [class*="icon-"] {
      color: #dd5555;
      position: relative;
      top: 2px;
      margin: 0 5px;
      display: inline-block;
    }
    .calendar .days {
      background: #ffccd2;
      padding: 20px;
      border-radius: 0 0 2pt 2pt;
    }
    @media (max-width: 980px) {
      .calendar .days {
        padding: 10pt 8pt 24pt 8pt;
      }
    }
    .calendar .days ul {
      margin: 0;
      padding: 0;
    }
    .calendar .days li {
      display: inline-block;
      color: #12263b;
      padding: 5px 0;
      width: 30px;
      height: 30px;
      text-align: center;
      font-size: 11pt;
      font-family: "CircularBook";
      opacity: 0.7;
      padding-top: 4pt;
    }
    @media (max-width: 980px) {
      .calendar .days li {
        width: 26px;
        height: 30px;
        font-size: 11pt;
        display: inline-flex;
        justify-content: center;
        align-items: center;
      }
    }
    
    .calendar .days li.next,
    .calendar .days li.previous {
      color: rgba(0, 0, 0, 0.2);
    }
    
    .nav {
      background: #dde1e7;
    }
    .nav a {
      text-decoration: none;
      color: black;
      text-shadow: 1px 1px 1px white;
      font-size: 8pt;
    }
    .nav a:hover {
      color: black;
    }
    .nav [class*="icon-"],
    .nav .fa-regular,
    .nav .fa-brands,
    .nav .fa-duotone {
      font-size: 15pt;
      margin-bottom: 5px;
      display: inline-block;
      text-shadow: 1.5px 1px 1px rgba(255, 255, 255, 0.98);
      background: #dde1e7;
      box-shadow: -3px -3px 7px #ffffff73, 3px 3px 5px rgba(94, 104, 121, 0.29);
      padding: 10pt;
      border-radius: 50%;
      color: #646c7d;
    }
    .nav ul {
      margin: 0;
      padding: 0;
      list-style: none;
      text-align: center;
    }
    .nav li {
      display: inline-block;
    }
    .nav li a {
      display: block;
      padding: 14pt 13pt;
    }
    @media (max-width: 980px) {
      .nav a {
        font-size: 7.5pt;
      }
    }
    @media (max-width: 980px) {
      .nav [class*="icon-"],
      .nav .fa-regular,
      .nav .fa-brands,
      .nav .fa-duotone {
        font-size: 12pt;
        padding: 8pt;
      }
    }
    @media (max-width: 517px) {
      .nav [class*="icon-"],
      .nav .fa-regular,
      .nav .fa-brands,
      .nav .fa-duotone {
        font-size: 11pt;
        padding: 6pt;
      }
    }
    @media (max-width: 435px) {
      .nav [class*="icon-"],
      .nav .fa-regular,
      .nav .fa-brands,
      .nav .fa-duotone {
        font-size: 10pt;
        padding: 4.5pt;
      }
    }
    @media (max-width: 980px) {
      .nav li a {
        padding: 8pt 8pt;
      }
    }
    @media (max-width: 517px) {
      .nav li a {
        padding: 6pt 6pt;
      }
    }
    @media (max-width: 456px) {
      .nav li a {
        padding: 6pt 2pt;
      }
    }
    
    .plotit .header {
      border-radius: 4pt 4pt 0 0;
      padding: 6pt 0;
      background: #ff4532;
      text-align: center;
      color: #caf27a;
      font-size: 12pt;
      font-family: Roboto;
      font-weight: 700;
    }
    
    .weather .header {
      border-radius: 5px 5px 0 0;
      padding: 10pt 0;
      background: #ff4532;
      text-align: center;
      font-size: 16pt;
      border: 1.25pt solid rgba(237, 252, 207, 0.95);
      background: linear-gradient(315deg, #d99058 0%, #f8de7e 74%);
      background: linear-gradient(
        132deg,
        hsl(41deg 100% 60%) 0%,
        hsl(0deg 78% 56%) 50%,
        hsl(286deg 100% 62%) 100%
      );
      background-image: url(https://images.unsplash.com/photo-1546984575-757f4f7c13cf?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3870&q=80) !important;
      background-size: cover;
      background-position: 50% 50%;
    }
    @media (max-width: 980px) {
      .weather .header {
        border-radius: 4pt 4pt 0 0;
      }
    }
    .weather [class*="icon-"] {
      position: relative;
      top: 2px;
    }
    .weather article {
      text-align: center;
      padding: 5px 0;
      background: #ff6338;
      color: #caf27a;
      font-size: 12pt;
      text-shadow: 0.25px 0.25px 0.85px #1a1a1a;
      letter-spacing: 0.25pt;
    }
    
    .icon::before {
      display: inline-block;
      text-rendering: auto;
      -webkit-font-smoothing: antialiased;
    }
    
    .video {
      background: transparent;
      display: flex;
      border-radius: 10px;
      justify-content: center;
      flex-wrap: wrap;
      align-content: center;
      position: relative;
      align-items: center;
      /* background: linear-gradient(to left, #0003, #ffffff99); */
      /* box-shadow: 0 0.125em 0.125em 0.0625em #0001, 0 0.2em 0.25em #0003, 0 0.8em 1.6em -0.125em #0004, inset 0 0 1.2em 0.25em #0004, inset -0.85em 0 1.2em 0.85em #fff; */
    }
    
    .video {
      border-radius: 10%;
      border: 1px solid #cccccc;
      display: flex;
      /*background-image: url(assets/images/invetory.jpg) !important;*/
      background-position: 50% 50%;
      background-size: cover;
      transform: scale(0.75);
      z-index: 3 !important;
      padding: 50% 50%;
      justify-content: center;
    }
    
    .video:before {
      content: "";
      bottom: 0;
      top: 0;
      /*background: linear-gradient(*/
      /*  135deg,*/
      /*  #6c22bd,*/
      /*  #8b25bb,*/
      /*  #a52bb9,*/
      /*  #bc34b7,*/
      /*  #d040b5,*/
      /*  #e645a5,*/
      /*  #f55195,*/
      /*  #ff6188,*/
      /*  #ff796e,*/
      /*  #ff965b,*/
      /*  #f5b255,*/
      /*  #e1cd60*/
      /*);*/
      position: absolute;
      border-radius: 10%;
      display: flex;
      z-index: 2 !important;
      transform: scaleX(0.72) scaleY(0.72);
      filter: blur(9px);
      background-size: cover;
      top: 14px;
      left: 0;
      right: 0;
      height: 100%;
      width: 100%;
    }
    
    .video:hover:before {
      background: rgba(255, 255, 255, 0.3);
    }
    .video img {
      border-radius: 5px;
    }
    
    .post img {
      border-radius: 5px 5px 0 0;
      box-shadow: 0px -3px 2px 0px #212428 inset, 0px 2px 2px #535d65 inset,
        0px 2px 2px -1px #050606, 0px 4px 4px -2px rgba(72, 80, 86, 0.5);
    }
    .post .details {
      padding: 10px 20px;
    }
    .post .details p {
      line-height: 1.5em;
    }
    
    .audio {
      position: relative;
      height: 126px;
    }
    .audio .image {
      position: absolute;
      top: 0;
      left: 0;
    }
    .audio .image img {
      border-radius: 5px 1px 1px 5px;
      width: 95pt;
      height: 95pt;
    }
    .audio .details {
      padding: 10px 0;
      margin-left: 140px;
    }
    .audio .details p {
      font-size: 12pt !important;
      letter-spacing: 0.25pt;
      margin-top: -3% !important;
      margin-bottom: 13% !important;
    }
    @media (max-width: 980px) {
      .audio .details p {
        font-size: 10pt !important;
        margin-top: -6% !important;
        margin-bottom: 8% !important;
      }
    }
    @media (max-width: 768px) {
      .audio .details p {
        font-size: 11pt !important;
        margin-top: -2% !important;
        margin-bottom: 8% !important;
      }
      .audio .details h2 {
        font-size: 13pt !important;
        margin-bottom: 3%;
      }
    }
    .audio .actions {
      position: absolute;
      right: 8px;
      bottom: 10px;
    }
    @media (max-width: 980px) {
      .audio .actions {
        transform: scale(1.05);
        display: flex;
        justify-content: flex-start;
        position: relative;
        margin-left: 2%;
        bottom: 15pt !important;
      }
    }
    @media (max-width: 768px) {
      .audio .actions {
        margin-left: 50%;
        transform: scale(1.095);
        margin-top: -7%;
        justify-content: center;
      }
    }
    .audio .actions .btn {
      width: 25px;
      height: 25px;
      float: left;
      margin-left: 8px;
      padding: 6px 6px;
      color: #fff;
      border-radius: 50%;
      background: -webkit-radial-gradient(
          50% 0%,
          8% 50%,
          rgba(255, 255, 255, 0.5) 0%,
          rgba(255, 255, 255, 0) 100%
        ),
        -webkit-radial-gradient(50% 100%, 12% 50%, rgba(255, 255, 255, 0.6) 0%, rgba(
                255,
                255,
                255,
                0
              )
              100%),
        -webkit-radial-gradient(0% 50%, 50% 7%, rgba(255, 255, 255, 0.5) 0%, rgba(
                255,
                255,
                255,
                0
              )
              100%),
        -webkit-radial-gradient(100% 50%, 50% 5%, rgba(255, 255, 255, 0.5) 0%, rgba(
                255,
                255,
                255,
                0
              )
              100%),
        -webkit-repeating-radial-gradient(50% 50%, 100% 100%, transparent 0%, transparent
              3%, rgba(0, 0, 0, 0.1) 3.5%),
        -webkit-repeating-radial-gradient(50% 50%, 100% 100%, rgba(255, 255, 255, 0)
              0%, rgba(255, 255, 255, 0) 6%, rgba(255, 255, 255, 0.1) 7.5%),
        -webkit-repeating-radial-gradient(50% 50%, 100% 100%, rgba(255, 255, 255, 0)
              0%, rgba(255, 255, 255, 0) 1.2%, rgba(255, 255, 255, 0.2) 2.2%),
        -webkit-radial-gradient(50% 50%, 200% 50%, #e6e6e6 5%, #d9d9d9 30%, #999999
              100%) !important;
      box-shadow: 0 0px 2px rgba(0, 0, 0, 0.4), inset 0 0px 1px rgba(0, 0, 0, 0.3),
        0 1.15px 1.25px rgba(0, 0, 0, 0.46), 0 2px 3px rgba(0, 0, 0, 0.07),
        0 4px 4px rgba(0, 0, 0, 0.05), inset 0 2px 1px rgba(255, 255, 255, 0.6);
      border: 1px solid #d5d5d5;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    @media (max-width: 980px) {
      .audio .actions .btn {
        width: 21pt;
        height: 21pt;
        font-size: 11pt;
        margin-top: 9pt;
        margin-right: 3%;
        margin-left: 10%;
      }
      .audio .player .bar .progress:before {
        opacity: 0;
      }
      .audio .player .bar .progress:after {
        opacity: 0;
      }
    }
    
    @media (max-width: 768px) {
      .audio .actions .btn {
        width: 18pt;
        height: 18pt;
        font-size: 11pt;
        margin-top: 9pt;
        margin-right: 0%;
        margin-left: 8%;
      }
      .audio .player .bar .progress:before {
        opacity: 1;
      }
      .audio .player .bar .progress:after {
        opacity: 1;
      }
    }
    
    .audio .player .bar {
      margin: 30px 0 0;
      height: 6px;
      background: #cccccc;
      width: 60%;
      border-radius: 2px;
    }
    @media (max-width: 980px) {
      .audio .player .bar {
        margin: -4% 0 10% 0;
        height: 5pt;
        background: #cccccc;
        width: 90%;
        display: flex;
        border-radius: 10pt;
      }
    }
    @media (max-width: 768px) {
      .audio .player .bar {
        width: 48%;
        margin: 22pt 0 4pt 0;
      }
    }
    .audio .player .bar .progress {
      width: 45%;
      background-image: linear-gradient(90deg, #ff9e66, #ff0040);
      height: 6px;
      border-radius: 10pt;
    
      position: relative;
      box-shadow: 1px 1px 2px -0.5px #bdbdbd,
        inset 0.25px 0.875px 4px 0.125px rgba(240, 240, 240, 0.85),
        inset -1px -1.5px 1.5px -1.25px rgba(26, 26, 26, 0.75);
    }
    .audio .player .bar .progress:before {
      content: attr(data-time);
      background: #dd5555;
      display: inline;
      position: absolute;
      font-size: 0.6875em;
      color: #fff;
      padding: 4px 6px;
      border-radius: 3px;
      right: -14px;
      bottom: 13px;
    }
    .audio .player .bar .progress:after {
      content: "";
      border: 5px solid transparent;
      border-top: 5px solid #dd5555;
      position: absolute;
      right: -3px;
      bottom: 4px;
    }
    
    .find {
      padding: 10px;
    }
    
    section.box.widget.locations {
      background: -webkit-radial-gradient(
          center top,
          farthest-side,
          #2a5989,
          #1c395a 40%,
          #12263b 95%,
          #12263b
        ),
        #12263b;
    }
    
    
    
    .weather article h2 {
      color: black;
      text-shadow: 0.25px 0.25px 0.85px #1a1a1a;
      letter-spacing: 0.25pt;
      font-family: Roboto;
      font-weight: 300;
      line-height: 2pt;
      font-size: 10pt;
    }
    
    .locations .details p {
        font-size: 10pt !important;
        margin-top: -6pt !important;
        margin-bottom: 2pt !important;
        text-transform: uppercase;
        font-family: "CircularBlack";
        letter-spacing: 1pt;
        color: transparent;
        background-image: linear-gradient(-20deg, #5e7a52 0%, #d1ef95 35%, #d8fb92 78%);
        -webkit-background-clip: text;
        filter: drop-shadow(0px 2px 2px #5e7a5233);
        text-shadow: none !important;
    }
    
    .calendar .header h2 {
      color: black !important;
      font-family: "CircularLight";
      letter-spacing: 2.85pt;
      font-size: 18pt;
      margin-bottom: -6pt;
      margin-top: 8pt;
    }
    
    .calendar .header p {
      color: hsl(80deg 82% 78%) !important;
      margin-bottom: 2pt;
      font-family: "CircularBlack";
      text-transform: uppercase;
      letter-spacing: 1pt;
      font-size: 10pt !important;
    }
    
    #analytics {
      padding-top: 6pt;
      width: 100%;
      height: 60%;
      background: transparent;
      margin-bottom: -36pt;
    }
    @media (max-width: 980px) {
      #analytics {
        margin-bottom: -26pt;
      }
    }
    @media (max-width: 768px) {
      #analytics {
        margin-bottom: -26%;
      }
    }
    
    section.box.widget.plotit .days ul {
      top: 58%;
      left: 5px;
      padding: 0;
    }
    
    section.box.widget.plotit .days li {
      display: inline-flex;
      padding-right: 2pt;
      padding-left: 2pt;
      text-transform: uppercase;
      letter-spacing: 1pt;
      font-size: 6pt;
      color: white;
    }
    @media (max-width: 980px) {
      section.box.widget.plotit .days li {
        padding: 0;
        font-size: 5.5pt;
        letter-spacing: 0.75pt;
      }
    }
    
    .nopadding {
      padding: 0;
    }
    
    .insights {
      width: 100%;
      height: 23%;
      bottom: 7%;
      display: flex;
      font-size: 10pt;
      line-height: 4pt;
      font-family: Roboto Condensed;
      margin-top: -4pt;
    }
    
    .insight-left {
      left: 0;
      width: 50%;
      top: 12%;
    }
    
    .insight-right {
      right: 0;
      width: 50%;
      top: 12%;
    }
    
    .insights p {
      font-size: 7.25pt !important;
      line-height: 2pt;
      color: #5e6063;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-align: center;
      font-family: "Source Sans Pro", sans-serif;
      font-weight: 500;
    }
    
    .temp {
      font-size: 8pt;
      text-align: center;
        margin: 0;
        position: relative;
        z-index: 2 !important;
        color: black;
        padding: 4pt 4pt 8pt 2pt;
        font-family: "Courier Prime", monospace;
        line-height: 145%;
        margin-bottom: 5pt;
    }
    @media (max-width: 980px) {
      .temp {
        font-size: 7pt;
      }
    }
    
    section.box.widget.find {
      width: 85%;
      padding: 6pt;
      height: 92pt;
      background-image: url(https://images.unsplash.com/photo-1548534721-cf7f80338856?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3871&q=80);
      background-size: cover;
      background-position: 50% 50%;
    }
    
    @media (max-width: 600px) {
      section.box.widget.find {
        width: 100%;
        margin-bottom: 20%;
      }
    }
    
    #logo {
      height: 58pt;
      width: 58pt;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: rgba(31, 33, 28, 0.5);
      box-shadow: 0.25pt 0.25pt 3pt -0.25pt rgba(28, 29, 33, 0.85),
        inset 0.5pt 0.25pt 2.5pt -0.25pt #f1f2f3;
    }
    
    #logo:before {
      content: "";
      position: absolute;
      height: inherit;
      width: inherit;
      border-radius: 50%;
      background-image: radial-gradient(#ffffff 2.5pt, #d8f0a8 2.5pt);
      background-size: 8pt 8pt;
      z-index: 0;
      transform: rotate(25deg);
      border: 2pt solid rgba(237, 252, 207, 0.95);
      box-shadow: 1pt 1pt 4pt #1c1d21;
      opacity: 0.9;
    }
    
    #text {
      font-family: Roboto;
      color: #000;
      white-space: nowrap;
      filter: drop-shadow(1.5pt 1.5pt 1pt white);
      font-size: 26pt;
      text-align: center;
      z-index: 2 !important;
      font-weight: 900;
      position: relative;
      letter-spacing: 0.75pt;
      text-transform: uppercase;
    }
    
    .weather .header::before {
      content: "";
      position: absolute;
      height: 54pt;
      width: 120pt;
      z-index: 0 !important;
      transform: translate(-60pt, 0pt);
      border-top: 2px solid rgba(255, 255, 255, 0.4);
      border-left: 2px solid rgba(255, 255, 255, 0.4);
      border-right: 3px solid rgba(255, 255, 255, 0.32);
      border-bottom: 3px solid rgba(255, 255, 255, 0.32);
      background: linear-gradient(
        135deg,
        transparent 0%,
        transparent 8%,
        rgba(255, 255, 255, 0.03) 30%,
        rgba(255, 255, 255, 0.03) 60%,
        rgba(255, 255, 255, 0) 68%
      );
      background-color: rgba(255, 255, 255, 0.32);
      border-radius: 12px;
      border-color: #ffffff99 #ffffff60 #0001 #ffffff50;
      backdrop-filter: blur(11px) saturate(110%);
      box-shadow: 0px 1px 5px 1px hsl(0deg 0% 16% / 45%),
        0 0.33em 0.725em -0.455em rgb(0 0 0 / 40%),
        inset 0 0.0625em 0 rgb(255 255 255 / 50%),
        inset 0 -0.125em 0.0625em hsl(0deg 0% 0% / 28%);
      border: 2px outset rgba(255, 255, 255, 0.3);
    }
    @media (max-width: 980px) {
      .weather .header::before {
        width: 94pt;
        height: 54pt;
        transform: translate(-46pt, -1pt);
        
      }
      .audio .actions .fa-duotone,
      .fa {
        margin: 30% 12pt;
        transform: scale(1.25);
      }
    }
    
    @media (max-width: 768px) {
      .weather .header::before {
        height: 54pt;
        width: 100pt;
        transform: translate(-50pt, -1pt);
      }
    
      .container {
        width: 90%;
        margin-left: auto;
        margin-right: auto;
      }
      .container [class*="grid"] {
        width: 98%;
      }
      .audio .actions .fa-duotone,
      .fa {
        margin: 40% 2%;
        transform: scale(0.98);
      }
    }
    @media (max-width: 600px) {
      .weather .header::before {
        height: 54pt;
        width: 72%;
        transform: none;
        margin: auto 8%;
        display: flex;
        align-content: center;
        justify-content: center;
        align-items: center;
      }
    
      .audio .actions {
        margin-left: 50%;
        transform: scale(1.395);
        margin-top: -6%;
        justify-content: center;
        bottom: 14pt !important;
      }
    
      .audio .actions .btn {
        width: 18pt;
        height: 18pt;
        font-size: 11pt;
        margin-top: 6pt;
        margin-right: 0%;
        margin-left: 8%;
      }
    
      .audio .player .bar {
        width: 50%;
        margin: 22pt 0px 4pt 0;
        height: 8pt;
      }
    
      .audio .player .bar .progress {
        height: 8pt;
      }
    
      .audio .player .bar .progress:after {
        margin-bottom: 2.5pt !important;
        transform: scale(1.45);
      }
      .audio .player .bar .progress:before {
        margin-bottom: 4pt !important;
      }
    }
    
    @media (max-width: 505px) {
      .audio .actions {
        transform: scale(1.125);
      }
    }
    
    .audio .actions .fa-duotone,
    .fa {
      font-weight: 900;
      border-radius: 50%;
      background: -webkit-radial-gradient(
          50% 0%,
          8% 50%,
          rgba(255, 255, 255, 0.5) 0%,
          rgba(255, 255, 255, 0) 100%
        ),
        -webkit-radial-gradient(50% 100%, 12% 50%, rgba(255, 255, 255, 0.6) 0%, rgba(
                255,
                255,
                255,
                0
              )
              100%),
        -webkit-radial-gradient(0% 50%, 50% 7%, rgba(255, 255, 255, 0.5) 0%, rgba(
                255,
                255,
                255,
                0
              )
              100%),
        -webkit-radial-gradient(100% 50%, 50% 5%, rgba(255, 255, 255, 0.5) 0%, rgba(
                255,
                255,
                255,
                0
              )
              100%),
        -webkit-repeating-radial-gradient(50% 50%, 100% 100%, transparent 0%, transparent
              3%, rgba(0, 0, 0, 0.1) 3.5%),
        -webkit-repeating-radial-gradient(50% 50%, 100% 100%, rgba(255, 255, 255, 0)
              0%, rgba(255, 255, 255, 0) 6%, rgba(255, 255, 255, 0.1) 7.5%),
        -webkit-repeating-radial-gradient(50% 50%, 100% 100%, rgba(255, 255, 255, 0)
              0%, rgba(255, 255, 255, 0) 1.2%, rgba(255, 255, 255, 0.2) 2.2%),
        -webkit-radial-gradient(50% 50%, 200% 50%, #e6e6e6 5%, #d9d9d9 30%, #999999
              100%) !important;
      box-shadow: 0 0px 2px rgb(0 0 0 / 40%), inset 0 0px 1px rgb(0 0 0 / 30%),
        0 1.15px 1.25px rgb(0 0 0 / 46%), 0 2px 3px rgb(0 0 0 / 7%),
        0 4px 4px rgb(0 0 0 / 5%), inset 0 2px 1px rgb(255 255 255 / 60%);
      width: 24px;
      height: 24px;
      float: left;
      margin-left: 8px;
      padding: 6px 6px;
      color: #fff;
      border: 1px solid #d5d5d5;
      display: flex;
      position: relative;
      align-items: center;
      justify-content: space-around;
    }
    
    button.marble-4 {
      border-radius: 50%;
      box-shadow: -12px 21px 27px -21px #545454;
      background-color: #ff9a8b;
      background-image: linear-gradient(
        90deg,
        #ff997a 0%,
        #ff5c7c 55%,
        #ff8fe3 100%
      );
      margin: auto !important;
      filter: saturate(1.1) blur(0.075px);
      padding: 20pt 20pt;
      transform-style: preserve-3d;
      transform: scale(0.75);
      border: none !important;
      display: flex !important;
      justify-content: center;
      letter-spacing: 1.5pt;
      text-transform: uppercase;
      color: white;
      font-size: 12pt;
      font-weight: 300;
    }
    
    button.marble-4:before {
      background: white;
      border-radius: 4px;
      height: 8px;
      width: 8px;
      left: 60%;
      top: 8px;
      content: "";
      position: absolute;
      opacity: 0.4;
    }
    button.marble-4:after {
      background: transparent;
      width: 24px;
      height: 10px;
      border-radius: 50%;
      box-shadow: 4px 4px 0 0 white;
      transform: rotate(42deg);
      left: 10%;
      bottom: 16px;
      content: "";
      position: absolute;
      opacity: 0.4;
    }
    
    .button-rows {
      display: flex !important;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      margin: auto -50%;
    }
    
    button.learn-more,
    button.learn-more2 {
      font-weight: 600;
      padding: 8.5pt 20pt;
      transform-style: preserve-3d;
      margin: auto;
      background: #a48bfe !important;
      border-radius: 15px;
      font-family: "Source Sans Pro", sans-serif;
      transform: scale(0.75);
      border: none !important;
      border-bottom: 8px solid rgba(0, 0, 0, 0.15) !important;
      box-shadow: 0 1px 5px rgb(0 0 0 / 20%), 0 2px 2px rgb(0 0 0 / 14%),
        0 3px 1px -2px rgb(0 0 0 / 12%) !important;
      display: flex !important;
      justify-content: center;
      letter-spacing: 1.5pt;
      text-transform: uppercase;
      color: white;
      font-size: 12pt;
      font-weight: 300;
    }
    
    button.learn-more {
      background: linear-gradient(45deg, #fff0f0, #f9c3d1) !important;
      border-bottom: 8px solid hsl(335deg 22% 61% / 45%) !important;
    }
    
    .somebuttons {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    #chart {
        max-width: 300px;
        padding: 0;
        transform: scale(0.65);
        height: auto !important;
        min-height: 100pt !important;
        max-height: 180pt !important;
        margin: -24% 0 10% 0;
        filter: drop-shadow(1px 1px 3px #12263b76);
    }
    .text-right
    {
        text-align: right;
    }
    td{
        font-size: 16px!important;
    }
    th{
        font-size: 16px!important;
    }
    
    tr:nth-child(even) {background-color: #f2f2f2;}
    .mar
    {
        color: blue;
        font-weight: 600;
        font-size: 53px;
    }
    #ctx1
    {
        margin-top: 10%;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="card"> 
         <div class="card-body">
             <!--<div class="col-md-12"><marquee><h4 class="mar"><b>KEN GLOBAL PVT. LTD.</b></h4></marquee></div>-->
             <div class="row">
              <div class="col-md-12">
                <nav class="box nav">
                  <ul>
                    <li>
                      <a href="#">
                        <span class="title span_menu" style="background: antiquewhite;" id="invent">Inventory Dashboard</span>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <span class="title span_menu" style="background: darkorange;color: black;" id="prod">Production</span>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <span class="title span_menu" style="background: antiquewhite;" id="invent1">Inventory</span>
                      </a>
                    </li>
                    <!--<li>-->
                    <!--  <a href="#">-->
                    <!--    <span class="title span_menu" style="background: #ffff00a6;" id="capacity">Capacity Planing</span>-->
                    <!--  </a>-->
                    <!--</li>-->
                    <!--<li>-->
                    <!--  <a href="#">-->
                    <!--    <span class="title span_menu" style="background: violet;color: black;" id="costing">Costing OCR Compression</span>-->
                    <!--  </a>-->
                    <!--</li>-->
                    <!--<li>-->
                    <!--  <a href="#">-->
                    <!--    <span class="title span_menu" style="background: #41e157ad;" id="hourly">Hourly Production</span>-->
                    <!--  </a>-->
                    <!--</li>-->
                  </ul>
                </nav>
                <div id="inventory">
                    <div class="inner_container">
                      <div class="col_1of3">
                        <!--<section class="box widget video" style="background-image: url(assets/images/invetory.jpg) !important;">-->
                        <!--</section>-->
                      </div>
                      <div class="col_2of3" style="width: 94.666667%!important;">
                        <div class="text-center div_title">Inventory For Fabric, Trims, FG, WIP (values in Lakh) </div> 
                        <div class="row">
                            <div class="col-md-6">
                                    <canvas id="ctx" width="666" height="600"></canvas>
                            </div>
                            <div class="col-md-6">
                                    <canvas id="ctx1" width="666" height="530"></canvas>
                            </div>
                        </div>
                        <article class="box post" style="margin-top: 5%;">
                            <table class="table">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th>Inventory</th>
                                        <th class="text-right">Moving (L)</th>
                                        <th class="text-right">Non-Moving (L)</th>
                                        <th class="text-right">Total (L)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>Fabric</b></td>
                                        <td id="fabMove" class="text-right"></td>
                                        <td id="fabNonMove" class="text-right"></td>
                                        <td id="fabNonTotal" class="text-right"></td>
                                    </tr> 
                                    <tr>
                                        <td><b>Trims</b></td>
                                        <td id="trimMove" class="text-right"></td>
                                        <td id="trimNonMove" class="text-right"></td>
                                        <td id="trimTotal" class="text-right"></td>
                                    </tr>
                                    <tr>
                                        <td><b>FG</b></td>
                                        <td id="fgMove" class="text-right"></td>
                                        <td id="fgNonMove" class="text-right"></td>
                                        <td id="fgTotal" class="text-right"></td>
                                    </tr>
                                    <tr>
                                        <td><b>WIP</b></td>
                                        <td id="WIPMove" class="text-right"></td>
                                        <td id="WIPNonMove" class="text-right"></td>
                                        <td id="WIPTotal" class="text-right"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td id="totalInventoryMove" class="text-right"></td>
                                        <td id="totalInventoryNonMove" class="text-right"></td>
                                        <td id="totalInventoryTotal" class="text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>
                        
                        <article class="box post">
                              <table class="table">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th></th>
                                        <th class="text-right">Pcs (L)</th>
                                        <th class="text-right">Min (L)</th>
                                        <th class="text-right">Value (L)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>Order Booking MTD</b></td>
                                        <td id="bookingQty" class="text-right"></td>
                                        <td id="bookingMin" class="text-right"></td>
                                        <td id="bookingValue" class="text-right"></td>
                                    </tr> 
                                    <tr>
                                        <td><b>Order Dispatch MTD</b></td>
                                        <td id="salesDispatchQty" class="text-right">0.00%</td>
                                        <td id="salesDispatchMin" class="text-right">0.00 L</td>
                                        <td id="salesDispatchValue" class="text-right">0</td>
                                    </tr> 
                                </tbody>
                            </table>
                        </article>
                      </div>
                    </div>
                    <div class="inner_container">
                     <div class="row" style="margin-left: 0%;">
                       <div class="col-md-5">
                        <section class="box widget">
                            
                            <table class="table" style="margin-top: 1%!important;">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th></th>
                                        <th class="text-right">Pcs (L)</th>
                                        <th class="text-right">Mins (L)</th>
                                        <th class="text-right">No of Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>Open Order</b></td>
                                        <td id="openOrderPCS" class="text-right"></td>
                                        <td id="openOrderMin" class="text-right"></td>
                                        <td id="totalOrder" class="text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                        </div>
                       <div class="col-md-6">
                        <section class="box widget" style="margin-top: -1%;">
                          <table class="table">
                                <thead style="background: antiquewhite;">
                                    <tr>	
                                        <th>Cut-to-ship%</th>
                                        <th>Order to Ship%</th>
                                        <th>Cut to Ship Order Qty</th>
                                        <th>No of Closed Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>0.00%</b></td>
                                        <td>0.00%</td>
                                        <td>0.00 L</td>
                                        <td>0</td>
                                    </tr> 
                                </tbody>
                            </table>
                        </section>
                        </div>
                      </div> 
                      </div>
                </div>
                <div id="production" class="hide">
                    <div class="inner_container">
                      <div class="col_1of3" style="margin-top: 10%;">
                      </div>
                      <div class="col_2of3">
                        <div class="text-center div_title">Inventory Wise Production  
                             <div class="row">
                                <div class="col-md-6">
                                    <canvas id="ctx2" width="666" height="300"></canvas>
                                </div>
                            </div>
                            <div class="row pull-right">
                                <div class="col-md-6"></div>
                                <div class="col-md-4"></div>
                                <div class="col-md-2"><input type="date" class="form-control" id="currentDate" onchange="LoadProduction(this.value);" value="{{date('Y-m-d',strtotime('-1 days'))}}"></div>
                            </div>
                        </div>
                        <article class="box post" id="tbodyProduction">
                        </article>
                      </div>
                    </div>
                </div>
                <div id="inventory1" class="hide">
                    <div class="inner_container">
                      <div class="col_1of3" style="margin-top: 10%;">
                        <section class="box widget video" style="background-image: url(assets/images/production.jpg) !important;">
                        </section>
                      </div>
                      <div class="col_2of3">
                        <div class="text-center div_title">INVENTORY</div>
                        <article class="box post">
                            <table class="table">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th>Date</th>
                                        <th colspan="3" class="text-center">Quantity</th>
                                        <th colspan="5" class="text-center">Value (Rs Cr)</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Fabric (Lakh Mtrs)</th>
                                        <th>WIP (Lakh Pcs)</th>
                                        <th>FG (Lakh Pcs)</th>
                                        <th>Fabric</th>
                                        <th>Trims</th>
                                        <th>WIP</th>
                                        <th>FG</th>
                                        <th>Total</th>
                                    </tr>
                                </thead> 
                                <tbody id="tbodyInventory">
                                    <tr>
                                        <td><b>Ken Global 1</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Ken Global 2</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Ansh</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Shirala</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Anita</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Warehouse</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Myntra MH</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Myntra KA</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Other Vendors</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>
                      </div>
                    </div>
                </div>
                <div id="capacity_planning" class="hide">
                    <div class="inner_container">
                      <div class="col_1of3">
                        <section class="box widget video" style="background-image: url(assets/images/capacity.jpg) !important;">
                        </section>
                      </div>
                      <div class="col_2of3">
                        <div class="text-center div_title">CAPACITY PLANNING</div>
                        <article class="box post">
                            <table class="table">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th></th>
                                        <th>Ken 1</th>
                                        <th>Ken 2</th>
                                        <th>Shirala</th>
                                        <th>Ansh</th>
                                        <th>Outsourcing</th>
                                        <th>Total</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Compliant</th>
                                        <th>Approved</th>
                                        <th>Approved</th>
                                        <th>Non Compliant</th>
                                        <th>Non Compliant</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>Shirts + Boxers + Pyjama</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Shirts</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Boxers + Pyjama</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Innerwear</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>
                      </div>
                    </div>
                    <div class="inner_container">
                        <div class="col-md-12">
                        <article class="box post">
                            <table class="table" style="margin-top: 3%!important;">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th></th>
                                        <th colspan="3" class="text-center">April</th>
                                        <th colspan="3" class="text-center">May</th>
                                        <th colspan="3" class="text-center">June</th>
                                        <th colspan="3" class="text-center">Q1 Total</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Avail</th>
                                        <th>Booked</th>
                                        <th>Open</th> 
                                        <th>Avail</th>
                                        <th>Booked</th>
                                        <th>Open</th> 
                                        <th>Avail</th>
                                        <th>Booked</th>
                                        <th>Open</th> 
                                        <th>Avail</th>
                                        <th>Booked</th>
                                        <th>Open</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>Compliance</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Approved</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Non Compliance</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>
                      </div>
                      </div>
                </div>
                <div id="costing_ocr_compression" class="hide">
                    <div class="inner_container">
                      <div class="col_1of3">
                        <section class="box widget video" style="background-image: url(assets/images/costing.jpg) !important;"> 
                        </section>
                      </div>
                      <div class="col_2of3">
                        <div class="text-center div_title">COSTING OCR COMPRESSION</div>
                        <article class="box post">
                            <table class="table">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th colspan="3" class="text-center">Costing</th>
                                        <th colspan="3" class="text-center">OCR</th>
                                        <th colspan="3" class="text-center">Deviation</th>
                                    </tr>
                                    <tr>
                                        <th>KDPL No</th>
                                        <th>Total Pieces</th> 
                                        <th>Fabric Mrs</th>
                                        <th>Fabric Value</th>
                                        <th>Trims Value</th>
                                        <th>Fabric Mrs</th>
                                        <th>Fabric Value</th>
                                        <th>Trims Value</th>
                                        <th>Fabric Mrs</th>
                                        <th>Fabric Value</th>
                                        <th>Trims Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>
                      </div>
                    </div>
                    <div class="inner_container">
                     <div class="col_1of3">
                        <section class="box widget">
                        </section>
                      </div>
                      </div>
                </div>
                <div id="hourly_production" class="hide">
                    <div class="inner_container">
                      <div class="col_1of3">
                        <section class="box widget video" style="background-image: url(assets/images/hourly.jpg) !important;"> 
                        </section>
                      </div>
                      <div class="col_2of3">
                        <div class="text-center div_title">HOURLY PRODUCTION</div>
                        <article class="box post">
                            <table class="table">
                                <thead style="background: antiquewhite;">
                                    <tr>
                                        <th>Hour</th>
                                        <th>Pcs</th>
                                        <th>SAM</th>
                                        <th>Prod Min</th>
                                        <th>Operators</th>
                                        <th>Eff</th>
                                        <th>DHU%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> 
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </article>
                      </div>
                    </div>
                </div>
              </div>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>  
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script> 

@php
        
        $totalMoving = 0; 
        $totalNonMoving = 0;
        
        $move = explode(',', $movingArr);
        
        foreach($move as $row)
        {
           $totalMoving += $row;
        }
        
        $nonmove = explode(',', $non_movingArr);
        
        foreach($nonmove as $row)
        {
           $totalNonMoving += $row;
        }
         
         
        $ken1 = json_encode($array3[0][1][0][1]);
        $lines = $array3[0][1];
        $lineArr = [];
        
     //   json_encode($array3[0][1][1][0]);
        foreach($lines as $key=>$value)
        {
            $line = json_encode($array3[0][1][$key][0]);
            $lineArr[] = $line;
        }
       $l1 = stripslashes(str_replace("\"",'',str_replace(array('[',']'),'',json_encode($lineArr)))); 
       $l2 = implode( ', ', $lineArr ); 

        
@endphp
<script>

   $( document ).ready(function() 
   {
        var lbl = "{{$l1}}";
        var chart2 = new Chart(ctx2, {
              type: 'bar',
              data: {
                  labels: [lbl],

                  datasets: [{
                     label: 'Pieces',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'blue'
                  }, {
                     label: 'SAM',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'orange'
                  },{
                     label: 'Operators',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'orange'
                  }, {
                     label: 'Total Min Prod',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'orange'
                  }, {
                     label: 'Min Available',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'orange'
                  }, {
                     label: 'Efficiency%',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'orange'
                  },{
                     label: 'DHU%',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'orange'
                  },{
                     label: 'Rejection%',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'orange'
                  }]
              },
              options: {
                  responsive: false,
                  legend: {
                     position: 'right' // place legend on the right side of chart
                  },
                  scales: {
                     xAxes: [{
                        stacked: true // this should be set to make the bars stacked
                     }],
                     yAxes: [{
                        stacked: true // this also..
                     }]
                  }
              }
            });
            
         $.ajax({
            dataType: "json",
            type: "GET",
            data: {'table_head':10},
            url: "{{ route('loadERPInventoryData') }}",
            success: function(data)
            {
                $("#fabMove").html(numberWithCommas(data.fabmoving));
                $("#fabNonMove").html(numberWithCommas(data.fabnon_moving));
                $("#fabNonTotal").html(numberWithCommas(data.fabtotal.toFixed(2)));
                $("#trimMove").html(numberWithCommas(data.trimmoving));
                $("#trimNonMove").html(numberWithCommas(data.trimnon_moving));
                $("#trimTotal").html(numberWithCommas(data.trimtotal.toFixed(2)));
                $("#fgMove").html(numberWithCommas(data.fgmoving));
                $("#fgNonMove").html(numberWithCommas(data.fgnon_moving));
                $("#fgTotal").html(numberWithCommas(data.fgtotal.toFixed(2)));
                $("#WIPMove").html(numberWithCommas(data.WIPmoving));
                $("#WIPNonMove").html(numberWithCommas(data.WIPnon_moving));
                $("#WIPTotal").html(numberWithCommas(data.WIPtotal.toFixed(2)));
                var totalInventoryMove = parseFloat(data.fabmoving) + parseFloat(data.trimmoving) + parseFloat(data.fgmoving) + parseFloat(data.WIPmoving);
                var totalInventoryNonMove = parseFloat(data.fabnon_moving) + parseFloat(data.trimnon_moving) + parseFloat(data.fgnon_moving);
                var totalInventoryTotal = parseFloat(data.fabtotal) + parseFloat(data.trimtotal) + parseFloat(data.fgtotal) + parseFloat(data.WIPtotal);
                
                $('#totalInventoryMove').html(numberWithCommas(totalInventoryMove.toFixed(2)));
                $('#totalInventoryNonMove').html(numberWithCommas(totalInventoryNonMove.toFixed(2)));
                $('#totalInventoryTotal').html(numberWithCommas(totalInventoryTotal.toFixed(2)));
                
                $("#openOrderPCS").html(numberWithCommas(data.openOrderPCS));
                $("#openOrderMin").html(numberWithCommas(data.openOrderMin));
                $("#openOrdertotal").html(numberWithCommas(data.openOrdertotal));
                $("#totalOrder").html(numberWithCommas(data.totalOrder));
                
                $('#bookingQty').html(numberWithCommas(data.bookingQty));
                $('#bookingMin').html(numberWithCommas(data.bookingMin));
                $('#bookingValue').html(numberWithCommas(data.bookingValue));
                
                $("#salesDispatchQty").html(numberWithCommas(data.salesBookingQty));
                $("#salesDispatchMin").html(numberWithCommas(data.salesBookingMin));
                $("#salesDispatchValue").html(numberWithCommas(data.salesBookingValue));
    
                LoadProduction($("#currentDate").val());
            }
         });
         
         function numberWithCommas(x) {
            return x.toString().split('.')[0].length > 3 ? x.toString().substring(0,x.toString().split('.')[0].length-3).replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + x.toString().substring(x.toString().split('.')[0].length-3): x.toString();
        }
        $.ajax({
            dataType: "json",
            type: "GET",
            url: "{{ route('loadERPInventoryData1') }}",
            success: function(data)
            {
                $('#tbodyInventory').html(data.html);
            }
        });
      
            var chart = new Chart(ctx, {
               type: 'bar',
               showDatapoints: true,
               position: "top",
               data: {
                  type: "stackedColumn",
                  labels: ["Fabric", "Trims", "FG", "WIP"],
                  datasets: [{
                     label: 'Moving(Lakh)',
                     data: [{{$movingArr}}],
                     backgroundColor: 'orange',
                     color: 'white'
                  }, {
                     label: 'Non Moving(Lakh)',
                     data: [{{$non_movingArr}}],
                     backgroundColor: 'red'
                  }]
               }, 
               options: {
                  responsive: true,
                    title: {
                        display: true,
                    },
                  legend: { display: true, position: "top" },
                  scales: {
                     xAxes: [{
                        stacked: true,
                     }],
                     yAxes: [{
                        ticks: {
                            // Include a dollar sign in the ticks
                          },
                          stacked: true
                     }]
                  },
                   plugins: {
                    datalabels: { 
                        color: '#fff',
						display: function(context) {
							return context.dataset.data[context.dataIndex] > 15;
						},
						font: {
							weight: 'bold'
						},
					}, 
                },
               },
                
            }); 
            
            let fabricSum = 0;
            let trimsSum = 0;
            let fgSum = 0;
            let wipSum = 0;
            chart.data.datasets.forEach((dataset, datasetIndex) => {
                fabricSum = parseFloat(fabricSum) + parseFloat(dataset.data[0]); 
                trimsSum = parseFloat(trimsSum) + parseFloat(dataset.data[1]);
                fgSum = parseFloat(fgSum) + parseFloat(dataset.data[2]);
                wipSum = parseFloat(wipSum) + parseFloat(dataset.data[3]);
            }); 
                 
            var barTitles = [fabricSum, trimsSum, fgSum, wipSum];
            var barWidth = chart.chart.width / chart.data.labels.length;
           
            for (var i = 0; i < barTitles.length; i++) 
            {
                var xPos = chart.chartArea.left + i * barWidth + barWidth / 1.7;
                var yPos = chart.chartArea.top - (-325); 
                
                if(i == 0)
                {
                    $('<div class="bar-title">' + barTitles[i] + '</div>').css({
                        position: 'absolute',
                        left: 94.3041,
                        top: yPos + 'px',
                        transform: 'translateX(-50%)',
                        fontWeight: 900,
                    }).appendTo($('#ctx').parent()); 
                }
                else if(i == 1)
                {
                     $('<div class="bar-title">' + barTitles[i] + '</div>').css({
                        position: 'absolute',
                         left: 194.3041,
                        top: yPos + 'px',
                        transform: 'translateX(-50%)',
                        fontWeight: 900,
                    }).appendTo($('#ctx').parent()); 
                }
                else if(i == 2)
                {
                     $('<div class="bar-title">' + barTitles[i] + '</div>').css({
                        position: 'absolute',
                         left: 294.3041,
                        top: yPos + 'px',
                        transform: 'translateX(-50%)',
                        fontWeight: 900,
                    }).appendTo($('#ctx').parent()); 
                }
                else if(i == 3)
                {
                     $('<div class="bar-title">' + barTitles[i] + '</div>').css({
                        position: 'absolute',
                         left: 394.3041,
                        top: yPos + 'px',
                        transform: 'translateX(-50%)',
                        fontWeight: 900,
                    }).appendTo($('#ctx').parent()); 
                }
                else
                {
                    
                     $('<div class="bar-title">' + barTitles[i] + '</div>').css({
                        position: 'absolute',
                        left: xPos + 'px',
                        top: yPos + 'px',
                        transform: 'translateX(-50%)',
                        fontWeight: 900,
                    }).appendTo($('#ctx').parent()); 
                }
               
            }
            
            
            // var oilCanvas = document.getElementById("ctx1");

            // Chart.defaults.global.defaultFontFamily = "Lato";
            // Chart.defaults.global.defaultFontSize = 18;
            
            // var oilData = {
            //     labels: [
            //       'Moving', 'Non Moving'
            //     ],
            //     datasets: [
            //         {
            //             data: [{{$totalMoving}},{{$totalNonMoving}}],
            //             backgroundColor: [
            //                 "orange",
            //                 "red",
            //             ]
            //         } 
            //     ],
                   
            // }
            // var options = {
            //     series: {
            //         pie: {
            //             show: true,
            //             label: {
            //                 formatter: function (label, series) {
            //                     var labelName = label;
            //                     var labelNumber = series.data[0][1];
            //                     var labelPercent = series.percent;
            //                     var labelContent = label + '<br/>' + labelNumber + ' (' + labelPercent + '%)';
            //                     return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + labelContent + '</div>';
            //                 },
            //             }
            //         }
            //     },
            //     legend: {
            //         show: false
            //     },
            //     grid: {
            //         hoverable: false,
            //         clickable: true
            //     },
            //     colors: ["#94BEE0", "#D9DD81", "#E67A77","#747ddd","#669aaa","#aa7765"],
            //     tooltip: true,
            //     tooltipOpts: {
            //         defaultTheme: false
            //     }
            // };
            // var pieChart = new Chart(oilCanvas, {
            //   type: 'pie',
            //   data: oilData,
            // });

            
            //  var data1 = {
            //         labels: ['Moving', 'Non Moving'],
            //         datasets: [{
            //             data: [{{$totalNonMoving}},{{$totalNonMoving}}],
            //             backgroundColor: ['blue', 'orange']
            //         }]
            //     };
            
            //     // Get the canvas element
            //     var ctx3 = document.getElementById('ctx1').getContext('2d');
            
            //     // Create the pie chart
            //     var pieChart = new Chart(ctx3, {
            //         type: 'pie',
            //         data: data1
            //     });
            // var chart1 = new Chart(ctx1, {
            //   type: 'pie',
            //   data: {
            //       labels: ['Moving', 'Non Moving'],

            //       datasets: [{
            //          label: 'Moving',
            //          data: [{{$totalMoving}}],
            //          backgroundColor: 'blue'
            //       }, {
            //          label: 'Non Moving',
            //          data: [{{$totalNonMoving}}],
            //          backgroundColor: 'orange'
            //       }]
            //   }, 
 
            // });
            
           
    });
    
                 
 window.onload=function(){ 
 var data2 = [{
   labels: ['Moving', 'Non Moving'],
   data: [{{$totalMoving}},{{$totalNonMoving}}],
   backgroundColor: [
     "orange",
     "red",
   ],
   borderColor: "#fff",
 }];
 var options = {
      legend: {
        display: true,
      },
   tooltips: {
     enabled: false
   },
   plugins: {
     datalabels: {
       formatter: (value, ctx) => {
         let datasets = ctx.chart.data.datasets;
         if (datasets.indexOf(ctx.dataset) === datasets.length - 1) {
           let sum = datasets[0].data.reduce((a, b) => a + b, 0);
           console.log(datasets[0].data[0]);
          let percentage = 0;
          if(datasets[0].data[0] == value)
          {
              percentage = '  Moving \n'+value+' ('+Math.round((value / sum) * 100) + '%)';
          }
          else
          {
              percentage = '  Non-Moving \n'+value+' ('+Math.round((value / sum) * 100) + '%)';
          }
            
           return percentage;
         } else {
           return percentage;
         }
       },
       color: '#fff',
     }
   },
 };
 var ctx5 = document.getElementById("ctx1").getContext('2d');
 var myChart = new Chart(ctx5, {
   type: 'pie',
   data: {
     datasets: data2
   },
   options: options,
   
   
 });
    }       
           
    function LoadProduction(curDate)
    {
             
       $.ajax({
            dataType: "json",
            type: "GET",
            data: {'curDate':curDate},
            url: "{{ route('loadERPProductionData') }}",
            success: function(data)
            {
                $('#tbodyProduction').html(data.html);
            }
        });
    }
    
    $(document).on('click','#invent',function(){
        $("#inventory").removeClass('hide');
        $("#production").addClass('hide');
        $("#capacity_planning").addClass('hide');
        $("#costing_ocr_compression").addClass('hide');
        $("#hourly_production").addClass('hide');
        $("#inventory1").addClass('hide');
        
        //  $.ajax({
        //     dataType: "json",
        //     type: "GET",
        //     data: {'table_head':10},
        //     url: "{{ route('loadERPInventoryData') }}",
        //     success: function(data)
        //     {
        //         $("#fabMove").html(data.fabmoving);
        //         $("#fabNonMove").html(data.fabnon_moving);
        //         $("#fabNonTotal").html(data.fabtotal);
        //         $("#trimMove").html(data.trimmoving);
        //         $("#trimNonMove").html(data.trimnon_moving);
        //         $("#trimTotal").html(data.trimtotal);
        //         $("#fgMove").html(data.fgmoving);
        //         $("#fgNonMove").html(data.fgnon_moving);
        //         $("#fgTotal").html(data.fgtotal);
        //         $("#WIPMove").html(data.WIPmoving);
        //         $("#WIPNonMove").html(data.WIPnon_moving);
        //         $("#WIPTotal").html(data.WIPtotal);
        //         var totalInventoryMove = parseFloat(data.fabmoving) + parseFloat(data.trimmoving) + parseFloat(data.fgmoving) + parseFloat(data.WIPmoving);
        //         var totalInventoryNonMove = parseFloat(data.fabnon_moving) + parseFloat(data.trimnon_moving) + parseFloat(data.fgnon_moving);
        //         var totalInventoryTotal = parseFloat(data.fabtotal) + parseFloat(data.trimtotal) + parseFloat(data.fgtotal) + parseFloat(data.WIPtotal);
                
        //         $('#totalInventoryMove').html(totalInventoryMove.toFixed(2));
        //         $('#totalInventoryNonMove').html(totalInventoryNonMove.toFixed(2));
        //         $('#totalInventoryTotal').html(totalInventoryTotal.toFixed(2));
                
        //         $("#openOrderPCS").html(data.openOrderPCS);
        //         $("#openOrderMin").html(data.openOrderMin);
        //         $("#openOrdertotal").html(data.openOrdertotal);
                
        //         $('#bookingQty').html(data.bookingQty);
        //         $('#bookingMin').html(data.bookingMin);
        //         $('#bookingValue').html(data.bookingValue);
        //     }
        //  });
    });
    $(document).on('click','#prod',function(){
        $("#inventory").addClass('hide');
        $("#production").removeClass('hide');
        $("#capacity_planning").addClass('hide');
        $("#costing_ocr_compression").addClass('hide');
        $("#hourly_production").addClass('hide');
        $("#inventory1").addClass('hide');
          
    
        
    });
    $(document).on('click','#invent1',function(){
        $("#inventory1").removeClass('hide');
        $("#inventory").addClass('hide');
        $("#production").addClass('hide');
        $("#capacity_planning").addClass('hide');
        $("#costing_ocr_compression").addClass('hide');
        $("#hourly_production").addClass('hide');
         
    });
    $(document).on('click','#capacity',function(){
        $("#inventory").addClass('hide');
        $("#inventory1").addClass('hide');
        $("#production").addClass('hide');
        $("#capacity_planning").removeClass('hide');
        $("#costing_ocr_compression").addClass('hide');
        $("#hourly_production").addClass('hide');
    });
    $(document).on('click','#costing',function(){
        $("#inventory").addClass('hide');
        $("#inventory1").addClass('hide');
        $("#production").addClass('hide');
        $("#capacity_planning").addClass('hide');
        $("#costing_ocr_compression").removeClass('hide');
        $("#hourly_production").addClass('hide');
    });
    $(document).on('click','#hourly',function(){
        $("#inventory").addClass('hide');
        $("#inventory1").addClass('hide');
        $("#production").addClass('hide');
        $("#capacity_planning").addClass('hide');
        $("#costing_ocr_compression").addClass('hide');
        $("#hourly_production").removeClass('hide');
    });
    
</script>
@endsection