<?php
require 'function.php';


const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (object)array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;

        case "getTradingBoard":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            if(!isset($jwt) || $jwt == null){
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->is_success = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getTradingBoard();

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "중고거래 게시판 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "getTrading":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            if(!isset($jwt) || $jwt == null){
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->is_success = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $trading_board = $vars["trading_board_idx"];

            $res->result = getTrading($trading_board);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 6
         * API Name : 테스트 Body & Insert API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "postTrading":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $trading_board_idx = $req->trading_board_idx;
            $post_name = $req->post_name;
            $post_picture = $req->post_picture;
            $post_content = $req->post_content;
            $post_price = $req->post_price;
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($jwt) || $jwt == null){
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->is_success = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidTradingBoard_idx($trading_board_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 중고거래 게시판 index입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($post_price == 0){
                $res->is_success = FALSE;
                $res->code = 204;
                $res->message = "잘못된 가격 형식입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            postTrading($user_idx, $trading_board_idx, $post_name, $post_picture, $post_content, $post_price);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "중고거래 게시글 작성 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "postTradingComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $trading_idx = $req->trading_idx;
            $comment_content = $req->comment_content;
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($jwt) || $jwt == null){
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->is_success = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidTrading_idx($trading_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 중고거래 게시글 index 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            $res->result = postTradingComment($user_idx, $trading_idx, $comment_content);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "중고거래 게시글에 댓글 작성 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        case "deleteTradingComment":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            if(!isset($jwt) || $jwt == null){
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->is_success = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $comment_idx = $vars["comment_idx"];

            $res->result = deleteTradingComment($comment_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



    }
}
catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}