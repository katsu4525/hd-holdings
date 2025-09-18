<?php

/**
 * @file message.php
 * @brief ヘッダーでページ遷移後のメッセージ
 * @date 2022-10-14
 *
 * Copyright isis Co.,ltd.
 */

$message_array = [];
$message_array[0] = "登録しました";
$message_array[1] = "更新しました";
$message_array[2] = "削除しました";
$message_array[3] = "使用できないメールアドレスです";
$message_array[100] = "エラーが発生しました";

$message_param = filter_input(INPUT_GET, 'MS', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$pageID = filter_input(INPUT_GET, 'pageID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (empty($pageID)) $pageID = "open";

if (is_null($message_param) || $message_param == "") {
  $message_dialog = "";
} else {
  $message_dialog = <<< "HTML"
    <script>
      $(function(){
        let perfEntries = performance.getEntriesByType("navigation");
        let type = "";
        perfEntries.forEach(function(pe){
          // 読み込みタイプを取得
          type = pe.type;
        });

        if (type === "navigate" && "{$pageID}" === "open"){
          $("#msdialog").dialog(
            {
              modal:true, //モーダル表示
              title:"", //タイトル
              buttons: { //ボタン
              "OK": function() {
                $(this).dialog("close");
                }
              }
            }
          );
          // remove the title bar
          $(".ui-dialog-titlebar").hide();
        } else {
          $("#msdialog").hide();
        }
      });
    </script>
  HTML;

  $message_dialog .= <<< "HTML"
    <div id="msdialog">
      <div class="ui-widget">
        <div class="ui-state-highlight ui-corner-all" style="padding: 1em;">
          <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
              {$message_array[$message_param]}
          </p>
        </div>
      </div>
    </div> 
  HTML;
}
