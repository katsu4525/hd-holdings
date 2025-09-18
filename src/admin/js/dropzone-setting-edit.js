Dropzone.autoDiscover = false;
$(function(){ 
  // メイン画像用 
  const mainDropzone = new Dropzone('#main_dropzone', { 
    url: './file_upload.php', // アップロード先
    paramName: 'mainfile', // 送信パラメータ名
    autoDiscover: false, // 自動検知
    maxFilesize: 10, // MB
    maxFiles: 1, // 最大ファイル数
    parallelUploads: 1, // 同時アップロード数
    acceptedFiles: '.jpg, .png, .gif', // 許可ファイルタイプ
    uploadMultiple: false, // 複数ファイルアップロード
    autoProcessQueue: false, // 自動処理
    addRemoveLinks: true, // 削除追加
    timeout: null, // タイムアウト時間
    // デフォルトメッセージ
    dictDefaultMessage: '<img src="./img/add_image.svg"><br>ここにドラッグ＆ドロップ<br>またはクリックしてください', 
    // ファイルサイズオーバー
    dictFileTooBig: `ファイルサイズは{{maxFilesize}}MBまでです。`, 
    // ファイルタイプエラー
    dictInvalidFileType: '.jpg .png .gif画像のみアップロードできます。', 
    // ファイル数オーバー
    dictMaxFilesExceeded: '画像は{{maxFiles}}枚までです。', 
    // 削除文言
    dictRemoveFile: "削除", 
    // キャンセル
    dictCancelUpload: "キャンセル",
  });

  // サブ画像用 
  const subDropzone = new Dropzone('#sub_dropzone', { 
    url: './file_upload.php', // アップロード先
    paramName: 'subfiles', // サブ画像用のパラメータ名 
    autoDiscover: false, // 自動検知
    maxFilesize: 10, // MB
    maxFiles: 10, // 最大ファイル数
    parallelUploads: 10, // 同時アップロード数
    acceptedFiles: '.jpg, .png, .gif', // 許可ファイルタイプ
    uploadMultiple: true, // 複数ファイルアップロード
    autoProcessQueue: false, // 自動処理
    addRemoveLinks: true, // 削除追加
    timeout: null, // タイムアウト時間
    // デフォルトメッセージ
    dictDefaultMessage: '<img src="./img/add_image.svg"><br>ここにドラッグ＆ドロップ<br>またはクリックしてください（最大10枚）', 
    // ファイルサイズオーバー
    dictFileTooBig: `ファイルサイズは{{maxFilesize}}MBまでです。`, 
    // ファイルタイプエラー
    dictInvalidFileType: '.jpg .png .gif画像のみアップロードできます。', 
    // ファイル数オーバー
    dictMaxFilesExceeded: '画像は{{maxFiles}}枚までです。', 
    // 削除文言
    dictRemoveFile: "削除", 
    // キャンセル
    dictCancelUpload: "キャンセル",
  });

  // フォーム送信するかどうかのフラグ
  let result = true;

  // 既存画像の表示
  const mainImage = window.mainImage;
  const subImage = window.subImage;

  let mainMockFile;
  let subMockFile;

  // メイン画像のプレビュー表示
  if (mainImage){
    mainMockFile = { 
      name: mainImage.nf_originalname, 
      size: mainImage.nf_size,
      accepted: true, // 有効ファイルであることを示す
      saveName: mainImage.nf_savename,
      isExisting: true, // 既存ファイルであることを示す
    };
    // プレビューに追加
    mainDropzone.displayExistingFile(mainMockFile, `./newsfile/${mainImage.nf_savename}`);
    mainDropzone.files.push(mainMockFile); // files配列に追加しないとmaxFilesの制御が効かない
  }

  // サブ画像のプレビュー表示
  if (subImage){
    subImage.forEach((img) => {
      subMockFile = { 
        name: img.nf_originalname, 
        size: img.nf_size,
        accepted: true, // 有効ファイルであることを示す
        saveName: img.nf_savename,
        isExisting: true, // 既存ファイルであることを示す
      };
      // プレビューに追加
      subDropzone.displayExistingFile(subMockFile, `./newsfile/${img.nf_savename}`);
      subDropzone.files.push(subMockFile); // files配列に追加しないとmaxFilesの制御が効かない
    });
  }

  // フォーム送信時に両方のDropzoneアップロードを開始 
  $('form').on('submit', function(e){ 
    e.preventDefault(); 

    // バリデーションチェック
    const validcheck = $('form').valid();
    if (!validcheck) return false;

    // 両方ファイルがある場合は両方アップロード 
    result = true;
    let mainQueued = mainDropzone.getQueuedFiles().length > 0; 
    let subQueued = subDropzone.getQueuedFiles().length > 0;
    if (mainQueued) {
      mainDropzone.processQueue();
    } else if (subQueued) {
      subDropzone.processQueue();
    } else {
      loading();
      this.submit();
    }
  });

  // メイン画像アップロード成功後にサブ画像アップロード 
  mainDropzone.on('success', function(file, response){ 

    if (response[0].status === 'error'){
      // エラーはresponseで判断するのでエラー時もsuccessイベントが発火（必要に応じてプレビューをエラー表示に切り替え）
      result = false;
      file.previewElement.classList.remove("dz-success");
      file.previewElement.classList.add("dz-error");
      file.previewElement.querySelector("[data-dz-errormessage]").textContent = response.message;
      file.myErrorMessage = res.message;
    } else {
      // メイン画像の保存名・サイズなどをフォームに追加
      $('<input>').attr({
        type: 'hidden',
        name: 'main_file[saveName]',
        value: response[0].file
      }).appendTo('#news');

      $('<input>').attr({
        type: 'hidden',
        name: 'main_file[originalName]',
        value: file.name
      }).appendTo('#news');

      $('<input>').attr({
        type: 'hidden',
        name: 'main_file[size]',
        value: file.size
      }).appendTo('#news');
    }

    if (subDropzone.getQueuedFiles().length > 0) { 
      // サブ画像があればアップロード
      subDropzone.processQueue(); 
    } else { 
      // メイン画像だけの場合はフォーム送信するかしないか判定
      if (result){
        loading();
        $('#news')[0].submit(); 
      } else {
        // hidden input を削除（次の送信時にゴミが残らないように）
        $('#news input[type="hidden"]').remove();

        // mainDropzoneのファイルを一時保存
        const mainFile = mainDropzone.files[0];

        // ファイルを全部消す
        mainDropzone.removeAllFiles(true);
        
        // 必要なら再度追加（メイン画像）
        if (mainFile) {
          const newMainFile = new File([mainFile], mainFile.name, { type: mainFile.type });
          mainDropzone.addFile(newMainFile);
          if (mainFile.myErrorMessage) {
            newMainFile.previewElement.classList.add("dz-error");
            newMainFile.previewElement.querySelector("[data-dz-errormessage]").textContent = mainFile.myErrorMessage;
          } else {
            newMainFile.previewElement.classList.add("dz-success");
          }
        }
      }
    } 
  });

  // メイン画像の削除
  mainDropzone.on('removedfile', function(file){
    if (!file.saveName) return; // 新規追加したファイルにはsaveNameがないので無視
    // 削除した画像の情報をサーバーに送るために保存名をフォームに追加
    $('<input>').attr({
      type: 'hidden',
      name: 'remove_files[]',
      value: file.saveName
    }).appendTo('#news');
  });

  // 既存ファイルは送信しない
  mainDropzone.on("sending", function(file, xhr, formData) {
    if (file.isExisting) {
      xhr.abort(); // 既存ファイルは送信しない
    }
  });

  // メイン画像アップロード失敗（全部成功で返す、エラーはresponseで判断するので必要なし。php側で400返せばイベント発火）
  mainDropzone.on('error', function(file, errorMessage, xhr){
    // エラーメッセージを表示
    // alert('アップロードに失敗しました: ');
  });

  // サブ画像アップロード成功後にフォーム送信 
  subDropzone.on('successmultiple', function(files, response){ 
    // response = [{file:"xx.jpg", status:"success", message:"アップロード成功"}, ...]
    response.forEach((res, i) => {
      const file = files[i];
      if (res.status === "error") {
        result = false;
        file.previewElement.classList.remove("dz-success");
        file.previewElement.classList.add("dz-error");
        file.previewElement.querySelector("[data-dz-errormessage]").textContent = res.message;
        file.myErrorMessage = res.message;
      } else {
        // サブ画像の保存名・サイズなどをフォームに追加
        $('<input>').attr({
          type: 'hidden',
          name: `sub_files[${i}][saveName]`,
          value: res.file
        }).appendTo('#news');

        $('<input>').attr({
          type: 'hidden',
          name: `sub_files[${i}][originalName]`,
          value: file.name
        }).appendTo('#news');

        $('<input>').attr({
          type: 'hidden',
          name: `sub_files[${i}][size]`,
          value: file.size
        }).appendTo('#news');
      }
    });

    if (result){
      // 全部成功ならフォーム送信
      loading();
      $('#news')[0].submit(); 
    } else {
      // mainDropzoneのファイルを一時保存
      const mainFile = mainDropzone.files[0];
      // subDropzoneのファイルを一時保存（配列）
      const subFiles = subDropzone.files.slice();

      // ファイルを全部消す
      mainDropzone.removeAllFiles(true);
      subDropzone.removeAllFiles(true);

      // hidden input を削除（次の送信時にゴミが残らないように）
      $('#news input[type="hidden"]').remove();

      // 必要なら再度追加（メイン画像）
      if (mainFile) {
        const newMainFile = new File([mainFile], mainFile.name, { type: mainFile.type });
        mainDropzone.addFile(newMainFile);
        if (mainFile.myErrorMessage) {
          newMainFile.previewElement.classList.add("dz-error");
          newMainFile.previewElement.querySelector("[data-dz-errormessage]").textContent = mainFile.myErrorMessage;
        } else {
          newMainFile.previewElement.classList.add("dz-success");
        }
      }

      // 必要なら再度追加（サブ画像）
      subFiles.forEach(file => {
        const newSubFile = new File([file], file.name, { type: file.type });
        subDropzone.addFile(newSubFile);
        // if (file.previewElement.classList.contains("dz-error")){
        if (file.myErrorMessage) {
          newSubFile.previewElement.classList.add("dz-error");
          newSubFile.previewElement.querySelector("[data-dz-errormessage]").textContent = file.myErrorMessage;
        } else {
          newSubFile.previewElement.classList.add("dz-success");
        }
      });
    }
  });

  // サブ画像の削除
  subDropzone.on('removedfile', function(file){
    // 削除した画像の情報をサーバーに送るために保存名をフォームに追加
    if (!file.saveName) return; // 新規追加したファイルにはsaveNameがないので無視
    $('<input>').attr({
      type: 'hidden',
      name: 'remove_files[]',
      value: file.saveName
    }).appendTo('#news');

    const defaultMessage = this.element.querySelector(".dz-message");

    // 画像が0枚になったらデフォルトメッセージを表示
    if (this.element.querySelectorAll('.dz-preview:not(.dz-error)').length > 0) {
      if (defaultMessage) defaultMessage.style.display = "none";
    } else {
      if (defaultMessage) defaultMessage.style.display = "";
    }
  });

  // 既存ファイルは送信しない
  subDropzone.on("sending", function(file, xhr, formData) {
    if (file.isExisting) {
      xhr.abort(); // 既存ファイルは送信しない
    }
  });

  // サブ画像アップロード失敗
  subDropzone.on('error', function(file, errorMessage, xhr){
    // エラーメッセージを表示
    // alert('アップロードに失敗しました: ');
  });
});
