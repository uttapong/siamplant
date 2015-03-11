<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

<title>@yield('title', 'สมัครสมาชิก')</title>

<!-- Bootstrap core CSS -->

<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/teebadgun.css') }}" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,900,300,100' rel='stylesheet' type='text/css'>
<link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/bootstrap-formhelpers.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-formhelpers.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=th&libraries=places"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
@section('script')
    <script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '612396988819496',                        // App ID from the app dashboard
      status     : true,                                 // Check Facebook Login status
      xfbml      : true,                                  // Look for social plugins on the page
      cookie    :true
    });

    // Additional initialization code such as adding Event Listeners goes here
  };

  // Load the SDK asynchronously
  (function(){
     // If we've already installed the SDK, we're done
     if (document.getElementById('facebook-jssdk')) {return;}

     // Get the first script element, which we'll use to find the parent node
     var firstScriptElement = document.getElementsByTagName('script')[0];

     // Create a new script element and set its id
     var facebookJS = document.createElement('script'); 
     facebookJS.id = 'facebook-jssdk';

     // Set the new script's source to the source of the Facebook JS SDK
     facebookJS.src = '//connect.facebook.net/en_US/all.js';

     // Insert the Facebook JS SDK into the DOM
     firstScriptElement.parentNode.insertBefore(facebookJS, firstScriptElement);
   }());
function loginFB(){
    FB.login(function(response) {
   if (response.authResponse) {
     console.log('กำลังดึงข้อมูลจาก Facebook');
     FB.api('/me', function(response) {
       location.href='{{ route('fbsignin') }}';
     });
   } else {
     console.log('User cancelled login or did not fully authorize.');
     location.href='{{ route('home') }}';
   }
  // location.href='{{ route('home') }}';
 });
    
}
</script>
@show
<style>
body{
height:100vh;

background-image:url('{{ url('/') }}/assets/images/login_bg.svg') !important;
background-position:left top;
background-size: 100% 100%;
    background-repeat: no-repeat;
}
}
</style>
</head>

<body>


<div class="container">
@section('content')

<div class="row" style="margin-top: 50px;">
    <div class="col-md-2 center">
    </div>
    <div class="col-md-8 center">
        <div class="welcome">
        <h1 style="font-size: 4rem;color: black;">Siamplant<span style="color: #518c04;font-size: 10rem;font-family:serif">.</span><span style="color: #333;font-size: 2.4rem;">com</span></h1>
        <p>ตลาดต้นไม้ออนไลน์ที่ซื้อขายง่ายที่สุด </p>
        <p>แหล่งช๊อปปิ้ง สำหรับคนรักต้นไม้ ร่วมกันเพิ่มพื้นที่สีเขียวให้กับโลก</p>
        </div>
    </div>
    <div class="col-md-2 center">
    </div>
</div>

<div class="row">
    <div class="col-md-2 center">
    </div>
    <div class="col-md-8 center">
        <div id="login-bar">
          {{ Form::open(array('url' => 'user/signin','method'=>'post','id'=>'addgym','role'=>'form','class'=>'form-inline')) }}
          
    @if (Session::has('login_errors'))
        <div class="alert alert-danger ">ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</div>
    @endif
     @if(Session::has('message'))
                        {{ Session::get('message') }}
                    
      @endif
            <div class="form-group"><input type="text" name="username" placeholder="ชื่อผู้ใช้" class="form-control"> </div>
            <div class="form-group"><input type="password" name="password" placeholder="รหัสผ่าน" class="form-control"> </div>
            <p style="margin-top: 10px;"><button type="submit" class="btn btn-info">เข้าสู่ระบบ</button>
            <button type="button" class="btn btn-primary" onclick="loginFB();" ><i class="fa fa-facebook-square fa-lg"></i> เข้าสู่ระบบโดย Facebook</button>
            <a href="" class="btn btn-warning">ลืมรหัสผ่าน</a>
            <a href="{{ action('UserController@signup'); }}" class="btn btn-success"><span class="glyphicon glyphicon-user"></span> สมัครสมาชิก</a></p>
          {{ Form::close() }}
        </div>
        </div>
    </div>
    <div class="col-md-2 center">
    </div>
</div>



@show

</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/bootbox.min.js') }}"></script>
</body>
</html>
