<?php

// --- 画面出力処理 ---

function displayHtml($template, $bindarray){

    // テンプレートファイルを読み込む
    $html = file_get_contents($template);
    
    // xxx テンプレートファイルから、返還する変数名を取得する
    preg_match_all( '/###.+?###/', $html, $matches );
//var_dump( $matches );
    
    // 前後の#を取り除く
    $bind = array();
    foreach( $matches[0] as $string ){
        $bind[] = trim( $string, '#');
    }
//var_dump( $matches );

    // XXX データベースから所得した値をHTMLに置き換える
    foreach( $bind as $bindname ){
        $html = str_replace( '###'.$bindname.'###', $bindarray[$bindname], $html );
    }

    // 読み込んだhtmlを出力する
    echo $html;
}