<?php

$id_answer=$_GET[ida];
$id_user=$_GET[id];

require_once('funcs.php');

//1. POSTデータ取得
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare("DELETE FROM ogiri_answer WHERE id_answer = :id_answer ");
$stmt->bindValue(':id_answer', $id_answer, PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
    sql_error($stmt);
}else{
    redirect('dashboard.php?id=' . $id_user);
}
?>