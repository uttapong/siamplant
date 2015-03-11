@extends('layouts.main')
@section('title')
Teebadgun.com : {{ $user->firstname }} {{ $user->lastname }}
@stop
@section('script')
<script>
function showBadge(id,name,desc){

  var content="<div class='userbadge'>"+
  "<img src='{{route('home')}}assets/images/badge/"+id+".png' />"+
  "<h2>เหรียญ '"+name+"'</h2>"+
  "<p>"+desc+"</p>"+
  "</div>";
  bootbox.alert(content, function() {
  });
}


</script>

<style>
body{
height:100vh;

background-image:url('{{ url('/') }}/assets/images/profile_hd1.jpg') !important;
background-position:left top;
}
</style>
@stop
@section('style')

@stop
@section('content')

<div class="row">
  <div class="col-md-12" style="margin-top: 80px;">
  <div class='row'>
        <div class="col-md-3 left">
          
          @if($user->picture)
          <div class="profile-circular" style="background-image:url('{{ $user->picture }}');">
          </div>
          
          @else
           <div class="profile-circular" style="background-image:url('{{ asset('assets/images/guest.jpg'); }}');"></div>
         
          @endif
          
        </div>
        <div class="col-md-6 left">
            <h1 style="font-size: 4rem;">{{ $user->firstname }} {{ $user->lastname }}</h1>
           <p>{{ $user->signature }}</p>
           <ul style="list-style-type:none;padding: 0px ;">
            <li><span class="glyphicon glyphicon-map-marker"></span>จังหวัด @if($user->province())  {{ $user->province()->name }} @else - @endif</li>
            
          </ul>
          <p>
            @if($user->flag_racket&&$user->gear_racket)
            <div class="gear-circular" style="background-image:url('{{ asset('uploads/gears/racket') }}/{{$user->id}}/{{$user->gear_racket}}');"></div>
            @else
            <div class="gear-circular" style="background-image:url('{{ asset('assets/images/gear_racket.jpg')}}');"></div>
            @endif

            @if($user->flag_string&&$user->gear_string)
            <div class="gear-circular" style="background-image:url('{{ asset('uploads/gears/string') }}/{{$user->id}}/{{$user->gear_string}}');"></div>
            @else
            <div class="gear-circular" style="background-image:url('{{ asset('assets/images/gear_string.jpg')}}');"></div>
            @endif

            @if($user->flag_shoes&&$user->gear_shoes)
            <div class="gear-circular" style="background-image:url('{{ asset('uploads/gears/shoes') }}/{{$user->id}}/{{$user->gear_shoes}}');"></div>
            @else
            <div class="gear-circular" style="background-image:url('{{ asset('assets/images/gear_shoes.jpg')}}');"></div>
            @endif

            @if($user->flag_bag&&$user->gear_bag)
            <div class="gear-circular" style="background-image:url('{{ asset('uploads/gears/bag') }}/{{$user->id}}/{{$user->gear_bag}}');"></div>
            @else
            <div class="gear-circular" style="background-image:url('{{ asset('assets/images/gear_bag.jpg')}}');"></div>
            @endif


           
          </p>
           
        </div>
        <div class="col-md-3 left">
          <h2>สังกัดทีม</h2>
           @foreach($user->teams as $team)
            <div class='team-icon'><a href='{{route('team',array($team->team->id,$team->team->name))}}'>
            <p>
            @if($team->team->logo)
                      <img src="{{ asset('uploads/teams/') }}/{{$team->team->id}}/{{$team->team->logo}}" class='team-logo-icon'/>
                    @else
                      <img src="{{ asset('assets/images/unknow.png') }}" class='team-logo-icon'/>
                    @endif
            <h5>{{ $team->team->name }}</h5></p>
            </a></div>
          @endforeach
           <h2>เหรียญรางวัล</h2>
           @foreach($user->userbadges as $userbadge)
            <div class='badge-icon'><a href='javascript:void(0);' onclick='showBadge({{$userbadge->badge->id}},"{{$userbadge->badge->name}}","{{$userbadge->badge->desc}}")'>
            <p><img src='{{ asset('assets/images/badge/'.$userbadge->badge->id.'.png') }}' alt="{{$userbadge->badge->name}}"/></p>
            </a></div>
          @endforeach
        </div>
    </div>
  
  </div>
  <div class="col-md-12 " style="margin-top: 0px;">
    <div class="arrow-up"></div>
    <div class="profile-meta">
      
       @if($user->fb_id)
             <a href="{{$user->link}}" target='_blank' class='btn btn-primary btn-xs' ><i class="fa fa-share-alt-square fa-lg"></i> Facebook Connect</a>
            @else
            @endif
       <a href="mailto:{{$user->email}}" class='btn btn-success btn-xs' ><i class="fa fa-paper-plane-o fa-lg"></i> ส่งอีเมล์</a>
       <div class="fb-share-button" data-href="{{Request::url()}}" data-type="button"></div>
  </div>
</div>




@stop