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





        /*
         * API No. 38
         * API Name : 내 쪽지함 조회 API
         */
        case "getMessageRoomAll":
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

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getMessageRoomAll($user_idx);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "내 쪽지함 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 39
         * API Name : 특정 쪽지함 조회 API
         */
        case "getMessageRoom":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $message_room_idx = $vars['message_room_idx'];

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

            if(!isValidMessageRoom($message_room_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 쪽지함입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getMessageRoom($user_idx, $message_room_idx);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "특정 쪽지함 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 40
         * API Name : 특정 쪽지함 삭제 API
         */
        case "delMessageRoom":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $message_room_idx = $vars['message_room_idx'];

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

            if(!isValidMessageRoom($message_room_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 쪽지함입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            delMessageRoom($message_room_idx);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "특정 쪽지함 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 42
         * API Name : 쪽지보내기 API
         */
        case "postMessage":
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


            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $opponent = $req->user_idx;
            $content = $req->content;

            postMessage($user_idx, $opponent, $content);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "쪽지 보내기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
