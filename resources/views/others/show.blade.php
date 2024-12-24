@extends('layouts.app')

@section('content')

<div class="lp_section lp_section_show">
  <div class="lp_show_container">
    <div class="lp_show_head">
      <div>
        <h1 class="lp_title">{{ $other_design->name }}</h1>
      </div>
      <div class="">
        <p class="fav-btn">
          <a href=""></a>
        </p>
        <p class="fav-num"></p>
      </div>
    </div>
    <hr>
    <div class="lp_show_card">
        <img src="{{ asset($other_design->image) }}" class="others_show_img">
    </div>
    <hr>
    <div>
      <div class="show_back_button">
        <a class="page-link bg-black" id="back_button" onclick="goBack()">＜</a>
      </div>
      <div class="show_back_label">
        <p>戻る</p>
      </div>
    </div>
  </div>
</div>

<script>
  function goBack() {
    window.history.back();
  }
</script>

@endsection