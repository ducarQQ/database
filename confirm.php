<?php
// セッションの利用を開始する
session_start();

// ユーザー認証処理
require_once('include/user_auth.php');


// --- データ入力 ---

    // GETパラメーターを取得する
    $inquiry = $_GET['inquiry'];


// --- データ処理 ---

    // 取得したパラメーターをセッション変数に保存する
    $_SESSION['inquiry'] = $inquiry;

  // 表示するユーザーの名前とメールアドレスをDBから取得する
    // セッションからログインIDを所得する
    $login_id = $_SESSION['login_id'];
    // データベースに接続する
    require_once('include/db_connect.php');

    // 情報取得用のSQLを設定する(user_profiles)
    $statement = $pdo->prepare('SELECT '.
                               '    * '.
                               'FROM users '.
                               '    INNER JOIN user_profiles '.
                               '        ON '.
                               '    users.id = user_profiles.users_id '.
                               'WHERE '.
                               '    users.login = :login_id');
    // DBの登録用変数とPHP側の変数をつなげる
    $statement->bindParam( ':login_id', $login_id, PDO::PARAM_STR );

    // 取得のSQLを実行する
    $statement->execute();
    // 結果を取得する
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // 名前とメールアドレスを取り出す
    $name = $result['name'];
    $mail = $result['mail'];


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();
    $bindArray['name'] = $name;
    $bindArray['mail'] = $mail;
    $bindArray['inquiry'] = $inquiry;


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'confirm.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();