<?php

// テンプレートHTMLを実際のデータで置き換える処理

class HtmlTemplate {
    
// クラス変数(プロパティ)
    // テンプレートファイルのファイル名
    private $templatefile = '';
    // 変換する値の配列
    private $bindArray = array();
    // 変換し終わった後のHTMLを保存する
    private $html = '';
    

// インターフェース
    // テンプレートファイルを設定する
    public function setTemplate( $filename ) {
        $this->templatefile = $filename;
    }

    // 変換する値の配列を設定する
    public function setBindArray( $array ) {
        $this->bindArray = $array;
    }

    // 変換したHTMLを返す
    public function getHtml() {
        return $this->html;
    }

    // 実際に変換してもらう
    public function execute() {
        // テンプレートファイルを読み込む
        $this->html = file_get_contents( $this->templatefile );

        // ループ部分の処理を行う
        $this->executeLoop();
        // ループ以外の処理を行う
        $this->transform();
    }


// クラス内関数(メソッド)
    
    // ループ部分の抜き出しを行う
    private function executeLoop(){
        // ループ用のタグを抜き出す
        preg_match_all( '/<!-- \[([^\/].+)?\] !-->/', $this->html, $matches );
        // タグ内の文字だけを取得してリストにする
        $loop_list = $matches[1];
        
        // タグリスト分、変換処理を行う
        foreach( $loop_list as $loopname ){
            $this->executeSingleLoop( $loopname );
        }
    }
    
    // 一件分のループ処理を行う
    private function executeSingleLoop( $name ){
        // テンプレートのHTMLから、$nameで渡されたループの最初から最後までのHTMLを切り出す
        // ループ前の部分を取り出す
        $str_array_top = preg_split('/\<!-- \['.$name.'\] !--\>/', $this->html );
        // ループ部分の後を切り出して、ループ部分だけを取り出す
        $str_array_bottom = preg_split('/\<!-- \[\/'.$name.'\] !--\>/', $str_array_top[1] );
        // ループ部分のHTML
        $loop_html = $str_array_bottom[0];
        
        // ループ部分のhtmlから、変換する変数名を取得する
        preg_match_all( '/###(.+?)###/', $loop_html, $matches );

        // 前後の#を取り除く
        $bind = $matches[1];
    
        // ループして置き換えた分のhtmlをここに溜める
        $list_html = '';
        // データの件数分、変換処理を行う
        foreach( $this->bindArray[$name] as $line ){
            $work = $loop_html;
            // パラメーターから所得した値をHTMLに置き換える
            foreach( $bind as $bindname ){
                $work = str_replace( '###'.$bindname.'###', $line[$bindname], $work );
            }
            $list_html = $list_html . $work;
        }
        // HTMLをつなぎ合わせる
        $this->html = $str_array_top[0] . $list_html . $str_array_bottom[1];
    }

    // 指定されたHTMLの変換を行う
    private function transform(){
        // 指定されたhtmlから、変換する変数名を取得する
        preg_match_all( '/###(.+?)###/', $this->html, $matches );

        // 前後の#を取り除く
        $bind = $matches[1];
    
        // パラメーターから所得した値をHTMLに置き換える
        foreach( $bind as $bindname ){
            $this->html = str_replace( '###'.$bindname.'###', $this->bindArray[$bindname], $this->html );
        }
    }
}