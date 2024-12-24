<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OtherFavorite;
use App\Models\OtherFolder;
use App\Models\OtherFavoriteFolderRelationship;
use App\Models\OtherDesign;
use App\Models\LandingPage;
use App\Models\Color;
use App\Models\MajorCategory;
use App\Models\OtherMajorCategory;
use App\Models\SnsMenu;
use App\Models\Folder;


class OtherFavoriteController extends Controller
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

        return compact('count_pc_pages', 'count_sp_pages', 'colors', 'major_categories', 'other_major_categories', 'sns_menus', 'user_folders', 'favorite_ids', 'other_favorite_ids');
    }


    public function index(Request $request)
    {
        $query = OtherDesign::withCount('other_favorites');
        $favorites_view_flag = true;
        $userId = Auth::user()->id;
        $folderId = $request->query('folder_id');

        $query = OtherFavorite::with('other_design')->where('user_id', $userId);

        $contents_type = $request->input('contents_type');

        // ユーザーのお気に入りとそれに関連するフォルダ情報を取得
        $favoritesQuery = OtherFavorite::with('other_folders')->where('user_id', $userId);

        // フォルダが選択されている場合は、そのフォルダに属するお気に入りだけを取得
        if (!empty($folderId)) {
            $favoritesQuery->whereHas('other_folders', function ($query) use ($folderId) {
                $query->where('other_folders.id', $folderId);
            });
        }

        $favorites = $favoritesQuery->get();

        // お気に入りのランディングページIDを取得
        $favoriteOtherDesignIds = $favorites->pluck('other_design_id')->toArray();

        // ランディングページのクエリを絞り込む
        $query = OtherDesign::withCount('other_favorites')->whereIn('id', $favoriteOtherDesignIds);

        $filtered_other_designs_count = $query->count();
        $otherDesigns = $query->paginate(15);

        // ユーザーのフォルダ一覧を取得
        $userFolders = OtherFolder::where('user_id', $userId)->get();

        // ログインユーザーのお気に入りランディングページIDとそれぞれのフォルダIDを取得
        $favoriteFolderRelationships = [];
        if (auth()->check()) {
            $userId = auth()->id();
            $otherFavoriteFolderRelationships = OtherFavoriteFolderRelationship::whereHas('other_favorite', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['other_favorite', 'other_folder'])->get()->keyBy('other_favorite.other_design_id');
        }

        // OtherDesignのモデルで、フォルダIDを取得する処理を確認・修正する
        $otherDesignsFolderIds = $otherDesigns->mapWithKeys(function ($otherDesign) {
            // ここで、各OtherDesignに紐付く最初のOtherFavoriteを取得し、それを通じてOtherFolderのIDを取得
            $folderId = $otherDesign->other_favorites->first()?->other_folders->first()?->id;
            return [$otherDesign->id => $folderId];
        });


        // 選択されたフォルダ名を取得、フォルダが選択されていない場合は「すべてのお気に入り」を表示
        $selectedFolderName = $folderId ? (OtherFolder::find($folderId) ? OtherFolder::find($folderId)->name : null) : 'すべてのお気に入り';

        // getCommonDataメソッドから共通データを取得（このメソッドの実装は省略）
        $commonData = $this->getCommonData();

        // 現在のリクエストから全クエリパラメータを取得
        $currentFilters = $request->except('page');

        $favorites_flag = true;

        // ビューにフィルタ条件、共通データ、および現在のフィルタ条件を渡す
        return view('others.index', array_merge($commonData, [
            'other_designs' => $otherDesigns,
            'userFolders' => $userFolders, // フォルダ一覧をビューに渡す
            'contents_type' => $contents_type,
            'currentFilters' => $currentFilters,
            'favorites_flag' => $favorites_flag,
            'filtered_other_designs_count' => $filtered_other_designs_count,
            'landingPagesFolderIds' => $otherDesignsFolderIds, // 追加: ランディングページごとのフォルダID
            'selectedFolderId' => $folderId, // 選択されたフォルダIDをビューに渡す
            'selectedFolderName' => $selectedFolderName, // 選択されたフォルダ名または「すべてのお気に入り」をビューに渡す
        ]));
    }

    public function store(Request $request)
    {

        $other_design_id = $request->input('other_design_id');

        // 既にお気に入り登録されているかチェック
        $exists = OtherFavorite::where('user_id', Auth::user()->id)
            ->where('other_design_id', $request->other_design_id)
            ->exists();

        if ($exists) {
            // 既に登録されている場合
            return back()->with('error', '登録済みです。');
        }

        // お気に入りを作成
        $otherFavorite = new OtherFavorite();
        $otherFavorite->user_id = Auth::user()->id;
        $otherFavorite->other_design_id = $request->other_design_id;
        $otherFavorite->save();

        // 新しいフォルダが指定された場合、フォルダを作成
        if (!empty($request->new_other_folder_name)) {
            $otherFolder = new OtherFolder();
            $otherFolder->name = $request->new_other_folder_name;
            $otherFolder->user_id = Auth::user()->id;
            $otherFolder->save();
            $other_folder_id = $otherFolder->id; // 新しく作成したフォルダのID
        } else {
            // 既存のフォルダが指定された場合
            $other_folder_id = $request->input('other_folder_id');
        }

        // お気に入りをフォルダに追加（新規作成または既存）
        if (!empty($other_folder_id)) {
            $otherFavoriteFolderRelationship = new OtherFavoriteFolderRelationship();
            $otherFavoriteFolderRelationship->other_favorite_id = $otherFavorite->id;
            $otherFavoriteFolderRelationship->other_folder_id = $other_folder_id; // 指定されたフォルダのIDを関連付ける
            $otherFavoriteFolderRelationship->save();
        }

        return back()->with('success', 'お気に入りに追加しました。');
    }

    public function update(Request $request)
    {

        $otherDesignId = $request->input('other_design_id');
        $folderOption = $request->input('folder_option');
        $user = auth()->user();

        // ユーザーのお気に入りを特定
        $other_favorite = OtherFavorite::where('user_id', $user->id)
            ->where('other_design_id', $otherDesignId)
            ->firstOrFail();

        if ($folderOption == 'existing') {
            // 既存のフォルダに更新
            $otherFolderId = $request->input('other_folder_id');
            // 既存の関連を更新するか、新しく関連を作成
            $relationship = OtherFavoriteFolderRelationship::updateOrCreate(
                ['other_favorite_id' => $other_favorite->id],
                ['other_folder_id' => $otherFolderId]
            );
        } else {
            // 新規フォルダの作成
            $other_folder = new OtherFolder();
            $other_folder->name = $request->input('new_folder_name');
            $other_folder->user_id = $user->id;
            $other_folder->save();

            // 新規フォルダとの関連を作成
            $relationship = new OtherFavoriteFolderRelationship();
            $relationship->other_favorite_id = $other_favorite->id;
            $relationship->other_folder_id = $other_folder->id;
            $relationship->save();
        }

        return back()->with('success', 'お気に入りの保存先を変更しました。');
    }

    public function destroy(Request $request)
    {
        // リクエストからlanding_page_idを取得
        $otherDesignId = $request->input('other_design_id');

        // ログインユーザーのお気に入りから、特定のlanding_page_idを持つものを検索
        $favorite = OtherFavorite::where('other_design_id', $otherDesignId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$favorite) {
            // お気に入りが見つからないか、所有していない場合はエラーメッセージと共にリダイレクト
            return back()->with('error', 'お気に入りを削除する権限がありません。');
        }

        $favorite->delete();

        return back()->with('success', 'お気に入りが正常に削除されました。');
    }
}
