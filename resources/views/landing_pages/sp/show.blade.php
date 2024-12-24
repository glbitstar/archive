@extends('layouts.app')

@section('content')

<div class="lp_section lp_section_show">
<div class="breadcrumb"><a href="{{ route('landing_pages.index') }}">ホーム</a>＞ {{ $major_category->name }} ＞ {{ $category->name }} ＞ {{ $landing_page->title }}</div>
  <div class="lp_show_container">
    <div class="lp_show_head">
      <div>
        <h1>{{ $landing_page->title }}</h1>
      </div>
      <div class="">
        <p class="fav-btn">
          <a href=""></a>
        </p>
        <p class="fav-num"></p>
      </div>
      @if($landing_page->pc_url)
      <div class="show_toggle_container">
        <div class="show_toggle_box">
          <a href="{{ route('landing_pages.show', $landing_page->id ) }}" class="show_toggle_button">PCページはこちら</a>
        </div>
      </div>
      @endif
    </div>
    <hr>
    <div class="lp_show_sp_card">
      <a href="{{ $landing_page->sp_url }}">
        <img src="{{ asset($landing_page->sp_image) }}" class="lp_show_img">
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