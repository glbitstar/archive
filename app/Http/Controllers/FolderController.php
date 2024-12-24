<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = new Folder();
        $folder->name = $request->name;
        $folder->user_id = Auth::user()->id;
        $folder->save();

        return back()->with('success', 'フォルダを作成しました。');
    }

    public function update(Request $request)
    {
        // フォルダ名のバリデーション
        $validated = $request->validate([
            'new_folder_name' => 'required|string|max:255', // フォームのname属性をチェック
        ]);

        $selectedFolderId = $request->input('selectedFolderId');

        // フォルダを検索
        $folder = Folder::findOrFail($selectedFolderId);

        // フォルダを更新
        $folder->update([
            'name' => $request->new_folder_name // 'name'はフォルダの名前を保存するカラム
        ]);

        // リダイレクト
        return back()->with('success', 'フォルダが更新されました。');
    }
    
    public function destroy(Request $request)
    {
        $selectedFolderId = $request->input('selectedFolderId');
        // フォルダを検索

        $folder = Folder::findOrFail($selectedFolderId);
    
        // フォルダを削除
        $folder->delete();
    
        // 削除成功のフラッシュメッセージをセッションに保存し、リダイレクト
        return back()->with('success', 'フォルダが削除されました。');
    }
    
}
