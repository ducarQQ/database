<?php

// --- 画面出力処理 ---

function displayLoop($template, $bindArray){

    // テンプレートファイルを読み込む
    $html = file_get_contents($template);
    
// ループ部分を切り出す    
    // ループ前の部分を取り出す
    $str_array_top = preg_split('/\<!-- loop !--\>/', $html );

    // ループ部分の後を切り出して、ループ部分だけを取り出す
    $str_array_bottom = preg_split('/\<!-- \/loop !--\>/', $str_array_top[1] );

    $loop_html = $str_array_bottom[0];


// ループ内の変換処理
    
    // ループ部分のhtmlから、返還する変数名を取得する
    preg_match_all( '/###.+?###/', $loop_html, $matches );
//var_dump( $matches );
    
    // 前後の#を取り除く
    $bind = array();
    foreach( $matches[0] as $string ){
        $bind[] = trim( $string, '#');
    }
//var_dump( $matches );
    
    // ループして置き換えた分のhtmlをここに溜める
    $list_html = '';
    // データの件数分、変換処理を行う
    foreach( $bindArray['loop'] as $line ){
        $work = $loop_html;
        // パラメーターから所得した値をHTMLに置き換える
        foreach( $bind as $bindname ){
            $work = str_replace( '###'.$bindname.'###', $line[$bindname], $work );
        }
        $list_html = $list_html . $work;
    }
    
    
    // HTMLをつなぎ合わせる
    $html = $str_array_top[0] . $list_html . $str_array_bottom[1];
    
    // 読み込んだhtmlを出力する
    echo $html;
}