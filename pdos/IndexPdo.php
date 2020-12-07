<?php

//READ
function isValidUserIdx($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from USER where user_idx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}


function isValidNaverUser($server_id)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from USER where server_id = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$server_id]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

function isValidNickname($nickname)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from USER where user_nickname = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$nickname]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidDepartment_idx($department_name)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from DEPARTMENT where department_name = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$department_name]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidStudentId($student_id)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from USER where user_student_id = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$student_id]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function postUser($username, $nickname, $student_id, $department_name, $server_id)
{
    $pdo = pdoSqlConnect();
    $query = "insert into USER (user_name, user_nickname, user_student_id, department_name, server_id, is_deleted) values (?, ?, ?, ?, ?, 0);";

    $st = $pdo->prepare($query);
    $st->execute([$username, $nickname, $student_id, $department_name, $server_id]);
    $st = null;
    $pdo = null;
    //    $st->execute();

}

// CREATE
//    function addMaintenance($message){
//        $pdo = pdoSqlConnect();
//        $query = "INSERT INTO MAINTENANCE (MESSAGE) VALUES (?);";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message]);
//
//        $st = null;
//        $pdo = null;
//
//    }


// UPDATE
//    function updateMaintenanceStatus($message, $status, $no){
//        $pdo = pdoSqlConnect();
//        $query = "UPDATE MAINTENANCE
//                        SET MESSAGE = ?,
//                            STATUS  = ?
//                        WHERE NO = ?";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message, $status, $no]);
//        $st = null;
//        $pdo = null;
//    }

// RETURN BOOLEAN
//    function isRedundantEmail($email){
//        $pdo = pdoSqlConnect();
//        $query = "SELECT EXISTS(SELECT * FROM USER_TB WHERE EMAIL= ?) AS exist;";
//
//
//        $st = $pdo->prepare($query);
//        //    $st->execute([$param,$param]);
//        $st->execute([$email]);
//        $st->setFetchMode(PDO::FETCH_ASSOC);
//        $res = $st->fetchAll();
//
//        $st=null;$pdo = null;
//
//        return intval($res[0]["exist"]);
//
//    }
