<?php
// db_connect.phpの読み込み
require_once('db_connect.php');

// function.phpの読み込み
require_once('function.php');

// // セッション開始
session_start();

// セッションスコアが保存されていない時
if (empty($_SESSION['score'])) {
  header("Location: main.php"); // リダイレクト
  exit;
// セッションスコアが保存されている時
} else {
  // セッションに保存されている名前とスコアを変数に代入
  $name = $_SESSION['name'];
  $score = $_SESSION['score'];
}

// セッション変数のクリア
$_SESSION = array();
// セッションクリア
session_destroy();


// ランキング10位以内を取得
$pdo = db_connect();
try {
  $sql = "SELECT
    number,
    name,
    score
  FROM ( SELECT
    RANK() OVER(ORDER BY score) AS number,
    name,
    score
    FROM ranking )
  AS ranking
  WHERE
    number <= 10
  ORDER BY
    number";
    
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
} catch (PDOException $e) {
  echo 'エラーが発生しました。';
  // echo 'Error: ' . $e->getMessage();
  die();
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>ランキング登録完了</title>
    <meta name=viewport content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="l-wrapper">
      <div class="ranking l-inner">

        <h1 class="ranking__title">☆ランキング☆</h1>
    
        <!-- ランキング表 -->
        <table class="table">
          <tr><th>順位</th><th>ニックネーム</th><th>スコア</th></tr>
          <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>

            <!-- 名前とスコアが一致するとき（＝自分のデータ）にクラス付与 -->
            <?php $class = ($name == $row['name'] && $score == $row['score'])? 'you' : '';?>
            <tr class="<?php echo $class; ?>">
              <td><?php echo $row['number']; ?></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php score_conversion($row['score']) ; ?></td>
            </tr>
          <?php } ?>
        </table>

        <!-- ゲームに戻るボタン -->
        <div class="ranking__back-link">
          <a href="main.php">ゲームに戻る</a>
        </div>

      </div>
    </div> <!-- /l-wrapper -->

  </body>
</html>