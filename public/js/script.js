
// マウスホバーで更新日を表示する
$(document).ready(function () {
    $('.lp_card').hover(
        function () {
            // マウスが要素に入った時
            $(this).find('.updated_at').css('display', 'block');
        },
        function () {
            // マウスが要素から出た時
            $(this).find('.updated_at').css('display', 'none');
        }
    );


    // ヘッダーのログイン/マイページボタンにマウスホバーで色を変える
    $('.hd_menu').hover(
        function () {
            // マウスが要素に入った時
            $(this).css('background-color', '#000');
            $(this).find('i').css('color', '#fff'); // 'i'要素を見つけて色を変更
        },
        function () {
            // マウスが要素から出た時
            $(this).css('background-color', '');
            $(this).find('i').css('color', ''); // 'i'要素の色を元に戻す（必要に応じて）
        }
    );


    // クリックでマイページメニューを開閉する
    $('.mypage_button').on('click', function (e) {
        // .toggle()メソッドを使用して表示状態をトグル
        $('.mypage_menu').slideToggle(300);
        e.stopPropagation(); // イベントの伝播を停止
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.mypage_button, .mypage_menu').length) {
            // mypage_menu以外の場所がクリックされた場合、メニューを閉じる
            $('.mypage_menu').slideUp(300);
        }
    });


    // PC/SPデザインのフィルター周りの機能---------------------------------------------------------

    // アコーディオンのタイトルをクリックしたときの挙動
    $('.accordion-title').on('click', function () {
        // この要素の直後のアコーディオンコンテンツをスライド表示/非表示する
        $(this).next('.accordion-content').slideToggle(300);
        $(this).toggleClass('open');
    });

    // アコーディオンのリストをクリックした時の挙動
    $('.accordion-list').on('click', function (e) {
        // チェックボックス自体がクリックされた場合は、デフォルトの動作を優先する
        if (!$(e.target).is('.child-checkbox')) {
            var checkbox = $(this).find('.child-checkbox');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
        }
    });
    

    // ページ読み込み時に子カテゴリーのチェックボックスの状態を確認してアコーディオンを開閉する
    $('.child-checkbox').each(function () {
        if ($(this).is(':checked')) {
            var content = $(this).closest('.accordion-content');
            content.show();
            content.prev('.accordion-title').addClass('open');
        }
    });

    $('.parent-checkbox').change(function () {
        var isChecked = $(this).is(':checked');
        // 共通の親要素を見つけてから、その中の.child-checkboxを選択
        $(this).closest('.accordion-section').find('.child-checkbox').prop('checked', isChecked);
    });

    // 子カテゴリーのチェックボックスの状態が変わったときのイベントハンドラー
    $('.child-checkbox').on('change', function () {
        // 現在の子カテゴリーのチェックボックスが属しているメジャーカテゴリーのセクションを取得
        var $parentSection = $(this).closest('.accordion-section');

        // そのセクション内の全ての子カテゴリーのチェックボックスを取得
        var $childCheckboxes = $parentSection.find('.child-checkbox');

        // メジャーカテゴリーのチェックボックスを取得
        var $parentCheckbox = $parentSection.find('.parent-checkbox');

        // チェックされている子カテゴリーのチェックボックスの数をカウント
        var checkedCount = $childCheckboxes.filter(':checked').length;

        // 全ての子カテゴリーがチェックされているか判断
        var allChecked = $childCheckboxes.length === checkedCount;

        // 1つ以上の子カテゴリーがチェックされているが、全てではないか判断
        var someChecked = checkedCount > 0 && !allChecked;

        // 全ての子カテゴリーがチェックされている場合にはメジャーカテゴリーにチェックを入れる
        if (allChecked) {
            $parentCheckbox.prop('checked', true);
        } else if (someChecked || checkedCount === 0) {
            // 子カテゴリーが1つ以上チェックされているが全てではない、または1つもチェックされていない場合にはメジャーカテゴリーのチェックを外す
            $parentCheckbox.prop('checked', false);
        }
    });

    // フォーム変更時の自動送信
    $('#filterForm').on('change', 'input[type="checkbox"], input[type="radio"]', function () {
        $('#filterForm').submit();
    });

    // その他のデザインのフィルター周りの機能--------ここから-------------------------------------------------
    // アコーディオンのリストをクリックした時の挙動
    $('.other-accordion-title').on('click', function (e) {
        // チェックボックス自体がクリックされた場合は、デフォルトの動作を優先する
        if (!$(e.target).is('.other-parent-checkbox')) {
            var checkbox = $(this).find('.other-parent-checkbox');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
        }
    });

    // ページ読み込み時に子カテゴリーのチェックボックスの状態を確認してアコーディオンを開閉する
    $('.other-child-checkbox').each(function () {
        if ($(this).is(':checked')) {
            var content = $(this).closest('.accordion-content');
            content.show();
            content.prev('.accordion-title').addClass('open');
        }
    });

    $('.other-parent-checkbox').change(function () {
        var isChecked = $(this).is(':checked');
        // 共通の親要素を見つけてから、その中の.child-checkboxを選択
        $(this).closest('.accordion-section').find('.other-child-checkbox').prop('checked', isChecked);
    });

    // 子カテゴリーのチェックボックスの状態が変わったときのイベントハンドラー
    $('.other-child-checkbox').on('change', function () {
        // 現在の子カテゴリーのチェックボックスが属しているメジャーカテゴリーのセクションを取得
        var $parentSection = $(this).closest('.accordion-section');

        // そのセクション内の全ての子カテゴリーのチェックボックスを取得
        var $childCheckboxes = $parentSection.find('.other-child-checkbox');

        // メジャーカテゴリーのチェックボックスを取得
        var $parentCheckbox = $parentSection.find('.other-parent-checkbox');

        // チェックされている子カテゴリーのチェックボックスの数をカウント
        var checkedCount = $childCheckboxes.filter(':checked').length;

        // 全ての子カテゴリーがチェックされているか判断
        var allChecked = $childCheckboxes.length === checkedCount;

        // 1つ以上の子カテゴリーがチェックされているが、全てではないか判断
        var someChecked = checkedCount > 0 && !allChecked;

        // 全ての子カテゴリーがチェックされている場合にはメジャーカテゴリーにチェックを入れる
        if (allChecked) {
            $parentCheckbox.prop('checked', true);
        } else if (someChecked || checkedCount === 0) {
            // 子カテゴリーが1つ以上チェックされているが全てではない、または1つもチェックされていない場合にはメジャーカテゴリーのチェックを外す
            $parentCheckbox.prop('checked', false);
        }

    });

    $('.other-parent-checkbox').on('change', function () {
        // 他のチェックボックスの選択を解除
        $('.other-parent-checkbox').not(this).prop('checked', false).each(function () {
            var parentId = $(this).val();
            $('.other-accordion-content-' + parentId + ' .other-child-checkbox').prop('checked', false);
        });
    });

    // フォーム変更時の自動送信
    $('#other-filterForm').on('change', 'input[type="checkbox"], input[type="radio"]', function () {
        $('#other-filterForm').submit();
    });

    $('.filter_delete_button').on('click', function (e) {
        e.preventDefault(); // デフォルトの挙動を防ぐ

        // フィルタの種類と値を取得
        var filter_type = $(this).data('filter-type');
        var filter_value = $(this).data('filter-value');

        // フォームに既に存在する同名の隠しフィールドを削除
        $('input[name="filter_type"]').remove();
        $('input[name="filter_delete"]').remove();

        // フォームに新しい隠しフィールドを追加
        var form = $(this).closest('form');
        $('<input>').attr({
            type: 'hidden',
            name: 'filter_type',
            value: filter_type
        }).appendTo(form);
        $('<input>').attr({
            type: 'hidden',
            name: 'filter_delete',
            value: filter_value
        }).appendTo(form);

        // フォーム送信
        form.submit();
    });

    // お気に入りボタンのクリックイベントを設定
    $('.favorite_button').on('click', function (e) {
        // お気に入り登録済みかどうかを判定
        const isFavorite = $(this).hasClass('favorite-active');
        const landingPageId = $(this).data('landing-page-id');
        const imageUrl = $(this).data('img-url');

        if (isFavorite) {
            e.preventDefault(); // デフォルトのイベントを停止

            // 更新/削除モーダルを表示するための準備
            // ランディングページIDをモーダルのフォームに設定
            $('#update_landing_page_id').val(landingPageId);
            $('#delete_landing_page_id').val(landingPageId);

            // フォルダIDを取得してセレクトボックスに設定
            var folderId = $(this).data('folder-id');
            $('#updateFolder').val(folderId);

            $('.modal-lp-img').css('background-image', 'url(' + imageUrl + ')');

            // 更新/削除用モーダルを表示
            $('#updateDeleteModal').modal('show');
        } else {
            // 新規登録用モーダルを表示するための準備
            $('#landing_page_id').val(landingPageId);
            $('.modal-lp-img').css('background-image', 'url(' + imageUrl + ')');

            // 新規登録用モーダルを表示
            $('#exampleModal').modal('show');
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // お気に入り登録用モーダルのラジオボタンの状態変更イベント
    document.querySelectorAll('#exampleModal input[name="folder_option"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const isNewFolderSelected = document.getElementById('newFolderOptionRegister').checked;
            document.getElementById('newFolderGroup').style.display = isNewFolderSelected ? 'block' : 'none';
            document.getElementById('folderId').style.display = !isNewFolderSelected ? 'block' : 'none';
        });
    });

    // 更新/削除用モーダルのラジオボタンの状態変更イベント
    document.querySelectorAll('#updateDeleteModal input[name="folder_option"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const isNewFolderSelected = document.getElementById('newFolderOptionUpdate').checked;
            // 新規フォルダ名入力ボックスとラベルの表示/非表示を切り替え
            const newFolderNameGroup = document.getElementById('newFolderNameGroup');
            newFolderNameGroup.style.display = isNewFolderSelected ? 'block' : 'none';
            // 既存フォルダのセレクトボックスの表示/非表示を切り替え
            document.getElementById('updateFolderGroup').style.display = !isNewFolderSelected ? 'block' : 'none';
        });
    });
});


// ドキュメントが読み込まれた後に関数を実行
document.addEventListener('DOMContentLoaded', function () {
    // フォルダ管理ボタンにイベントリスナーを設定
    document.querySelectorAll('.folder_management_button').forEach(button => {
        button.addEventListener('click', function () {

            // data-folder-name属性からフォルダ名を取得
            const folderName = this.getAttribute('data-folder-name');

            // モーダル内の要素にフォルダ名を設定
            document.getElementById('currentFolderName').textContent = folderName;

            // data-folder-id属性からフォルダIDを取得
            const folderId = this.getAttribute('data-folder-id');

            // 隠しフィールドにフォルダIDを設定
            document.getElementById('renameFolderId').value = folderId;
            document.getElementById('deleteFolderId').value = folderId;
        });
    });
});

// ドキュメントが読み込まれた後に関数を実行
document.addEventListener('DOMContentLoaded', function () {
    // フォルダ管理ボタンにイベントリスナーを設定
    document.querySelectorAll('.other_folder_management_button').forEach(button => {
        button.addEventListener('click', function () {

            // data-folder-name属性からフォルダ名を取得
            const folderName = this.getAttribute('data-folder-name');

            // モーダル内の要素にフォルダ名を設定
            document.getElementById('currentFolderName').textContent = folderName;

            // data-folder-id属性からフォルダIDを取得
            const folderId = this.getAttribute('data-folder-id');

            // 隠しフィールドにフォルダIDを設定
            document.getElementById('renameFolderId').value = folderId;
            document.getElementById('deleteFolderId').value = folderId;
        });
    });
});


$('.other_favorite_button').on('click', function (e) {
    const isFavorite = $(this).hasClass('favorite-active');
    const otherDesignId = $(this).data('other-design-id');
    const imageUrl = $(this).data('img-url');
    const folderId = $(this).data('folder-id'); // フォルダIDを取得

    console.log(folderId);

    // 更新/削除モーダルに表示する画像を設定
    $('.modal-lp-img').css('background-image', 'url(' + imageUrl + ')');

    if (isFavorite) {
        // デフォルトのイベントを停止
        e.preventDefault();

        // 更新/削除モーダル用にデータを設定
        $('#update_other_design_id').val(otherDesignId);
        $('#delete_other_design_id').val(otherDesignId);
        $('#updateFolder').val(folderId); // 更新/削除モーダルのセレクトボックスにフォルダIDを設定

        // 更新/削除用モーダルを表示
        $('#updateDeleteModal').modal('show');
    } else {
        // 新規登録用モーダル用にデータを設定
        $('#other_design_id').val(otherDesignId);

        // 新規登録用モーダルを表示
        $('#exampleModal').modal('show');
    }
});



// サイドバーのトグルボタンのアニメーション
document.getElementById('toggleSidebar').addEventListener('click', function () {
    this.classList.toggle('rotated');
});

// サイドバーを開閉する
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    const lp_section = document.querySelector('.lp_section');

    toggleButton.addEventListener('click', function () {
        sidebar.classList.toggle('hide');
        lp_section.classList.toggle('slide-left');
    });
});



document.addEventListener('DOMContentLoaded', function () {
    var pcButton = document.getElementById('toggle-pc');
    var spButton = document.getElementById('toggle-sp');

    pcButton.addEventListener('click', function () {
        pcButton.classList.add('active');
        spButton.classList.remove('active');
        // ここにPCデザイン用の処理を追加
    });

    spButton.addEventListener('click', function () {
        spButton.classList.add('active');
        pcButton.classList.remove('active');
        // ここにSPデザイン用の処理を追加
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const updateUrlParameter = (contents_type) => {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('contents_type', contents_type); // 選択されたバージョンをセット

        // パラメータを更新したURLでページを再読み込み
        window.location.search = urlParams.toString();
    };

    // PCデザインボタンのイベントリスナー
    document.getElementById('toggle-pc').addEventListener('click', function (e) {
        e.preventDefault();
        updateUrlParameter('pc'); // PCバージョンをセットしてURLを更新
    });

    // SPデザインボタンのイベントリスナー
    document.getElementById('toggle-sp').addEventListener('click', function (e) {
        e.preventDefault();
        updateUrlParameter('sp'); // SPバージョンをセットしてURLを更新
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // セレクトボックスの値が変更された時のイベントリスナーを設定
    document.getElementById('folderSelect').addEventListener('change', function () {
        // 選択されたfolder_idを取得
        var selectedFolderId = this.value;

        // 基本となるURLを取得（Laravelのrouteヘルパーから生成されるURL）
        var baseUrl = document.getElementById('favoritesLink').getAttribute('href');

        // URLにfolder_idのクエリパラメータを追加
        var newUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'folder_id=' + encodeURIComponent(selectedFolderId);

        // アンカータグのhref属性を更新
        document.getElementById('favoritesLink').setAttribute('href', newUrl);
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.querySelector('.hd_bars a');
    const sidebar = document.querySelector('.sidebar');

    // メニューボタンのクリックイベントを設定
    menuButton.addEventListener('click', function (e) {
        e.preventDefault(); // リンクのデフォルトの挙動をキャンセル
        sidebar.classList.toggle('show-sidebar'); // サイドバーを表示/非表示
    });

    // ドキュメント全体のクリックイベントを設定
    document.addEventListener('click', function (e) {
        // クリックされた要素がサイドバー内、またはメニューボタンであれば何もしない
        if (e.target.closest('.sidebar') || e.target.closest('.hd_bars')) {
            return;
        }

        // サイドバーが表示されている場合、それを隠す
        if (sidebar.classList.contains('show-sidebar')) {
            sidebar.classList.remove('show-sidebar');
        }
    });
});
