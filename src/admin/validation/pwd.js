$(function(){
  //独自ルールの追加
  $.validator.addMethod(
    "hkana_regex",
    function(value, element, regexp) {
      return this.optional(element) || regexp.test(value);
    },
    "ひらがな以外が入力されています。"
  );

  $.validator.addMethod(
    "kkana_regex",
    function(value, element, regexp) {
      return this.optional(element) || regexp.test(value);
    },
    "カタカナ以外が入力されています。"
  );

  $.validator.addMethod(
    "pass_regex",
    function(value, element, regexp) {
      // var check = false;
      return this.optional(element) || regexp.test(value);
    },
    "パスワードの形式が正しくありません。"
  );

  $.validator.addMethod(
    "number_regex",
    function(value, element, regexp) {
      // var check = false;
      return this.optional(element) || regexp.test(value);
    },
    "半角数字のみで入力してください"
  );

  // $.fn.autoKana('#name', '#name_f', {
  //   katakana : false  //true：カタカナ、false：ひらがな（デフォルト）
  // });

  // 登録フォーム
  $('#pwd').validate({

    //検証ルール設定
    rules: {
      //ここに検証ルールを設定
      old: {
        required: true,
      },
      new: {
        required: true,
        minlength: 6,
        maxlength: 50,
        pass_regex: /^([a-zA-Z0-9-_]{6,50})$/,
      },
      conf: {
        required: true,
        equalTo: "#new",
      },
    },

    //エラーメッセージ設定
    messages: {
      //ここにエラーメッセージを設定
      old: {
        required: "現在のパスワードを入力してください。",
      },
      new: {
        required: "新しいパスワードを入力してください。",
        minlength: "パスワードの形式が正しくありません。",
        maxlength: "パスワードの形式が正しくありません。",
      },
      conf: {
        required: "確認のため再度入力してください。",
        equalTo: "上記に入力した内容と異なります。ご確認ください。"
      },
    },

    errorPlacement: function (error, element) {
      // エラー要素の直後に出す
      let err = $('<p class="red" style="color:red;"></p>').append(error);
      element.after(err);
      // // (入力フィールドの)name+’_err’のidを持つp要素の子要素にlabelとして出力
      // var name = "#" + element.attr("name") + "_err";
      // $(name).append(error);
    },
  });

});