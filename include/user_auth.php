<?php

// ログイン処理

    // セッションから、ログインIDとパスワードを取得する
$login = $_SESSION['login_id'];
$password = $_SESSION['login_password'];

    // データベースにアクセスして、ログイン可能か判定する
        // データベースに接続する
    $pdo = new PDO('mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password');

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

    // ログインしているユーザーのIDを取得しておく
    $users_id = $result['id'];