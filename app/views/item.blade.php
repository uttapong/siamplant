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
    <?php $img=explode(",",$item->filelist) ;
//echo $order->item->owner->id.'/'.$img[0];
      $image=Imagecache::get($item->owner->id.'/'.$img[0], 'single');

        ?>
    
     <div class="col-xs-4 single-img">
      <img class="round" src="{{$image->src}}" width="100%" />
      <div class="single-meta">
      </div>
          
     </div>
     <div class="col-xs-8 single-info" >
        <div class="row">
            <div class="col-md-3">
            <h1 class="grey">{{$item->itemname}}</h1>
            </div>
            <div class="col-md-3">
                <div class="single-price">{{$item->price}} ฿</div>
            </div>
            <div class="col-md-6">
                 <div class="single-btn"><button class="btn btn-info">หยิบใส่ตะกร้า</button></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div role="tabpanel" class="single-tab">

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#itemdata" aria-controls="itemdata" role="tab" data-toggle="tab">ข้อมูลสินค้า</a></li>
                    <li role="presentation"><a href="#sellerdata" aria-controls="sellerdata" role="tab" data-toggle="tab">ข้อมูลผู้ขาย</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active single-tab-panel" id="itemdata">
                        <p class="grey"><strong>หมวดหมู่สินค้า </strong>{{$item->category->name}}</p>
                        <p class="grey"><strong>ข้อมูลสินค้า </strong>{{$item->detail}}</p>
                        <p class="grey"><strong>ลงประกาศ </strong>{{$item->thai_date_single()}}</p>

                    </div>
                    <div role="tabpanel" class="tab-pane single-tab-panel" id="sellerdata">test</div>
                  </div>

                </div>
            
            </div>
        </div>
        
     </div>


</div>


@stop
