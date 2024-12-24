<div class="header_bar">
    <a class="header_logo" href="{{ route('landing_pages.index') }}">design archive</a>
</div>
<header class="header">
    <div class="hd_bars">
        <a><i class="fa-solid fa-bars"></i></a>
    </div>
    <form action="{{ route('landing_pages.index') }}" method="GET" id="searchForm" class="hd_search_box">
        <div class="hd_select">
            <div id="hd_select_btn">
                <div id="hd_selected"></div>
            </div>
            <select id="hd_select_list" name="contents_type">
                <option value="pc" @if(request()->get('contents_type') == 'pc') selected @endif>パソコンLP</option>
                <option value="sp" @if(request()->get('contents_type') == 'sp') selected @endif>スマホLP</option>
                <option value="banner" @if(request()->get('contents_type') == 'banner') selected @endif>バナー</option>
                <option value="flyer" @if(request()->get('contents_type') == 'flyer') selected @endif>チラシ</option>
                <option value="line" @if(request()->get('contents_type') == 'line') selected @endif>LINE</option>
                <option value="instagram" @if(request()->get('contents_type') == 'instagram') selected @endif>インスタ</option>
                <option value="thumbnail" @if(request()->get('contents_type') == 'thumbnail') selected @endif>サムネイル</option>
                <option value="other" @if(request()->get('contents_type') == 'other') selected @endif>その他</option>
            </select>

        </div>
        <input type="text" name="keyword" class="search_form" value="{{ $currentFilters['keyword'] ?? '' }}" placeholder="キーワードで探す">

        @if(request()->has('category'))
        @foreach(request()->get('category') as $category)
        <input type="hidden" name="category[]" value="{{ $category }}">
        @endforeach
        @endif
        @if(request()->has('colors'))
        @foreach(request()->get('colors') as $color)
        <input type="hidden" name="colors[]" value="{{ $color }}">
        @endforeach
        @endif
        @if(request()->has('sort'))
        <input type="hidden" name="sort" value="{{ request()->get('sort') }}">
        @endif
        <button type="submit" class="search_button" name="action" value="search" style="background-image: url( {{ asset('img/search_button.png') }} ) ;"></button>
    </form>
    <div class="hd_menu">
        @guest
        <a class="mypage_button"><i class="fa-solid fa-right-to-bracket mypage_login"></i></a>
        <div class="mypage_menu" style="display: none;">
            <div class="mypage_container">
                <div><a href="{{ route('login') }}">ログイン</a></div>
                <div><a href="{{ route('register') }}">新規登録</a></div>
            </div>
        </div>
        @endguest

        @auth
        <a class="mypage_button"><i class="fa-solid fa-circle-user mypage"></i></a>
        <div class="mypage_menu" style="display: none;">
            <div class="mypage_container">
                <div><a href="{{ route('profile.edit') }}">アカウント編集 </a></div>
                <div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </div>
</header>