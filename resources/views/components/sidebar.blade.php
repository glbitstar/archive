<div class="aside">
    <button id="toggleSidebar" class="" style="background-image: url( {{ asset('img/aside_button.png') }} );"></button>
</div>

<div class="sidebar">
    <div class="side_close" style="display:none;">
        <a><i class="fa-solid fa-xmark"></i></a>
    </div>
    <form method="GET" action="{{ route('landing_pages.index') }}" id="filterForm">

        @if(request()->has('keyword'))
        <input type="hidden" name="keyword" value="{{ request()->get('keyword') }}">
        @endif
        @if(request()->has('sort'))
        <input type="hidden" name="sort" value="{{ request()->get('sort') }}">
        @endif

        <div class="toggle-switch">
            <div class="toggle-labels">
                <button id="toggle-pc" class="toggle-button {{ $contents_type === 'pc' ? 'active' : '' }}" input="" name="contents_type" value="pc"><span class="label-left" data-page="pc">PCデザイン</span></button>
                <button id="toggle-sp" class="toggle-button {{ $contents_type === 'sp' ? 'active' : '' }}" input="" name="contents_type" value="sp"><span class="label-right" data-page="sp">SPデザイン</span></button>
            </div>
        </div>

        <div class="side_lp_count">
            @if($contents_type === 'sp')
            <div>
                <h5 class="lp_count_title">SPデザイン登録総数</h5>
            </div>
            <div>
                <h1 class="count_pages">{{ number_format($count_sp_pages) }}</h1>
            </div>
            @else
            <div>
                <h5 class="lp_count_title">PCデザイン登録総数</h5>
            </div>
            <div>
                <h1 class="count_pages">{{ number_format($count_pc_pages) }}</h1>
            </div>
            @endif
        </div>

        <div class="side_category">
            <div class="category_title">
                <!-- <img src="{{ asset('img/folder_icon.png') }}" class="side_icon"> -->
                <p>ジャンルから探す</p>
            </div>
            <div class="accordion-top">
                <div class="accordion-container">
                    <div class="accordion-item">
                        @foreach($major_categories as $major_category)
                        <div class="accordion-section">
                            <div class="accordion-title">
                                <div>
                                    <input type="checkbox" class="parent-checkbox" name="major_category[]" value="{{ $major_category->id }}" {{ in_array($major_category->id, isset($currentFilters['major_category']) && is_array($currentFilters['major_category']) ? $currentFilters['major_category'] : []) ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <p>{{ $major_category->name }}</p>
                                </div>
                            </div>
                            <div class="accordion-content accordion-content-{{ $major_category->id }}">
                                @foreach($major_category->categories as $category)
                                <div class="accordion-list">
                                    <div>
                                        <input type="checkbox" class="child-checkbox" name="category[]" value="{{ $category->id }}" {{ in_array($category->id, isset($currentFilters['category']) && is_array($currentFilters['category']) ? $currentFilters['category'] : []) ? 'checked' : '' }}>
                                    </div>
                                    <div>
                                        <p>{{ $category->name }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <div class="side_color"  style="border-bottom: solid 1px;">
            <div class="side_color_title"  style="border-top: solid 1px;">
                <!-- <img src="{{ asset('img/pallet_icon.png') }}" class="side_icon" /> -->
                <p class="color_title">カラーから探す</p>
            </div>
            <div class="side_color_container" style="border: none;">
                @foreach($colors as $color)
                <label class="side_color_box">
                    <div class="color_sheet" style="background-color: {{ $color->color_code }}; border: solid 1px #c0c0c0;">
                        <input type="checkbox" name="colors[]" value="{{ $color->id }}" class="color_checkbox" style="background-color: {{ $color->color_code }};" {{ in_array($color->id, $currentFilters['colors'] ?? []) ? 'checked' : '' }}>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="d-none">フィルター適用</button>
    </form>

    <form method="GET" action="{{ route('landing_pages.index') }}" id="other-filterForm">
        <div class="side_other_category">
            <div class="other_category_title">
                <!-- <img src="{{ asset('img/moon_icon.png') }}" class="side_icon"> -->
                <p>その他のデザイン</p>
            </div>
            <div class="accordion-top">
                <div class="accordion-container">
                    <div class="accordion-item">
                        @foreach($other_major_categories as $other_major_category)
                        <div class="accordion-section">
                            <div class="other-accordion-title">
                                <div>
                                <input type="checkbox" class="other-parent-checkbox" name="other_major_category[]" value="{{ $other_major_category->id }}" data-contents-type="{{ $other_major_category->contents_type }}" {{ in_array($other_major_category->id, isset($currentFilters['other_major_category']) && is_array($currentFilters['other_major_category']) ? $currentFilters['other_major_category'] : []) ? 'checked' : '' }}>
                                </div>
                                <div class="other-accordion-list">
                                    <p>{{ $other_major_category->name }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="contents_type" id="contents_type" value="other_designs">
        <button type="submit" class="d-none">フィルター適用</button>
    </form>
    <div class="side_sns">
        <div class="side_sns_container">
            @if($sns_menus)
            <div class="side_sns_item">
                @foreach($sns_menus as $sns_menu)
                <a href="{{ $sns_menu->url }}" class="circle_link">
                    <div class="circle_image" style="background-image: url('{{ $sns_menu->icon }}');"></div>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>