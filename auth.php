<?php
// ログイン認証処理

// セッションの利用を宣言する
session_start();

// --- データの処理 ---

// --- ログイン処理 ---

// GETパラメーターから、ログインIDとパスワードを取得する
$login = $_GET['login'];
$password = $_GET['password'];

// データベースにアクセスして、ログイン可能か判定する
    // データベースに接続する
    require_once('include/db_connect.php');

    // 情報取得用のSQLを設定する(users)
    $statement = $pdo->prepare('SELECT * FROM users WHERE login = :login');
    // DBの登録用変数とPHP側の変数をつなげる
    $statement->bindParam( ':login', $login, PDO::PARAM_STR );

    // 取得のSQLを実行する
    $statement->execute();
    // 結果を取得する
    $result = $statement->fetch(PDO::FETCH_ASSOC);

// ログインできないなら、ログインページにリダイレクトする
    if ( !password_verify( $password, $result['password'] ) ) {
        header( 'Location: login.php' );
        exit;
    }

// ログインできたら、ログインチェックするための情報をセッションに保存する
$_SESSION['login_id'] = $login;
$_SESSION['login_password'] = $password;

// マイページにリダイレクトする
header( 'Location: mypage.php' );
exit;