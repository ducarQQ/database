<?php
// セッションの利用を開始する
session_start();

// ユーザー認証処理
require_once('include/user_auth.php');


// --- データ入力 ---

    // セッション変数から値を取り出す
    $inquiry= $_SESSION['inquiry'];


// --- データ処理 ---

    // データベースに接続する
    require_once('include/db_connect.php');

    // セッションから取得したデータをデーターベースに登録する
    // データベースに接続する
    $pdo = new PDO('mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password');

    // 情報登録用のSQLを設定する(inquiries)
    $statement = $pdo->prepare('INSERT INTO inquiries (users_id) VALUE(:users_id)');
    // DBの登録用変数とPHP側の変数をつなげる($user_idはログイン処理で取得)
    $statement->bindParam( ':users_id', $users_id, PDO::PARAM_STR );

    // 登録を実行する
    $statement->execute();

    // 今登録したIDをもらう
    $inquiries_id = $pdo->lastInsertId();

    // 情報登録用のSQLを設定する(answers)
    $statement = $pdo->prepare('INSERT INTO answers (inquiry_id, detail) VALUE(:inquiry_id, :detail)');
    // DBの登録用変数とPHP側の変数をつなげる
    $statement->bindParam( ':inquiry_id', $inquiries_id, PDO::PARAM_INT );
    $statement->bindParam( ':detail', $inquiry, PDO::PARAM_STR );

    // 登録を実行する
    $statement->execute();


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'complete.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();