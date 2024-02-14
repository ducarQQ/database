<?php
// 問い合わせ回答確認画面表示

// セッションの利用を開始する
session_start();


// --- データ入力 ---

    // GETパラメーターを取得する
    $answer = $_GET['answer'];
    // セッション変数から問合せidを取得する
    $id = $_SESSION['id'];


// --- データ処理 ---

    // 取得したパラメーターをセッション変数に保存する
    $_SESSION['answer'] = $answer;

    // データベース処理クラスを呼び出す
    require_once('include/dbobj.php');

    // データベース処理用のインスタンスを生成する
    $dbobj = new DBObj();

    // SQLを設定する
    $dbobj->setSQL( 'SELECT '.
                   '    up.name, '.
                   '    up.mail, '.
                   '    an.detail, '.
                   '    an.answer, '.
                   '    iq.created_at, '.
                   '    an.updated_at '.
                   'FROM inquiries as iq '.
                   '    INNER JOIN answers as an '.
                   '        ON iq.id = an.inquiry_id '.
                   '    INNER JOIN users as us'.
                   '        ON iq.users_id = us.id '.
                   '    INNER JOIN user_profiles as up'.
                   '        ON up.users_id = us.id '.
                   'WHERE '.
                   '    iq.id = :id'
    );

    // 変換用配列を設定する(今回はない)
    $bindArray = array();
    $bindArray[':id'] = $id;
    $dbobj->setBindArray( $bindArray );

    // データベースにアクセスさせる
    $dbobj->execute();

    // 結果を全部取得する
    $result = $dbobj->fetch();


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();
    $bindArray['id'] = $id;
    $bindArray['name'] = $result['name'];
    $bindArray['mail'] = $result['mail'];
    $bindArray['detail'] = $result['detail'];
    $bindArray['answer'] = $answer;
    $bindArray['created_at'] = $result['created_at'];
    $bindArray['updated_at'] = $result['updated_at'];


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'answer_confirm.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();