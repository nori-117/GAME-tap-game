<?php
// db_connect.phpの読み込み
require_once('db_connect.php');

// function.phpの読み込み
require_once('function.php');

// セッション開始
session_start();
// セッションスコアが保存されていない時
if (empty($_SESSION['score'])) {
  header("Location: main.php"); // リダイレクト
  exit;
// セッションスコアが保存されている時
} else {
  $score = $_SESSION['score']; // スコアを変数に代入
}


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


// 値が小さい順に並べた時の10番目を取得
$pdo = db_connect();
try {
  $sql = "SELECT score FROM ranking ORDER BY score LIMIT 1 OFFSET 9";
  $stmt02 = $pdo->prepare($sql);
  $stmt02->execute();
} catch (PDOException $e) {
  echo 'エラーが発生しました。';
  // echo 'Error: ' . $e->getMessage();
  die();
}


// 送信ボタンが押された時の処理
if (!empty($_POST['post'])) {

  // nameが入力されていない場合
  if (empty($_POST['name'])) {
    $message = '名前を入力してください';
  }

  // nameが入力されている場合
  if (!empty($_POST['name'])) {

    $name = htmlspecialchars($_POST['name'], ENT_QUOTES); // 入力されたnameを格納
    $_SESSION['name'] = $name; // 名前をセッションに保存

    // データベースに登録する
    $pdo = db_connect();
    try {
      $sql = "INSERT INTO ranking (name, score) VALUES (:name, :score)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $name); // バインド
      $stmt->bindParam(':score', $score); // バインド
      $stmt->execute();
      header("Location: ranking.php"); // ranking.phpにリダイレクト
      exit;
    } catch (PDOException $e) {
      echo 'エラーが発生しました。';
      // echo 'Error: ' . $e->getMessage();
      die();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>ランキング</title>
    <meta name=viewport content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="l-wrapper">
      <div class="result l-inner">

        <h1 class="result__title">☆ランキング☆</h1>

        <!-- ランキング表 -->
        <table class="table">
          <tr><th>順位</th><th>ニックネーム</th><th>スコア</th></tr>
          <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
              <td><?php echo $row['number']; ?></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php score_conversion($row['score']) ; ?></td>
            </tr>
          <?php } ?>
        </table>
        
        <!-- スコアの10番目が存在しないか、10番目よりも良かった場合 -->
        <?php $row = $stmt02->fetch(PDO::FETCH_ASSOC); ?>
        <?php if(empty($row['score']) || $row['score'] > $score) { ?>

          <!-- 名前を登録できるフォーム -->
          <div class="result__register">

            <p class="result__message">ランキングに名前を登録しよう！</p>
            <p class="result__your-score">あなたのスコア： <?php score_conversion($score); ?></p>

            <form class="result__form" method="POST" action="">
              <input class="result__name" type="text" maxlength="10" placeholder="ニックネーム（10文字まで）" name="name">
              <input class="result__button" type="submit" value="送信" name="post">
              <p class="result__alert">
                <?php if(!empty($message)) {
                  echo $message;
                }  ?>
              </p>
  
            </form>
            <div class="result__back-link">
              <a href="main.php">登録せずにゲームに戻る</a>
            </div>

          </div>

        <!-- スコアがランキングの10位よりも悪かった場合 -->
        <?php } else { ?>
          <div class="result__back-link">
            <a href="main.php">ゲームに戻る</a>
          </div>
        <?php } ?>
  
      </div>
    </div> <!-- /l-wrapper -->

  </body>
</html>