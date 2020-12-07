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
         * API No. 11
         * API Name : 동아리 게시판 전체 조회 API
         */
        case "getCircle":
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

            $res->result = getCircle();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시판 전체 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No. 12
         * API Name : 특정 동아리 게시판 조회 API
         */
        case "getCircleDetail":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $circle_name = $vars['circle_name'];

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
            if(!isValidCircle($circle_name)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 동아리입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getCircleDetail($circle_name);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "특정 동아리 게시판 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 13
         * API Name : 동아리 게시판 특정 게시물 조회 API
         */
        case "getCircleBoardDetail":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $board_idx = $vars['circle_board_idx'];

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
            if(!isValidCircleIdx($board_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 게시물입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getCircleBoardDetail($board_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시판 특정 게시물 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No. 13-1
         * API Name : 동아리 게시물 댓글 조회 API
         */
        case "getCircleBoardComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $board_idx = $vars['circle_board_idx'];

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
            if(!isValidCircleIdx($board_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 게시물입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getCircleBoardComment($board_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시물 댓글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No. 14
         * API Name : 동아리 게시판 글쓰기 API
         */
        case "postCircle":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $circle = $req->circle;
            $title = $req->title;
            $content = $req->content;
            $photo = $req->photo;

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
            if(!isValidCircle($circle)){
                $res->is_success = FAlSE;
                $res->code = 203;
                $res->message = "존재하지 않는 동아리입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;


            postCircle($circle, $user_idx, $title, $content, $photo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시판 게시글 쓰기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 15
         * API Name : 동아리 게시판 게시글에 댓글 쓰기 API
         */
        case "postCircleComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $circle_idx = $vars['circle_board_idx'];
            $comment = $req->comment;

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

            if(!isValidCircleIdx($circle_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            postCircleComment($circle_idx, $user_idx, $comment);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시판 게시글에 댓글 쓰기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 16
         * API Name : 동아리 게시물 좋아요 API
         */
        case "patchCircleLike":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $circle_idx = $vars['circle_board_idx'];
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
            if(!isValidCircleIdx($circle_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidCircleLike($user_idx, $circle_idx)){
                $res->is_success = FALSE;
                $res->code = 204;
                $res->message = "내 게시물에는 공감할 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $result = patchCircleLike($circle_idx, $user_idx);
            $status = $result['status'];

            if($status == 1){
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "동아리 게시물 좋아요 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{
                $res->isSuccess = TRUE;
                $res->code = 101;
                $res->message = "동아리 게시물 좋아요 취소 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


        /*
         * API No. 20
         * API Name : 동아리 게시물 수정하기 API
         */
        case "patchCircle":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $circle_idx = $vars['circle_board_idx'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $title = $req->title;
            $content = $req->content;
            $photo = $req->photo;

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
            if(!isValidCircleIdx($circle_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidCircleLike($user_idx, $circle_idx)){
                $res->is_success = FALSE;
                $res->code = 204;
                $res->message = "내 게시물만 수정할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            patchCircle($circle_idx, $user_idx, $title, $content, $photo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시물 수정하기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 21
         * API Name : 동아리 게시물 삭제하기 API
         */
        case "deleteCircle":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $circle_idx = $vars['circle_board_idx'];
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
            if(!isValidCircleIdx($circle_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidCircleLike($user_idx, $circle_idx)){
                $res->is_success = FALSE;
                $res->code = 204;
                $res->message = "내 게시물만 삭제할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteCircle($circle_idx, $user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시물 삭제하기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 22
         * API Name : 동아리 게시물 댓글 삭제하기 API
         */
        case "deleteCircleComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $circle_comment_idx = $vars['circle_comment_idx'];
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
            if(!isValidCircleCommentIdx($circle_comment_idx)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "없는 댓글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidCircleCommentMine($user_idx, $circle_comment_idx)){
                $res->is_success = FALSE;
                $res->code = 204;
                $res->message = "내 댓글만 삭제할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteCircleComment($circle_comment_idx, $user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "동아리 게시물 댓글 삭제하기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
