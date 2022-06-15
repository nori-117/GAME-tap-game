'use strict';

const scoreText = document.getElementById("js-score-text"); // スコア
const gameArea = document.getElementById("js-game-area"); // ゲームエリア
const startButton = document.getElementById("js-start-button"); // スタートボタン
const scoreForm = document.getElementById("js-score-form"); // スコア送信フォーム
const scoreTime = document.getElementById("js-score-time"); // スコア送信用のinput（変換前）
const target = document.querySelectorAll(".target") // ゲームのターゲット

let targetStatus = ["on", "on", "on", "on"]; //ターゲットのステータス
let gameStatus = "start"; // ゲームのステータス

let startMs; // スタート時間を入れる
let stopMs; // クリア時間を入れる


// スタートボタンがクリックされたら以下の処理
startButton.addEventListener("click", ()=> {

  // ゲーム開始時の時
  if(gameStatus === "start") {

    startButton.classList.add("display-none"); // スタートボタン非表示
    gameArea.classList.remove("display-none"); // ゲームエリア表示
    startMs = Date.now(); // 時刻を変数に入れておく

  // ゲームクリア後の時（リスタートの場合）
  } else if (gameStatus === "clear"){

    startButton.classList.add("display-none"); // スタートボタン非表示
    scoreText.classList.add("display-none"); // スタートボタン非表示
    scoreForm.classList.add("display-none"); // スコア送信フォーム非表示
    gameStatus = "start"; // ゲームのステータス初期化
    targetStatus = ["on", "on", "on", "on"]; // ターゲットのステータス初期化
    startMs = Date.now(); // 時刻を変数に入れなおす

    // 各ターゲットのoffクラス除去
    target.forEach(function(value) {
      value.classList.remove("off");
    });
  }

});


// 各ターゲットがタップされたときの処理
target.forEach(function(value,index) {
  value.addEventListener("click", ()=> {

    value.classList.add("off"); // offクラス付与=カラー変更
    targetStatus[index] = "off"; // ターゲットのステータス変更
  
    // 全てのターゲットをタップした後の処理
    if(targetStatus[0] === "off" && targetStatus[1] === "off" && targetStatus[2] === "off" && targetStatus[3] === "off") {

      gameStatus = "clear"; // ゲームのステータス変更
      stopMs = Date.now(); // 時刻を変数に入れておく

      // 開始時刻～クリア時刻の計算
      const score = stopMs - startMs;
      const millisec = score % 1000;
      const sec = Math.floor(score / 1000) % 60;

      // スコアテキスト変更
      scoreText.textContent = `あなたのタイム：${sec}秒${millisec}`;

      // スコア送信用のinputに値を持たせる
      scoreTime.value = score;
      
  
      scoreText.classList.remove("display-none"); // スコア表示
      scoreForm.classList.remove("display-none"); // スコア送信フォーム表示
      startButton.classList.remove("display-none"); // スタートボタン表示
      startButton.textContent = "もう一度"; // スタートボタンのテキスト変更

    }
  });
});