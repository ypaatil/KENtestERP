@extends('layouts.master') 
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.0.5/flickity.min.css"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" >
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css" >
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" >
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css" />
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
@php setlocale(LC_MONETARY, 'en_IN'); @endphp

<style>
  
    #chartdiv123 {
      width: 100%;
      height: 300px;
    }		
    
    #chartdiv1234 {
      width: 100%;
      height: 300px;
    }			
    
    #chartdiv12345 {
      width: 100%;
      height: 300px;
    }	


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
  
    iframe {
        width: 100%;
        height: 800px; /* Adjust height as needed */
        border: none;
    }
        
</style>
 <div class="dashboard_content" id="dashboard_content">
 <div id="load_div"><div id="loading"></div></div>
 <div class="content" id="content">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
               <li class="breadcrumb-item active">Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@php
  if($chekform->write_access == 1)
  {
@endphp
<div class="row">
    
   <div class="col-lg-12">
        <div class="hero-slider" data-carousel id="hero-slider"  style="height: 195vh;">
           
          <!--<div class="carousel-cell">-->
          <!--  <div class="overlay"></div>-->
          <!--  <div class="row inner">-->
          <!--          <label class="mb-4" style="font-size: 25px;color: black;">Performance Indicators</label> -->
          <!--          <div class="col-md-2"></div>-->
          <!--          <div class="col-md-8 table-responsive"> -->
          <!--               <table class="table table-bordered nowrap w-100">-->
          <!--                <thead style="background-color:#f79733; color:white; text-align:left;" >-->
          <!--                   <tr >-->
          <!--                      <th>Performance Indicators</th>-->
          <!--                      <th style="text-align:right;">Today(Last Day)</th>-->
          <!--                      <th style="text-align:right;">Month To Date</th>-->
          <!--                      <th style="text-align:right;">Year To Date</th>-->
          <!--                   </tr>-->
          <!--                </thead>-->
          <!--                <tbody id="mainData">-->
          <!--                </tbody>-->
          <!--             </table>-->
          <!--          </div>-->
          <!--          <div class="col-md-2"></div>-->
          <!--  </div>-->
          <!--</div>-->
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner">  
                <div class="row" style="justify-content:center;">   
                     <iframe src="https://app.powerbi.com/view?r=eyJrIjoiN2VkZTVmOTEtNDQ1Yy00MTI0LThhYzEtMTg5NzhlYzg4ZmVkIiwidCI6ImUwNTJhYzU5LTNkN2QtNGY3YS1hYzMyLTk1YmFlOGFlM2UyNyJ9" 
                     allowFullScreen="true"></iframe>
                </div>  
            </div>
          </div> 
          
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner">  
                <div class="row" style="justify-content:center;">  
                <div class="col-md-9 text-center">  
                    <div class="row">
                       <div class="col-md-3 text-center">
                         <div class="mb-3">
                            <label for="WorkProgressDate" class="form-label">Date</label> 
                            <input type="date" class="form-control" name="WorkProgressDate" id="WorkProgressDate" value="{{date('Y-m-d')}}">
                         </div>
                       </div> 
                       <div class="col-sm-3 text-center">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                              <button class="btn btn-primary w-md" onclick="GetWorkInProgress();">Search</button>
                          </div>
                        </div>  
                    </div> 
                </div>  
                </div> 
                 <div class="col-md-12 text-center">    
                    <label class="mb-4" style="font-size: 25px;color: black;">Work In Progress Status</label> 
                    <div id="waitingGif"><img src="../images/loading.gif" width="800" height="300" class="img-fluid"></div>
                    <div class="table-responsive" id="WorkProgressTbl">
                       <iframe src="" width="1161" height="800"></iframe>
                    </div>
                 </div> 
            </div>
          </div> 
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner">
                <div class="row" style="justify-content:center;">
                <div class="col-md-3"></div>  
                <div class="col-md-9 text-center">  
                    <div class="row mt-5">
                       <div class="col-md-3 text-center">
                         <div class="mb-3">
                            <label for="OrderFromDate" class="form-label">From Date</label> 
                            <input type="date" class="form-control" name="OrderFromDate" id="OrderFromDate" value="{{date('Y-m-01')}}">
                         </div>
                       </div>
                       <div class="col-md-3 text-center">
                         <div class="mb-3">
                            <label for="OrderToDate" class="form-label">To Date</label>
                            <input type="date" class="form-control" name="OrderToDate" id="OrderToDate" value="{{date('Y-m-d')}}">
                         </div>
                       </div> 
                       <div class="col-sm-3 text-center">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                              <button class="btn btn-primary w-md" onclick="GetTotalOrderBookingSummary();">Search</button>
                          </div>
                        </div>  
                    </div> 
                </div>  
                </div> 
               <div class="row"  id="OrderBookingSummeryTables">  
               </div>
            </div>
          </div>
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner">
               <div class="row">
                   <div class="col-lg-6">
                    <label class="mb-4" style="font-size: 25px;color: black;">FOB Order Booking Costing Confirmed</label> 
                      <div class="card">
                         <div class="card-body"style="background: #00ffff30;">
                            <div class="col-md-12"> 
                                <div class="row">  
                                    <div class="col-md-6"><div id="booking_loader2"><img src="/images/loading5.gif" width="50" height="40">  <b> Please wait.....!</b></div></div>
                                    <!--<div class="col-md-1"><button class="btn btn-primary" onclick="GetBookingData(1);">Open</button></div>-->
                                    <!--<div class="col-md-1"><button class="btn btn-danger" onclick="GetBookingData(2);">Close</button></div>-->
                                </div>
                            </div>
                            <div class="table-responsive"> 
                                <table   class="table table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th>Sr.No.</th>
                                        <th>Particular</th>
                                        <th>MTD</th>
                                        <th>YTD (2023-24)</th> 
                                        <th>Open Orders</th>
                                        <th>Close Orders</th> 
                                     </tr>
                                  </thead>
                                  <tbody id="bookingTbody"></tbody>
                               </table>
                            </div>
                         </div>
                      </div>
                   </div> 
                   <div class="col-lg-6">  
                    <label class="mb-4" style="font-size: 25px;color: black;">Jobwork Order Booking Costing Confirmed</label>  
                      <div class="card">
                         <div class="card-body"style="background: #ffc00030;">
                            <div class="col-md-12"> 
                                <div class="row"> 
                                    <div class="col-md-6"><div id="booking_loader1"><img src="/images/loading5.gif" width="50" height="40">  <b> Please wait.....!</b></div></div>
                                    <!--<div class="col-md-1"><button class="btn btn-primary" onclick="GetBookingData(1);">Open</button></div>-->
                                    <!--<div class="col-md-1"><button class="btn btn-danger" onclick="GetBookingData(2);">Close</button></div>-->
                                </div>
                            </div>
                            <div class="table-responsive"> 
                                <table id="costing_table" class="table table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th>Sr.No.</th>
                                        <th>Particular</th>
                                        <th>MTD</th>
                                        <th>YTD (2023-24)</th> 
                                        <th>Open Orders</th>
                                        <th>Close Orders</th> 
                                     </tr>
                                  </thead>
                                  <tbody id="bookingTbody1"></tbody>
                               </table>
                            </div>
                         </div>
                      </div>
                      </div>
                   </div>
            </div>
          </div>
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner">
                <div class="row"> 
                   <div class="col-lg-2"></div>
                   <div class="col-lg-8">
                    <label class="mb-4" style="font-size: 25px;color: black;">Sales (FOB + JOB WORK)</label> 
                      <div class="card">
                         <div class="card-body">
                            <div class="col-md-12"> 
                                <div class="row">  
                                    <div class="col-md-12"> 
                                           <input type="hidden" name="slide_no" value="3">
                                           <div class="row">
                                               <div class="col-md-4">
                                                 <div class="mb-3">
                                                    <label for="fromDate" class="form-label">From Date</label> 
                                                    <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{isset($fdate) ? $fdate : date('Y-m-01')}}">
                                                 </div>
                                               </div>
                                               <div class="col-md-4">
                                                 <div class="mb-3">
                                                    <label for="toDate" class="form-label">To Date</label>
                                                    <input type="date" class="form-control" name="toDate" id="toDate" value="{{isset($tdate) ? $tdate : date('Y-m-d')}}">
                                                 </div>
                                               </div> 
                                               <div class="col-sm-4">
                                                  <label for="formrow-inputState" class="form-label"></label>
                                                  <div class="form-group">
                                                      <button class="btn btn-primary w-md" onclick="GetMontlyBudgetSalesReport();" type="button">Search</button>
                                                  </div>
                                               </div> 
                                           </div> 
                                    </div>
                                </div>
                            </div>
                            <div id="MBTbl1"> 
                            </div>
                            <div id="MBTbl3"> 
                            </div>
                            <div id="MBTbl2"> 
                            </div>
                         </div>
                      </div>
                   </div>
                   <div class="col-lg-4"></div>
            </div>
          </div>
          </div>
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner">
                <div class="row">  
                   <div class="col-lg-2"></div>
                   <div class="col-lg-8">
                    <label class="mb-4" style="font-size: 25px;color: black;">All Production (FOB + Job Work + Stock)</label> 
                      <div class="card">
                         <div class="card-body">
                            <div class="col-md-12"> 
                                <div class="row">  
                                    <div class="col-md-12">  
                                          <input type="hidden" name="slide_no" value="4">  
                                           <div class="row">
                                               <div class="col-md-4">
                                                 <div class="mb-3">
                                                    <label for="ProdFromDate" class="form-label">From Date</label>
                                                    <input type="date" class="form-control" name="fromDate" id="ProdFromDate" value="{{isset($fdate) ? $fdate : date('Y-m-01')}}">
                                                 </div>
                                               </div>
                                               <div class="col-md-4">
                                                 <div class="mb-3">
                                                    <label for="ProdTDate" class="form-label">To Date</label>
                                                    <input type="date" class="form-control" name="toDate" id="ProdTDate" value="{{isset($tdate) ? $tdate : date('Y-m-d')}}">
                                                 </div>
                                               </div> 
                                               <div class="col-sm-4">
                                                  <label for="formrow-inputState" class="form-label"></label>
                                                  <div class="form-group"> 
                                                  <button class="btn btn-primary w-md" onclick="GetMontlyBudgetProductionReport();" type="button">Search</button>
                                                  </div>
                                               </div> 
                                           </div>  
                                    </div>
                                </div>
                            </div>
                            <div  id=MBtbl> 
                            </div>
                         </div>
                      </div>
                   </div>
                    <div class="col-lg-2"></div>
                    <div class="col-lg-2"></div>
                   <div class="col-lg-8 mt-3">
                    <label class="mb-4" style="font-size: 25px;color: black;">All Production (Vendors)</label> 
                      <div class="card">
                         <div class="card-body"> 
                            <div  id=MBVendortbl> 
                            </div>
                         </div>
                      </div>
                   </div>  
                   <div class="col-lg-4"></div>
                </div>
            </div>
          </div>
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner"> 
               <div class="row">
               <div class="col-lg-12">
                 <label class="mb-4" style="font-size: 25px;color: black;">Open Order Report</label> 
                  <div class="card">
                     <div class="card-body">  
                       <input type="hidden" name="slide_no" value="5">  
                        <div class="col-md-12"> 
                            <div class="row">  
                                <div class="col-md-12">   
                                       <div class="row">
                                           <div class="col-md-4">
                                             <div class="mb-3">
                                                <label for="OpenOrderTrDate" class="form-label">Date</label>
                                                <input type="date" class="form-control" name="toDate" id="OpenOrderTrDate" value="{{ isset($tdate) ? $tdate : date('Y-m-d')}}">
                                             </div>
                                           </div> 
                                           <div class="col-sm-4">
                                              <label for="formrow-inputState" class="form-label"></label>
                                              <div class="form-group"> 
                                                <button class="btn btn-primary w-md" onclick="OpenOrderReports();" type="button">Search</button> 
                                              </div>
                                           </div> 
                                       </div> 
                                </div>
                            </div>
                        </div> 
                        <div class="table-responsive"  style="overflow-y: scroll;overflow-x: scroll;"> 
                            <table id="openOrderReport" class="table dt-datatable table-bordered nowrap w-100">
                              <thead>  
                                 <tr style="white-space:nowrap;background: #00000061;color: #fff; border:1px solid;">
                                    <th class="text-center"  style="background: #e2d72b00;color: #fff;border-right: 3px solid;">Sr.</th>
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;">Buyer Name</th> 
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;">Order</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">No. Of</th> 
                                    <th class="text-center"  style="background: blueviolet;color: #fff;border-right: 3px solid;" colspan="3">Order Qty.</th> 
                                    <th class="text-center" style="background: chartreuse;" colspan="3">Produced</th>  
                                    <th class="text-center" style="background: #f57474;color:white;border-right: 3px solid;" colspan="3">Dispatch</th>   
                                    <th class="text-center" style="background: #1861d6;color:#fff;border-right: 3px solid;" colspan="3">B 2 P</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;">Completion</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;">(LM)</th> 
                                 </tr> 
                                 <tr style="white-space:nowrap;background: #00000061;color: #fff; border:1px solid;">
                                    <th class="text-center"  style="background: #e2d72b00;color: #fff;border-right: 3px solid;"> No.</th>
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;"></th> 
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;">Type</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">Orders</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">L Pcs</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">L Min</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;border-right: 3px solid;">Rs. Cr.</th> 
                                    <th class="text-center" style="background: chartreuse;">L Pcs</th> 
                                    <th class="text-center" style="background: chartreuse;">L Min</th> 
                                    <th class="text-center" style="background: chartreuse;border-right: 3px solid;">Rs. Cr.</th>  
                                    <th class="text-center" style="background: #f57474;color:white;">L Pcs</th> 
                                    <th class="text-center" style="background: #f57474;color:white;">L Min</th> 
                                    <th class="text-center" style="background: #f57474;color:white;border-right: 3px solid;">Rs. Cr.</th> 
                                    <th class="text-center" style="background: #1861d6;color:#fff;">L Pcs</th> 
                                    <th class="text-center" style="background: #1861d6;color:#fff;">L Min</th> 
                                    <th class="text-center" style="background: #1861d6;color:#fff;border-right: 3px solid;">Rs. Cr.</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;"> %</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;">% </th> 
                                 </tr>
                              </thead>
                              <tbody id="OpenOrderReportTbody"></tbody> 
                              <tfoot id="OpenOrderReportTbody1"></tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               </div>
            </div>
          </div>
          
        <div class="carousel-cell">
            <div class="overlay"></div>
            <div class="inner"> 
              
               <div class="row">
                <div class="col-lg-2"></div>   
               <div class="col-lg-8">
                 <label class="mb-4" style="font-size: 25px;color: black;">KGDPL - TOTAL SALES DETAILS </label> 
                  <div class="card">
                     <div class="card-body"style="background: #ffc00030;"> 
                        <div class="col-md-12"> 
                            <div class="row">  
                                <div class="col-md-12">   
                                       <div class="row">
                                           <div class="col-md-2">
                                             <div class="mb-4">
                                                @php
                                                    $financialYearData = DB::SELECT("SELECT fin_year_id,fin_year_name FROM financial_year_master WHERE delflag=0");
                                                @endphp
                                                <label for="fin_year_id1" class="form-label">Financial Year </label>
                                                <select name="fin_year_id1" id="fin_year_id1" class="form-control">
                                                    @foreach($financialYearData as $row)
                                                        <option value="{{$row->fin_year_id}}" {{$row->fin_year_id = 4  ? 'selected="selected"' : '' }}>{{$row->fin_year_name}}</option>
                                                    @endforeach
                                                </select>
                                             </div>
                                           </div> 
                                           <div class="col-md-2">
                                              <label for="formrow-inputState" class="form-label"></label>
                                              <div class="form-group">
                                                 <button onclick="GetKGDPLSalesReport();" class="btn btn-primary w-md">Search</button>
                                              </div>
                                           </div> 
                                       </div> 
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive"> 
                            <table id="openOrderReport1" class="table dt-datatable table-bordered nowrap w-100">
                              <thead style="background: #80808096;color: #fff;">  
                                 <tr>
                                    <th class="text-center" rowspan="2" style="vertical-align: middle;border-right:3px solid black;"><b>Months</b></th>
                                    <th class="text-center" style="border-right:3px solid black;"><b>Dispatch Pcs Qty</b></th> 
                                    <th class="text-center" style="border-right:3px solid black;" ><b>Dispatch Minutes</b></th> 
                                    <th class="text-center" style="border-right:3px solid black;" ><b>Total Sale Amount</b></th> 
                                 </tr>
                                 <tr>
                                    <th class="text-center" style="border-right:3px solid black;" ><b>In Lakh</b></th> 
                                    <th class="text-center" style="border-right:3px solid black;" ><b>In Lakh</b></th> 
                                    <th class="text-center" style="border-right:3px solid black;" ><b>In Cr.</b></th> 
                                 </tr>
                              </thead>
                              <tbody id="KGDPLSalesTbody"></tbody> 
                           </table>
                        </div>
                     </div>
                  </div>
                 </div>
                  <div class="col-lg-4"></div>
                </div>
                </div>
            </div> 
        </div>
   </div>  
</div> 
@php
  }
  else
  {
@endphp
   <!--<div class="col-lg-12 text-center">-->
   <!--   <div class="card">-->
   <!--      <div class="card-body">-->
             <!--<img src="../images/sparkles1.gif" width="800" height="300" class="img-fluid">-->
   <!--      </div>-->
   <!--   </div>-->
   <!--</div> -->
@php
  }  
@endphp
</div>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
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
   
    window.onload = function() 
    {
        if (window.innerWidth <= 600) 
        {
          window.location.href = "https://kenerp.com/BuyerCosting";  
        }
    };
  
    function html_table_to_excel(type,orderTypeId)
    {
        var data = document.getElementById('tab_'+orderTypeId);

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
    
            XLSX.writeFile(file, 'Order Booking Report.' + type);
    }
     
    $( document ).ready(function() 
    {
      $('#starting').removeClass('hide');
     quantitive_inventory();
    //   GetTableData();
      GetBookingData(1);
      GetBookingData1(1);
      GetMontlyBudgetSalesReport();
      GetMontlyBudgetProductionReport();
      OpenOrderReports();
      GetKGDPLSalesReport();  
      GetTotalOrderBookingSummary();
      GetWorkInProgress();
      
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
              GetMontlyBudgetSalesReport();
              GetMontlyBudgetProductionReport();
              OpenOrderReports();
              GetKGDPLSalesReport();
              setTimeout(function() {
                  GetTotalOrderBookingSummary();
              }, 500);
              
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

  function GetSalesSummaryReport()
   {
        var options = {
          accessibility: true,
          prevNextButtons: true,
          pageDots: true,
          setGallerySize: false,
          arrowShape: {
            x0: 10,
            x1: 60,
            y1: 50,
            x2: 60,
            y2: 45,
            x3: 15
          }
        };
    
        var carousel = document.querySelector('[data-carousel]');
        var slides = document.getElementsByClassName('carousel-cell');
        var flkty = new Flickity(carousel, options);
        
        // Go to a specific slide (index starts from 0)
        var slideIndex = {{$slide_no}}; // Change to the index of the slide you want to navigate to
        flkty.select(slideIndex);
        var salesSummaryFromDate =$("#fromDate").val(); 
        var salesSummaryToDate = $("#toDate").val(); 
      
        $.ajax({
          dataType: "json", 
          data : { 'salesSummaryFromDate' : salesSummaryFromDate,'salesSummaryToDate': salesSummaryToDate},
          url: "{{ route('GetSalesSummaryReport') }}", 
          success: function(data)
          {
                $('#SalesSummaryTbl').html(data.html); 
          },
      });
      
   }
   
  
    function GetProductionSummaryReport()
    { 
          var options = {
          accessibility: true,
          prevNextButtons: true,
          pageDots: true,
          setGallerySize: false,
          arrowShape: {
            x0: 10,
            x1: 60,
            y1: 50,
            x2: 60,
            y2: 45,
            x3: 15
          }
        };
    
        var carousel = document.querySelector('[data-carousel]');
        var slides = document.getElementsByClassName('carousel-cell');
        var flkty = new Flickity(carousel, options);
        
        // Go to a specific slide (index starts from 0)
        var slideIndex = {{$slide_no}}; // Change to the index of the slide you want to navigate to
        flkty.select(slideIndex);
        
        var ProdSummaryFromDate =$("#ProdFromDate").val(); 
        var ProdSummaryToDate = $("#ProdTDate").val();
        $.ajax({
          dataType: "json", 
           data : { 'ProdSummaryFromDate' : ProdSummaryFromDate,'ProdSummaryToDate': ProdSummaryToDate},
          url: "{{ route('GetProductionSummaryReport') }}", 
          success: function(data)
          {
              $('#productionSummaryReport').html(data.html); 
          },
        });
    }
    
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
  var options = {
      accessibility: true,
      prevNextButtons: true,
      pageDots: true,
      setGallerySize: false,
      arrowShape: {
        x0: 10,
        x1: 60,
        y1: 50,
        x2: 60,
        y2: 45,
        x3: 15
      }
    };
    
    var carousel = document.querySelector('[data-carousel]');
    var slides = document.getElementsByClassName('carousel-cell');
    var flkty = new Flickity(carousel, options);
    
    flkty.on('scroll', function () {
      flkty.slides.forEach(function (slide, i) {
        var image = slides[i];
        var x = (slide.target + flkty.x) * -1/3;
        image.style.backgroundPosition = x + 'px';
      });
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
   
   function GetTotalOrderBookingSummary()
   {
       
        var OrderFromDate = $("#OrderFromDate").val();
        var OrderToDate = $("#OrderToDate").val();
        $.ajax({
          dataType: "json", 
          data : { 'OrderFromDate' : OrderFromDate, 'OrderToDate':OrderToDate},
          url: "{{ route('GetTotalOrderBookingSummary') }}", 
          beforeSend: function() {
             $("#booking_loader").removeClass('hide');
          },
          success: function(data)
          {
              $('#OrderBookingSummeryTables').html(data.html); 
            
          },
          complete: function(){
            $("#booking_loader").addClass('hide');  
            $("#OrderFromDate").val(OrderFromDate).change();
            $("#OrderToDate").val(OrderToDate).change();
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
   
   function GetMontlyBudgetSalesReport()
   {
      var fromDate =$("#fromDate").val(); 
      var toDate = $("#toDate").val();
      $.ajax({
          dataType: "json", 
          data : { 'fromDate' : fromDate,'toDate': toDate},
          url: "{{ route('GetMontlyBudgetSalesReport') }}", 
          success: function(data)
          {
                $('#MBTbl1').html(data.html); 
                 var dataTable = $('#monthly_budget1').DataTable({
                 //   "order": [[6, "desc"]],  
                    "bProcessing": true,
                    "sAutoWidth": false,
                    "bDestroy":true,
                    "sPaginationType": "bootstrap", // full_numbers 
                    "bPaginate": false, //hide pagination
                    "bFilter": false, //hide Search bar
                    "bInfo": false, // hide showing entries
                    'iDisplayLength': 100,
                    "scrollY": 700,     
                    "responsive": true
              });
                dataTable.on('order.dt search.dt', function () {
                    dataTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
                
                
                $('#MBTbl2').html(data.html1); 
                 var dataTable1 = $('#monthly_budget3').DataTable({
                 //   "order": [[6, "desc"]],  
                    "bProcessing": true,
                    "sAutoWidth": false,
                    "bDestroy":true,
                    "sPaginationType": "bootstrap", // full_numbers 
                    "bPaginate": false, //hide pagination
                    "bFilter": false, //hide Search bar
                    "bInfo": false, // hide showing entries
                    'iDisplayLength': 100,
                    "scrollY": 700,     
                    "responsive": true
              });
              
                dataTable1.on('order.dt search.dt', function () {
                    dataTable1.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
               
               
                $('#MBTbl3').html(data.html2); 
                 var dataTable2 = $('#monthly_budget4').DataTable({
                 //   "order": [[6, "desc"]],  
                    "bProcessing": true,
                    "sAutoWidth": false,
                    "bDestroy":true,
                    "sPaginationType": "bootstrap", // full_numbers 
                    "bPaginate": false, //hide pagination
                    "bFilter": false, //hide Search bar
                    "bInfo": false, // hide showing entries
                    'iDisplayLength': 100,
                    "scrollY": 700,     
                    "responsive": true
              });
              
                dataTable2.on('order.dt search.dt', function () {
                    dataTable2.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
               
               
          },
      });
      
      // GetSalesSummaryReport();
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
    //  GetProductionSummaryReport();
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
   
    function GetKGDPLSalesReport()
    { 
      var fin_year_id =$("#fin_year_id1").val();  
      $.ajax({
          dataType: "json",  
          data : { 'fin_year_id' : fin_year_id},
          url: "{{ route('GetKGDPLSales') }}", 
          success: function(data)
          {
              $('#KGDPLSalesTbody').html(data.html);  
               
          },
      });
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
</script>
@endsection
@section('script')
@endsection