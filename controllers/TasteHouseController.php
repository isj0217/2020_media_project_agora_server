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
         * API No. 4
         * API Name : 테스트 API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getTasteHouseAll":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $filter = $vars["filter"];
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
            if($filter == 0 or $filter > 6 or $filter < 0){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "path variable인 filter의 형식이 올바르지 않습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if ($filter == 1){
                $res->result = getTasteHouseAll1();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "아주 맛집 전체 별점 높은 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 2){
                $res->result = getTasteHouseAll2();
                $res->isSuccess = TRUE;
                $res->code = 101;
                $res->message = "아주 맛집 전체 댓글 많은 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 3){
                $res->result = getTasteHouseAll3();
                $res->isSuccess = TRUE;
                $res->code = 102;
                $res->message = "아주 맛집 전체 낮은 가격 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 4){
                $res->result = getTasteHouseAll4();
                $res->isSuccess = TRUE;
                $res->code = 103;
                $res->message = "아주 맛집 전체 높은 가격 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 5){
                $res->result = getTasteHouseAll5();
                $res->isSuccess = TRUE;
                $res->code = 104;
                $res->message = "아주 맛집 전체 최신 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 6){
                $res->result = getTasteHouseAll6();
                $res->isSuccess = TRUE;
                $res->code = 105;
                $res->message = "아주 맛집 카테고리별 오래된 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
         * API No. 5
         * API Name : 테스트 Path Variable API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getTasteHouse":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $category = $vars["category"];
            $filter = $vars["filter"];
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
            if($filter <= 0 or $filter > 6){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "path variable인 filter의 형식이 올바르지 않습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($category <= 0 or $category > 3){
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "path variable인 category의 형식이 올바르지 않습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if ($filter == 1){
                $res->result = getTasteHouse1($category);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "아주 맛집 카테고리별 별점 높은 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 2){
                $res->result = getTasteHouse2($category);
                $res->isSuccess = TRUE;
                $res->code = 101;
                $res->message = "아주 맛집 카테고리별 댓글 많은 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 3){
                $res->result = getTasteHouse3($category);
                $res->isSuccess = TRUE;
                $res->code = 102;
                $res->message = "아주 맛집 카테고리별 낮은 가격 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 4){
                $res->result = getTasteHouse4($category);
                $res->isSuccess = TRUE;
                $res->code = 103;
                $res->message = "아주 맛집 카테고리별 높은 가격 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 5){
                $res->result = getTasteHouse5($category);
                $res->isSuccess = TRUE;
                $res->code = 104;
                $res->message = "아주 맛집 카테고리별 최신 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($filter == 6){
                $res->result = getTasteHouse6($category);
                $res->isSuccess = TRUE;
                $res->code = 105;
                $res->message = "아주 맛집 카테고리별 오래된 순 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        case "getTasteHouseDetail":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $tastehouse_idx = $vars['tastehouse_idx'];
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
            if(!isValidTasteHouseIdx($tastehouse_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = getTasteHouseDetail($user_idx, $tastehouse_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "아주맛집 특정 게시글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No. 6
         * API Name : 테스트 Body & Insert API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "postTasteHouse":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $tastehouse_category = $req->tastehouse_category;
            $tastehouse_name = $req->tastehouse_name;
            $tastehouse_star = $req->tastehouse_star;
            $menu_picture = $req->menu_picture;
            $menu_name = $req->menu_name;
            $menu_price = $req->menu_price;
            $tastehouse_content = $req->tastehouse_content;
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            // Packet의 Body에서 데이터를 파싱합니다.
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
            if($menu_price == 0){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "잘못된 가격 형식입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            postTasteHouse($user_idx, $tastehouse_category, $tastehouse_name, $tastehouse_star,
                $menu_picture, $menu_name, $menu_price, $tastehouse_content);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "아주맛집 게시글 작성 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;




        case "patchTasteHouse":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $tastehouse_idx = $vars['tastehouse_idx'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $tastehouse_star = $req->tastehouse_star;
            $menu_picture = $req->menu_picture;
            $menu_name = $req->menu_name;
            $menu_price = $req->menu_price;
            $tastehouse_content = $req->tastehouse_content;


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
            if(!isValidTasteHouseIdx($tastehouse_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidTasteHouseUser($user_idx, $tastehouse_idx)){
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "내 게시글만 수정할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            patchTasteHouse($tastehouse_star, $menu_picture, $menu_name, $menu_price, $tastehouse_content, $tastehouse_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "아주맛집 게시글 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "deleteTasteHouse":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $tastehouse_idx = $vars['tastehouse_idx'];
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
            if(!isValidTasteHouseIdx($tastehouse_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidTasteHouseUser($user_idx, $tastehouse_idx)) {
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "내 게시글만 삭제할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteTasteHouse($tastehouse_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "아주 맛집 게시글 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "getTasteHouseComment":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $tastehouse_idx = $vars['tastehouse_idx'];
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
            if(!isValidTasteHouseIdx($tastehouse_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = getTasteHouseComment($user_idx, $tastehouse_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "아주맛집 특정 게시글의 댓글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "postTasteHouseComment":
            http_response_code(200);
            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $comment_content = $req->comment_content;
            $tastehouse_idx = $vars['tastehouse_idx'];
            $user_idx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            // Packet의 Body에서 데이터를 파싱합니다.
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
            if(!isValidTasteHouseIdx($tastehouse_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 게시글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            postTasteHouseComment($user_idx, $tastehouse_idx, $comment_content);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "아주맛집 게시글에 댓글 작성 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "deleteTasteHouseComment":
            http_response_code(200);

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
            $comment_idx = $vars['comment_idx'];
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
            if(!isValidTasteHouseCommentIdx($comment_idx)){
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "없는 댓글 번호입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidTasteHouseCommentUser($user_idx, $comment_idx)) {
                $res->isSuccess = FALSE;
                $res->code = 204;
                $res->message = "내 댓글만 삭제할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            deleteTasteHouseComment($comment_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "아주맛집 게시글에 댓글 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
