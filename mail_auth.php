<?php

// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'mail_auth.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();