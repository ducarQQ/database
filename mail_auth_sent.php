<?php
// メール認証 メール送信完了画面表示

// --- データの処理 ---

// 使用する変数を初期化する
$link = '';

// GETパラメーターから、メールアドレスを取得する
$mail = $_GET['mail'];

// ユーザー情報詳細テーブルに、メールアドレスが登録されていないかチェックする
    // データベースに接続する
    require_once('include/db_connect.php');

    // 情報取得用のSQLを設定する(user_profiles)
    $statement = $pdo->prepare('SELECT * FROM user_profiles WHERE mail = :mail');
    // DBの登録用変数とPHP側の変数をつなげる
    $statement->bindParam( ':mail', $mail, PDO::PARAM_STR );

    // 取得のSQLを実行する
    $statement->execute();
    // 結果を取得する
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // 結果が0件だったら、メール認証管理テーブルに登録する
    if ( empty( $result ) ) {
        
        // メール認証管理テーブルに、メールアドレスを登録し、登録用リンクを
        //  メールに添付してユーザーに渡す（メール送信は未実装）

        // ユーザー情報登録用リンクを作成する
        $link = bin2hex( random_bytes(64) );

        // 情報登録用のSQLを設定する(mail_auth)
        $statement = $pdo->prepare('INSERT INTO mail_auth (mail, link) VALUE(:mail, :link)');
        // DBの登録用変数とPHP側の変数をつなげる
        $statement->bindParam( ':mail', $mail, PDO::PARAM_STR );
        $statement->bindParam( ':link', $link, PDO::PARAM_STR );

        // 登録を実行する
        $statement->execute();
    
    }


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();
    $bindArray['mail'] = $mail;
    $bindArray['link'] = $link;


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'mail_auth_sent.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();