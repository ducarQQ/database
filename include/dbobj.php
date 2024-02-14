<?php

// データベースの処理をつかさどるクラス

class DBObj {
    
// クラス変数(プロパティ)
    // SQLを格納する
    private $sql = '';
    // 変換用配列を格納する
    private $bindArray = array();
    // 実行結果を保存する
    private $statement;
    // データベースハンドラ（データベースの接続を管理している）
    private $dbh;
    

// インターフェース
    // SQLを設定する
    public function setSQL( $sql ) {
        $this->sql = $sql;
    }

    // データベース変数に値を渡す配列をセットする
    public function setBindArray( $array ) {
        $this->bindArray = $array;
var_dump( $array );
    }

    // 結果を一件だけ返す
    public function fetch() {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    // 結果を全部返す
    public function fetchAll() {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // データベース処理をしてもらう
    public function execute() {
        // データベースに接続する
        $this->dbConnect();
        // SQLを設定する
        $statement = $this->dbh->prepare( $this->sql );
        // 変換用配列を設定する
        foreach( $this->bindArray as $key => $value ){
            $statement->bindParam( $key, $value,  );
        }
var_dump( $statement );        
        // SQLを実行する
        $statement->execute();
        // 実行結果をクラス変数に保存しておく
        $this->statement = $statement;
    }

    // データベース処理をしてもらう
    public function executeTest($array) {
        // データベースに接続する
        $this->dbConnect();
        // SQLを設定する
        $statement = $this->dbh->prepare( $this->sql );
        // 変換用配列を設定する
        foreach( $array as $key => $value ){
            var_dump( $statement->bindValue( $key, $value  ) );
        }
        // SQLを実行する
        $statement->execute();
$statement->debugDumpParams();
        // 実行結果をクラス変数に保存しておく
        $this->statement = $statement;
    }


// クラス内関数(メソッド)
    // データベース接続を行う
    private function dbConnect(){
        // データベース接続処理
        try {
            // データベースに接続する
            $this->dbh = new PDO('mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password');
        } catch(Exception $e) {
            die("接続できません: " . $e->getMessage());
        }
    }

}