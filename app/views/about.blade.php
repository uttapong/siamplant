@extends('layouts.main')
@section('title')
Teebadgun.com : เกี่ยวกับเรา
@stop
@section('script')
<script>
</script>
@stop
@section('style')

@stop
@section('content')

<div class="row">
  <div class="col-md-12">
    <h1>ตีแบดกันดอทคอม</h1>
    <p>{{$about->content}}</p>
  </div>
</div>
@stop