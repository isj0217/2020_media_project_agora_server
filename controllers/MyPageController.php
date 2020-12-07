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
         * API No. 3
         * API Name : 학과 게시판 전체 조회 API
         */
        case "getMyPage":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isset($jwt) || $jwt == null) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getMyPage($user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "마이페이지 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "patchMyNickname":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $user_nickname = $req -> nickname;

            if (!isset($jwt) || $jwt == null) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isValidpatchMyNickname($user_idx, $user_nickname)) {
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "기존의 닉네임과 같습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            patchMyNickname($user_idx, $user_nickname);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "마이페이지 닉네임 변경 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "patchMyPicture":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $user_picture = $req->user_picture;

            if (!isset($jwt) || $jwt == null) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isValidpatchMyPicture($user_idx, $user_picture)) {
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "기존의 사진과 같습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            patchMyPicture($user_idx, $user_picture);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "마이페이지 프로필 사진 변경 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



        case "getMyPageinLike":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isset($jwt) || $jwt == null) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getMyPageinLike($user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "마이페이지 좋아요 표시한 글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}