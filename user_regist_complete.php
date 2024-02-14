<?php
// 新規ユーザー登録 ユーザー情報登録完了画面表示

// セッションの利用を宣言する
session_start();

// --- データの処理 ---

// セッション変数から値を取り出す
$login= $_SESSION['login'];
$name = $_SESSION['name'];
$mail = $_SESSION['mail'];
$password = $_SESSION['password'];

// セッション変数を初期化する
unset( $_SESSION['login'] );
unset( $_SESSION['name'] );
unset( $_SESSION['mail'] );
unset( $_SESSION['password'] );

// 登録するパスワードを暗号化する
$crypt_password = password_hash( $password, PASSWORD_DEFAULT );

// セッションから取得したデータをデーターベースに登録する

    // データベースに接続する
    require_once('include/db_connect.php');

    try {
        // データベースエラー時に例外を発生するようにする
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // トランザクション開始
        $pdo->beginTransaction();

        // #1# ログインユーザー登録用のSQLを設定する(users)
        $statement = $pdo->prepare('INSERT INTO users (login, password) VALUE(:login, :password)');
        // DBの登録用変数とPHP側の変数をつなげる
        $statement->bindParam( ':login', $login, PDO::PARAM_STR );
        $statement->bindParam( ':password', $crypt_password, PDO::PARAM_STR );

        // 登録を実行する
        $statement->execute();
        // 今登録したIDをもらう
        $users_id = $pdo->lastInsertId();

        // #2# ユーザー詳細情報登録用のSQLを設定する(user_profiles)
        $statement = $pdo->prepare('INSERT INTO user_profiles (users_id, name, mail) VALUE(:users_id, :name, :mail)');
        // DBの登録用変数とPHP側の変数をつなげる
        $statement->bindParam( ':users_id', $users_id, PDO::PARAM_INT );
        $statement->bindParam( ':name', $name, PDO::PARAM_STR );
        $statement->bindParam( ':mail', $mail, PDO::PARAM_STR );

        // 登録を実行する
        $statement->execute();

        // #3# メール認証削除用のSQLを設定する(mail_auth)
        $statement = $pdo->prepare('DELETE FROM mail_auth WHERE mail = :mail');
        // DBの登録用変数とPHP側の変数をつなげる
        $statement->bindParam( ':mail', $mail, PDO::PARAM_STR );

        // 登録を実行する
        $statement->execute();

        // トランザクション内容の確定
        $pdo->commit();
    } catch(Exception $e) {
        $pdo->rollBack();
        echo "データベースエラー" . $e->getMessage();
    }


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'user_regist_complete.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();