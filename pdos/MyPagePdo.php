<?php
function isValidpatchMyNickname($user_idx, $user_nickname)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from USER where user_idx = ? and user_nickname = ? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $user_nickname]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidpatchMyPicture($user_idx, $user_picture)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from USER where user_idx = ? and user_picture = ? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $user_picture]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

function getMyPage($user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select user_picture, user_nickname as nickname, user_name, department_name, user_student_id
       from USER
where user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function patchMyNickname($user_idx, $user_nickname)
{
    $pdo = pdoSqlConnect();
    $query = "update USER set user_nickname = ? where user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_nickname, $user_idx]);

    $st = null;
    $pdo = null;

}
function patchMyPicture($user_idx, $user_picture)
{
    $pdo = pdoSqlConnect();
    $query = "update USER set user_picture = ? where user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_picture,$user_idx]);

    $st = null;
    $pdo = null;

}

function getMyPageinLike($user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select department_board_idx, type, title, content, user_nickname as nickname,
                     case when timestampdiff(second, DEPARTMENT_BOARD.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, DEPARTMENT_BOARD.created_at, now()) < 60
                          then concat(timestampdiff(minute, DEPARTMENT_BOARD.created_at, now()),' 분전')
                          when timestampdiff(hour, DEPARTMENT_BOARD.created_at, now()) < 24
                          then concat(timestampdiff(hour, DEPARTMENT_BOARD.created_at, now()),' 시간전')
                          when timestampdiff(day, DEPARTMENT_BOARD.created_at, now()) < 30
                          then concat(timestampdiff(day, DEPARTMENT_BOARD.created_at, now()),' 일전') end as time,
                     case when photo is null then 0
                          else 1 end as photo_status,
                     like_num, comment_num
             from DEPARTMENT_BOARD, USER
             where USER.user_idx = DEPARTMENT_BOARD.user_idx 
               and department_board_idx in (select department_board_idx
from DEPARTMENT_BOARD_LIKE
where user_idx = ? and status = 1) and DEPARTMENT_BOARD.is_deleted = 0
             order by DEPARTMENT_BOARD.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}