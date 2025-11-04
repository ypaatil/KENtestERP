@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.0.5/flickity.min.css"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" >
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css" >
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" >
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css" />
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<style>
 .row1{
   padding-bottom:10px;
   }
   .hide
   {
   display:none;
   }
   .show
   {
   display:block; 
   }
   .blurCls
   {
   -webkit-filter: blur(2px);
   }
   
   .slideToggle {
      -webkit-transition: height .3s ease;
      display:none;
    }
    .text-right
    {
        text-align:right;
    }

    .expand
    {
        font-size: 50px;
        position: absolute;
        margin-left: -18px;
        margin-top: -30px;
    } 
    



    .form-inline {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-flow: row wrap;
    flex-flow: row wrap;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}
.mb-sm-0, .my-sm-0 {
    margin-bottom: 0!important;
}
.btn-outline-primary {
    color: #007bff;
    background-color: transparent;
    background-image: none;
    border-color: #007bff;
}

.horizontalMenucontainer .main-header {
    border-bottom: 1px solid #dce7f5;
}
.main-header-left {
    display: flex;
    align-items: center;
}

.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
.mr-sm-2, .mx-sm-2 {
    margin-right: .5rem!important;
}

.tx-16 {
    font-size: 16px;
}

.bg-primary-gradient {
    background-color: #845aec !important;
}

/*.card {*/
/*    border-radius: 7px;*/
/*    box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;*/
/*}*/
.bg-danger-gradient {
    background-color: #657fe4 !important;
}
.navbar-nav {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
}
.main-header {
    margin-bottom: 20px;
    border-bottom: 1px solid #dee4ec;
    box-shadow: 5px 7px 26px -5px #cdd4e7;
}
.horizontalMenucontainer .layout-pin.side-header.sticky-pin {
    position: fixed!important;
}


.bg-success-gradient {
    background-color: #4a9bed !important;
}
.bg-warning-gradient {
    background-color: #1fc3d4 !important;
}
.bg-purple-gradient {
    background-color: #75daa3 !important;
}
.bgorange {
    background-color: #f5be39 !important;
}
.bg-danger-gradient {
    background-color: #657fe4 !important;
}

.counter-icon
{
  position:relative;
}
.counter-icon {
    margin-bottom: 0;
    display: inline-flex;
    width: 4rem;
    height: 4rem;
    padding: 0.5rem;
    /* overflow: hidden; */
    border-radius: 50%;
    text-align: center;
    background: rgb(255 255 255);
    line-height: 42px;
    position: relative;
}
#zoomBtn {
    background-color: white;
    border-radius: 50%;
    padding: 4px;
    margin-top: -13px;
    width: 30px;
    height: 30px;
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
    border: 1px solid #ffffff;
}
#mapcontainer,
#barChartContainer,
#lineChartContainer,
#pieChartContainer,
#tableContainer {
    transition: opacity 0.5s ease;
}
.sidebar-open {
    box-shadow: rgb(59 91 124) -4px -2px 60px 0px !important;
}
.sidebar-open {
    -webkit-animation: bounce-in-right 1.1s both;
    animation: bounce-in-right 1.1s both;
}
/**
 * ----------------------------------------
 * animation bounce-in-right
 * ----------------------------------------
 */
@-webkit-keyframes bounce-in-right {
    0% {
        -webkit-transform: translateX(600px);
        transform: translateX(600px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
        opacity: 0;
    }

    38% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
        opacity: 1;
    }

    55% {
        -webkit-transform: translateX(68px);
        transform: translateX(68px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
    }

    72% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
    }

    81% {
        -webkit-transform: translateX(32px);
        transform: translateX(32px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
    }

    90% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
    }

    95% {
        -webkit-transform: translateX(8px);
        transform: translateX(8px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
    }

    100% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
    }
}

@keyframes bounce-in-right {
    0% {
        -webkit-transform: translateX(600px);
        transform: translateX(600px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
        opacity: 0;
    }

    38% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
        opacity: 1;
    }

    55% {
        -webkit-transform: translateX(68px);
        transform: translateX(68px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
    }

    72% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
    }

    81% {
        -webkit-transform: translateX(32px);
        transform: translateX(32px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
    }

    90% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
    }

    95% {
        -webkit-transform: translateX(8px);
        transform: translateX(8px);
        -webkit-animation-timing-function: ease-in;
        animation-timing-function: ease-in;
    }

    100% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
        -webkit-animation-timing-function: ease-out;
        animation-timing-function: ease-out;
    }
}

.main-dashboard-header-right {
    display: none;
}

[pointer-events="bounding-box"] {
    display: none;
}

svg .raphael-group-5-background rect {
    stroke: white;
}

svg .raphael-group-4-background rect {
    stroke-width: 0;
}
/* Add this CSS to your stylesheet */

.district {
    transition: transform 0.3s; /* Add a smooth transition effect */
}

.district:hover {
    transform: scale(1.1); /* Scale up the district on hover */
}

.district {
    transition: transform 0.3s;
}

.district:hover {
    transform: scale(1.1);
    z-index: 1; /* Ensures the hovered district is on top */
}
.main-dashboard-header-right .rdiobox span:before {
    top: auto;
    left: 10px;
}
.main-dashboard-header-right .rdiobox span {
    color: white;
}
.btn-icon {
    width: 36px;
    height: 36px;
}
.main-dashboard-header-right .rdiobox span:after {
    top: 13px;
    left: 14px;
    width: 8px;
    height: 8px;
}
.boxDsh .card-body input[type="radio"]:checked,
[type="radio"]:not(:checked) {
    position: absolute;
    left: -9999px;
}

.boxDsh .card-body input[type="radio"]:checked + label,
.boxDsh .card-body input[type="radio"]:not(:checked) + label {
    position: absolute;
    padding-left: 28px;
    cursor: pointer;
    line-height: 20px;
    display: inline-block;
    color: #666;
    left: 6px;
    top: 6px;
}

.tablenew tr th,
.tablenew tr td {
    padding: 7px !important;
}
.boxDsh .card-body input[type="radio"]:checked + label:before,
.boxDsh .card-body input[type="radio"]:not(:checked) + label:before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 18px;
    height: 18px;
    border: 1px solid #ddd;
    border-radius: 100%;
    background: #fff;
    box-shadow: inset 0 7px 10px #7a7cff;
}

.boxDsh .card-body input[type="radio"]:checked + label:after,
.boxDsh .card-body input[type="radio"]:not(:checked) + label:after {
    content: "";
    width: 10px;
    height: 10px;
    background: #025fdb;
    position: absolute;
    top: 4px;
    left: 4px;
    border-radius: 100%;
    -webkit-transition: all 0.2s ease;
    transition: all 0.2s ease;
}

.boxDsh .card-body input[type="radio"]:not(:checked) + label:after {
    opacity: 0;
    -webkit-transform: scale(0);
    transform: scale(0);
}

.boxDsh .card-body input[type="radio"]:checked + label:after {
    opacity: 1;
    -webkit-transform: scale(1);
    transform: scale(1);
}

/*************************/

.boxDsh .card:hover .checkbox-input {
    clip: auto !important;
    -webkit-clip-path: fill-box;
    clip-path: none;
}

.checkbox-input:checked + .checkbox-tile .checkbox-icon,
.checkbox-input:checked + .checkbox-tile .checkbox-label {
    color: #2260ff;
}

.checkbox-input:focus + .checkbox-tile {
    border-color: #2260ff;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1), 0 0 0 4px #b5c9fc;
}

.fs-12 {
    font-size: 13px;
}

.card-dashboard-map-one {
    padding: 4px;
}
.tablecustom td,
.table-bordered th {
    padding: 3px;
}

.checkbox-input:checked + .checkbox-tile:before {
    transform: scale(1);
    opacity: 1;
    background-color: #7016d0;
    border-color: #7016d0;
}

input[type="checkbox"],
input[type="radio"] {
    box-sizing: border-box;
    padding: 0;
    z-index: 999;
    position: absolute;
    left: 4px;
    top: 4px;
    display: block;
    background-color: white;
    height: 20px;
    width: 20px;
    cursor: pointer;
}

.card-price {
    background-color: rgb(0 0 0 / 6%);
    float: left;
    width: 100%;
    padding: 7px 0px;
    border-top: 1px solid rgb(255 255 255 / 21%);
}

.counter-icon::after {
    content: "";
    position: absolute;
    width: 72px;
    height: 72px;
    border: 2px dotted white;
    border-radius: 50%;
    margin: auto;
    text-align: center;
    right: -4px;
    top: -4px;
    z-index: 1;
}

.boxDsh .card-body {
    padding-bottom: 5px;
}

.breadcrumb-header {
    background-color: white;
    padding: 8px 5px;
    border-radius: 5px;
    box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
}

.breadcrumb-header .left-content h2 {
    padding-top: 5px;
    padding-left: 10px;
}
.jsplgrad {
    background-color: #f5713d !important;
}

.card {
    border-radius: 7px;
    box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
}

.boxDsh .col:hover .card {
    /* -webkit-animation: scale-up-ver-top 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
            animation: scale-up-ver-top 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;*/
}

.boxDsh .col:hover .card .counter-icon::after {
    -webkit-animation: rotate-center 8s linear infinite forwards;
    animation: rotate-center 8s linear infinite forwards;
}

/**
 * ----------------------------------------
 * animation scale-up-ver-top
 * ----------------------------------------
 */
@-webkit-keyframes scale-up-ver-top {
    0% {
        -webkit-transform: scaleY(0.4);
        transform: scaleY(0.4);
        -webkit-transform-origin: 100% 0%;
        transform-origin: 100% 0%;
    }

    100% {
        -webkit-transform: scaleY(1);
        transform: scaleY(1);
        -webkit-transform-origin: 100% 0%;
        transform-origin: 100% 0%;
    }
}

@keyframes scale-up-ver-top {
    0% {
        -webkit-transform: scaleY(0.4);
        transform: scaleY(0.4);
        -webkit-transform-origin: 100% 0%;
        transform-origin: 100% 0%;
    }

    100% {
        -webkit-transform: scaleY(1);
        transform: scaleY(1);
        -webkit-transform-origin: 100% 0%;
        transform-origin: 100% 0%;
    }
}

/**
		* ----------------------------------------
		* animation rotate-center
		* ----------------------------------------
		*/
@-webkit-keyframes rotate-center {
    0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
    }

    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes rotate-center {
    0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
    }

    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

.sortable-list {
    max-width: 100%;
    display: block;
}

.sortable-item {
    color: rgba(0, 0, 0, 0.87);
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    user-select: none;
}

#sortable-horizontal .sortable-item {
    width: 100%;
    border-bottom: none;
}

#sortable-grid {
    border: none;
    padding: 0;
    margin: 0;
}

#sortable-grid .sortable-item {
    text-align: center;
}

#sortable-condition-1,
#sortable-condition-2 {
    max-width: 100%;
    display: block;
}

/*****************/
.tooltip {
    position: absolute;
    backdrop-filter: saturate(180%) blur(20px);
    top: 0;
    font-size: 14px;
    background: rgb(255 255 255 / 65%);
    color: #ffffff;
    padding: 5px 8px;
    border-radius: 5px;
    box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.tooltip::before {
    position: absolute;
    content: "";
    height: 8px;
    width: 8px;
    background: rgb(255 255 255 / 31%);
    bottom: -3px;
    left: 50%;
    transform: translate(-50%) rotate(45deg);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.boxDsh .card:hover .tooltip {
    top: 94px;
    left: 5px;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    color: #0267e8;
}
.totalBnf {
    background-color: rgb(255 255 255 / 19%);
    padding: 6px 8px;
    width: 90%;
    margin: auto;
    text-align: center;
    display: block;
    border-radius: 10px;
    color: white;
    border: 1px solid rgb(0 0 0 / 16%);
    font-size: 12px;
    margin: 4px auto;
}

.boxDsh .card {
    transition: opacity 0.2s;
    opacity: 1;
}

.boxDsh .card:not(.highlight) {
    opacity: 1;
}
.breadcrumb-header {
    background-color: white;
    padding: 8px 5px;
    border-radius: 5px;
    box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
  margin: 20px 0px;
}
.boxDsh h4.counter
{
  font-size: 16px;
}

.boxDsh .highlighted {
    transform: scale(1.1);
    box-shadow: rgb(69 69 255 / 48%) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px;
    border: none;
    transition: 0.2s;
}

.zoomDV {
    overflow-x: hidden;
    overflow-y: auto;
    z-index: 1072;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    transition: opacity 0.15s linear;
    background-color: rgb(0 0 0 / 49%);
    max-width: 100%;
    backdrop-filter: saturate(180%) blur(10px);
}

.zoomDV .card {
    -webkit-transform: translate(0, 0);
    transform: translate(0, 0);
    max-width: 55%;
    margin: 1.75rem auto;
    position: relative;
    width: auto;
}

$font-family-primary: 'Montserrat', sans-serif;
$font-family-secondary: 'Roboto Slab', serif;

body {
  background-color: #000;
}

.overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient( to bottom, transparentize(#0E1D33, 0.2) , transparentize(#0E1D33, 0.8) );
}

.hero-slider {
  width: 100%;
  height: 150vh;
  overflow: hidden;

  .carousel-cell {
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;

    .inner {
      position: relative;
      top: 20%;
      transform: translateY(-20%);
      color: white;
      text-align: center;

      .subtitle {
        font-family: $font-family-secondary;
        font-size: 2.2rem;
        line-height: 1.2em;
        font-weight: 200;
        font-style: italic;
        letter-spacing: 3px;
        color: rgba(255,255,255,0.5);
        margin-bottom: 5px;
      }

      .title {
        font-family: $font-family-primary;
        font-size: 3rem;
        line-height: 1.2em;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 40px;
      }
      .btn{
        border: 1px solid #fff;
        padding: 14px 18px;
        text-transform: uppercase;
        font-family: $font-family-primary;
        font-size: 0.8rem;
        letter-spacing: 3px;
        color: #fff;
        text-decoration: none;
        transition: all .2s ease;
        &:hover{
          background: #fff;
          color: #000;
        }
      }
    }
  }

  .flickity-prev-next-button {
    width: 80px;
    height: 80px;
    background: black;
    &:hover{
      background: pink;
    }
    .arrow{
      fill: white;
    }
  }
  .flickity-page-dots {
    bottom: 30px;
    .dot{
      width: 30px;
      height: 4px;
      opacity: 1;
      background: rgba(255,255,255,0.5);
      border: 0 solid white;
      border-radius: 0;
      &.is-selected{
        background: #ff0000;
        border: 0 solid #ff0000; 
      }
    }
  }
}

.flickity-prev-next-button.previous {
    left: 10px;
    top: 3%;
}
.flickity-prev-next-button.next { 
    top: 3%;
}

table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after,
table.dataTable thead .sorting_asc_disabled:after,
table.dataTable thead .sorting_desc_disabled:after {
  position: absolute;
  bottom: 8px;
  right: 8px;
  display: block;
  font-family: 'Glyphicons Halflings';
  opacity: 0.5;
}
table.dataTable thead .sorting:after {
  opacity: 0.2;
  content: "\e150";
  /* sort */
}
table.dataTable thead .sorting_asc:after {
  content: "\e155";
  /* sort-by-attributes */
}
table.dataTable thead .sorting_desc:after {
  content: "\e156";
  /* sort-by-attributes-alt */
}
.dataTables_scrollBody{
    height:auto!important;
}

 
   
   #openOrderReport thead {
      top: 0;
      position: sticky;
      z-index: 1; 
    }
    
    #openOrderReport tr td:nth-child(1),
    #openOrderReport tr td:nth-child(2),
    #openOrderReport tr td:nth-child(3),
    #openOrderReport tr td:nth-child(4),
    #openOrderReport tr th:nth-child(1),
    #openOrderReport tr th:nth-child(2),
    #openOrderReport tr th:nth-child(3),
    #openOrderReport tr th:nth-child(4){
      position: sticky;
      left: 0;
      background-color: #4a4646!important; 
      color: #fff!important; 
      
    }
    
    #openOrderReport tr th:nth-child(2),
    #openOrderReport tr td:nth-child(2){
      left: 58px;
    }
    #openOrderReport tr th:nth-child(3),
    #openOrderReport tr td:nth-child(3){
      left: 215px;
    }
    
    #openOrderReport tr th:nth-child(4),
    #openOrderReport tr td:nth-child(4){
      left: 280px;
    }
    
    .tbl-res{
      height: 700px;
      max-width: 100vw;
      overflow-x: auto;
      overflow-y: auto;
      position: relative;
      margin-top: 100px;
    }

    #load_div {
        position: fixed;
        z-index: 9999;
        height: 100%;
        width: 100%;
        overflow: visible;
        background:#fff;
        top: 0;
    }

    #loading {
        position: fixed;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        position: fixed;
        top: 50%;
        left: 50%;
        margin-top: -60px; /* Half of the loader's height */
        margin-left: -60px; /* Half of the loader's width */
        
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    /* Hide content when loading */
    /*.content {*/
    /*    display: none;*/
    /*}*/

  
    .hide
    {
        display:none;
    }
 
 #quantitive_inventory td
 {
     color:black!important;
 }
 @media (max-width: 600px) 
 {
    .dashboard_content 
    {
        display: none;
    }
    
    .navbar-header
    {
        background: #703eb385;
    }
  }
  
    body{
         overflow: hidden; /* Prevents vertical scrolling */
    } 
    /* Tab container styles */
    .tab-container {
        width: 100%;
        height: calc(100vh - 80px); /* Adjust based on your header/footer heights */
        margin: -25px 0;
        overflow: hidden; /* Prevents vertical scrolling */
        display: flex;
        flex-direction: column;
    }
    
    /* Tab buttons */
    .tab-buttons {
        display: flex;
        flex-wrap: wrap; /* Allow wrapping for many tabs */
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        overflow-x: auto; /* Allows horizontal scrolling if needed */
        flex-shrink: 0; /* Prevent shrinking */
    }
    
    /* Tab buttons */
    .tab-button {
        flex: 1 1 200px; /* Allow buttons to take up equal space, minimum width 200px */
        text-align: center;
        padding: 15px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        font-weight: bold;
        transition: background 0.4s, box-shadow 0.4s;
        margin: 5px; /* Margin around buttons for spacing */
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }
    
    /* Tab button active state */
    .tab-button.active {
        background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
        color: white;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Tab content */
    .tab-content {
        display: none;
        flex: 1; /* Allow content to grow */
        overflow-y: auto; /* Allows scrolling within the tab content if it exceeds the maximum height */
        padding: 30px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.6s ease-out;
        box-sizing: border-box; /* Ensures padding and border are included in the element's total width and height */
    }
    
    /* Active tab content */
    .tab-content.active {
        display: block;
    }
    
    /* Tab content color styles */
    /*.tab-content.tab1-active {*/
    /*    background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);*/
    /*}*/
    
    /* Additional tab content color styles... (repeat for other tabs) */
    
    /* Responsive iframe container */
    .iframe-container {
        position: relative;
        width: 100%; /* Full width of the tab-content */
        padding-bottom: 47.25%; /* Maintain 16:9 Aspect Ratio */
        height: 0;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }


    
    .iframe-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        border: none;
    }
   
    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .hide
    {
        display:none;
    }
    .hider {
      z-index: 1000;
      height: 40px;
      transform: translateY(-80px);
      background-color: white;
    }
    .footer
    {
        height: 10%;
    }
    
    #playPauseButton
    {
        margin: 6%;
        position: absolute;
        z-index: 1;
        font-size: 40px;
        border: none;
        background: none;
    }
    
     #progressBar 
    {
        width: 100%;
        background-color: #f3f3f3;
        border: 1px solid #ccc;
        height: 30px;
        border-radius: 5px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }
    #progress 
    {
        width: 0;
        height: 100%;
        background-color: #4caf50;
        text-align: center;
        line-height: 30px;
        color: white;
        border-radius: 5px;
        transition: width 0.4s ease;
    }
    #checkmark 
    {
        display: none;
        width: 50px;
        height: 50px;
        margin: 20px auto;
    }
    .checkmark_circle 
    {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4caf50;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark_check 
    {
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4caf50;
        fill: none;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
    }
    @keyframes stroke 
    {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @import 'https://fonts.googleapis.com/css?family=Open+Sans:600,700';
    
    * {font-family: 'Open Sans', sans-serif;}
    
    .rwd-table {
      margin: auto;
      min-width: 300px;
      max-width: 100%;
      border-collapse: collapse;
    }
    
    .rwd-table tr:first-child {
      border-top: none;
      background: #ff000017;
      color: black;
    }
    
    .rwd-table tr {
      border-top: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
      background-color: #f5f9fc;
    }
    
    .rwd-table tr:nth-child(odd):not(:first-child) {
      background-color: #ebf3f9;
    }
    
    .rwd-table th {
      display: none;
    }
    
    .rwd-table td {
      display: block;
    }
    
    .rwd-table td:first-child {
      margin-top: .5em;
    }
    
    .rwd-table td:last-child {
      margin-bottom: .5em;
    }
    
    .rwd-table td:before {
      content: attr(data-th) ": ";
      font-weight: bold;
      width: 120px;
      display: inline-block;
      color: #000;
    }
    
    .rwd-table th,
    .rwd-table td {
      /*text-align: left;*/
    }
    
    .rwd-table {
      color: #333;
      border-radius: .4em;
      overflow: hidden;
    }
    
    .rwd-table tr {
      border-color: #bfbfbf;
    }
    
    .rwd-table th,
    .rwd-table td {
      padding: .5em 1em;
    }
    @media screen and (max-width: 601px) {
      .rwd-table tr:nth-child(2) {
        border-top: none;
      }
    }
    @media screen and (min-width: 600px) {
      .rwd-table tr:hover:not(:first-child) {
        background-color: #d8e7f3;
      }
      .rwd-table td:before {
        display: none;
      }
      .rwd-table th,
      .rwd-table td {
        display: table-cell;
        padding: .25em .5em;
      }
      .rwd-table th:first-child,
      .rwd-table td:first-child {
        padding-left: 0;
      }
      .rwd-table th:last-child,
      .rwd-table td:last-child {
        padding-right: 0;
      }
      .rwd-table th,
      .rwd-table td {
        padding: 1em !important;
      }
    }
    
    
    /* THE END OF THE IMPORTANT STUFF */
    
    /* Basic Styling */
    body {
    /*background: #4B79A1;*/
    /*background: -webkit-linear-gradient(to left, #4B79A1 , #283E51);*/
    /*background: linear-gradient(to left, #4B79A1 , #283E51);        */
    }
    h1 {
      text-align: center;
      font-size: 2.4em;
      color: #f2f2f2;
    }
    .container {
      display: block;
      text-align: center;
    }
    h3 {
      display: inline-block;
      position: relative;
      text-align: center;
      font-size: 1.5em;
      color: #cecece;
    }
    h3:before {
      content: "\25C0";
      position: absolute;
      left: -50px;
      -webkit-animation: leftRight 2s linear infinite;
      animation: leftRight 2s linear infinite;
    }
    h3:after {
      content: "\25b6";
      position: absolute;
      right: -50px;
      -webkit-animation: leftRight 2s linear infinite reverse;
      animation: leftRight 2s linear infinite reverse;
    }
    @-webkit-keyframes leftRight {
      0%    { -webkit-transform: translateX(0)}
      25%   { -webkit-transform: translateX(-10px)}
      75%   { -webkit-transform: translateX(10px)}
      100%  { -webkit-transform: translateX(0)}
    }
    @keyframes leftRight {
      0%    { transform: translateX(0)}
      25%   { transform: translateX(-10px)}
      75%   { transform: translateX(10px)}
      100%  { transform: translateX(0)}
    }
    
    /*
        Don't look at this last part. It's unnecessary. I was just playing with pixel gradients... Don't judge.
    */
    /*
    @media screen and (max-width: 601px) {
      .rwd-table tr {
        background-image: -webkit-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: -moz-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: -o-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: -ms-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
      }
      .rwd-table tr:nth-child(odd) {
        background-image: -webkit-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: -moz-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: -o-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: -ms-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
      }
    }*/

 
   .center-image 
   {
        display: flex;
        justify-content: center;
        align-items: center;
   }
</style>
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">  
@if(isset($chekform) && $chekform->write_access == 1)
<div class="row">
    <div class="col-12">
        <!--<button id="playPauseButton">-->
        <!--    <span id="playButton" class="icon">鈻讹笍</span>-->
        <!--    <span id="pauseButton" class="icon">鈴革笍</span>-->
        <!--</button>-->
        <div class="tab-container">
            <div class="tab-buttons">
                    <div class="tab-button active" data-tab="tab1">Daily Efficiency Report</div> 
                <div class="tab-button" data-tab="tab2">Linewise Efficiency Report</div>
                <div class="tab-button" data-tab="tab3">Stylewise Efficiency</div>
                <div class="tab-button" data-tab="tab4">Operatorwise Efficiency</div>
                <div class="tab-button" data-tab="tab5">Style-wise Table</div>
              
            </div>
            <div id="tab1" class="tab-content active tab1-active"> 
                <div class="iframe-container">
                    <iframe id="powerBiIframe" src="https://app.powerbi.com/view?r=eyJrIjoiNTc2NzRhZjUtOTk1Yi00NGY0LWI2ZjQtZjM3NWVmZDkxZDFkIiwidCI6ImUwNTJhYzU5LTNkN2QtNGY3YS1hYzMyLTk1YmFlOGFlM2UyNyJ9" 
                        allowFullScreen="true"></iframe>  
                </div>
            </div> 
            <div id="tab2" class="tab-content tab2-active"> 
                <div class="iframe-container">
                    <iframe id="powerBiIframe" src="https://app.powerbi.com/view?r=eyJrIjoiZWRmMDNhMWUtYmY3Ny00MjlmLWI2ODAtMzFkMDQwZDNjNzdjIiwidCI6ImUwNTJhYzU5LTNkN2QtNGY3YS1hYzMyLTk1YmFlOGFlM2UyNyJ9" 
                        allowFullScreen="true"></iframe>  
                </div>
            </div> 

        <div id="tab3" class="tab-content tab3-active"> 
                <div class="iframe-container">
                    <iframe id="powerBiIframe" src="https://app.powerbi.com/view?r=eyJrIjoiODgzNWYzNmEtNDIwOC00MTc3LWFiNWMtZWQyYjljZTBhY2YzIiwidCI6ImUwNTJhYzU5LTNkN2QtNGY3YS1hYzMyLTk1YmFlOGFlM2UyNyJ9" 
                        allowFullScreen="true"></iframe>  
                </div>
            </div> 
            
                    <div id="tab4" class="tab-content tab4-active"> 
                <div class="iframe-container">
                    <iframe id="powerBiIframe" src="https://app.powerbi.com/view?r=eyJrIjoiZjYwNmViOTQtYTBmMS00OWNiLWEwZDMtNWQyNTI2ZTdlY2Y5IiwidCI6ImUwNTJhYzU5LTNkN2QtNGY3YS1hYzMyLTk1YmFlOGFlM2UyNyJ9" 
                        allowFullScreen="true"></iframe>  
                </div>
            </div> 
            
                    <div id="tab5" class="tab-content tab5-active"> 
                <div class="iframe-container">
                    <iframe id="powerBiIframe" src="https://app.powerbi.com/view?r=eyJrIjoiMmM4ODFjMDEtNTZmMi00OWIyLWIwODQtNTNkOTVkYTJkYjE1IiwidCI6ImUwNTJhYzU5LTNkN2QtNGY3YS1hYzMyLTk1YmFlOGFlM2UyNyJ9" 
                        allowFullScreen="true"></iframe>  
                </div>
            </div> 

            
            
        </div>
    </div>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.0.5/flickity.pkgd.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
<script>
        // let currentTabIndex = 0;
        // const tabs = $('.tab-button');
        // const tabCount = tabs.length;
        // const interval = 15000; // Time in milliseconds (20 seconds)
        // let intervalId;
        // let isPaused = false;
        
        // function autoClickTabs() {
        //     if (!isPaused) {
        //         tabs.eq(currentTabIndex).trigger('click');
        //         currentTabIndex = (currentTabIndex + 1) % tabCount; // Cycle through the tabs
        //     }
        // }
        
        // function startAutoClicking() {
        //     intervalId = setInterval(autoClickTabs, interval);
        // }
        
        // function pauseAutoClicking() {
        //     isPaused = true;
        // }
        
        // function playAutoClicking() {
        //     isPaused = false;
        // }
        
        // Start the auto-clicking after the document is ready
        // $(document).ready(function () {
          
        // });


        $(document).ready(function() {
            $('.tab-button').click(function() {
                var tab_id = $(this).attr('data-tab');
                console.log(tab_id);
                $('.tab-button').removeClass('active');
                $(this).addClass('active');
    
                $('.tab-content').removeClass('active');
                $("#" + tab_id).addClass('active');
            });
            // $('#playButton').show();
            // $('#pauseButton').hide();
            // startAutoClicking();

            // // Toggle function
            // $('#playPauseButton').on('click', function() {
            //     $('#playButton').toggle();  // Toggles the play button
            //     $('#pauseButton').toggle(); // Toggles the pause button
            // });
            // // Example usage: Pause and play buttons
            // $('#pauseButton').on('click', pauseAutoClicking);
            // $('#playButton').on('click', playAutoClicking);
            
            // let currentTabIndex = 0;
            // const tabs = $('.tab-button');
            // const tabCount = tabs.length;
            // const interval = 20000; // Time in milliseconds (3 seconds)
        
            // function autoClickTabs() {
            //     tabs.eq(currentTabIndex).trigger('click');
            //     currentTabIndex = (currentTabIndex + 1) % tabCount; // Cycle through the tabs
            // }
        
            // // Start the auto-clicking after the document is ready
            // setInterval(autoClickTabs, interval);
             
    
        });
 
    //   window.onload = function() 
    //     {
    //         if (window.innerWidth <= 600) 
    //         {
    //           window.location.href = "https://kenerp.com/BuyerCosting";  
    //         }
    //     };
      
    //     function html_table_to_excel(type,orderTypeId)
    //     {
    //         var data = document.getElementById('tab_'+orderTypeId);
    
    //         var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
    
    //         XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
        
    //             XLSX.writeFile(file, 'Order Booking Report.' + type);
    //     }
         
        $( document ).ready(function() 
        {
          $('#starting').removeClass('hide');
        //   quantitive_inventory();
        // //   GetTableData(); 
        //   GetBookingData(1);
        //   GetBookingData1(1); 
        //   GetMontlyBudgetProductionReport();
        //   OpenOrderReports(); 
        //   GetWorkInProgress();
        //   GetInventoryReportMovingNonMovingIframe();
             var dataTable = $("#example").DataTable({
                "order": [[4, "desc"]],  
                "iDisplayStart ": 14,  
                "iDisplayLength": 14  
            });
            
             dataTable.on("order.dt search.dt", function () {
                dataTable.column(0, { search: "applied", order: "applied" }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
       });
       
       function GetWorkInProgress()
       { 
            $('#waitingGif').show();
            var WorkProgressDate = $("#WorkProgressDate").val();
            $.ajax({
            dataType: "json",  
            data : { 'WorkProgressDate' : WorkProgressDate},
            url: "{{ route('WorkInProgressStatusList') }}", 
            success: function (data) 
            {
                var iframeContent = data.html;
                var iframe = $('<iframe>', {
                    src: 'data:text/html;charset=utf-8,' + encodeURIComponent(iframeContent),
                    width: 1161,
                    height: 800
                });
                $('#WorkProgressTbl').html(iframe);
                
                  var dataTable = $("#example").DataTable({
                        "order": [[4, "desc"]], 
                        "iDisplayStart ": 14,
                        "iDisplayLength": 14,
                    });
                    dataTable.on("order.dt search.dt", function () {
                        dataTable.column(0, { search: "applied", order: "applied" }).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();
                    $('#waitingGif').hide();
            },
            error: function (xhr, status, error) 
            {
                console.error("AJAX Error:", status, error);
                $('#waitingGif').hide();
            }
        });
       }
       
    //   function GetInventoryReportMovingNonMovingIframe()
    //   {  
    //         $.ajax({
    //         dataType: "json",   
    //         url: "{{ route('InventoryReportMovingNonMovingIframe') }}", 
    //         success: function (data) 
    //         { 
    //             $('#InventoryReportTbl').html(data.html);
                
    //             var dataTable = $("#example").DataTable({
    //                     "order": [[4, "desc"]], 
    //                     "iDisplayStart ": 14,
    //                     "iDisplayLength": 14,
    //             });
    //             dataTable.on("order.dt search.dt", function () {
    //                     dataTable.column(0, { search: "applied", order: "applied" }).nodes().each(function (cell, i) {
    //                         cell.innerHTML = i + 1;
    //                     });
    //             }).draw();
    //             $('#waitingGif').hide();
    //         },
    //         error: function (xhr, status, error) 
    //         {
    //             console.error("AJAX Error:", status, error);
    //             $('#waitingGif').hide();
    //         }
    //     });
    //   } 
       
      function quantitive_inventory()
      { 
           
             LoadFabricQuantitiveReport();
             LoadTrimsQuantitiveReport();
             LoadWIPQuantitiveReport();
             LoadFGQuantitiveReport();
      }
      
          
        
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
       
         $('button[type="submit"]').click(function(e){
            $('#load_div').removeClass('hide');
            $('#loading').removeClass('hide');
        });
          
         $(window).on('load', function() {
          //$('#content').removeClass('hide');
            // Show loading spinner/message
            $('#load_div').addClass('hide');
            $('#loading').addClass('hide');
            
    
            // Hide loading spinner/message once content is fully loaded
            $('#content').on('load', function() {
                
                  $('#content').removeClass('hide');
                  $('#starting').removeClass('hide');
                //   GetTableData();
                  GetBookingData(1);
                  GetBookingData1(1);  
                  GetMontlyBudgetProductionReport();
                  OpenOrderReports();
                  
                    var dataTable = $('#monthly_budget0').DataTable({
                        "order": [[8, "desc"]],  
                        "bProcessing": true,
                        "sAutoWidth": false,
                        "bDestroy":true,
                        "sPaginationType": "bootstrap", // full_numbers 
                        "bPaginate": false, //hide pagination
                        "bFilter": false, //hide Search bar
                        "bInfo": false, // hide showing entries 
                        "bSort": true // enable sorting
                        // "scrollY": 700,     
                        // "responsive": true
                    });
                    
                    console.log("DataTable initialized:", dataTable);
                    
                    dataTable.on('order.dt search.dt', function () {
                        console.log("Order/Search event triggered.");
                        dataTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();
                
         
                    var dataTable = $('#monthly_budget2').DataTable({
                        "order": [[7, "desc"]],  
                        "bProcessing": true,
                        "sAutoWidth": false,
                        "bDestroy":true,
                        "sPaginationType": "bootstrap", // full_numbers 
                        "bPaginate": false, //hide pagination
                        "bFilter": false, //hide Search bar
                        "bInfo": false, // hide showing entries
                        'iDisplayLength': 100,
                        "scrollY": 700,     
                        "responsive": true,
                         "bSort": true
                   });
                    dataTable.on('order.dt search.dt', function () {
                        dataTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();
                    
            });
            
             
       
       
        });
     
       
        function GetOpenOrderSummaryReport()
        { 
          var OpenOrderTrDate = $("#OpenOrderTrDate").val();
          $.ajax({
              dataType: "json", 
              data : { 'OpenOrderTrDate' : OpenOrderTrDate},
              url: "{{ route('GetOpenOrderSummaryReport') }}", 
              success: function(data)
              {
                  $('#openOrderSummaryTbl').html(data.html);  
                  
              },
          });
        }
        
       
      $(function()
      {
          $('footer').hide();
        //   var carousel = document.querySelector('[data-carousel]');
        //   var slides = document.getElementsByClassName('carousel-cell');
        //   var flkty = new Flickity(carousel, options);
        
       
        //   var slideIndex = 1;  
        //   flkty.select(slideIndex);
      });
     
    
       $(document).on("click","#productionDetails",function(e) 
       { 
            var cls = $("#tbl_order_Status").attr('class'); 
            if(cls == 'table hide')
            {
                $('#tbl_order_Status').removeClass('hide');
            }
            else
            {
                $('#tbl_order_Status').addClass('hide');
            }
        });
        
        $(document).on("click","#workInProgressStatus",function(e) 
        { 
            var cls = $("#workProgressDiv").attr('class'); 
            if(cls == 'col-lg-6 hide')
            {
                $('#workProgressDiv').removeClass('hide');
            }
            else
            {
                $('#workProgressDiv').addClass('hide');
            }
        });
        
        function collapseDiv(row)
        { 
            var current = $(row).parent().parent().next('tr').attr('class'); 
         
            if($(row).html() == "-")
            {
                $("."+current).children().slideUp();
                $(row).html("+");
            }
            else
            {
                $("."+current).children().slideDown();
                $(row).html("-");
            }
        }
    
    //   function GetTableData()
    //   {
    //       $.ajax({
    //           dataType: "json",
    //           url: "{{ route('AllDataMDDashboard1') }}",
    //           beforeSend: function() {
    //           $('#loader1').show();
    //           $('table #tbl1').removeClass('show').addClass('hide');
    //           },
    //           complete: function(){
    //           $('#loader1').hide();
    //           $('table #tbl1').removeClass('hide').addClass('show');
    //           },
    //           success: function(data)
    //           {
    //               $('#mainData').html(data.html); 
    //               $(".minus").each(function(){
    //                   collapseDiv($(this));
    //               });
                  
    //           }
    //       });
    //   }
       
       function GetBookingData(row)
       {
          $.ajax({
              dataType: "json", 
              data : { 'job_status_id' : row},
              url: "{{ route('loadBookingSummary') }}", 
              beforeSend: function() {
                 $("#booking_loader2").removeClass('hide');
              },
              success: function(data)
              {
                  $('#bookingTbody').html(data.html);
              },
              complete: function(){
                $("#booking_loader2").addClass('hide');
              },
          });
       }
        
       function GetBookingData1(row)
       {
          $.ajax({
              dataType: "json", 
              data : { 'job_status_id' : row},
              url: "{{ route('loadJobWorkBookingSummary') }}", 
              beforeSend: function() {
                 $("#booking_loader1").removeClass('hide');
              },
              success: function(data)
              {
                  $('#bookingTbody1').html(data.html);
              },
              complete: function(){
                $("#booking_loader1").addClass('hide');
              },
          });
       }
       
        
         
       function GetMontlyBudgetProductionReport()
       {
          var fromDate =$("#ProdFromDate").val(); 
          var toDate = $("#ProdTDate").val();
          $.ajax({
              dataType: "json", 
              data : { 'fromDate' : fromDate,'toDate': toDate},
              url: "{{ route('GetMontlyBudgetProductionReport') }}", 
              success: function(data)
              {        
                    $('#MBtbl').html(data.html);
                    $('#MBVendortbl').html(data.html1);
                 
                //   $('#monthly_budget').dataTable().api().page.len( -1 ).draw(); 
                //   $('#monthlyBudgetTfootProduction1').html(data.html1); 
             
              },
          }); 
       } 
    
       function OpenOrderReports()
       { 
          var OpenOrderTrDate =$("#OpenOrderTrDate").val();  
          
          $.ajax({
              dataType: "json",  
              data : { 'OpenOrderTrDate' : OpenOrderTrDate},
              url: "{{ route('GetOpenOrderReport') }}", 
              success: function(data)
              { 
                   $('#OpenOrderReportTbody').html(data.html); 
                   $('#OpenOrderReportTbody1').html(data.html1); 
            
                    
                        
                   $(document).ready(function() 
                   {
                        var dataTable = $('#openOrderReport').DataTable({
                            "order": [[13, "desc"]],  
                            "bProcessing": true,
                            "sAutoWidth": false,
                            "bDestroy":true,
                            "sPaginationType": "bootstrap", // full_numbers 
                            "bPaginate": false, //hide pagination
                            "bFilter": false, //hide Search bar
                            "bInfo": false, // hide showing entries 
                            // "scrollY": 700,     
                            // "responsive": true
                        });
                         dataTable.on('order.dt search.dt', function () {
                            dataTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                                cell.innerHTML = i + 1;
                            });
                        }).draw();
                    });
                      
                    
              },
          });
          
          GetOpenOrderSummaryReport();
       }
        
        
       function refreshData()
       {
              
          $.ajax({
              dataType: "json",
              url: "{{ route('refreshData') }}",
              beforeSend: function(e, xhr, settings) 
              {
                 var percentage = 0;
       
                  var timer = setInterval(function(){
                     percentage = percentage + 20;
                     //progress_bar_process(percentage, timer);
                  }, 1000);
                    
                  var counter = 0;
                  var timer = 0;
                  setInterval(function () {
                    var t = '';
                    if(counter < 60)
                    {
                       t = 'Seconds';
                       $('#counter').html(counter+' '+t);
                       if(timer > 0)
                       {
                            $('#counter').html(timer+' Minutes '+counter+' '+t);
                       }
                    }
                    else
                    {
                       t = 'Minutes';
                       timer++;
                       $('#counter').html(timer+' '+t);
                       counter = 0;
                    }
                    ++counter;
                  }, 1000);
       
              },
              complete: function()
              {
                   $.ajax({
                      dataType: "json",
                      url: "{{ route('inventoryStatusMDDashboard') }}",
                      beforeSend: function(e, xhr, settings) 
                      {
                         var percentage = 0;
               
                          var timer = setInterval(function(){
                             percentage = percentage + 20;
                             //progress_bar_process(percentage, timer);
                          }, 1000);
                            
                          var counter = 0;
                          var timer = 0;
                          setInterval(function () {
                            var t = '';
                            if(counter < 60)
                            {
                               t = 'Seconds';
                               $('#counter').html(counter+' '+t);
                               if(timer > 0)
                               {
                                    $('#counter').html(timer+' Minutes '+counter+' '+t);
                               }
                            }
                            else
                            {
                               t = 'Minutes';
                               timer++;
                               $('#counter').html(timer+' '+t);
                               counter = 0;
                            }
                            ++counter;
                          }, 1000);
               
                      },
                   });
                //   GetTableData();
                  $('#starting').addClass('hide');
                  var d = new Date();
                  var Hours = "";
                  var Minutes = "";
                  var Seconds = "";
                  
                  if(d.getHours() < 10 )
                  {
                       Hours = "0"+d.getHours();
                  }
                  else
                  {
                        Hours = d.getHours();
                  }
                  if(d.getMinutes() < 10)
                  {
                       Minutes = "0"+d.getHours();
                  }
                  else
                  {
                       Minutes = d.getMinutes();
                  }
                  
                  if(d.getSeconds() < 10)
                  {
                       Seconds = "0"+d.getSeconds();
                  }
                  else
                  {
                       Seconds = d.getSeconds();
                  }
                  
                  var time = Hours + ":"+ Minutes + ":" + Seconds;
               
                  const timeString = time;
                  const timeString12hr = new Date('1970-01-01T' + timeString + 'Z').toLocaleTimeString('en-US',
                      {
                          timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'
                      }
                    );
       
                 $('#last_updated_time').html("Last Synchronization : "+timeString12hr);
              },
              success: function(data)
              {
              }
          });
       }
      
       
       $(function() {
          window.ajax_loading = false;
          $.hasAjaxRunning = function() 
          {
              return window.ajax_loading;
          };
          $(document).ajaxStart(function() {
              $('#refreshBtn').attr('disabled','disabled');
              $('#refreshImg').addClass('blurCls');
              $('#waiting').removeClass('hide');
              $('#starting').addClass('hide');
              window.ajax_loading = true;
          });
          $(document).ajaxStop(function() 
          {
              $('#refreshBtn').removeAttr('disabled');
              $('#refreshImg').removeClass('blurCls');
              $('#waiting').addClass('hide');
              $('#starting').removeClass('hide');
              window.ajax_loading = false;
          });
        
       });
       
    // LoadFabricInventoryMovingNonMovingReport();
    // LoadTrimsInventoryMovingNonMovingReport();
    // LoadFGInventoryMovingNonMovingReport();
    
    var totalRequests = 4;  
    var completedRequests = 0;
    
    // function html_table_to_excel(type)
    // {
    //     var data = document.getElementById('tbleData');

    //     var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

    //     XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

    //     XLSX.writeFile(file, 'INVENTORY MOVING/NON-MOVING REPORT.' + type);
    // }

    // const export_button = document.getElementById('export_button');

    // export_button.addEventListener('click', () =>  {
    //     html_table_to_excel('xlsx');
    // });
       
   
    function updateProgressBar() 
    {
            var percentage = Math.round((completedRequests / totalRequests) * 100);
            $('#progress').css('width', percentage + '%').text(percentage + '% ...Loading');
 
            if (percentage === 100) {
                $('#progressBar').fadeOut('slow', function() {
                    $('#checkmark').fadeIn('slow');
                });
            }
    }
    function LoadFabricInventoryMovingNonMovingReport()
    {
        var fin_year_id = 5;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFabricInventoryMovingNonMovingReport') }}",
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
                $("#tablebodyInventory").append(data.html);
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            },
            error: function (error) 
            {
            }
        });
    }
    
    function LoadTrimsInventoryMovingNonMovingReport()
    {
        var fin_year_id = 5;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadTrimsInventoryMovingNonMovingReport') }}",
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
                $("#tablebodyInventory").append(data.html);
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            },
            error: function (error) 
            {
            }
        });
    }
    
    function LoadFGInventoryMovingNonMovingReport()
    {
        var fin_year_id = 5;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFGInventoryMovingNonMovingReport') }}",
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
                $("#tablebodyInventory").append(data.html);
                LoadWIPInventoryMovingNonMoving();
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            },
            error: function (error) 
            {
            }
        });
    }
    
         function LoadWIPInventoryMovingNonMoving() {
            var fin_year_id = 5;
            $.ajax({
                dataType: "json",
                data: { 'fin_year_id': fin_year_id },
                url: "{{ route('LoadWIPInventoryMovingNonMoving') }}",
                beforeSend: function() {
                    //$("#sync").attr('disabled','disabled');
                },
                complete: function(data) {
                    // $("#sync").removeAttr('disabled');
                    // setTimeout(function() 
                    // { 
                    //     $(".alert-success").addClass('hide'); 
                        
                    // }, 2500);
                },
                success: function(data) {
                    // Assuming data.html contains the HTML for table rows
                    // Append the HTML to the table body
                    $("#tbl tbody").append(data.html);
                    
        
                    // Call any additional functions needed after appending data
                    calculateGrandTotal();
                    completedRequests++;
                    updateProgressBar();
                },
                error: function(error) {
                    console.error('Error loading data:', error);
                }
            });
        }

    $(document).ready(function() 
    {
       setTimeout(function() 
       {
            try {
                $('#tbl').DataTable({
                    dom: 'Bfrtip', // B for Buttons
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            } catch (error) {
                // Handle or ignore the error as needed
                console.error("An error occurred while initializing the DataTable:", error);
            }
        }, 60000);
    });

    
   function calculateGrandTotal() 
   {
        var trims = $(".trims_total_value");
        var fabric = $(".fabric_total_value");
        var FG = $(".FG_total_value");
        var WIP = $(".WIP_total_value");
       
        var GrandTotalHtml = '<tr><th></th><th></th><th nowrap>Grand Total</th>';
        for(var i=0; i<=11;i++)
        { 
            var total = 0;
            var trim = 0;
            var fab = 0;
            var fg = 0;
            var wip = 0;
            
            if(trims.length > 0)
            {
                trim = parseFloat($(trims[i]).text().replace(/,/g, ''));
            }
            
            if(fabric.length > 0)
            {
                fab = parseFloat($(fabric[i]).text().replace(/,/g, ''));
            }
            
            if(FG.length > 0)
            {
                fg = parseFloat($(FG[i]).text().replace(/,/g, ''));
            }
            
            if(WIP.length > i)
            {
                wip = parseFloat($(WIP[i]).text().replace(/,/g, ''));
            }
            
            var total = trim + fab + fg +wip;
            
            GrandTotalHtml += '<th></th><th class="grand_total text-right">' + formatCurrency(total.toFixed(0)) + '</th>';
        }
        GrandTotalHtml += '</tr>';
        var NonMovingTotalHtml = '<tr><th></th><th></th><th nowrap>Total Non moving stock</th>';
        
        var Trims_non = $(".Trims_non_moving_value");
        var Fabric_non = $(".Fabric_non_moving_value");
        var FG_non = $(".FG_non_moving_value");
        
        for(var i=0; i<=11;i++)
        { 
            var total_non = 0;
            var trim_non = 0;
            var fab_non = 0;
            var fg_non = 0; 
            
            if(Trims_non.length > 0)
            {
                trim_non = parseFloat($(Trims_non[i]).text().replace(/,/g, ''));
            }
            
            if(Fabric_non.length > 0)
            {
                fab_non = parseFloat($(Fabric_non[i]).text().replace(/,/g, ''));
            }
            
            if(FG_non.length > 0)
            {
                fg_non = parseFloat($(FG_non[i]).text().replace(/,/g, ''));
            } 
            
            var total_non = trim_non + fab_non + fg_non;
            
            NonMovingTotalHtml += '<th></th><th class="total_non text-right">' + formatCurrency(total_non.toFixed(0)) + '</th>';
        }
        NonMovingTotalHtml += '</tr>';
        
        var PerNonMovingTotalHtml = '<tr><th></th><th></th><th nowrap>% of non moving Inventory in Total Inventory</th>';
        var grand_total = $(".grand_total"); 
        var total_non = $(".total_non");
        var WIP_total_value = $(".WIP_total_value");
        console.log(grand_total);
        for(var i=0; i<=11;i++)
        { 
            var per_total_non = 0;
            var grand = 0;
            var tot_non = 0; 
            var WIP_tot_value = 0;
            
            if(grand_total.length > 0 && total_non.length > 0)
            {
                grand = parseFloat($(grand_total[i]).text().replace(/,/g, ''));
                tot_non = parseFloat($(total_non[i]).text().replace(/,/g, ''));
                WIP_tot_value = parseFloat($(WIP_total_value[i]).text().replace(/,/g, ''));
                var grand1 = parseFloat(grand) + parseFloat(WIP_tot_value); 
                
                if(grand1 > 0 && tot_non > 0)
                {  
                    per_total_non = (tot_non/grand1) * 100;
                }
            } 
             
            PerNonMovingTotalHtml += '<th></th><th class="text-right">' + formatCurrency(per_total_non.toFixed(2)) + '</th>';
        }
        PerNonMovingTotalHtml += '</tr>';
        
        var HoldingCostPAHtml = '<tr><th></th><th></th><th nowrap>Holding cost P.A.</th>'; 
        
        for(var i=0; i<=11;i++)
        {  
            HoldingCostPAHtml += '<th></th><th class="text-right">15.00%</th>';
        }
        HoldingCostPAHtml += '</tr>';
        
        var HoldingCostTotalHtml = '<tr><th></th><th></th><th>Holding Cost </th>';
        
        for(var i=0; i<=11;i++)
        { 
            var holding_cost = 0;
            var tot_non1 = 0; 
            
            if(total_non.length > 0)
            {
                tot_non1 = parseFloat($(total_non[i]).text().replace(/,/g, ''));
                holding_cost = tot_non1*0.15/365*31;
            } 
             
            HoldingCostTotalHtml += '<th></th><th class="text-right">' + formatCurrency(holding_cost.toFixed(0)) + '</th>';
        } 
        HoldingCostTotalHtml += '</tr>';
        $("#tablefootInventory").html(GrandTotalHtml+NonMovingTotalHtml+PerNonMovingTotalHtml+HoldingCostPAHtml+HoldingCostTotalHtml);
    } 

    function formatCurrency(amount) 
    {
        var strAmount = amount.toString();
        var parts = strAmount.split(".");
        var wholePart = parts[0];
        var decimalPart = parts.length > 1 ? "." + parts[1] : "";
        
        // Split the whole part into an array of digits
        var wholePartArray = wholePart.split('');
        var formattedWholePart = '';
        
        // Start from the end of the whole part
        var counter = 0;
        for (var i = wholePartArray.length - 1; i >= 0; i--) {
            formattedWholePart = wholePartArray[i] + formattedWholePart;
            counter++;
            if (counter === 3 && i > 0) {
                formattedWholePart = ',' + formattedWholePart;
                counter = 0;
            } else if (counter === 2 && i > 0 && wholePartArray.length - i > 3) {
                formattedWholePart = ',' + formattedWholePart;
                counter = 0;
            }
        }
        
        return formattedWholePart + decimalPart;
    }

</script>
@else
<div class="row center-image">
  <img src="{{ URL::asset('logo/ken.jpeg')}}" alt="Ken Global Designs Pvt. Ltd." style="width: 1056px; height: 520px;">
</div>
@endif
@endsection
