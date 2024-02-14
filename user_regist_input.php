<?php
// 新規ユーザー登録 ユーザー情報入力画面表示

// セッションの利用を宣言する
session_start();

// --- データの処理 ---

    // 利用する変数の初期化を行う
    $name = '';
    $mail = '';
    $login = '';
    $password = '';
    $password_confirm = '';

    // 確認画面から戻ってきたときは、認証用リンクチェックを省略する
    if ( !empty( $_SESSION['mail']) ){
        // メールアドレスをセッション変数から取得しておく
        $mail = $_SESSION['mail'];
        // 入力した値を復元して、セッションを掃除しておく
        if ( !empty( $_SESSION['login']) ){
            $login = $_SESSION['login'];
            unset( $_SESSION['login'] );
        }
        if ( !empty( $_SESSION['name']) ){
            $name = $_SESSION['name'];
            unset( $_SESSION['name'] );
        }
        if ( !empty( $_SESSION['password']) ){
            unset( $_SESSION['password'] );
        }

    } else {

    // GETパラメーターから、認証用リンクを取得する
        $authlink = $_GET['al'];

    // データベースに接続し、認証用リンクが利用可能かを判定する
        // データベースに接続する
        require_once('include/db_connect.php');

        // 情報取得用のSQLを設定する
        $statement 
            = $pdo->prepare('SELECT * FROM mail_auth '.
                           'WHERE link = :link');
        // DBの登録用変数とPHP側の変数をつなげる
        $statement->bindParam( ':link', $authlink, PDO::PARAM_STR );


        // SQLを実行する
        $statement->execute();
        // 結果を取得する
        $result = $statement->fetch(PDO::FETCH_ASSOC);


        // 利用不可な場合は、ログインページにリダイレクトする
        if ( empty( $result ) ) {
            header("Location: login.php");
            exit;
        }

        // メールアドレスを取り出す(セッションにも登録する)
        $mail = $result['mail'];
        $_SESSION['mail'] = $mail;

    }


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();
    $bindArray['login'] = $login;
    $bindArray['password'] = $password;
    $bindArray['password_confirm'] = $password_confirm;
    $bindArray['name'] = $name;
    $bindArray['mail'] = $mail;


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'user_regist_input.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();