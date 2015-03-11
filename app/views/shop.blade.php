@extends('layouts.main')
@section('title')
siamplant.com ตลาดต้นไม้หน้าแรก
@stop
@section('script')
<script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script>
$( document ).ready(function( ) {

// init

var $container = $('#itemcontainer').imagesLoaded( function() {
  $container.isotope({
  // options
    itemSelector: '.item'
  });
});


});

function addFav(thisobj,itemid){
  
  if(!thisobj.hasClass('item-fav-hover')){
   $.ajax(
      {
          url : '{{route('addfav')}}',
          type: "POST",
          data : {'itemid':itemid},
          success:function(data, textStatus, jqXHR) 
          {

            console.log(data);
              if(data==1)thisobj.addClass('item-fav-hover');
              else {console.log('add favourites error');}
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
              console.log(textStatus+":"+errorThrown);     
          }
      });
  }
  else{
      $.ajax(
      {
          url : '{{route('removefav')}}',
          type: "POST",
          data : {'itemid':itemid},
          success:function(data, textStatus, jqXHR) 
          {
            console.log(data);
              if(data==1)thisobj.removeClass('item-fav-hover');
              else {console.log('remove favourites error');}
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
              console.log(textStatus+":"+errorThrown);     
          }
      });

  }     
}

function addCart(thisobj,itemid){
  
  if(!thisobj.hasClass('item-cart-hover')){
   $.ajax(
      {
          url : '{{route('addcart')}}',
          type: "POST",
          data : {'itemid':itemid},
          success:function(data, textStatus, jqXHR) 
          {

            console.log(data);
              if(data==1)thisobj.addClass('item-cart-hover');
              else {console.log('add cart error');}
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
              console.log(textStatus+":"+errorThrown);     
          }
      });
  }
}

    </script>
@stop
@section('content')
@if(Session::has('message'))
    {{ Session::get('message') }}
@endif
<div class="row">
  <div class="shop-meta round">
    
    <?php
          if(Auth::user()->shoppicture)$image=Imagecache::get(Auth::user()->id.'/profile/'.Auth::user()->shoppicture, 'shopprofile');
          else  $image=Imagecache::get('no-shop-image.jpg', 'shopprofile');
        ?>
     <div class="col-xs-2 shop-icon-wrapper" style="background-image: url(
     {{$image->src}}
      );">
     
        
     </div>
     <div class="col-xs-3 shop-meta-block">
     <h1>{{$user->shopname}}</h1>
        <p class="grey">{{$user->shopdetail}}</p>
          
     </div>
     <div class="col-xs-4 shop-meta-block" style="padding-top: 40px;">
        <p class="grey"><i class="fa fa-home"></i> {{$user->shopaddress}} {{$user->shopprovince}}</p>
        <p class="grey"><i class="fa fa-envelope-o"></i><a href="mailto:{{$user->shopemail}}">  {{$user->shopemail}}</a></p>
        <p class="grey"><i class="fa fa-phone-square"></i>  {{$user->shoptel}}</p>
        <p class="grey"><span class="glyphicon glyphicon-grain" aria-hidden="true"></span> เริ่มขาย: {{$user->start_sell_thai_date()}}</p>
     </div>
     <div class="col-xs-1 shop-meta-block text-center">
     <p class="grey">วางขายแล้ว</p>
     <p class="numsold">{{$numsold}}</p>
     <p class="grey">รายการ</p>
     </div>
     <div class="col-xs-2 shop-meta-block">
     </div>
  </div>
</div>
<div id="row">
  <div id="itemcontainer">
    @foreach ($items as $item)
     <?php $img=explode(",",$item->filelist); 
    $image=Imagecache::get($item->owner->id.'/'.$img[0], 'home');
    ?>
      <div class="item round">
      <div class="@if($item->status!='new')side-corner-tag @endif">
      <!--<div class="itembadge itembadge-{{$item->status}}" ></div>-->
      <img style="width:100%" src="{{$image->src}}" />
       @if($item->status!='new')<p><span class="tag-{{$item->status}}">{{$item->status_thai()}}</span></p>@endif
      </div>
     <div class="pricetag">{{$item->price}}.00 ฿</div>
      <h2>{{$item->itemname}}</h2>
      <p>{{$item->detail}}</p>
      <div class='item-feature'>
        <div class='itemdate'><i class="fa fa-clock-o"></i> {{$item->thai_date()}}</div>
        <div class='item-btn item-fav @if(Auth::user()&&is_array(unserialize(Auth::user()->favitems)) and in_array($item->id,unserialize(Auth::user()->favitems))) item-fav-hover @endif' onclick="addFav($(this),{{$item->id}})"><i class="fa fa-heart"></i></div>
       
        <a class='item-btn item-shop' href="{{ route('shop',$item->owner->username ) }}"><i class="fa fa-leaf"></i></a>
        <div class='item-btn item-cart @if(Auth::user()&&is_array(unserialize($item->reserved_user)) and in_array(Auth::user()->id,unserialize($item->reserved_user))) item-cart-hover @endif' onclick="addCart($(this),{{$item->id}})"><i class="fa fa-shopping-cart"></i></div>
      </div>
    </div>  
    @endforeach
  
   
  </div>
</div>

@stop
