<?php

session_start();
include("funcs.php");
sessionCheck();

//1.  DB接続
$pdo = db_conn();

//２．質問取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM ogiri_question"); //すでにあるのをとるからバインドは不要
$status = $stmt->execute();


//３．質問表示
$question="";
if ($status==false) {
    sql_error($status);
}else{
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //JS引き渡し
    $json_list = json_encode( $result , JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    //初期質問表示
    // $questionset = ($result[array_rand($result)]);
    // $question = $questionset['text'];
    // while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // var_dump($result);
    // $view .= "<p>";
    // $view .= $result['indate'] .' '. $result['name'].' '.$result['text'];
    // $view .= "</p>";
//   }
}
?>


<!DOCTYPE html>
<html>
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
        <h1 class ="text-center">Ideation</h1>
        </header>
        <main>
            <div class ="h-container">
                <div>Welcome&nbsp;</div>
                <div id="uname"></div>
                <div>!</div>
            </div>        
            <div class=q-container>
                <img src="img/Q.jpg" width="20"  alt="ロゴ">
                <div class ="question" id="question2"><?= $question ?></div>
            </div>
                <form method="POST" action="insert.php">
                <div class="text-center">
                    <legend>回答</legend>
                    <textArea name="answer" id="answer" name="answer" rows="4" cols="40"></textArea>
                    <input type ="hidden" id="id_question" name="id_question"> 
                    <input type ="hidden" id="question" name="question">
                    <input type ="hidden" id="id_user" name="id_user">
                    <input  type="submit" value="投稿" class ="sbtn">
                </div>
            </form>
            <div class="b-container">
               <button type="button" id="change" class="cbtn">お題変更</button>
            </div>
        </main>
    </div>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- JQuery -->

    <script>
    //関数：ランダム質問表示
    function rand(){
        var js_list = JSON.parse('<?php echo $json_list; ?>');
        var i = js_list.length;
        var r = Math.floor(Math.random()*i)+1;
        var questionset = js_list[r];
        var question = questionset['question'];
        var id_question = questionset['id_question'];
        console.log(questionset);
        $("#question2").html(question); 
        $("#question").val(question); 
        $("#id_question").val(id_question); 
        sessionStorage.setItem('questionset',JSON.stringify(questionset));
    }

    //ページロード時
    window.onload = function(){
        const uname = localStorage.getItem("uname")
        $("#uname").html(uname)
        $("#id_user").val(uname)
        rand();
    }

    //0.Change クリックイベント
    $("#change").on("click",function(){
        rand();
    });

    </script>
<footer><small>-</small></footer>
</body>
</html>