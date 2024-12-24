@extends('layouts.app')
@section('content')
<div class="lp_section">

  <form action="{{ route('landing_pages.index') }}" method="GET">

    <!-- フィルタ適用後の件数表示 -->
    <div class="filter-count">
      @if ($currentFilters)
      <h5>
        検索結果：{{ $filtered_other_designs_count }} 件
      </h5>
      @endif
    </div>

    <!-- フィルタのサマリー表示 -->
    <div class="filter-summary">
      @if(!empty($currentFilters['other_category']))
      @foreach((array)$currentFilters['other_category'] as $other_categoryId)
      @php
      $other_category = $other_major_categories->flatMap(function($other_major_category) {
      return $other_major_category->other_categories;
      })->where('id', $other_categoryId)->first();
      @endphp

      @if($other_category)
      <div class="filter-item">
        <div>{{ $other_category->name }}</div>
        <a href="#" class="filter_delete_button" data-filter-type="other_category" data-filter-value="{{ $other_category->id }}">×</a>
      </div>
      @endif
      @endforeach
      @endif

      @if(!empty($currentFilters['colors']))
      @foreach($currentFilters['colors'] as $colorId)

      @php
      $color = $colors->where('id', $colorId)->first();
      @endphp

      @if($color)
      <div class="filter-item">
        <div>{{ $color->name }}</div>
        <a href="#" class="filter_delete_button" data-filter-type="color" data-filter-value="{{ $color->id }}">×</a>
      </div>
      @endif

      @endforeach
      @endif

      @if(!empty($currentFilters['keyword']))
      <div class="filter-item">
        <div>キーワード: {{ $currentFilters['keyword'] }}</div>
        <a href="#" class="filter_delete_button" data-filter-type="keyword" data-filter-value="{{ $currentFilters['keyword'] }}">×</a>
      </div>
      @endif
    </div>

    <!-- 並び替え機能 -->
    <div class="sort_container">
      <select class="sort_select" name="sort" onchange="this.form.submit()">
        <option value="">並び替え</option>
        <option value="created_at_asc" {{ request()->get('sort') == 'created_at_asc' ? 'selected' : '' }}>登録日が古い順</option>
        <option value="created_at_desc" {{ request()->get('sort') == 'created_at_desc' ? 'selected' : '' }}>登録日が新しい順</option>
        <option value="updated_at_asc" {{ request()->get('sort') == 'updated_at_asc' ? 'selected' : '' }}>更新日が古い順</option>
        <option value="updated_at_desc" {{ request()->get('sort') == 'updated_at_desc' ? 'selected' : '' }}>更新日が新しい順</option>
        <option value="favorites_count_desc" {{ request()->get('sort') == 'favorites_count_desc' ? 'selected' : '' }}>お気に入り多い順</option>
      </select>
      @if (!$favorites_flag)
      <a href="{{ route('landing_pages.index') }}" class="list_view_button" style="text-decoration: none;">
        <!DOCTYPE svg PUBLIC '-//W3C//DTD SVG 1.1//EN' 'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'><svg enable-background="new 0 0 32 32" height="30px" style="margin-bottom: 4px;" id="Layer_1" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="grid-2">
            <path d="M10.246,4.228c0-0.547-0.443-0.991-0.99-0.991H3.914c-0.548,0-0.991,0.443-0.991,0.991V9.57   c0,0.546,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.444,0.99-0.99V4.228z" fill="#e5508b" />
            <path d="M19.453,4.228c0-0.547-0.443-0.991-0.991-0.991h-5.343c-0.546,0-0.99,0.443-0.99,0.991V9.57   c0,0.546,0.444,0.99,0.99,0.99h5.343c0.548,0,0.991-0.444,0.991-0.99V4.228z" fill="#e5508b" />
            <path d="M28.868,4.228c0-0.547-0.443-0.991-0.99-0.991h-5.342c-0.548,0-0.991,0.443-0.991,0.991V9.57   c0,0.546,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.444,0.99-0.99V4.228z" fill="#e5508b" />
            <path d="M10.246,13.224c0-0.547-0.443-0.99-0.99-0.99H3.914c-0.548,0-0.991,0.443-0.991,0.99v5.342   c0,0.549,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.441,0.99-0.99V13.224z" fill="#e5508b" />
            <path d="M19.453,13.224c0-0.547-0.443-0.99-0.991-0.99h-5.343c-0.546,0-0.99,0.443-0.99,0.99v5.342   c0,0.549,0.444,0.99,0.99,0.99h5.343c0.548,0,0.991-0.441,0.991-0.99V13.224z" fill="#e5508b" />
            <path d="M28.868,13.224c0-0.547-0.443-0.99-0.99-0.99h-5.342c-0.548,0-0.991,0.443-0.991,0.99v5.342   c0,0.549,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.441,0.99-0.99V13.224z" fill="#e5508b" />
            <path d="M10.246,22.43c0-0.545-0.443-0.99-0.99-0.99H3.914c-0.548,0-0.991,0.445-0.991,0.99v5.344   c0,0.547,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.443,0.99-0.99V22.43z" fill="#e5508b" />
            <path d="M19.453,22.43c0-0.545-0.443-0.99-0.991-0.99h-5.343c-0.546,0-0.99,0.445-0.99,0.99v5.344   c0,0.547,0.444,0.99,0.99,0.99h5.343c0.548,0,0.991-0.443,0.991-0.99V22.43z" fill="#e5508b" />
            <path d="M28.868,22.43c0-0.545-0.443-0.99-0.99-0.99h-5.342c-0.548,0-0.991,0.445-0.991,0.99v5.344   c0,0.547,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.443,0.99-0.99V22.43z" fill="#e5508b" />
          </g>
        </svg>
      </a>
      @else
      <a href="{{ route('landing_pages.index') }}" class="list_view_button" style="text-decoration: none;">
        <!DOCTYPE svg PUBLIC '-//W3C//DTD SVG 1.1//EN' 'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'><svg enable-background="new 0 0 32 32" height="30px" style="margin-bottom: 4px;" id="Layer_1" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="grid-2">
            <path d="M10.246,4.228c0-0.547-0.443-0.991-0.99-0.991H3.914c-0.548,0-0.991,0.443-0.991,0.991V9.57   c0,0.546,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.444,0.99-0.99V4.228z" fill="#515151" />
            <path d="M19.453,4.228c0-0.547-0.443-0.991-0.991-0.991h-5.343c-0.546,0-0.99,0.443-0.99,0.991V9.57   c0,0.546,0.444,0.99,0.99,0.99h5.343c0.548,0,0.991-0.444,0.991-0.99V4.228z" fill="#515151" />
            <path d="M28.868,4.228c0-0.547-0.443-0.991-0.99-0.991h-5.342c-0.548,0-0.991,0.443-0.991,0.991V9.57   c0,0.546,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.444,0.99-0.99V4.228z" fill="#515151" />
            <path d="M10.246,13.224c0-0.547-0.443-0.99-0.99-0.99H3.914c-0.548,0-0.991,0.443-0.991,0.99v5.342   c0,0.549,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.441,0.99-0.99V13.224z" fill="#515151" />
            <path d="M19.453,13.224c0-0.547-0.443-0.99-0.991-0.99h-5.343c-0.546,0-0.99,0.443-0.99,0.99v5.342   c0,0.549,0.444,0.99,0.99,0.99h5.343c0.548,0,0.991-0.441,0.991-0.99V13.224z" fill="#515151" />
            <path d="M28.868,13.224c0-0.547-0.443-0.99-0.99-0.99h-5.342c-0.548,0-0.991,0.443-0.991,0.99v5.342   c0,0.549,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.441,0.99-0.99V13.224z" fill="#515151" />
            <path d="M10.246,22.43c0-0.545-0.443-0.99-0.99-0.99H3.914c-0.548,0-0.991,0.445-0.991,0.99v5.344   c0,0.547,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.443,0.99-0.99V22.43z" fill="#515151" />
            <path d="M19.453,22.43c0-0.545-0.443-0.99-0.991-0.99h-5.343c-0.546,0-0.99,0.445-0.99,0.99v5.344   c0,0.547,0.444,0.99,0.99,0.99h5.343c0.548,0,0.991-0.443,0.991-0.99V22.43z" fill="#515151" />
            <path d="M28.868,22.43c0-0.545-0.443-0.99-0.99-0.99h-5.342c-0.548,0-0.991,0.445-0.991,0.99v5.344   c0,0.547,0.443,0.99,0.991,0.99h5.342c0.547,0,0.99-0.443,0.99-0.99V22.43z" fill="#515151" />
          </g>
        </svg>
      </a>
      @endif
      <a href="{{ route('other_favorites.index') }}" id="favoritesLink" class="favorites_filter">
        <i class="fa-solid fa-heart {{ $favorites_flag ? 'heart-pink' : '' }}"></i>
      </a>

      <!-- 既存のフィルタ条件を隠しフィールドとして保持 -->
      @if(request()->has('keyword'))
      <input type="hidden" name="keyword" value="{{ request()->get('keyword') }}">
      @endif
      @if(request()->has('contents_type'))
      <input type="hidden" name="contents_type" value="{{ request()->get('contents_type') }}">
      @endif
      @if(request()->has('other_category'))
      @foreach(request()->get('other_category') as $other_category)
      <input type="hidden" name="other_category[]" value="{{ $other_category }}">
      @endforeach
      @endif
      @if(request()->has('colors'))
      @foreach(request()->get('colors') as $color)
      <input type="hidden" name="colors[]" value="{{ $color }}">
      @endforeach
      @endif
  </form>
  {{-- フォルダ選択のセレクトボックス --}}
  @if($favorites_flag)
  <div class="filter-folder">
    <form action="{{ route('other_favorites.index') }}" method="GET">
      <select class="folder_select" name="folder_id" onchange="this.form.submit()">
        <option value="">すべてのお気に入り</option>
        @foreach($userFolders as $folder)
        <option value="{{ $folder->id }}" {{ request('folder_id') == $folder->id ? 'selected' : '' }}>{{ $folder->name }}</option>
        @endforeach
      </select>
      @if (!$selectedFolderId == null)
      <!-- フォルダ操作ボタン -->
      <button type="button" class="other_folder_management_button" data-bs-toggle="modal" data-bs-target="#folderManagementModal" data-folder-id="{{ $selectedFolderId }}" data-folder-name="{{ $selectedFolderName }}">フォルダ管理</button>
      @endif
    </form>
  </div>
  @endif
</div>

<!-- LPカードの表示 -->
<div class="lp_container">
  @foreach($other_designs as $other_design)
  <div class="lp_card">
    <div class="created_at" style="display: none;">{{ $other_design->updated_at->format('M. d. Y'); }}</div>
    <div class="lp_card_img_container">
      <a href="{{ route('others.show', $other_design->id) }}">
        <div class="lp_card_img other_img" style="background-image: url('{{ asset($other_design->image) }}');"></div>
      </a>
    </div>
    <div class="lp_card_data">
      <p class="lp_card_label">{{ $other_design->name }}</p>
      @auth
      <a href="#" class="other_favorite_button {{ in_array($other_design->id, $other_favorite_ids) ? 'favorite-active' : '' }}" data-bs-toggle="modal" data-bs-target="#favoriteModal" data-other-design-id="{{ $other_design->id }}" data-folder-id="{{ $otherDesignsFolderIds[$other_design->id] ?? '' }}" data-img-url="{{ asset($other_design->image) }}">
        <i class="fa-solid fa-heart"></i> <span class="favorites_count">{{$other_design->favorites_count}}</span>
      </a>
      @endauth
      @guest
      <a href="{{ route('login') }}" class="favorite_button_guest {{ in_array($other_design->id, $other_favorite_ids) ? 'favorite-active' : '' }}">
        <i class="fa-solid fa-heart"></i> <span class="favorites_count">{{$other_design->favorites_count}}</span>
      </a>      
      @endguest
    </div>
  </div>
  @endforeach
</div>


<!-- ページネーション -->
{{ $other_designs->links() }}
</div>


<!-- お気に入り登録のためのモーダル表示 -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">お気に入りに追加する</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Modal Body -->
      <div class="modal-body">
        <div class="modal-lp-img mx-4"></div>
        <form id="favoriteForm" action="{{ route('other_favorites.store') }}" method="POST">
          @csrf
          <input type="hidden" id="other_design_id" name="other_design_id" value="">
          <!-- ラジオボタンとセレクトボックスの切り替え -->
          <div class="mb-3">
            <label class="form-label">フォルダ選択</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="folder_option" id="newFolderOptionRegister" value="new" checked>
              <label class="form-check-label" for="newFolderOptionRegister">新規フォルダ</label><br>
              <input class="form-check-input" type="radio" name="folder_option" id="existingFolderOption" value="existing">
              <label class="form-check-label" for="existingFolderOption">既存フォルダ</label>
            </div>
            <!-- 既存フォルダを選択するセレクトボックス -->
            <select class="form-select mt-3" name="folder_id" id="folderId" style="display: none;">
              <option value="">選択してください</option>
              @foreach($userFolders as $folder)
              <option value="{{ $folder->id }}">{{ $folder->name }}</option>
              @endforeach
            </select>
          </div>
          <!-- 新規フォルダを入力するフィールド -->
          <div class="mb-3" id="newFolderGroup">
            <label for="newFolder" class="form-label">新規フォルダ名</label>
            <input type="text" class="form-control" id="newFolder" name="new_other_folder_name">
          </div>
        </form>
      </div>
      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
        <button type="submit" class="btn btn-primary" form="favoriteForm">保存</button>
      </div>
    </div>
  </div>
</div>

<!-- 更新/削除用モーダル -->
<div class="modal fade" id="updateDeleteModal" tabindex="-1" aria-labelledby="updateDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="updateDeleteModalLabel">お気に入りの編集</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Modal Body -->
      <div class="modal-body">
        <div class="modal-lp-img mx-4"></div>
        <form id="updateFavoriteForm" action="{{ route('other_favorites.update') }}" method="POST">
          @csrf
          <input type="hidden" id="update_other_design_id" name="other_design_id">

          <!-- フォルダ選択オプション -->
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="folder_option" id="existingFolderOption" value="existing" checked>

              <label class="form-check-label" for="existingFolderOption">既存フォルダ</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="folder_option" id="newFolderOptionUpdate" value="new">
              <label class="form-check-label" for="newFolderOptionUpdate">新規フォルダ</label>
            </div>

            <!-- 新規フォルダ名入力ボックス -->
            <div class="my-3" id="newFolderNameGroup" style="display: none;">
              <label for="newFolderName" class="form-label">新規フォルダ名</label>
              <input type="text" class="form-control" id="newFolderNameUpdate" name="new_folder_name">
            </div>

            <!-- 既存フォルダの選択 -->
            <div class="my-3" id="updateFolderGroup">
              <label for="updateFolder" class="form-label">保存先フォルダ</label>
              <select class="form-select" name="other_folder_id" id="updateFolder">
                @foreach($userFolders as $folder)
                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <button type="submit" class="btn btn-primary w-100">変更を保存</button>
            </div>
        </form>
        <!-- お気に入り削除ボタン -->
        <div class="mb-3">
          <form id="deleteFavoriteForm" action="{{ route('other_favorites.destroy') }}" method="POST">
            @csrf
            <input type="hidden" id="delete_other_design_id" name="other_design_id">
            <button type="submit" class="btn btn-danger w-100">お気に入りから削除</button>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
    </div>
  </div>
</div>
</div>

<!-- フォルダ管理モーダル -->
<div class="modal fade" id="folderManagementModal" tabindex="-1" aria-labelledby="folderManagementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="folderManagementModalLabel">フォルダ管理</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="display: flex; flex-direction: column;">
        <!-- 現在のフォルダ名表示 -->
        <div class="mb-3">
          <label for="newFolderName" class="form-label">現在のフォルダ名：</label>
          <span id="currentFolderName">ここに表示されます</span>
        </div>
        <!-- フォルダ名変更フォーム -->
        <form id="renameFolderForm" action="{{ route('other_folders.update') }}" method="POST">
          @csrf
          <input type="hidden" id="renameFolderId" name="selectedFolderId" value="{{ $selectedFolderId }}">
          <div class="mb-3">
            <label for="newFolderName" class="form-label">新しいフォルダ名</label>
            <input type="text" class="form-control" id="newFolderName" name="new_folder_name" required>
          </div>
          <button type="submit" class="btn btn-primary">保存</button>
        </form>
        <!-- フォルダ削除フォーム -->
        <form id="deleteFolderForm" action="{{ route('other_folders.destroy') }}" method="POST" class="mt-4">
          @csrf
          <input type="hidden" id="deleteFolderId" name="selectedFolderId">
          <button type="submit" class="btn btn-danger">フォルダを削除</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection