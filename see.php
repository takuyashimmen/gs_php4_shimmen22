<?php
session_start();
include("funcs.php");
sessionCheck();

$id_user=$_GET[id];
//1.  DB接続
$pdo = db_conn();

//２．質問取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM ogiri_answer WHERE NOT (id_user = :id_user)"); 
$stmt->bindValue(':id_user', $id_user, PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();
// var_dump($status);

// ３．質問表示
$question="";
if ($status==false) {
    sql_error($status);
}else{
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //JS引き渡し
    $json_list = json_encode( $result , JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
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
                <div>This is&nbsp;</div>
                <div id="twisted_id_user"></div>
                <div>&nbsp's idea!</div>
            </div>        
            <div class=q-container>
                <img src="img/Q.jpg" width="20"  alt="ロゴ">
                <div class ="question" id="twisted_question"></div>     
            </div>
            <div class=q-container>
                <div class ="question" id="twisted_answer"></div>
            </div>
            <div hidden id="twisted_id_question" name="twisted_id_question"> </div>
            <div class="b-container">
               <button type="button" id="like" class="cbtn">Like</button>
               <button type="button" id="twist" class="cbtn">Twist!</button>
               <button type="button" id="skip" class="cbtn">Skip</button>
            </div>
        </main>
    </div>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- JQuery -->

    <script>

    //ページロード時
    window.onload = function(){
        rand();
    }

    //関数：質問表示
    function rand(){
        var js_list = JSON.parse('<?php echo $json_list; ?>');
        var i = js_list.length;
        var r = Math.floor(Math.random()*i)+1;
        var questionset = js_list[r];
        console.log(questionset);
        var twisted_question = questionset['question'];
        var twisted_id_question = questionset['id_question'];
        var twisted_answer = questionset['answer'];
        var twisted_id_user = questionset['id_user'];
        $("#twisted_question").html(twisted_question); 
        $("#twisted_id_user").html(twisted_id_user); 
        $("#twisted_answer").html(twisted_answer); 
        $("#twisted_id_question").html(twisted_id_question); 
        sessionStorage.setItem('questionset', JSON.stringify(questionset));
    }

    //Twist クリックイベント
    $("#twist").on("click",function(){
        var twisted_id_question = $("#twisted_id_question").html(); 
        location.href = "twist.php?id=" + twisted_id_question;
    });

    //Skip クリックイベント
    $("#skip").on("click",function(){
        location.href = "dashboard.php?id=<?=$id_user?>";
    });



    </script>
<footer><small>-</small></footer>
</body>
</html>