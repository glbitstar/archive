@extends('layouts.app')

@section('content')

<div class="lp_section lp_section_show">
  <div class="breadcrumb"><a href="{{ route('landing_pages.index') }}">ホーム</a>＞ {{ $major_category->name }} ＞ {{ $category->name }} ＞ {{ $landing_page->title }}</div>
  <div class="lp_show_container">
    <div class="lp_show_head">
      <div>
        <h1 class="lp_title">{{ $landing_page->title }}</h1>
      </div>
      <div class="">
        <p class="fav-btn">
          <a href=""></a>
        </p>
        <p class="fav-num"></p>
      </div>
      @if($landing_page->sp_url)
      <div class="show_toggle_container" style="z-index: 100;">
        <div class="show_toggle_box">
          <a href="{{ route('landing_pages.sp.show', $landing_page->id) }}" class="show_toggle_button">SPページはこちら</a>
        </div>
      </div>
      @endif
    </div>
    <hr>
    <div class="lp_show_card">
      <a href="{{ $landing_page->pc_url }}">
        <img src="{{ asset($landing_page->pc_image) }}" class="lp_show_img">
      </a>
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