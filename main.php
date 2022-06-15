<?php
// セッション開始
session_start();

// ランキングを見るボタンが押されたとき
if (!empty($_POST)) {
  $_SESSION['score'] = $_POST['score']; // スコアをセッションに保存
  header("Location: result.php"); // リダイレクト
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>タップゲーム</title>
    <meta name=viewport content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="l-wrapper">
      <div class="game l-inner">

        <h1>タップゲーム</h1>
        <p class="game__text">色のついた丸をタップしてね！</p>
    
        <!-- スコア表示 -->
        <p class="game__score-text display-none" id="js-score-text"></p>
        
        <!-- ゲームエリア -->
        <div class="game__area display-none" id="js-game-area">
          <div class="target target01"></div>
          <div class="target target02"></div>
          <div class="target target03"></div>
          <div class="target target04"></div>
        </div>
        
        <!-- スコア送信フォーム -->
        <form class="game__score-form display-none" id="js-score-form" action="" method="POST">
          <input class="game__ranking-button" id="button" type="submit" value="ランキングを見る" name="post"> <!-- ランキングを見るボタン -->
          <input type="hidden" value="" name="score" id="js-score-time"> <!-- スコア送信用 -->
        </form>

        <!-- スタートボタン -->
        <button class="game__start-button" id="js-start-button">ゲームスタート</button>

      </div>
    </div> <!-- /l-wrapper -->

    <script src="js/main.js"></script>
  </body>
</html>