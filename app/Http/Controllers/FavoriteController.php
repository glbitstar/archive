<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\LandingPage;
use Illuminate\Support\Facades\Auth;
use App\Models\FavoriteFolderRelationship;
use App\Models\Folder;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'landing_page_id' => 'required|exists:landing_pages,id',
            'folder_id' => 'nullable|exists:folders,id',
            'new_folder_name' => 'nullable|string|max:255',
        ]);

        // 既にお気に入り登録されているかチェック
        $exists = Favorite::where('user_id', Auth::user()->id)
            ->where('landing_page_id', $request->landing_page_id)
            ->exists();

        if ($exists) {
            // 既に登録されている場合
            return back()->with('error', '登録済みです。');
        }

        // お気に入りを作成
        $favorite = new Favorite();
        $favorite->user_id = Auth::user()->id;
        $favorite->landing_page_id = $request->landing_page_id;
        $favorite->save();

        // 新しいフォルダが指定された場合、フォルダを作成
        if (!empty($request->new_folder_name)) {
            $folder = new Folder();
            $folder->name = $request->new_folder_name;
            $folder->user_id = Auth::user()->id;
            $folder->save();
            $folder_id = $folder->id; // 新しく作成したフォルダのID
        } else {
            // 既存のフォルダが指定された場合
            $folder_id = $request->input('folder_id');
        }

        // お気に入りをフォルダに追加（新規作成または既存）
        if (!empty($folder_id)) {
            $favoriteFolderRelationship = new FavoriteFolderRelationship();
            $favoriteFolderRelationship->favorite_id = $favorite->id;
            $favoriteFolderRelationship->folder_id = $folder_id; // 指定されたフォルダのIDを関連付ける
            $favoriteFolderRelationship->save();
        }

        return back()->with('success', 'お気に入りに追加しました。');
    }

    public function update(Request $request)
    {

        $landingPageId = $request->input('landing_page_id');
        $folderOption = $request->input('folder_option');
        $user = auth()->user();

        // ユーザーのお気に入りを特定
        $favorite = Favorite::where('user_id', $user->id)
            ->where('landing_page_id', $landingPageId)
            ->firstOrFail();

        if ($folderOption == 'existing') {
            // 既存のフォルダに更新
            $folderId = $request->input('folder_id');
            // 既存の関連を更新するか、新しく関連を作成
            $relationship = FavoriteFolderRelationship::updateOrCreate(
                ['favorite_id' => $favorite->id],
                ['folder_id' => $folderId]
            );

        } else {
            // 新規フォルダの作成
            $folder = new Folder();
            $folder->name = $request->input('new_folder_name');
            $folder->user_id = $user->id;
            $folder->save();

            // 新規フォルダとの関連を作成
            $relationship = new FavoriteFolderRelationship();
            $relationship->favorite_id = $favorite->id;
            $relationship->folder_id = $folder->id;
            $relationship->save();
        }

        return back()->with('success', 'お気に入りの保存先を変更しました。');
    }


    public function destroy(Request $request)
    {
        // リクエストからlanding_page_idを取得
        $landingPageId = $request->input('landing_page_id');

        // ログインユーザーのお気に入りから、特定のlanding_page_idを持つものを検索
        $favorite = Favorite::where('landing_page_id', $landingPageId)
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
