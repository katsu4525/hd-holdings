CREATE DATABASE IF NOT EXISTS init_db
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_general_ci;

USE init_db;
SET GLOBAL time_zone = 'Asia/Tokyo';

CREATE TABLE IF NOT EXISTS `admin_tb` (
  `am_key` int(11) NOT NULL,
  `am_id` varchar(250) NOT NULL,
  `am_pass` varchar(60) NOT NULL,
  `am_logintime` datetime DEFAULT NULL,
  `am_ecnt` tinyint(1) NOT NULL DEFAULT '0',
  `am_allowtime` varchar(30) NOT NULL DEFAULT '',
  `am_secret` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`am_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `admin_tb`
--

INSERT INTO `admin_tb` (`am_key`, `am_id`, `am_pass`, `am_logintime`, `am_ecnt`, `am_allowtime`, `am_secret`) VALUES
(1, 'SuAdmin', '$2y$10$HbuHxdWupOd0dpz9fbIlYOVyLGPY5xyIjcXolHxGpeRDalYepB06W', NULL, 0, '', ''),
(2, 'isisadmin', '$2y$10$HbuHxdWupOd0dpz9fbIlYOVyLGPY5xyIjcXolHxGpeRDalYepB06W', NULL, 0, '', '');

CREATE TABLE IF NOT EXISTS `news_tb` (
  `ne_key` int NOT NULL COMMENT 'キー',
  `ne_created` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '作成日',
  `ne_is_public` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:公開　2:非公開',
  `ne_cate` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'カテゴリー',
  `ne_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'タイトル',
  `ne_content` text COLLATE utf8mb4_general_ci COMMENT '内容',
  `ne_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'リンクURL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのインデックス `news_tb`
--
ALTER TABLE `news_tb`
  ADD PRIMARY KEY (`ne_key`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `news_tb`
--
ALTER TABLE `news_tb`
  MODIFY `ne_key` int NOT NULL AUTO_INCREMENT COMMENT 'キー';
COMMIT;


-- ファイル名に 1_ 2_ などの番号を付けて、実行順序を明示的にすることができます。 