@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    .hide{
        display : none;
    }
    
    .bg-overlay1 {
        background: url("../assets/images/happy_new_year2024.gif");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
    
    @import url('https://fonts.googleapis.com/css2?family=El+Messiri:wght@700&display=swap');

    * {
      margin: 0;
      padding: 0;
      font-family: 'El Messiri', sans-serif;
    }
    
    body {
      background: #031323;
      overflow: hidden;
    }
    
    .fas {
      width: 32px;
    }
    
    section {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
      background-size: 400% 400%;
      animation: gradient 10s ease infinite;
    }
    
    @keyframes gradient {
        0% {
          background-position: 0% 50%;
          }
        50% {
          background-position: 100% 50%;
          }
        100% {
          background-position: 0% 50%;
          }
    }
 
    }
    
   
    .form {
      position: relative;
      width: 100%;
      height: 100%;
    
       h2 {
        color: #fff;
        letter-spacing: 2px;
        margin-bottom: 30px;
      }
    
      .inputBx {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
        
        select {
          width: 100%;
          outline: none;
          border: none;
          border: 1px solid rgba(255, 255, 255, 0.2);
          background: rgba(255, 255, 255, 0.2);
          padding: 15px 50px;
          padding-left: 40px;
          border-radius: 15px;
          color: #000;
          font-size: 16px;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        input {
          width: 100%;
          outline: none;
          border: none;
          border: 1px solid rgba(255, 255, 255, 0.2);
          background: rgba(255, 255, 255, 0.2);
          padding: 8px 10px;
          padding-left: 40px;
          border-radius: 15px;
          color: #000;
          font-size: 16px;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .password-control {
          position: absolute;
          top: 11px;
          right: 10px;
          display: inline-block;
          width: 20px;
          height: 20px;
          background: url(https://snipp.ru/demo/495/view.svg) 0 0 no-repeat;
          transition: 0.5s;
    }
     
            
          .view {
             background: url(https://snipp.ru/demo/495/no-view.svg) 0 0 no-repeat;
            transition: 0.5s;
      }
    
        
      
        .fas {
          position: absolute;
          top: 13px;
          left: 13px;
        }
        
        input[type="submit"] {
          background: #fff;
          color: #111;
          max-width: 100px;
          padding: 8px 10px;
          box-shadow: none;
          letter-spacing: 1px;
          cursor: pointer;
          transition: 1.5s;
        }
        
        input[type="submit"]:hover {
          background: linear-gradient(115deg, 
            rgba(0,0,0,0.10), 
            rgba(255,255,255,0.25));
          color: #fff;
          transition: .5s;
        }
        
        input::placeholder {
          color: #fff;
          font-size:16px;
        }
        
        span {
            position: absolute;
            left: 30px;
            padding: 10px;
            display: inline-block;
            color: #fff;
            transition: .5s;
            pointer-events: none;
            font-size:18px;
          }
        
        input:focus ~ span,select:focus ~ span,select:valid ~ span,
        input:valid ~ span {
          transform: translateX(-40px) translateY(-35px);
          font-size: 18px;
        }
      }
      
      p {
        color: #fff;
        font-size: 15px;
        margin-top: 5px;
      
        a {
          color: #fff;
        }
        
        a:hover {
          background-color: #000;
          background-image: linear-gradient(to right, #434343 0%, black 100%);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
        }
      }
    }
    
    .remember {
      position: relative;
      display: inline-block;
      color: #fff;
      margin-bottom: 10px;
      cursor: pointer;
    }
    
    
    @import url(https://fonts.googleapis.com/css?family=Nobile:400italic,700italic);
    @import url(https://fonts.googleapis.com/css?family=Dancing+Script);
    * {
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
    }
    body {
      background: #E5E5E5;
      background-image: url("/gray-floral.png");
    }
    
   
    
    .wrap {
        padding: 1.5em 2.5em;
        height: 100%;
    }
  
    #card {
        max-width: 960px;
        margin: 0 auto;
        transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        -webkit-transform-style: preserve-3d;
        perspective: 5000px;
        -moz-perspective: 5000px;
        -webkit-perspective: 5000px;
        position: relative;
    }
    
    #card h1 {
        text-align: center;
        font-family: 'Nobile', sans-serif;
        font-style: italic;
        font-size: 70px;
        text-shadow: 
            4px 4px 0px rgba(0, 0, 0, .15),
            1px 1px 0 rgba(255, 200, 200, 255),
            2px 2px 0 rgba(255, 150, 150, 255),
            3px 3px 0 rgba(255, 125, 125, 255);
        color: #FFF;
    }

    
    p {
        margin-top: 1em;
    }
    
    p:first-child {
        margin-top: 0;
    }
    
    p.signed {
        margin-top: 1.5em;
        text-align: center;
        font-family: 'Dancing Script', sans-serif;
        font-size: 1.5em;
    }
 
    #close {
      display: none;
    }
    
    #card.open-fully #close,
    #card-open-half #close {
      display: inline;
    }
    
    #card.open-fully #open {
      display: none;
    }
    
    
    #card.open-half #card-front,
    #card.close-half #card-front {
                transform: rotateY(-90deg);
           -moz-transform: rotateY(-90deg);
        -webkit-transform: rotateY(-90deg);
    }
    #card.open-half #card-front .wrap {
        background-color: rgba(0, 0, 0, .5);
    }
    
    #card.open-fully #card-front,
    #card.close-half #card-front {
      background: #FFEFEF;
    }
    
    #card.open-fully #card-front {
        transform: rotateY(-180deg);
        -moz-transform: rotateY(-180deg);
        -webkit-transform: rotateY(-180deg);
    }
    
    #card.open-fully #card-front .wrap {
        background-color: rgba(0, 0, 0, 0);
    }
    
    #card.open-fully #card-front .wrap *,
    #card.close-half #card-front .wrap * {
       display: none;
    }
    
    footer {
      max-width: 500px;
      margin: 40px auto;
      font-family: 'Nobile', sans-serif;
      font-size: 14px;
      line-height: 1.6;
      color: #888;
      text-align: center;
    }
*{
    font-family: math;
}

.box-canvas{
  position: relative;
  margin: auto;
  display: block;
  margin-bottom: 8%;
  width: 55px;
  height:200px;
  animation: floatUp 5s infinite linear;
}

@keyframes floatUp {
  0% {
    transform: translateY(50vh);
  }
  
  100% {
    transform: translateY(-240px);
  }
}

.string {
    position: absolute;
    top: 26px;
    left: 32px;
    transform: rotate(var(--string-angle));
    transform-origin: top left;
    width: 2px;
    height: 251px;
    background: #50535E;
}


canvas {
    overflow-y: hidden;
    overflow-x: hidden;
    width: 103%;
    margin: 0;

}

.col-12 {
  position: relative;
  padding: 50px; 
  min-height: 380px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(5px);
  border-radius: 10px;
}
.form {
  width: 100%;
  h2 {
    color: #fff;
    margin-bottom: 30px;
  }
  .inputBx {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
    input, select {
      width: 100%;
      border-radius: 15px;
      padding: 15px 40px;
      background: rgba(255, 255, 255, 0.2);
    }
    .fas {
      position: absolute;
      top: 13px;
      left: 13px;
    }
  }
}

</style>
      <section> 
          <div class="box"> 
               <div class="col-12"> 
                <div class="form"> 
                  <h5 style="margin-bottom:10%;" class="text-center"><img src="../assets/images/transperent_logo.png" width="80" height="80"  style="background: transparent;border-radius: 10px;margin-left: 5%;" /><br/>
                   <b>Ken Global Designs Pvt. Ltd.</b> </h5> 
                  <form action="{{ Route('auth') }}" method="POST">
                    @csrf 
                    <div class="inputBx select">
                       <select name="year_id"  id="year_id" required>
                         <option value="">--Financial Year--</option>
                         @foreach($financialYearList as  $rowList)
                         <option value="{{ $rowList->year_id }}" {{ $rowList->year_id == 3 ? 'selected="selected"' : '' }}>
                            {{ $rowList->year_name }}
                         </option>
                         @endforeach
                      </select> 
                      <i class="fas fa-calendar"></i>
                    </div>
                    <div class="inputBx">
                      <input type="text" required="required" name="username" id="username"  >
                      <span>User Name</span>
                      <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="inputBx password">
                      <input id="password-input" type="password" name="password" required="required">
                      <span>Password</span>
                      <a href="#" class="password-control" onclick="return show_hide_password(this);"></a>
                      <i class="fas fa-key"></i>
                    </div>
                    <div class="inputBx">  
                      <input type="submit" class="btn" value="Log In" />
                    </div>
                  </form>
                  <p>If forgot your password then contact to <b> <br/>IT Department <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
                      <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                      <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg> 9850507314</b></p></p> 
                </div>
              </div> 
          </div>
      
    </section>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
   $(function()
   {
       CheckTodayBirthdayHRMS();
   });
   function CheckTodayBirthdayHRMS()
   {
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('CheckTodayBirthdayHRMS') }}", 
          success: function(data)
          { 
               if(data.fullName != "")
               {
                   $('#bday_wishes').removeClass("hide");
                   $('.bday_name').html(data.fullName);
                   $('#bday_dept').html("- "+data.dept);
                   $('#dob_firstName').html(data.firstName);
                   $('#dob_date').html(data.dob_date);
                   $('#dob_profileImg').attr('src',data.profileImg);
               }
          }
        });
   }
    function show_hide_password(target)
    {
    	var input = document.getElementById('password-input');
    	if (input.getAttribute('type') == 'password') {
    		target.classList.add('view');
    		input.setAttribute('type', 'text');
    	} else {
    		target.classList.remove('view');
    		input.setAttribute('type', 'password');
    	}
    	return false;
    }
    
    (function() {
      function $(id) {
        return document.getElementById(id);
      }
    
      var card = $('card'),
          openB = $('open'),
          closeB = $('close'),
          timer = null;
      console.log('wat', card);
      openB.addEventListener('click', function () {
        card.setAttribute('class', 'open-half');
        if (timer) clearTimeout(timer);
        timer = setTimeout(function () {
          card.setAttribute('class', 'open-fully');
          timer = null;
        }, 1000);
      });
    
      closeB.addEventListener('click', function () {
        card.setAttribute('class', 'close-half');
        if (timer) clearTimerout(timer);
        timer = setTimeout(function () {
          card.setAttribute('class', '');
          timer = null;
        }, 1000);
      });
    
    }());
 
      let W = window.innerWidth;
        let H = window.innerHeight;
        const canvas = document.getElementById("canvas123");
        const context = canvas.getContext("2d");
        const maxConfettis = 200;
        const particles = [];
        
        const possibleColors = [
          "DodgerBlue",
          "OliveDrab",
          "Gold",
          "Pink",
          "SlateBlue",
          "LightBlue",
          "Gold",
          "Violet",
          "PaleGreen",
          "SteelBlue",
          "SandyBrown",
          "Chocolate",
          "Crimson"
        ];
        
        function randomFromTo(from, to) {
          return Math.floor(Math.random() * (to - from + 1) + from);
        }
        
        function confettiParticle() {
          this.x = Math.random() * W; // x
          this.y = Math.random() * H - H; // y
          this.r = randomFromTo(11, 33); // radius
          this.d = Math.random() * maxConfettis + 11;
          this.color =
            possibleColors[Math.floor(Math.random() * possibleColors.length)];
          this.tilt = Math.floor(Math.random() * 33) - 11;
          this.tiltAngleIncremental = Math.random() * 0.07 + 0.05;
          this.tiltAngle = 0;
        
          this.draw = function() {
            context.beginPath();
            context.lineWidth = this.r / 2;
            context.strokeStyle = this.color;
            context.moveTo(this.x + this.tilt + this.r / 3, this.y);
            context.lineTo(this.x + this.tilt, this.y + this.tilt + this.r / 5);
            return context.stroke();
          };
        }
        
        function Draw() {
          const results = [];
        
          // Magical recursive functional love
          requestAnimationFrame(Draw);
        
          context.clearRect(0, 0, W, window.innerHeight);
        
          for (var i = 0; i < maxConfettis; i++) {
            results.push(particles[i].draw());
          }
        
          let particle = {};
          let remainingFlakes = 0;
          for (var i = 0; i < maxConfettis; i++) {
            particle = particles[i];
        
            particle.tiltAngle += particle.tiltAngleIncremental;
            particle.y += (Math.cos(particle.d) + 3 + particle.r / 2) / 2;
            particle.tilt = Math.sin(particle.tiltAngle - i / 3) * 15;
        
            if (particle.y <= H) remainingFlakes++;
        
            // If a confetti has fluttered out of view,
            // bring it back to above the viewport and let if re-fall.
            if (particle.x > W + 30 || particle.x < -30 || particle.y > H) {
              particle.x = Math.random() * W;
              particle.y = -30;
              particle.tilt = Math.floor(Math.random() * 10) - 20;
            }
          }
        
          return results;
        }
        
        window.addEventListener(
          "resize",
          function() {
            W = window.innerWidth;
            H = window.innerHeight;
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
          },
          false
        );
        
        // Push new confetti objects to `particles[]`
        for (var i = 0; i < maxConfettis; i++) {
          particles.push(new confettiParticle());
        }
        
        // Initialize
        canvas.width = W;
        canvas.height = H;
        Draw();
</script>
@endsection