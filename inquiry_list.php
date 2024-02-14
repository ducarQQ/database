<?php
// XXX データベースから問合せ一覧を全部取得する

    // データベース処理クラスを呼び出す
    require_once('include/dbobj.php');

    // データベース処理用のインスタンスを生成する
    $dbobj = new DBObj();

    // SQLを設定する
    $dbobj->setSQL( 'SELECT '.
                       '    inquiries.id,'.
                       '    name,'.
                       '    mail,'.
                       '    inquiries.created_at,'.
                       '    detail,'.
                       '    answer,'.
                       '    answers.updated_at '.
                       'FROM inquiries '.
                       '    INNER JOIN answers '.
                       '        ON inquiries.id = answers.inquiry_id '.
                       '    INNER JOIN users '.
                       '        ON inquiries.users_id = users.id '.
                       '    INNER JOIN user_profiles '.
                       '        ON users.id = user_profiles.users_id '
    );

    // 変換用配列を設定する(今回はない)

    // データベースにアクセスさせる
    $dbobj->execute();

    // 結果を全部取得する
    $result = $dbobj->fetchAll();


// --- 画面の表示 ---

    // テンプレート変換用の配列を作る
    $bindArray = array();
    $bindArray['loop'] = $result;


    // テンプレート処理クラス(html_template.php)を読み込む
    require_once( 'include/html_template.php');

    // テンプレート変換クラスのインスタンスを生成する
    $templateobj = new HtmlTemplate();

    // テンプレートファイルを設定する
    $templateobj->setTemplate( 'inquiry_list.html' );
    // 変換する配列を設定する 
    $templateobj->setBindArray( $bindArray );

    // 変換処理を依頼する
    $templateobj->execute();

    // 変換したHTMLを表示する
    echo $templateobj->getHtml();