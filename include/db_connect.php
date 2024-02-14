<?php

// データベース接続処理

    try {
        // データベースに接続する
        $pdo = new PDO('mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password');
    } catch(Exception $e) {
        die("接続できません: " . $e->getMessage());
    }
