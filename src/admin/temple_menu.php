<?php

/**
 * @file temple_menu.php
 * @brief メニュー作成
 * @date 2024-01-09
 *
 * Copyright isis Co.,ltd.
 */

$left_nav = <<< "HTML"
  <div id="navArea">
    <nav>
      <!-- <ul id="leftNav">
        <li>メニュー</li>
        <li class="click-item">
          <a>
            <img src="img/user_side.svg" alt="管理ユーザー">
            顧客管理
            <div class="hov"></div>
          </a>
        </li>
        <li class="sub-list sort_block">
          <a href="">メニュー１</a>
          <a href="">メニュー２</a>
        </li>
      </ul> -->
      <ul id="leftNav">
        <!-- <h2>
          <a href="first.php" style="text-decoration:none;">メニュー</a>
        </h2> -->
        <li class="sort_block" id="item-1">
          <h3>記事管理</h3>
          <ul id="child1">
            <li><a href="news.php" id="menu1-1">記事登録</a></li>
            <li><a href="news-list.php" id="menu1-2">記事一覧</a></li>
          </ul>
        </li>
        <li class="sort_block" id="item-2">
          <h3>マスター</h3>
          <ul id="child2">
            <li><a href="pwd.php" id="menu2-1">パスワード変更</a></li>
          </ul>
        </li>
      </ul>
    </nav>
    <div class="toggle_btn">
      <span></span>
      <span></span>
      <span></span>
      <p class="burger">メニュー</p>
      <p class="closs">閉じる</p>
    </div>
  </div>
HTML;