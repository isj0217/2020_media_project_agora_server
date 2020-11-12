<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "melon.cicdljegbba4.ap-northeast-2.rds.amazonaws.com";
        $DB_NAME = "testagora";
        $DB_USER = "kevin";
        $DB_PW = "kv0903!!";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}