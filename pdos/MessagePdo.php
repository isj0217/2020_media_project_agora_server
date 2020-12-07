<?php

// 내 쪽지함 조회
function getMessageRoomAll($user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "with A as (select MESSAGE_RO0M.message_room_idx, if(user1 = ?, user2, user1) as opponent,
                                message_idx, content, max(MESSAGE.created_at) as time
                         from MESSAGE_RO0M, MESSAGE, USER
                         where MESSAGE_RO0M.message_room_idx = MESSAGE.message_room_idx and 
                               MESSAGE_RO0M.is_deleted = 0 and MESSAGE.is_deleted = 0 and (user1 = ? or user2 = ?) 
                         group by MESSAGE_RO0M.message_room_idx)
             select A.message_room_idx, A.opponent as user_idx, user_nickname, A.message_idx, A.content,
                    date_format(A.time, '%c/%d %H:%i') as time
             from A, USER
             where A.opponent = USER.user_idx
             order by time desc;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx,$user_idx,$user_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 특정 쪽지함 조회
function getMessageRoom($user_idx,$message_room_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select if(MESSAGE.from = ?, '보낸 쪽지', '받은 쪽지') as status, content, date_format(created_at, '%c/%d %H:%i') as time
              from MESSAGE
              where message_room_idx = ? and is_deleted = 0
              order by created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx,$message_room_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 특정 쪽지함 삭제
function delMessageRoom($message_room_idx)
{
    $pdo = pdoSqlConnect();
    try{
        $pdo->beginTransaction();
        $query = "update MESSAGE_RO0M set is_deleted = 1 where message_room_idx = ?;";

        $st = $pdo->prepare($query);
        $st->execute([$message_room_idx]);

        $query = "update MESSAGE set is_deleted = 1 where message_room_idx = ?;";

        $st = $pdo->prepare($query);
        $st->execute([$message_room_idx]);

        $pdo->commit();
        $st = null;
        $pdo = null;
    } catch (Exception $exception){
        $pdo->rollback();
    }
}

// 쪽지 보내기
function postMessage($user_idx, $opponent, $content)
{
    $pdo = pdoSqlConnect();
    try{
        $pdo->beginTransaction();
        $query = "insert into MESSAGE_RO0M (user1,user2,created_at) select ?,?,now() from dual
                                            where not exists(select user1, user2 from MESSAGE_RO0M
                  where (user1 = ? and user2 = ? and is_deleted = 0) or (user2 = ? and user1 = ? and is_deleted = 0));";

        $st = $pdo->prepare($query);
        $st->execute([$user_idx,$opponent,$user_idx,$opponent,$user_idx,$opponent]);

        $query = "insert into MESSAGE (message_room_idx,`from`,`to`,content,created_at)
                  select message_room_idx,?,?,?,now() from MESSAGE_RO0M 
                  where (user1 = ? and user2 = ? and is_deleted = 0) or (user2=? and user1=? and is_deleted = 0);";

        $st = $pdo->prepare($query);
        $st->execute([$user_idx,$opponent,$content,$user_idx,$opponent,$user_idx,$opponent]);

        $pdo->commit();
        $st = null;
        $pdo = null;
    } catch (Exception $exception){
        $pdo->rollback();
    }
}












/* ***************************************** 유효성 검사 ***************************************** */
function isValidMessageRoom($message_room_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select message_room_idx from MESSAGE_RO0M where message_room_idx=? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$message_room_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}