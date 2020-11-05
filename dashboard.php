<?php
session_start();
include("funcs.php");
sessionCheck();
$id_user=$_GET[id];
//1.  DB接続
$pdo = db_conn();

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM ogiri_answer WHERE id_user = :id_user"); //すでにあるのをとるからバインドは不要
$stmt->bindValue(':id_user', $id_user, PDO::PARAM_STR);
$status = $stmt->execute();

//３．データ表示
$view="";
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
    sql_error($status);
}else{
    $view .='<table class = "dbtable"><tr>
                <th class = "daywide">投稿日</th>
                <th>設問</th>
                <th>アイディア</th>
                <th class = "delwide">削除</th>
            </tr>';
    //Selectデータの数だけ自動でループしてくれる
    //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $view .= "<tr>";
        $view .= '<td>' . $result['time'] . '</td>';
        $view .= '<td>' . $result['question'] . '</td>';
        $view .= '<td>' . $result['answer'] . '</td>';
        $view .= '<td><a href="delete.php?id='. $result["id_user"].'&ida=' . $result["id_answer"].'">■</a></tr>';
    }
    $view .= '</table>';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ideation</title>
    <script src="js/jquery-2.1.3.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrap">
        <header>
        <div class="navbar-header"><a class="navbar-brand" href="logout.php">ログアウト</a></div>
        <h1 class ="text-center">Ideation</h1>
        <h2 class ="text-center">Your Ideas</h2>
        </header>
        <main>
            <button type="button" id="return" class ="sbtn">戻る</button>
            <div class="table"><?= $view ?></>
        </main>

<script>
    $("#return").on("click",function(){
        location.href = "question.php";
    });
</script>

</body>
</html>
