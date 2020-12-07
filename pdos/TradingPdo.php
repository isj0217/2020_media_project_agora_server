<?php

//READ
function isValidTrading_idx($trading_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Used_Trading_Post where used_trading_idx = ?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$trading_idx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

function isValidTradingBoard_idx($trading_board_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Used_Trading_Board where trading_board_idx = ?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$trading_board_idx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

function getTrading($trading_board)
{
    $pdo = pdoSqlConnect();
    $query = "select used_trading_idx, Used_Trading_Post.trading_board_idx, user_nickname, post_name, post_name, post_picture,
       post_content, post_price
from Used_Trading_Board, USER, Used_Trading_Post
where USER.user_idx = Used_Trading_Post.user_idx &&
      Used_Trading_Post.trading_board_idx = Used_Trading_Board.trading_board_idx &&
      Used_Trading_Post.trading_board_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$trading_board]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
function getTradingBoard()
{
    $pdo = pdoSqlConnect();
    $query = "select * from Used_Trading_Board;";

    $st = $pdo->prepare($query);
    $st->execute();
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
function postTrading($user_idx, $trading_board_idx, $post_name, $post_picture, $post_content, $post_price)
{
    $pdo = pdoSqlConnect();
    $query = "insert into Used_Trading_Post (user_idx, trading_board_idx, post_name, post_picture,
                          post_content, post_price) values(?, ?, ?, ?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $trading_board_idx, $post_name, $post_picture, $post_content, $post_price]);
    //    $st->execute();
    $st = null;
    $pdo = null;

}

function postTradingComment($user_idx, $trading_idx, $comment_content){
    $pdo = pdoSqlConnect();
    $query = "insert into Used_Trading_Comment (user_idx, used_trading_idx, comment_content) values (?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $trading_idx, $comment_content]);

    $query = "select comment_idx from Used_Trading_Comment where user_idx = ? and used_trading_idx = ?
                                               and created_at = (SELECT MAX(created_at) from Used_Trading_Comment);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $trading_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    //    $st->execute();
    $st = null;
    $pdo = null;

    return $res[0];
}

//function deleteTradingComment($trading_board)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select used_trading_idx, Used_Trading_Post.trading_board_idx, user_nickname, post_name, post_name, post_picture,
//       post_content, post_price
//from Used_Trading_Board, USER, Used_Trading_Post
//where USER.user_idx = Used_Trading_Post.user_idx &&
//      Used_Trading_Post.trading_board_idx = Used_Trading_Board.trading_board_idx &&
//      Used_Trading_Post.trading_board_idx = ?;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$trading_board]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}