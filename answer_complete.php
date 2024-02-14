<?php
// 問い合わせ回答登録完了画面表示

// セッションの利用を開始する
session_start();


// --- データ入力 ---

    // セッション変数から値を取り出す
    $id = $_SESSION['id'];
    $answer = $_SESSION['answer'];


// --- データ処理 ---

    // セッション変数を初期化する
    unset( $_SESSION['id'] );
    unset( $_SESSION['answer'] );

  // XXX セッションから取得したデータをデーターベースに登録する

    // データベース処理クラスを呼び出す
    require_once('include/dbobj.php');

    // 回答テーブル用のデータベース処理用のインスタンスを生成する
    $ansDBobj = new DBObj();

    // SQLを設定する
    $ansDBobj->setSQL( 'UPDATE answers SET answer = :ans WHERE inquiry_id = :id'
    );

    // 変換用配列を設定する(今回はない)
    $ansBindArray = array();
    $ansBindArray[':ans'] = $answer;
    $ansBindArray[':id'] = $id;
    $ansDBobj->setBindArray( $ansBindArray );

    // データベースにアクセスさせる
    $ansDBobj->executeTest($ansBindArray);


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'answer_complete.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();