<div id="sp-drop-area" style="border: 2px dashed #ccc; padding: 10px; text-align: center;">
    ここに画像をドロップ
    <div id="sp-preview" style="padding: 10px; text-align: center;">
        <img id="sp-preview-image" src="" alt="プレビュー画像" style="width: auto; height: 200px; display: none; margin: 0 auto;">
    </div>
    <div id="sp-file-name" style="padding: 0px; text-align: center; color: #666;">
    </div>
</div>


<script>
    var spDropArea = document.getElementById("sp-drop-area");
    var spFileNameDisplay = document.getElementById("sp-file-name");
    var spPreviewImage = document.getElementById("sp-preview-image");

    spDropArea.addEventListener("dragover", function(e) {
        e.preventDefault(); // ドロップエリアにドラッグされたときのデフォルトの挙動を無効化
        spDropArea.style.border = "2px dashed red"; // ドラッグオーバー時のボーダーの色を変更
    }, false);

    spDropArea.addEventListener("dragleave", function(e) {
        spDropArea.style.border = "2px dashed #ccc"; // ドラッグがエリア外に出た時のボーダーの色を元に戻す
    }, false);

    spDropArea.addEventListener("drop", function(e) {
        e.preventDefault();
        spDropArea.style.border = "2px dashed #ccc"; // ドロップ後のボーダーの色を元に戻す
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            var fileInput = document.querySelector("input[name='sp_image']");
            fileInput.files = files; // ドロップされたファイルをinputにセット
            spFileNameDisplay.textContent = "選択されたファイル: " + files[0].name; // ファイル名を表示

            // 画像プレビューの表示
            var reader = new FileReader();
            reader.onload = function(e) {
                spPreviewImage.src = e.target.result;
                spPreviewImage.style.display = 'block'; // プレビューを表示
            };
            reader.readAsDataURL(files[0]);
        }
    }, false);
</script>
