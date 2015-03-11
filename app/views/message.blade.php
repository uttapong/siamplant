@extends('layouts.main')
@section('title')
Teebadgun.com
@stop
@section('content')

<div class="row" >
  	<div class="col-md-12 left">
               @if(Session::has('message'))
				        {{ Session::get('message') }}
				    
			@endif
	<a class='btn btn-primary btn-large' href='{{ route('home') }}'>กลับสู่หน้าหลัก</a>
	</div>
</div>




@stop
