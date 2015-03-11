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
              else if(data=='soldout'){

                      bootbox.alert("<div class='alert alert-danger'> <span class='glyphicon glyphicon-exclamation-sign' ></span> สินค้าถูกจองหมด หรือไม่มีวางจำหน่ายแล้ว</div>", function() {
                        redrawgrid();
                });

              }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
              console.log(textStatus+":"+errorThrown);     
          }
      });
  }
}
function redrawgrid(){
  location.href='{{ action('HomeController@index'); }}';
}
    </script>
@stop
@section('content')
@if(Session::has('message'))
    {{ Session::get('message') }}
@endif

<div id="row">
  <div id="itemcontainer">
    @foreach ($items as $item)
    <?php $img=explode(",",$item->filelist) ;
          $image=Imagecache::get($item->owner->id.'/'.$img[0], 'home');
    ?>
      <div class="item">
      
      <div class="@if($item->status!='new')arrow-right @endif tag-{{$item->status}}">
       @if($item->status!='new')<span>{{$item->status_thai()}}</span>@endif
      </div>
      <!--<div class="itembadge itembadge-{{$item->status}}" ></div>-->
      <a href="{{route('item',array($item->id))}}">
      <img style="width:100%" src="{{ $image->src }}" />
      
      <div class="iteminfo">
     <div class="pricetag">{{$item->price}}.00 ฿</div>
      <h2>{{$item->itemname}}</h2>
      <p>{{$item->detail}}</p>
      </div>
      </a>
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
