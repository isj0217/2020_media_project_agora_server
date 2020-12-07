<?php

// 동아리 게시판 전체 조회
function getCircle()
{
    $pdo = pdoSqlConnect();
    $query = "select circle_name from CIRCLE;";

    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 특정 동아리 게시판 조회
function getCircleDetail($circle_name)
{
    $pdo = pdoSqlConnect();
    $query = "select circle_board_idx, title, content, user_nickname as nickname,
                     case when timestampdiff(second, CIRCLE_BOARD.created_at, now()) < 60
                     then '방금 전'
                     when timestampdiff(minute, CIRCLE_BOARD.created_at, now()) < 60
                     then concat(timestampdiff(minute, CIRCLE_BOARD.created_at, now()),' 분전')
                     when timestampdiff(hour, CIRCLE_BOARD.created_at, now()) < 24
                     then concat(timestampdiff(hour, CIRCLE_BOARD.created_at, now()),' 시간전')
                     when timestampdiff(day, CIRCLE_BOARD.created_at, now()) < 30
                     then concat(timestampdiff(day, CIRCLE_BOARD.created_at, now()),' 일전') end as time,
                     case when photo is null then 0
                     else 1 end as photo_status,
                     like_num, comment_num
             from CIRCLE_BOARD, USER
             where USER.user_idx = CIRCLE_BOARD.user_idx and  circle_name = ? and CIRCLE_BOARD.is_deleted = 0
             order by CIRCLE_BOARD.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_name]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 동아리 게시판 특정 게시물 조회
function getCircleBoardDetail($board_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select CIRCLE_BOARD.user_idx, user_picture, circle_board_idx, title, content, user_nickname                                                      as nickname,
                     case when timestampdiff(second, CIRCLE_BOARD.created_at, now()) < 60
                     then '방금 전'
                     when timestampdiff(minute, CIRCLE_BOARD.created_at, now()) < 60
                     then concat(timestampdiff(minute, CIRCLE_BOARD.created_at, now()),' 분전')
                     when timestampdiff(hour, CIRCLE_BOARD.created_at, now()) < 24
                     then concat(timestampdiff(hour, CIRCLE_BOARD.created_at, now()),' 시간전')
                     when timestampdiff(day, CIRCLE_BOARD.created_at, now()) < 30
                     then concat(timestampdiff(day, CIRCLE_BOARD.created_at, now()),' 일전') end as time,
                     IF(photo is null, null, photo) as photo,
                     like_num, comment_num
             from CIRCLE_BOARD, USER
             where USER.user_idx = CIRCLE_BOARD.user_idx and circle_board_idx = ? and CIRCLE_BOARD.is_deleted = 0
             order by CIRCLE_BOARD.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

// 동아리 게시글 댓글 조회 API
function getCircleBoardComment($board_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select circle_comment_idx, USER.user_nickname,
                     case when timestampdiff(second, CIRCLE_COMMENT.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, CIRCLE_COMMENT.created_at, now()) < 60
                          then concat(timestampdiff(minute, CIRCLE_COMMENT.created_at, now()),' 분전')
                          when timestampdiff(hour, CIRCLE_COMMENT.created_at, now()) < 24
                          then concat(timestampdiff(hour, CIRCLE_COMMENT.created_at, now()),' 시간전')
                          when timestampdiff(day, CIRCLE_COMMENT.created_at, now()) < 30
                          then concat(timestampdiff(day, CIRCLE_COMMENT.created_at, now()),' 일전') end as time,
                     comment
              from CIRCLE_BOARD, CIRCLE_COMMENT, USER
              where CIRCLE_BOARD.circle_board_idx = CIRCLE_COMMENT.circle_board_idx and CIRCLE_COMMENT.user_idx = USER.user_idx and
                    CIRCLE_BOARD.circle_board_idx = ? and CIRCLE_BOARD.is_deleted = 0
              order by CIRCLE_COMMENT.created_at;";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 동아리 게시판 글쓰기 API
function postCircle($circle, $user_idx, $title, $content, $photo)
{
    $pdo = pdoSqlConnect();
    $query = "insert into CIRCLE_BOARD (circle_name, user_idx, title, content, photo, created_at) values (?,?,?,?,?,now());";

    $st = $pdo->prepare($query);
    $st->execute([$circle, $user_idx, $title, $content, $photo]);


    $st = null;
    $pdo = null;

}

// 동아리 게시판 게시글에 댓글 달기 API
function postCircleComment($circle_idx, $user_idx, $comment)
{

    $pdo = pdoSqlConnect();
    try{
        $pdo->beginTransaction();
        $query = "insert into CIRCLE_COMMENT (circle_board_idx, user_idx, comment, created_at) values (?,?,?,now());";

        $st = $pdo->prepare($query);
        $st->execute([$circle_idx, $user_idx, $comment]);

        $query = "update CIRCLE_BOARD set comment_num = comment_num + 1 where circle_board_idx = ?;";

        $st = $pdo->prepare($query);
        $st->execute([$circle_idx]);

        $pdo->commit();
        $st = null;
        $pdo = null;
    } catch (Exception $exception){
        $pdo->rollback();
    }
}

// 동아리 게시글 좋아요 API
function patchCircleLike($circle_idx, $user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "insert into CIRCLE_BOARD_LIKE select ?,?,0 from dual
              where not exists(select circle_board_idx, user_idx from CIRCLE_BOARD_LIKE
                               where circle_board_idx=? and user_idx=?);";

    $st = $pdo->prepare($query);
    $st->execute([$circle_idx,$user_idx,$circle_idx,$user_idx]);

    $query = "update CIRCLE_BOARD_LIKE set status = if(status=1,0,1) where circle_board_idx = ? and user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_idx,$user_idx]);

    $query = "update CIRCLE_BOARD  join CIRCLE_BOARD_LIKE
              set like_num = (select count(*) from CIRCLE_BOARD_LIKE where CIRCLE_BOARD.circle_board_idx=? and user_idx=? and status=1)
              where CIRCLE_BOARD.circle_board_idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_idx,$user_idx,$circle_idx]);

    $query = "select circle_board_idx, status from CIRCLE_BOARD_LIKE where user_idx = ? and circle_board_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $circle_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res[0];
}

// 동아리 게시글 수정하기 API
function patchCircle($circle_idx, $user_idx, $title, $content, $photo)
{
    $pdo = pdoSqlConnect();
    $query = "update CIRCLE_BOARD set title =? , content =? , photo = ? , created_at = now() where user_idx =? and circle_board_idx = ? and is_deleted = 0;";

    $st = $pdo->prepare($query);
    $st->execute([$title,$content,$photo,$user_idx,$circle_idx]);

    $st = null;
    $pdo = null;

}

// 동아리 게시판 게시글 삭제하기
function deleteCircle($circle_idx, $user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "update CIRCLE_BOARD set is_deleted = 1 where user_idx = ? and circle_board_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx,$circle_idx]);

    $st = null;
    $pdo = null;

}

// 동아리 게시판 댓글 삭제하기
function deleteCircleComment($circle_comment_idx, $user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "update CIRCLE_COMMENT set is_deleted = 1 where circle_comment_idx = ? and user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_comment_idx, $user_idx]);

    $query = "update CIRCLE_COMMENT inner join CIRCLE_BOARD on CIRCLE_BOARD.circle_board_idx = CIRCLE_COMMENT.circle_board_idx
              set comment_num = comment_num - 1 where circle_comment_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_comment_idx]);

    $st = null;
    $pdo = null;

}


























/* *********************************************** 유효성 검사 *********************************************** */

// 동아리 존재 유효성 검사
function isValidCircle($circle_name)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select circle_name from CIRCLE where circle_name = ?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_name]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

// 게시물 유무 검사
function isValidCircleIdx($board_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select circle_board_idx from CIRCLE_BOARD where circle_board_idx = ? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidCircleCommentIdx($circle_comment_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select circle_comment_idx from CIRCLE_COMMENT where circle_comment_idx = ? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_comment_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

// 게시글 좋아요 유효성 검사
function isValidCircleLike($user_idx, $circle_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select circle_board_idx from CIRCLE_BOARD where circle_board_idx = ? and user_idx=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_idx, $user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidCircleCommentMine($user_idx, $circle_comment_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select circle_comment_idx from CIRCLE_COMMENT where circle_comment_idx = ? and user_idx=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$circle_comment_idx, $user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}