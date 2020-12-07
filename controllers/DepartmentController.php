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
        case "getDepartment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = getDepartment($user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과게시판 전체 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 4
         * API Name : 학과 게시판 상세 조회 API
         */
        case "getDepartmentDetail":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department_name = $vars['department_name'];

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartment_idx($department_name)){
                $res->isSuccess = FAlSE;
                $res->code = 203;
                $res->message = "존재하지 않는 학과입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;


            $res->result = getDepartmentDetail($department_name);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과게시판 상세 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 5
         * API Name : 학과별 게시판 -> 특정 게시물 조회 API
         */
        case "getBoardDetail":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $board_idx = $vars['department_board_idx'];

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartmentIdx($board_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;



            $res->result = getBoardDetail($user_idx, $board_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과게시판 특정 게시글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 5-1
         * API Name : 특정 게시물 댓글 조회 API
         */
        case "getBoardComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $board_idx = $vars['department_board_idx'];

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartmentIdx($board_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;



            $res->result = getBoardComment($user_idx, $board_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과 게시물 댓글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No. 6
         * API Name : 학과 게시판 글쓰기 API
         */
        case "postDepartment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department = $req->department;
            $title = $req->title;
            $content = $req->content;
            $photo = $req->photo;

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartment_idx($department)){
                $res->isSuccess = FAlSE;
                $res->code = 203;
                $res->message = "존재하지 않는 학과입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;


            postDepartment($department, $user_idx, $title, $content, $photo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과게시판 게시글 쓰기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 7
         * API Name : 학과별 게시판 게시글에 댓글 쓰기 API
         */
        case "postDepartmentComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department_idx = $vars['department_board_idx'];
            $comment = $req->comment;

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidDepartmentIdx($department_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            postDepartmentComment($department_idx, $user_idx, $comment);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과게시판 게시글에 댓글 쓰기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 8
         * API Name : 특정 학과 게시판 즐겨찾기 API
         */
        case "patchDepartmentLike":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department_name = $vars['department_name'];

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidDepartment_idx($department_name)){
                $res->isSuccess = FAlSE;
                $res->code = 203;
                $res->message = "존재하지 않는 학과입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $res->result = patchDepartmentLike($department_name, $user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "특정 학과 게시판 즐겨찾기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 9
         * API Name : 학과 게시물 좋아요 API
         */
        case "patchBoardLike":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $board_idx = $vars['department_board_idx'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartmentIdx($board_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidBoardLike($user_idx, $board_idx)){
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "내 게시물에는 공감할 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $result = patchBoardLike($board_idx, $user_idx);
            $status = $result['status'];

            if($status == 1){
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "학과 게시물 좋아요 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{
                $res->isSuccess = TRUE;
                $res->code = 101;
                $res->message = "학과 게시물 좋아요 취소 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


        /*
         * API No. 10
         * API Name : 즐겨찾기 게시판 조회 API
         */
        case "getDepartmentLike":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartmentLike($user_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "즐겨찾기한 학과 게시판이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            $res->result = getDepartmentLike($user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "즐겨찾기 게시판 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No. 17
         * API Name : 학과 게시물 수정하기 API
         */
        case "patchDepartment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department_idx = $vars['department_board_idx'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $title = $req->title;
            $content = $req->content;
            $photo = $req->photo;

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartmentIdx($department_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidBoardLike($user_idx, $department_idx)){
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "내 게시물만 수정할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            patchDepartment($department_idx, $user_idx, $title, $content, $photo);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과 게시물 수정하기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 18
         * API Name : 학과 게시물 삭제하기 API
         */
        case "deleteDepartment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department_idx = $vars['department_board_idx'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartmentIdx($department_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidBoardLike($user_idx, $department_idx)){
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "내 게시물만 삭제할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteDepartment($department_idx, $user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과 게시물 삭제하기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 19
         * API Name : 학과 게시물 댓글 삭제하기 API
         */
        case "deleteDepartmentComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department_comment_idx = $vars['department_comment_idx'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDepartmentCommentIdx($department_comment_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 댓글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidCommentMine($user_idx, $department_comment_idx)){
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "내 댓글만 삭제할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteDepartmentComment($department_comment_idx, $user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "학과 게시물 댓글 삭제하기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "searchDepartmentDetail":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $department_name = $_GET['department_name'];
            $title = $_GET['title'];

            if(!isset($jwt) || $jwt == null){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "로그인이 필요합니다(header에 토큰을 넣으세요)";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidHeader($jwt, JWT_SECRET_KEY)){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "잘못된 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!($department_name)){
                if (is_null($department_name)) {
                    $res->is_success = FALSE;
                    $res->code = 203;
                    $res->message = "query_string입니다. 학과 parameter를 확인하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                $res->is_success = FALSE;
                $res->code = 204;
                $res->message = "학과 검색어를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }
            if(!($title)){
                if (is_null($title)) {
                    $res->is_success = FALSE;
                    $res->code = 205;
                    $res->message = "query_string입니다. title parameter를 확인하세요.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                $res->is_success = FALSE;
                $res->code = 206;
                $res->message = "title 검색어를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }
            if (!isValidDepartment_idx($department_name)){
                $res->is_success = FALSE;
                $res->code = 207;
                $res->message = "해당하는 학과가 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidDepartment_title($department_name, $title)){
                $res->is_success = TRUE;
                $res->code = 101;
                $res->message = "해당하는 검색어의 게시글이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = searchDepartmentDetail($department_name, $title);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "특정 학과게시판의 게시글 검색 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
