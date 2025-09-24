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
  $('#news').validate({

    //検証ルール設定
    rules: {
      //ここに検証ルールを設定
      created: {
        required: true,
      },
      // title: {
      //   required: true,
      //   maxlength: 255,
      // },
      content: {
        required: true,
      },
      url: {
        url: true,
        maxlength: 255,
      }
    },

    //エラーメッセージ設定
    messages: {
      //ここにエラーメッセージを設定
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