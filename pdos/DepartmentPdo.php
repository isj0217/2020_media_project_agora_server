<?php


// 학과 게시판 전체 조회 API
function getDepartment($user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select department_name, status,
                     case when type in (select type from DEPARTMENT_BOARD where timestampdiff(hour, DEPARTMENT_BOARD.created_at, now()) < 24
                                        group by type) then 1
                     else 0 end as is_new
              from DEPARTMENT, DEPARTMENT_LIKE
              where DEPARTMENT.department_name = DEPARTMENT_LIKE.type and user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 학과 게시판 상세 조회 API
function getDepartmentDetail($department_name)
{
    $pdo = pdoSqlConnect();
    $query = "select department_board_idx, title, content, user_nickname as nickname,
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
             where USER.user_idx = DEPARTMENT_BOARD.user_idx and  type = ? and DEPARTMENT_BOARD.is_deleted = 0
             order by DEPARTMENT_BOARD.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$department_name]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 학과 게시판 특정 게시글 조회 API
function getBoardDetail($user_idx, $board_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select DEPARTMENT_BOARD.user_idx,user_picture, department_board_idx,title, content, user_nickname as nickname,
                     case when timestampdiff(second, DEPARTMENT_BOARD.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, DEPARTMENT_BOARD.created_at, now()) < 60
                          then concat(timestampdiff(minute, DEPARTMENT_BOARD.created_at, now()),' 분전')
                          when timestampdiff(hour, DEPARTMENT_BOARD.created_at, now()) < 24
                          then concat(timestampdiff(hour, DEPARTMENT_BOARD.created_at, now()),' 시간전')
                          when timestampdiff(day, DEPARTMENT_BOARD.created_at, now()) < 30
                          then concat(timestampdiff(day, DEPARTMENT_BOARD.created_at, now()),' 일전') end as time,
                     IF(photo is null, null, photo) as photo,
                     (select count(*) from DEPARTMENT_BOARD_LIKE where department_board_idx = ? and status = 1) as like_num, 
                     (select count(*) from DEPARTMENT_COMMENT where department_board_idx = ? and is_deleted = 0) as comment_num,
                     (select status from DEPARTMENT_BOARD_LIKE where department_board_idx = ? and user_idx = ?) as like_status,
                     if(DEPARTMENT_BOARD.user_idx=?,1,0) as is_mine
             from DEPARTMENT_BOARD, USER
             where USER.user_idx = DEPARTMENT_BOARD.user_idx and department_board_idx = ? and DEPARTMENT_BOARD.is_deleted = 0
             order by DEPARTMENT_BOARD.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx,$board_idx,$board_idx, $user_idx, $user_idx, $board_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getBoardComment($user_idx, $board_idx)
{
    $pdo = pdoSqlConnect();


    $query = "select department_comment_idx, USER.user_nickname as nickname,
                     case when timestampdiff(second, DEPARTMENT_COMMENT.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, DEPARTMENT_COMMENT.created_at, now()) < 60
                          then concat(timestampdiff(minute, DEPARTMENT_COMMENT.created_at, now()),' 분전')
                          when timestampdiff(hour, DEPARTMENT_COMMENT.created_at, now()) < 24
                          then concat(timestampdiff(hour, DEPARTMENT_COMMENT.created_at, now()),' 시간전')
                          when timestampdiff(day, DEPARTMENT_COMMENT.created_at, now()) < 30
                          then concat(timestampdiff(day, DEPARTMENT_COMMENT.created_at, now()),' 일전') end as time,
                     comment,
                     if(DEPARTMENT_COMMENT.user_idx=?,1,0) as is_mine
             from DEPARTMENT_BOARD, DEPARTMENT_COMMENT, USER
             where DEPARTMENT_BOARD.department_board_idx = DEPARTMENT_COMMENT.department_board_idx and DEPARTMENT_COMMENT.user_idx = USER.user_idx and
                   DEPARTMENT_BOARD.department_board_idx = ? and DEPARTMENT_BOARD.is_deleted = 0 and DEPARTMENT_COMMENT.is_deleted = 0
             order by DEPARTMENT_COMMENT.created_at;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $board_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}

// 학과 게시판 글쓰기 API
function postDepartment($department, $user_idx, $title, $content, $photo)
{
    $pdo = pdoSqlConnect();
    $query = "insert into DEPARTMENT_BOARD (type, user_idx, title, content, photo, created_at) values (?,?,?,?,?,now());";

    $st = $pdo->prepare($query);
    $st->execute([$department, $user_idx, $title, $content, $photo]);


    $st = null;
    $pdo = null;

}

// 게시글 번호 유효성 검사
function isValidDepartmentIdx($department_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select department_board_idx from DEPARTMENT_BOARD where department_board_idx=? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$department_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidDepartmentCommentIdx($department_comment_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select department_comment_idx from DEPARTMENT_COMMENT where department_comment_idx=? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$department_comment_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

// 학과 게시판 게시글에 댓글 달기 API
function postDepartmentComment($department_idx, $user_idx, $comment)
{

    $pdo = pdoSqlConnect();
    try{
        $pdo->beginTransaction();
        $query = "insert into DEPARTMENT_COMMENT (department_board_idx, user_idx, comment, created_at) values (?,?,?,now());";

        $st = $pdo->prepare($query);
        $st->execute([$department_idx, $user_idx, $comment]);

        $query = "update DEPARTMENT_BOARD set comment_num = comment_num + 1 where department_board_idx = ?;";

        $st = $pdo->prepare($query);
        $st->execute([$department_idx]);

        $pdo->commit();
        $st = null;
        $pdo = null;
    } catch (Exception $exception){
        $pdo->rollback();
    }
}

// 특정 학과 게시판 즐겨찾기 API
function patchDepartmentLike($department_name, $user_idx)
{
    $pdo = pdoSqlConnect();

    $query = "insert into DEPARTMENT_LIKE select ?,?,0 from dual 
              where not exists(select user_idx, type from DEPARTMENT_LIKE where user_idx=? and type=?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $department_name,$user_idx, $department_name]);

    $query = "update DEPARTMENT_LIKE set status = if(status=1,0,1) where user_idx = ? and type = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $department_name]);

    $query = "select type as department_name, status from DEPARTMENT_LIKE where user_idx = ? and type = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $department_name]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res[0];
}

// 게시글 좋아요 유효성 검사
function isValidBoardLike($user_idx, $board_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select department_board_idx from DEPARTMENT_BOARD where department_board_idx = ? and user_idx=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx, $user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidCommentMine($user_idx, $department_comment_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select department_comment_idx from DEPARTMENT_COMMENT where department_comment_idx = ? and user_idx=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$department_comment_idx, $user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

// 특정 게시물 좋아요 API
function patchBoardLike($board_idx, $user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "insert into DEPARTMENT_BOARD_LIKE select ?,?,0 from dual 
              where not exists(select department_board_idx, user_idx from DEPARTMENT_BOARD_LIKE 
                               where department_board_idx=? and user_idx=?);";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx,$user_idx,$board_idx,$user_idx]);

    $query = "update DEPARTMENT_BOARD_LIKE set status = if(status=1,0,1) where department_board_idx = ? and user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx,$user_idx]);

    $query = "update DEPARTMENT_BOARD  join DEPARTMENT_BOARD_LIKE
              set like_num = (select count(*) from DEPARTMENT_BOARD_LIKE where department_board_idx=? and status=1)
              where DEPARTMENT_BOARD.department_board_idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$board_idx,$board_idx]);

    $query = "select department_board_idx, status from DEPARTMENT_BOARD_LIKE where user_idx = ? and department_board_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $board_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res[0];
}

// 즐겨찾기 게시판 조회 API
function getDepartmentLike($user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select type as department from DEPARTMENT_LIKE where user_idx = ? and status = 1;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 즐겨찾기 게시판 존재 유무
function isValidDepartmentLike($user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select type from DEPARTMENT_LIKE where user_idx = ? and status = 1) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

// 학과 게시판 게시글 수정하기
function patchDepartment($department_idx, $user_idx, $title, $content, $photo)
{
    $pdo = pdoSqlConnect();
    $query = "update DEPARTMENT_BOARD set title =? , content =? , photo = ?,updated_at = now() where user_idx =? and department_board_idx = ? and is_deleted = 0;";

    $st = $pdo->prepare($query);
    $st->execute([$title,$content,$photo,$user_idx,$department_idx]);

    $st = null;
    $pdo = null;

}

// 학과 게시판 게시글 삭제하기
function deleteDepartment($department_idx, $user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "update DEPARTMENT_BOARD set is_deleted = 1 where user_idx = ? and department_board_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx,$department_idx]);

    $st = null;
    $pdo = null;

}

// 학과 게시판 댓글 삭제하기
function deleteDepartmentComment($department_comment_idx, $user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "update DEPARTMENT_COMMENT set is_deleted = 1 where department_comment_idx = ? and user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$department_comment_idx,$user_idx]);

    $query = "update DEPARTMENT_COMMENT inner join DEPARTMENT_BOARD on DEPARTMENT_BOARD.department_board_idx = DEPARTMENT_COMMENT.department_board_idx
              set comment_num = comment_num - 1 where department_comment_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$department_comment_idx]);

    $st = null;
    $pdo = null;

}

function searchDepartmentDetail($department_name, $title)
{
    $pdo = pdoSqlConnect();
    $query = "select department_board_idx, title, content, user_nickname as nickname,
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
where USER.user_idx = DEPARTMENT_BOARD.user_idx and type = ? and (title like concat('%',?,'%') or content like concat('%',?,'%')) and
      DEPARTMENT_BOARD.is_deleted = 0
order by DEPARTMENT_BOARD.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$department_name, $title, $title]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function isValidDepartment_title($department_name,$title)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from DEPARTMENT_BOARD where type = ? and 
(title like concat('%',?,'%') or content like concat('%',?,'%')) and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$department_name, $title, $title]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}