const fileChk = (fileDom) => {

  const sum_maxfilesize_m = 30;
  const one_maxfilesize_m = 2;
  const sum_maxfilesize = sum_maxfilesize_m * 1000 * 1000; // 30MB
  const one_maxfilesize = one_maxfilesize_m * 1000 * 1000;  // 8MB

  
  // inputファイルの種類・サイズ制御
  const file = fileDom.prop('files')[0];
  const accept = fileDom.attr('accept');
  let err_str;

  if (file !== undefined) {
    if (file.type != "") {
      if (accept){
        if (accept.includes(file.type) != true) {
          fileDom.val('');
          err_str = file.name + '\nこのファイルは使用できません。';
          alert(err_str);
          return false;
        }
      }
      if (one_maxfilesize < file.size) {
        fileDom.val('');
        err_str = file.name + '\nこのファイルはサイズが大きすぎます。' + one_maxfilesize_m + 'MByte以下にしてください。';
        alert(err_str);
        return false;
      }
    } else {
      fileDom.val('');
      err_str = file.name + '\nこのファイルは使用できません。';
      alert(err_str);
      return false;
    }
  }
  
  // const cnv = fileDom.parent().find('canvas');
  // if (cnv.length != 0) {   // canvas有無で画像のinputと判断
  //   if (file !== undefined) {
  //     //FileReader API
  //     const reader = new FileReader();
  //     //ファイルロード終了時イベント宣言
  //     reader.onload = function (e) {
  //       //画像表示関数コール
  //       drawImage(e.target.result, cnv);
  //     };
  //     //fileを読込の開始
  //     reader.readAsDataURL(file);
  //     cnv.after('<p class="imgal" style="color:red">※まだアップロードされていません。</p>')
  //   } else {
  //     // キャンバスクリア
  //     w = cnv.attr('width');
  //     h = cnv.attr('height');
  //     const ctx = cnv[0].getContext("2d");
  //     ctx.clearRect(0, 0, w, h);
  //     cnv.attr('width', 1);
  //     cnv.attr('height', 1);
  //     fileDom.parent().find('.imgal').remove();
  //   }
  // }

  // 全体バリデート
  // const input = $('input[type=file]');
  // let size = 0;
  // for (let index = 0; index < input.length; index++) {
  //   const file = input.eq(index).prop('files')[0];
  //   if (file !== undefined) {
  //     size = size + file.size;
  //   }
  // }

  // if (sum_maxfilesize < size) {
  //   err_str = '全体でアップできるファイルサイズを超えました。';
  //   alert(err_str);
  //   return false;
  // }
  // const cnv = fileDom.parent().find('canvas');
}