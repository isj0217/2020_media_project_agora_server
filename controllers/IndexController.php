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
        case "login":
            http_response_code(200);

            $accessToken = $req->accessToken;
            $header = "Bearer ".$accessToken;
            $url = "https://openapi.naver.com/v1/nid/me";
            $is_post = false;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, $is_post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $headers = array();
            $headers[] = "Authorization: ".$header;
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            //echo "status_code:" . $status_code;

            curl_close($ch);


            if ($status_code == 200) {
                $profileResponse = json_decode($response);
                $server_id = $profileResponse->response->id;
                $server_name = $profileResponse->response->name;

                if (!isValidNaverUser($server_id)) {
                    $res->result["server_id"] = $server_id;  //원래 "server_id"인데 클라 요구로 jwt로 변경
                    $res->result["server_name"] = $server_name;
                    $res->is_success = FALSE;
                    $res->code = 201;
                    $res->message = "존재하지 않는 유저입니다. 회원가입을 하세요";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                $userIdx = getUserIdxByID($server_id);
                $jwt = getJWT($userIdx, JWT_SECRET_KEY);

                $res->result["jwt"] = $jwt;
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "로그인 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }
            else {
                $res->is_success = FALSE;
                $res->code = 202;
                $res->message = "인증되지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        case "postUser":
            http_response_code(200);

            if (isValidNickname($req->nickname)) {
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "닉네임 중복입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else if (!isValidDepartment_idx($req->department_idx)) {
                $res->is_success = FAlSE;
                $res->code = 202;
                $res->message = "존재하지 않는 학과 번호입니다. 학과 번호 값을 확인하세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else if (isValidStudentId($req->student_id)) {
                $res->is_success = FAlSE;
                $res->code = 203;
                $res->message = "존재하는 학번입니다. 학번을 확인하세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }




            $username = $req->user_name;
            $nickname = $req->nickname;
            $server_id = $req->server_id;
            $department_idx = $req->department_idx;
            $student_id = $req->student_id;


            postUser($username, $nickname, $student_id, $department_idx, $server_id);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "deleteUser":
            http_response_code(200);
            $server_id = $req->server_id;
            if (!isValidNaverUser($server_id)) {
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "존재하지 않는 유저입니다. 회원가입을 하세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            deleteUser($server_id);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getUsers":
            http_response_code(200);

            $res->result = getUsers();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 5
         * API Name : 테스트 Path Variable API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getUserDetail":
            http_response_code(200);

            $res->result = getUserDetail($vars["userIdx"]);
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
        case "createUser":
            http_response_code(200);

            // Packet의 Body에서 데이터를 파싱합니다.
            $userID = $req->userID;
            $pwd_hash = password_hash($req->pwd, PASSWORD_DEFAULT); // Password Hash
            $name = $req->name;

            $res->result = createUser($userID, $pwd_hash, $name);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
