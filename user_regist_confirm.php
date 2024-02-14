<?php
// 新規ユーザー登録 ユーザー情報確認画面表示

// セッションの利用を宣言する
session_start();

// --- データの処理 ---

    // GETパラメーターを取得する
    $login = $_GET['login'];
    $password = $_GET['password'];
    $password_confirm = $_GET['password_confirm'];
    $name = $_GET['name'];

    // セッションにデータを保存する
    $_SESSION['login'] = $login;
    $_SESSION['name'] = $name;
    $_SESSION['password'] = $password;

    // パスワードが一致しなかったら、入力画面にリダイレクトする
    if ( $password !== $password_confirm ) {
        header( 'Location: user_regist_input.php');
        exit;
    }

    // セッション変数からメールアドレスを取り出す
    $mail = $_SESSION['mail'];


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();
    $bindArray['login'] = $login;
    $bindArray['name'] = $name;
    $bindArray['mail'] = $mail;


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'user_regist_confirm.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();