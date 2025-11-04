@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
 /* General Reset */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f4f6f8;
  color: #333;
}

.wrapper {
  padding: 20px 20px;
  overflow: hidden;
  background-color: #f9f9f9;
}

/* Container */
.container {
  max-width: 1000px;
  margin: 0 auto;
}

/* Accordion Button Section */
.acc-btn {
  margin: 5px 0;
  text-align: center;
}

.acc-btn > a {
  text-decoration: none;
  display: inline-block;
  height: 44px;
  line-height: 44px;
  background-color: #007bff;
  color: #fff;
  border-radius: 25px;
  padding: 0 24px;
  margin: 0 10px 10px 10px;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

.acc-btn > a:hover {
  background-color: #0056b3;
}

/* Accordion Styles */
.accordion-item {
  margin-bottom: 12px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 6px 12px rgba(0,0,0,0.06);
  background: #fff;
  transition: transform 0.3s ease;
}

.accordion-item:hover {
  transform: translateY(-2px);
}

/* Accordion Header */
.accordion-header {
  cursor: pointer;
  display: block;
  font-size: 18px;
  padding: 16px 60px 16px 24px;
  position: relative;
  background-color: #fff;
  color: #222;
  transition: background-color 0.3s ease;
}

.accordion-header:hover {
  background-color: #f0f0f0;
}

/* Plus Icon */
.accordion-header > span {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  height: 24px;
  width: 24px;
  border: 2px solid #007bff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
}

.accordion-header > span::before,
.accordion-header > span::after {
  content: "";
  position: absolute;
  background-color: #007bff;
  transition: transform 0.3s ease;
}

.accordion-header > span::before {
  width: 2px;
  height: 12px;
}

.accordion-header > span::after {
  width: 12px;
  height: 2px;
}

/* Rotate on open */
.accordion-open .accordion-header > span::before {
  transform: rotate(90deg);
  opacity: 0;
}

/* Accordion Body */
.accordion-body {
  padding: 20px 24px;
  font-size: 16px;
  line-height: 1.6;
  display: none;
  background-color: #fff;
  color: #555;
}

.accordion-open .accordion-body {
  display: block;
  animation: fadeIn 0.3s ease-in-out;
}

/* Fade In Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.accordion-title {
  flex: 1;
  text-align: center;
  font-weight: bold;
  font-size:18px;
}




  .search-container {
    display: flex;
    justify-content: center;
    margin: 5px 0;
  }

  .search-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    background-color: #f9f9f9;
    padding: 20px 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
  }

  .search-bar label {
    font-size: 14px;
    font-weight: 500;
    margin-right: 4px;
  }

  .search-bar input[type="date"] {
    padding: 6px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .search-bar button {
    padding: 6px 12px;
    font-size: 14px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.2s ease;
  }
  
  


  .search-bar button {
    background-color: #007BFF;
    color: #fff;
  }

  .search-bar button:hover {
    background-color: #0056b3;
  }

  .search-bar a {
    background-color: #6c757d;
    color: white;
  }

  .search-bar a:hover {
    background-color: #5a6268;
  }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Packing Inhouse Activity Log</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Packing Inhouse Activity Log</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 

            </div>
        </div>
    </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
   
   
   
              <div class="wrapper">  

  <!-- Accordion Starts Here -->
  <section id="accordion-sec">
  <span class="accordion-title">Activity Log</span>



    
    <div class="search-container">
  <div class="search-bar">
      
    <form method="GET"> 
    <label for="from-date">From:</label>
    <input type="date" id="fromDate" name="fromDate">

    <label for="to-date">To:</label>
    <input type="date" id="toDate" name="toDate">

    <button id="search-btn" type="submit">Search</button>
    </form> 

    <div class="acc-btn">
      <button  id="expand" class="animated slideInLeft">Expand All</button>
      <button  id="collapse" class="animated slideInRight">Collapse All</button>
    </div>
  </div>
</div>
    

    <div class="container">
      <div class="accordion">
   
          
        
        <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Packing Inhouse Detail<span></span></label>
          <div class="accordion-body">

          
<table class="table table-bordered" id="activityLogTableDetail">

   @include('partials.change_rows')
</table>



   
          </div>
        </div>
        
        
             
        <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Transfer Packing Inhouse Detail<span></span></label>
          <div class="accordion-body">

          
<table class="table table-bordered" id="activityLogTableDetail">

   @include('partials.change_row_transfer')
</table>



   
          </div>
        </div>
        
                <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Carton Packing Inhouse Detail<span></span></label>
          <div class="accordion-body">

          
<table class="table table-bordered" id="activityLogTableDetailCarton">

   @include('partials.change_row_carton')
</table>



   
          </div>
        </div>
        
                    <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Sales Transaction<span></span></label>
          <div class="accordion-body">

          
<table class="table table-bordered" id="activityLogTableDetailST">

   @include('partials.change_row_st')
</table>



   
          </div>
        </div>
        
                       <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Trims Inward<span></span></label>
          <div class="accordion-body">

   <div class="table-responsive">         
<table class="table table-bordered" id="activityLogTableDetailST">

   @include('partials.change_row_TIO')
</table>
</div>


   
          </div>
        </div>
        
                           <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Trims Outward<span></span></label>
          <div class="accordion-body">

   <div class="table-responsive">         
<table class="table table-bordered" id="activityLogTableDetailST">

   @include('partials.change_row_outward')
</table>
</div>


   
          </div>
        </div>
        
        
        
                               <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Fabric Checking<span></span></label>
          <div class="accordion-body">

   <div class="table-responsive">         
<table class="table table-bordered" id="activityLogTableDetailST">

   @include('partials.change_rows_fc')
</table>
</div>


   
          </div>
        </div>
        
                                <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Fabric Inward<span></span></label>
          <div class="accordion-body">

     <div class="table-responsive">         
<table class="table table-bordered" id="activityLogTableDetailFI">

   @include('partials.change_rows_inward')
   
  </table>
     </div>
          </div>
        </div>
        
        
        <div class="accordion-item animated slideInRight">
          <label class="accordion-header">Fabric Outward<span></span></label>
          <div class="accordion-body">

       <div class="table-responsive">   
<table class="table table-bordered" id="activityLogTableDetailFO">

   @include('partials.change_rows_outward')
</table>
  </div>


   
          </div>
        </div>
        
        
        
        
      </div>
    </div>
  </section>
</div>
   
   
   
   
   
   
   
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script>

$(document).ready(function() {
  accordion();
});

$(document).resize(function() {
  accordion();
});

// Accordion

function accordion() {
  $(".accordion-header").click(function() {
    if (
      $(this)
      .next()
      .is(":visible")
    ) {
      $(this)
        .next()
        .slideUp();
      $(this)
        .parent()
        .addClass("accordion-close");
      $(this)
        .parent()
        .removeClass("accordion-open");
    } else {
      $(".accordion-body").slideUp();
      $(".accordion-item").removeClass("accordion-open");
      $(this)
        .next()
        .slideDown();
      $(this)
        .parent()
        .addClass("accordion-open");
      $(this)
        .parent()
        .removeClass("accordion-close");
    }
  });

  $("#expand").click(function() {
    $(".accordion-body").slideDown();
    $(".accordion-item").addClass("accordion-open");
  });

  $("#collapse").click(function() {
    $(".accordion-body").slideUp();
    $(".accordion-item").removeClass("accordion-open");
  });
}





// For Activity Log Table Pagination
$(document).on('click', '#activityLogTable .pagination a', function(e) {
    e.preventDefault();
    var pageUrl = $(this).attr('href');

    $.ajax({
        url: pageUrl,
        type: 'GET',
        success: function(data) {
            $('#activityLogTable').html(data);
        },
        error: function() {
            alert('Failed to load activity log data.');
        }
    });
});

// For Activity Log Table Pagination


$(document).on('click', '#activityLogTableDetailFI .pagination a', function(e) {
    e.preventDefault();
    console.log('Pagination clicked'); // ✅ Add this
    var pageUrl = $(this).attr('href');

    $.ajax({
        url: pageUrl,
        type: 'GET',
        success: function(data) {
            $('#activityLogTableDetailFI').html(data);
        },
        error: function() {
            alert('Failed to load activity log data.');
        }
    });
});



</script>
@endsection