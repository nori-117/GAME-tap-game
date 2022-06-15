<?php

// スコアを「秒+ミリ秒」に変換する関数
function score_conversion($score) {
  $millisec = $score % 1000;
  $sec = floor($score / 1000) % 60;
  echo $sec . '秒' . $millisec;
}