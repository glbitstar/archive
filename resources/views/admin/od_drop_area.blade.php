<div id="od-drop-area" style="border: 2px dashed #ccc; padding: 10px; text-align: center;">
    ここに画像をドロップ
    <div id="od-preview" style="padding: 10px; text-align: center;">
        <img id="od-preview-image" src="" alt="プレビュー画像" style="width: auto; height: 200px; display: none; margin: 0 auto;">
    </div>
    <div id="od-file-name" style="padding: 0px; text-align: center; color: #666;">
    </div>
</div>


<script>
    var odDropArea = document.getElementById("od-drop-area");
    var odFileNameDisplay = document.getElementById("od-file-name");
    var odPreviewImage = document.getElementById("od-preview-image");

    odDropArea.addEventListener("dragover", function(e) {
        e.preventDefault(); // ドロップエリアにドラッグされたときのデフォルトの挙動を無効化
        odDropArea.style.border = "2px dashed red"; // ドラッグオーバー時のボーダーの色を変更
    }, false);

    odDropArea.addEventListener("dragleave", function(e) {
        odDropArea.style.border = "2px dashed #ccc"; // ドラッグがエリア外に出た時のボーダーの色を元に戻す
    }, false);

    odDropArea.addEventListener("drop", function(e) {
        e.preventDefault();
        odDropArea.style.border = "2px dashed #ccc"; // ドロップ後のボーダーの色を元に戻す
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            var fileInput = document.querySelector("input[name='image']");
            fileInput.files = files; // ドロップされたファイルをinputにセット
            odFileNameDisplay.textContent = "選択されたファイル: " + files[0].name; // ファイル名を表示

            // 画像プレビューの表示
            var reader = new FileReader();
            reader.onload = function(e) {
                odPreviewImage.src = e.target.result;
                odPreviewImage.style.display = 'block'; // プレビューを表示
            };
            reader.readAsDataURL(files[0]);
        }
    }, false);
</script>
