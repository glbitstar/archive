<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LandingPage;
use App\Models\Color;
use App\Models\MajorCategory;
use App\Models\Category;
use App\Models\OtherDesign;
use App\Models\OtherMajorCategory;
use App\Models\OtherCategory;
use App\Models\SnsMenu;
use App\Models\Favorite;
use App\Models\Folder;
use App\Models\User;
use App\Models\FavoriteFolderRelationship;

class LandingPageController extends Controller
{
    public function getCommonData()
    {
        $count_pc_pages = LandingPage::whereNotNull('pc_url')->count();
        $count_sp_pages = LandingPage::whereNotNull('sp_url')->count();
        $colors = Color::get();
        $major_categories = MajorCategory::with('categories')->get();
        $other_major_categories = OtherMajorCategory::with('other_categories')->get();
        $sns_menus = SnsMenu::get();
        $user_folders = []; // ユーザーフォルダの初期値は空の配列
        $userFolders = [];

        // ユーザーがログインしている場合、そのフォルダを取得
        if (auth()->check()) {
            $user_folders = Folder::where('user_id', auth()->id())->get();
        }

        // ログインユーザーのお気に入りLPのIDを取得
        $favorite_ids = [];
        if (auth()->user()) {
            $favorite_ids = auth()->user()->favorite_landing_pages()->pluck('landing_page_id')->toArray();
        }

        $other_favorite_ids = [];
        if (auth()->user()) {
            $other_favorite_ids = auth()->user()->favorite_other_designs()->pluck('other_design_id')->toArray();
        }

        return compact('count_pc_pages', 'count_sp_pages', 'colors', 'major_categories', 'other_major_categories', 'sns_menus', 'user_folders', 'userFolders', 'favorite_ids', 'other_favorite_ids');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contents_type = $request->input('contents_type');

        if (in_array($contents_type, [null, 'pc', 'sp'])) {

            if ($contents_type === null) {
                $contents_type = 'pc';
            }

            $query = LandingPage::withCount('favorites');

            switch ($request->get('sort')) {
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'updated_at_asc':
                    $query->orderBy('updated_at', 'asc');
                    break;
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                case 'favorites_count_desc':
                    $query->orderBy('favorites_count', 'desc');
                    break;
            }

            // リクエストからフィルター条件の値を取得
            $categories = $request->input('category');
            $colors = $request->input('colors');
            $keyword = $request->input('keyword');

            // リクエストから削除するフィルター条件の値を取得
            $filter_type = $request->input('filter_type');
            $filter_value = $request->input('filter_delete');

            // カテゴリーのフィルター除去
            if ($filter_type === 'category' && in_array($filter_value, $categories)) {
                // $categoriesから$filter_valueを削除
                $categories = array_filter($categories, function ($value) use ($filter_value) {
                    return $value != $filter_value;
                });
                // 更新された$categoriesをリクエストまたはセッションに保存
                $request->merge(['category' => array_values($categories)]);
            }

            // カラーのフィルター除去
            if ($filter_type === 'color' && in_array($filter_value, $colors)) {
                // $colorsから$filter_valueを削除
                $colors = array_filter($colors, function ($value) use ($filter_value) {
                    return $value != $filter_value;
                });
                // 更新された$colorsをリクエストまたはセッションに保存
                $request->merge(['colors' => array_values($colors)]);
            }

            // キーワードのフィルター除去
            if ($filter_type === 'keyword' && $keyword == $filter_value) {
                // $keywordを削除
                $keyword = null;
                // 更新された$keywordをリクエストまたはセッションに保存
                $request->merge(['keyword' => $keyword]);
            }

            // フィルタ条件の適用
            if (!empty($contents_type)) {
                if ($contents_type === 'pc') {
                    $query->whereNotNull('pc_url');
                } elseif ($contents_type === 'sp') {
                    $query->whereNotNull('sp_url');
                }
            }

            if (!empty($keyword)) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            }

            if (!empty($categories)) {
                $query->whereIn('category_id', $categories);
            }

            if (!empty($colors)) {
                $query->whereIn('color_id', $colors);
            }

            // ログインユーザーのお気に入りランディングページIDとそれぞれのフォルダIDを取得
            $favoriteFolderRelationships = [];
            if (auth()->check()) {
                $userId = auth()->id();
                $favoriteFolderRelationships = FavoriteFolderRelationship::whereHas('favorite', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->with(['favorite', 'folder'])->get()->keyBy('favorite.landing_page_id');
            }

            $filtered_landingPages_count = $query->count();
            $landingPages = $query->sortable()->paginate(15);

            // ランディングページごとに保存先フォルダIDをマッピング
            $landingPagesFolderIds = $landingPages->mapWithKeys(function ($landingPage) use ($favoriteFolderRelationships) {
                return [$landingPage->id => $favoriteFolderRelationships[$landingPage->id]->folder_id ?? null];
            });

            // getCommonDataメソッドから共通データを取得
            $commonData = $this->getCommonData();

            // 現在のリクエストから全クエリパラメータを取得
            $currentFilters = $request->query();

            $favorites_flag = false;
            $selectedFolderId = null;

            // ビューにフィルタ条件、共通データ、および現在のフィルタ条件を渡す
            return view('landing_pages.index', array_merge($commonData, [
                'landingPages' => $landingPages,
                'filtered_landingPages_count' => $filtered_landingPages_count,
                'currentFilters' => $currentFilters, // 現在のフィルタ条件を追加
                'favorites_flag' => $favorites_flag,
                'contents_type' => $contents_type,
                'landingPagesFolderIds' => $landingPagesFolderIds, // 追加: ランディングページごとのフォルダID
                'selectedFolderId' => $selectedFolderId,
            ]));
        } elseif (in_array($contents_type, ['banner', 'flyer', 'line', 'instagram', 'thumbnail', 'others', 'other_designs'])) {

            $query = OtherDesign::query()->withCount('other_favorites');
            $other_major_categories = $request->input('other_major_category'); // other_major_categoryの値を取得
            $keyword = $request->input('keyword');

            // other_categoryのフィルター適用
            if (!empty($other_major_categories)) {
                $query->whereIn('other_major_category_id', $other_major_categories);
            }

            if (!empty($keyword)) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            }

            // `contents_type` が指定されていて、かつ 'other_designs' でない場合にその値でフィルタリング
            if (!empty($contents_type) && $contents_type != 'other_designs') {
                $query->whereHas('other_major_category', function ($query) use ($contents_type) {
                    $query->where('contents_type', $contents_type);
                });
            }


            $filtered_other_designs_count = $query->count();
            $other_designs = $query->paginate(15);

            // 共通データを取得
            $commonData = $this->getCommonData();

            // 現在のリクエストから全クエリパラメータを取得
            $currentFilters = $request->query();

            $favorites_flag = false;
            $selectedFolderId = null;

            // 結果をビューに渡す
            return view('others.index', array_merge($commonData, [
                'other_designs' => $other_designs,
                'currentFilters' => $currentFilters,
                'filtered_other_designs_count' => $filtered_other_designs_count,
                'favorites_flag' => $favorites_flag,
                'contents_type' => $contents_type,
                'selectedFolderId' => $selectedFolderId,
            ]));
        }
    }

    public function favorites_index(Request $request)
    {
        $query = LandingPage::withCount('favorites');
        $favorites_view_flag = true;
        $userId = Auth::user()->id;
        $folderId = $request->query('folder_id');

        $query = Favorite::with('landingPage')->where('user_id', $userId);

        $contents_type = $request->input('contents_type');

        // ユーザーのお気に入りとそれに関連するフォルダ情報を取得
        $favoritesQuery = Favorite::with('folders')->where('user_id', $userId);

        // フォルダが選択されている場合は、そのフォルダに属するお気に入りだけを取得
        if (!empty($folderId)) {
            $favoritesQuery->whereHas('folders', function ($query) use ($folderId) {
                $query->where('folders.id', $folderId);
            });
        }

        $favorites = $favoritesQuery->get();

        // お気に入りのランディングページIDを取得
        $favoriteLandingPageIds = $favorites->pluck('landing_page_id')->toArray();

        // ランディングページのクエリを絞り込む
        $query = LandingPage::withCount('favorites')->whereIn('id', $favoriteLandingPageIds);

        $filtered_landingPages_count = $query->count();
        $landingPages = $query->paginate(15);

        // ユーザーのフォルダ一覧を取得
        $userFolders = Folder::where('user_id', $userId)->get();

        // ログインユーザーのお気に入りランディングページIDとそれぞれのフォルダIDを取得
        $favoriteFolderRelationships = [];
        if (auth()->check()) {
            $userId = auth()->id();
            $favoriteFolderRelationships = FavoriteFolderRelationship::whereHas('favorite', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['favorite', 'folder'])->get()->keyBy('favorite.landing_page_id');
        }

        // ランディングページごとに保存先フォルダIDをマッピング
        $landingPagesFolderIds = $landingPages->mapWithKeys(function ($landingPage) use ($favoriteFolderRelationships) {
            return [$landingPage->id => $favoriteFolderRelationships[$landingPage->id]->folder_id ?? null];
        });

        // 選択されたフォルダ名を取得、フォルダが選択されていない場合は「すべてのお気に入り」を表示
        $selectedFolderName = $folderId ? (Folder::find($folderId) ? Folder::find($folderId)->name : null) : 'すべてのお気に入り';

        // getCommonDataメソッドから共通データを取得（このメソッドの実装は省略）
        $commonData = $this->getCommonData();

        // 現在のリクエストから全クエリパラメータを取得
        $currentFilters = $request->except('page');

        $favorites_flag = true;

        // ビューにフィルタ条件、共通データ、および現在のフィルタ条件を渡す
        return view('landing_pages.index', array_merge($commonData, [
            'landingPages' => $landingPages,
            'userFolders' => $userFolders, // フォルダ一覧をビューに渡す
            'contents_type' => $contents_type,
            'currentFilters' => $currentFilters,
            'favorites_flag' => $favorites_flag,
            'filtered_landingPages_count' => $filtered_landingPages_count,
            'landingPagesFolderIds' => $landingPagesFolderIds, // 追加: ランディングページごとのフォルダID
            'selectedFolderId' => $folderId, // 選択されたフォルダIDをビューに渡す
            'selectedFolderName' => $selectedFolderName, // 選択されたフォルダ名または「すべてのお気に入り」をビューに渡す
        ]));
    }


    public function others_index(Request $request)
    {
        $keyword = $request->keyword;
        $creative_type = $request->creative_type;

        $query = OtherDesign::query();

        if ($request->filled('other_category')) {
            $query->where('other_category_id', $request->other_category);
            $other_category = OtherCategory::find($request->other_category);
        } else {
            $other_category = null;
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->keyword}%");
            });
        }

        $total_count = $query->total();
        $other_designs = $query->paginate(15);

        $commonData = $this->getCommonData();
        return view('others.index', array_merge($commonData, compact('other_designs', 'other_category', 'total_count', 'keyword', 'creative_type')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $landing_page = LandingPage::with('category')->find($id);

        $category = Category::find($landing_page->category_id);
        $major_category = MajorCategory::find($category->major_category_id);

        // 現在のリクエストから全クエリパラメータを取得
        $currentFilters = $request->query();
        $contents_type = $request->query('contents_type');

        $commonData = $this->getCommonData();
        return view('landing_pages.show', array_merge($commonData, compact('landing_page', 'category', 'major_category', 'currentFilters', 'contents_type')));
    }

    public function sp_show(Request $request, $id)
    {
        $landing_page = LandingPage::find($id);

        $currentFilters = $request->query();
        $contents_type = "sp";

        $category = Category::find($landing_page->category_id);
        $major_category = MajorCategory::find($category->major_category_id);

        $commonData = $this->getCommonData();
        return view('landing_pages.sp.show', array_merge($commonData, compact('landing_page', 'currentFilters', 'contents_type', 'category', 'major_category')));
    }

    public function others_show(Request $request, $id)
    {

        $other_design = OtherDesign::find($id);

        $currentFilters = $request->query();
        $contents_type = $request->input('contents_type');

        $commonData = $this->getCommonData();
        return view('others.show', array_merge($commonData, compact('other_design', 'currentFilters', 'contents_type')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
