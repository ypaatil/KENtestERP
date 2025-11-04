<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
   .navbar-header {  
    border: 1px solid #ccc; 
    justify-content: flex-start!important; 
     background-size: 300% 300%;  
}
#page-topbar {
    position: fixed;
    left: -17px;
}
.navbar-brand-box { 
    width: 251px !important;
}
/* 🔹 Professional Header User Dropdown Styling */
.header-user-area {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 20px;
}

.header-user-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(90deg, #007bff, #00c6ff);
    color: #fff !important;
    border: none;
    border-radius: 30px;
    padding: 6px 14px;
    transition: 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
}

.header-user-btn:hover {
    background: linear-gradient(90deg, #0066cc, #00aaff);
    transform: translateY(-1px);
}

.header-user-btn i {
    font-size: 18px;
}

.header-user-name {
    font-weight: 500;
    font-size: 14px;
}

.dropdown-menu-custom {
    border-radius: 10px;
    min-width: 180px;
    padding: 8px 0;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    padding: 8px 15px;
    transition: background 0.2s;
}

.dropdown-item i {
    font-size: 16px;
    color: #007bff;
}

.dropdown-item:hover {
    background-color: #f1f5ff;
}

</style>
<header id="page-topbar">
<div class="navbar-header">
<div class="col-md-4 d-flex">
<!-- LOGO -->

@php
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('/(iphone|ipod|android|blackberry|windows phone|webos)/i', $user_agent)) {
  
  @endphp
  
  <style>
  .vertical-menu1 li {
     margin-top:20px;
      
  }      
      
  </style>
  
  @php
} else{ @endphp
<div class="navbar-brand-box">
<a href="index.html" class="logo logo-dark">
<span class="logo-sm">
<img src="/images/logo.svg" alt="" height="22">
</span>
<span class="logo-lg">
<img src="/images/logo-dark.png" alt="" height="17">
</span>
</a>

<a href="index.html" class="logo logo-light">
<span class="logo-sm">
<img src="../assets/images/transperent_logo.png" alt="" height="22">
</span>

<span class="logo-lg">
<img src="/images/ken_logo.png" alt="" style="width:200px; height:50px;margin-top: 25px;">
</span>

</a>
</div>
@php } @endphp
<button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
<i class="fa fa-fw fa-bars"></i>
</button>
</div>

<div class="col-md-4 d-flex" style="justify-content: center;">
    
</div> 
<div class="col-md-4 header-user-area">
    <div class="dropdown d-inline-block">
      <button type="button" class="btn header-user-btn" id="page-header-user-dropdown"
              data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa-solid fa-user-circle"></i>
          <span class="header-user-name">{{ ucfirst(strtolower(Session::get('username'))) }}</span>
          <i class="fa-solid fa-chevron-down"></i>
      </button>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-custom" aria-labelledby="page-header-user-dropdown">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket text-danger"></i> Logout
            </a>
        </div>
    </div>
</div>
</div>
</header>