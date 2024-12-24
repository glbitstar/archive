<div id="drop-area" style="border: 2px dashed #ccc; padding: 10px; text-align: center;">
    ここに画像をドロップ
    <div id="preview" style="padding: 10px; text-align: center;">
        <img id="preview-image" src="" alt="プレビュー画像" style="width: auto; height: 200px; display: none; margin: 0 auto;">
    </div>
    <div id="file-name" style="padding: 0px; text-align: center; color: #666;">
    </div>
</div>


<script>
    var dropArea = document.getElementById("drop-area");
    var fileNameDisplay = document.getElementById("file-name");
    var previewImage = document.getElementById("preview-image");

    // ドラッグオーバー時のイベントハンドラ
    dropArea.addEventListener("dragover", function(e) {
        e.preventDefault(); // ドロップエリアにドラッグされたときのデフォルトの挙動を無効化
        dropArea.style.border = "2px dashed red"; // ボーダー色をスカイブルーに変更
    }, false);

    // ドラッグがエリア外に出た時のイベントハンドラ
    dropArea.addEventListener("dragleave", function(e) {
        dropArea.style.border = "2px dashed #ccc"; // ボーダー色を元に戻す
    }, false);

    // ドロップ時のイベントハンドラ
    dropArea.addEventListener("drop", function(e) {
        e.preventDefault();
        dropArea.style.border = "2px dashed #ccc"; // ドロップ後、ボーダー色を元に戻す
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            var fileInput = document.querySelector("input[name='pc_image']");
            fileInput.files = files; // ドロップされたファイルをinputにセット
            fileNameDisplay.textContent = "選択されたファイル: " + files[0].name; // ファイル名を表示

            // 画像プレビューの表示
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block'; // プレビューを表示
            };
            reader.readAsDataURL(files[0]);
        }
    }, false);
</script>
