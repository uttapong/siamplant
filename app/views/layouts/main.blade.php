<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
@section('og')


<meta property="og:image" content="{{ asset('assets/images/teebadgun_logo_128.png') }}" /> 
<meta property="og:url" content="{{Request::url()}}" /> 
<meta property="og:title" content="siamplant ซื้อต้นไม้ ขายต้นไม้ ง่าย ได้เงินไว" />
<meta property="og:description" content="ตลาดต้นไม้ ออนไลน์ ที่ง่ายและได้เงินไวที่สุด" />

<meta property="og:locale" content="th_TH" />
@show

    <link rel="icon" href="{{ asset('assets/images/siamplant_ico.png') }}">

    <title>'ตลาดต้นไม้ siamplant': @yield('title')</title>

    <!-- Bootstrap core CSS -->

<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/siamplant.css') }}" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,900,300,100' rel='stylesheet' type='text/css'>
<link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/bootstrap-formhelpers.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/js/bower_components/angular-loading-bar/build/angular-loading-bar.min.css') }}"></link>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bower_components/angular/angular.min.js') }}"></script>
<script src="{{ asset('assets/js/bower_components/angular-ui-router/release/angular-ui-router.min.js') }}"></script>
<script src="{{ asset('assets/js/bower_components/angular-strap/dist/angular-strap.min.js') }}"></script>
<script src="{{ asset('assets/js/bower_components/angular-strap/dist/angular-strap.tpl.min.js') }}"></script>
<script src="{{ asset('assets/js/bower_components/angular-loading-bar/build/loading-bar.min.js') }}"></script>
<script src="{{ asset('assets/js/bower_components/angular-local-storage/dist/angular-local-storage.min.js') }}"></script>
<script src="{{ asset('assets/js/angular/app.js') }}"></script>
<script src="{{ asset('assets/js/angular/config.js') }}"></script>
<script src="{{ asset('assets/js/angular/module.js') }}"></script>


<script src="{{ asset('assets/js/bootstrap-formhelpers.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

    <!-- Custom styles for this template -->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

@section('script')
@show
@section('style')
@show

<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '612396988819496',                        // App ID from the app dashboard
      status     : true,                                 // Check Facebook Login status
      xfbml      : true,                                  // Look for social plugins on the page
      cookie  :true
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
 },{scope: 'email,publish_actions,publish_stream'});
  
}

function connectFB(){
  FB.login(function(response) {
   if (response.authResponse) {
     console.log('กำลังดึงข้อมูลจาก Facebook');
     FB.api('/me', function(response) {
       location.href='{{ route('fbconnect') }}';
     });
   } else {
     console.log('User cancelled login or did not fully authorize.');
     location.href='{{ route('home') }}';
   }
  // location.href='{{ route('home') }}';
 },{scope: 'email,publish_actions,publish_stream'});
}


function showBadge(id,name,desc){

  var content="<div class='userbadge'>"+
  "<img src='{{route('home')}}/assets/images/badge/"+id+".png' />"+
  "<h2>ขอแสดงความยินดีคุณได้รับเหรียญ '"+name+"'</h2>"+
  "<p>"+desc+"</p>"+
  "</div>";
  bootbox.alert(content, function() {
  });
}
function inviteFriends(){
  /*FB.ui({method: 'apprequests',
        message: 'มาเข้าร่วมสังคมนักกีฬาแบดมินตันที่คุณเป็นเจ้าของกันเถอะ'
    }, function(request,to){

      console.log(to);

    });*/



    FB.ui({
        method: 'send',
        message: 'มาช๊อปต้นไม้แบบง่ายๆ กันเถอะ',
       // filters: ['app_non_users'],
       link: 'http://www.siamplant.com',
        title: 'ส่งคำเขิญหาเพื่อนของคุณ'
    });


}

</script>
  </head>

  <body ng-app="siamPlant">
 
  <siamplant-header></siamplant-header>

    <div class="container">
      
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=612396988819496&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
      
@section('content')
      
@show
      <footer class="footer">
        <p>&copy; Copyright, Siamplant.com 2015</p>
      </footer>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{ asset('assets/js/ie10-viewport-bug-workaround.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.md5.js') }}"></script>
    
  </body>
</html>
