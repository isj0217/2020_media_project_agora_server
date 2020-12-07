<?php

//READ
function isValidTasteHouseIdx($tastehouse_idx){
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Ajou_TasteHouse_Post where tastehouse_idx = ? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$tastehouse_idx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

function isValidTasteHouseUser($user_idx, $tastehouse_idx){
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Ajou_TasteHouse_Post 
    where user_idx = ? and ? = (select user_idx from Ajou_TasteHouse_Post where tastehouse_idx = ?)) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $user_idx, $tastehouse_idx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidTasteHouseCommentIdx($comment_idx){
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from TasteHouse_Comment where comment_idx = ? and is_deleted = 0) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$comment_idx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function isValidTasteHouseCommentUser($user_idx, $comment_idx){
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from TasteHouse_Comment 
    where user_idx = ? and ? = (select user_idx from TasteHouse_Comment where comment_idx = ?)) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $user_idx, $comment_idx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function getTasteHouseAll1(){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
            group by Ajou_TasteHouse_Post.tastehouse_idx
             order by tastehouse_star desc;";

    $st = $pdo->prepare($query);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouseAll2(){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
            group by Ajou_TasteHouse_Post.tastehouse_idx
             order by comment_num desc;";

    $st = $pdo->prepare($query);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouseAll3(){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
            group by Ajou_TasteHouse_Post.tastehouse_idx
             order by menu_price;";

    $st = $pdo->prepare($query);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouseAll4(){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
            group by Ajou_TasteHouse_Post.tastehouse_idx
             order by menu_price desc;";

    $st = $pdo->prepare($query);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouseAll5(){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
            group by Ajou_TasteHouse_Post.tastehouse_idx
order by Ajou_TasteHouse_Post.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouseAll6(){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
            group by Ajou_TasteHouse_Post.tastehouse_idx
order by Ajou_TasteHouse_Post.created_at;";

    $st = $pdo->prepare($query);
    $st->execute();

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}

function getTasteHouse1($category){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
             and tastehouse_category = ?
group by Ajou_TasteHouse_Post.tastehouse_idx
                order by tastehouse_star desc;";

    $st = $pdo->prepare($query);
    $st->execute([$category]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouse2($category){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
             and tastehouse_category = ?
group by Ajou_TasteHouse_Post.tastehouse_idx
            order by comment_num desc;";

    $st = $pdo->prepare($query);
    $st->execute([$category]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouse3($category){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
             and tastehouse_category = ?
group by Ajou_TasteHouse_Post.tastehouse_idx
                order by menu_price;";

    $st = $pdo->prepare($query);
    $st->execute([$category]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouse4($category){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
             and tastehouse_category = ?
group by Ajou_TasteHouse_Post.tastehouse_idx
                order by menu_price desc;";

    $st = $pdo->prepare($query);
    $st->execute([$category]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouse5($category){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
             and tastehouse_category = ?
group by Ajou_TasteHouse_Post.tastehouse_idx
             order by Ajou_TasteHouse_Post.created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$category]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}
function getTasteHouse6($category){
    $pdo = pdoSqlConnect();


    $query = "select Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
             and tastehouse_category = ?
group by Ajou_TasteHouse_Post.tastehouse_idx
             order by Ajou_TasteHouse_Post.created_at;";

    $st = $pdo->prepare($query);
    $st->execute([$category]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}

function getTasteHouseDetail($user_idx, $tastehouse_idx){
    $pdo = pdoSqlConnect();


    $query = "select USER.user_idx, Ajou_TasteHouse_Post.tastehouse_idx, tastehouse_name, tastehouse_star, menu_picture, menu_name, menu_price, tastehouse_content,
user_nickname as nickname, count(TasteHouse_Comment.tastehouse_idx) as comment_num,
                     case when timestampdiff(second, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()) < 60
                          then concat(timestampdiff(minute, Ajou_TasteHouse_Post.created_at, now()),' 분전')
                          when timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()) < 24
                          then concat(timestampdiff(hour, Ajou_TasteHouse_Post.created_at, now()),' 시간전')
                          when timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()) < 30
                          then concat(timestampdiff(day, Ajou_TasteHouse_Post.created_at, now()),' 일전') end as time,
                          if(Ajou_TasteHouse_Post.user_idx=?,1,0) as is_mine
             from Ajou_TasteHouse_Post left outer join TasteHouse_Comment on Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx
and TasteHouse_Comment.is_deleted = 0
and Ajou_TasteHouse_Post.is_deleted = 0, USER
             where USER.user_idx = Ajou_TasteHouse_Post.user_idx and Ajou_TasteHouse_Post.is_deleted = 0
                and Ajou_TasteHouse_Post.tastehouse_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $tastehouse_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res[0];
}

function postTasteHouse($user_idx, $tastehouse_category, $tastehouse_name, $tastehouse_star,
                        $menu_picture, $menu_name, $menu_price, $tastehouse_content)
{
    $pdo = pdoSqlConnect();
    $query = "insert into Ajou_TasteHouse_Post (
user_idx, tastehouse_category,
tastehouse_name, tastehouse_star,
menu_picture, menu_name,
menu_price, tastehouse_content
) values (?, ?, ?, ?, ?, ?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $tastehouse_category, $tastehouse_name, $tastehouse_star,
        $menu_picture, $menu_name, $menu_price, $tastehouse_content]);
    $st = null;
    $pdo = null;
    //    $st->execute();
}
function patchTasteHouse($tastehouse_star, $menu_picture, $menu_name, $menu_price, $tastehouse_content, $tastehouse_idx){
    $pdo = pdoSqlConnect();
    $query = "update Ajou_TasteHouse_Post
set tastehouse_star = ?, menu_picture = ?, menu_name = ?,
menu_price = ?, tastehouse_content = ?
 where tastehouse_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$tastehouse_star, $menu_picture, $menu_name, $menu_price, $tastehouse_content, $tastehouse_idx]);
    $st = null;
    $pdo = null;
    //    $st->execute();
}


function deleteTasteHouse($tastehouse_idx){
    $pdo = pdoSqlConnect();
    $query = "update Ajou_TasteHouse_Post set is_deleted = 1 where tastehouse_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$tastehouse_idx]);
    $st = null;
    $pdo = null;
    //    $st->execute();
}

function getTasteHouseComment($user_idx, $tastehouse_idx)
{
    $pdo = pdoSqlConnect();


    $query = "select comment_idx, USER.user_nickname as nickname,
                     case when timestampdiff(second, TasteHouse_Comment.created_at, now()) < 60
                          then '방금 전'
                          when timestampdiff(minute, TasteHouse_Comment.created_at, now()) < 60
                          then concat(timestampdiff(minute, TasteHouse_Comment.created_at, now()),' 분 전')
                          when timestampdiff(hour, TasteHouse_Comment.created_at, now()) < 24
                          then concat(timestampdiff(hour, TasteHouse_Comment.created_at, now()),' 시간 전')
                          when timestampdiff(day, TasteHouse_Comment.created_at, now()) < 30
                          then concat(timestampdiff(day, TasteHouse_Comment.created_at, now()),' 일 전') end as time,
                     comment_content,
                     if(TasteHouse_Comment.user_idx=?,1,0) as is_mine
             from Ajou_TasteHouse_Post, TasteHouse_Comment, USER
             where Ajou_TasteHouse_Post.tastehouse_idx = TasteHouse_Comment.tastehouse_idx and TasteHouse_Comment.user_idx = USER.user_idx and
                   Ajou_TasteHouse_Post.tastehouse_idx = ? and Ajou_TasteHouse_Post.is_deleted = 0 and TasteHouse_Comment.is_deleted = 0
             order by TasteHouse_Comment.created_at;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $tastehouse_idx]);

    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();


    $st = null;
    $pdo = null;

    return $res;
}

function postTasteHouseComment($user_idx, $tastehouse_idx, $comment_content){
    $pdo = pdoSqlConnect();
    $query = "insert into TasteHouse_Comment (user_idx, tastehouse_idx, comment_content) values (?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $tastehouse_idx, $comment_content]);

    $st = null;
    $pdo = null;
    //    $st->execute();
}
function deleteTasteHouseComment($comment_idx){
    $pdo = pdoSqlConnect();
    $query = "update TasteHouse_Comment set is_deleted = 1 where comment_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$comment_idx]);

    $st = null;
    $pdo = null;
    //    $st->execute();
}

