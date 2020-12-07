<?php

require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/JWTPdo.php';
require './vendor/autoload.php';
require './pdos/DepartmentPdo.php';
require './pdos/MessagePdo.php';
require './pdos/TasteHousePdo.php';
require './pdos/MyPagePdo.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

//에러출력하게 하는 코드
//error_reporting(E_ALL); ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ******************   JWT   ****************** */
    $r->addRoute('POST', '/jwt', ['JWTController', 'createJwt']);   // JWT 생성: 로그인 + 해싱된 패스워드 검증 내용 추가
    $r->addRoute('GET', '/jwt', ['JWTController', 'validateJwt']);  // JWT 유효성 검사
    $r->addRoute('GET', '/', ['IndexController', 'index']);

    /* *********************************************   로그인 회원가입   ********************************************* */
    $r->addRoute('POST', '/login', ['IndexController', 'login']); // 네이버 소셜 로그인 API

    $r->addRoute('POST', '/user', ['IndexController', 'postUser']); // 추가 회원가입 API

    /* *********************************************   학과 게시판 관련 기능   ********************************************* */

    $r->addRoute('GET', '/department', ['DepartmentController', 'getDepartment']); // 학과 게시판 전체 조회 API

    $r->addRoute('GET', '/department/{department_name}', ['DepartmentController', 'getDepartmentDetail']); // 학과 게시판 세부 조회 API

    $r->addRoute('GET', '/department-board-idx/{department_board_idx}', ['DepartmentController', 'getBoardDetail']); // 학과별 게시판 -> 특정 게시물 조회 API

    $r->addRoute('GET', '/department-board-idx/{department_board_idx}/comment', ['DepartmentController', 'getBoardComment']); // 특정 게시물 댓글 조회

    $r->addRoute('POST', '/department', ['DepartmentController', 'postDepartment']); // 학과 게시판 글쓰기 API

    $r->addRoute('POST', '/department-board-idx/{department_board_idx}/comment', ['DepartmentController', 'postDepartmentComment']); // 학과별 게시판 게시글에 댓글 쓰기 API

    $r->addRoute('PATCH', '/department/{department_name}/like', ['DepartmentController', 'patchDepartmentLike']); // 특정 학과 게시판 즐겨찾기 API

    $r->addRoute('PATCH', '/department-board-idx/{department_board_idx}/like', ['DepartmentController', 'patchBoardLike']); // 학과 게시물 좋아요 API

    $r->addRoute('GET', '/department-like', ['DepartmentController', 'getDepartmentLike']); // 즐겨찾기 게시판 조회 API

    $r->addRoute('PATCH', '/department-board-idx/{department_board_idx}/patch', ['DepartmentController', 'patchDepartment']); // 학과 게시글 수정하기 API

    $r->addRoute('DELETE', '/department-board-idx/{department_board_idx}/delete', ['DepartmentController', 'deleteDepartment']); // 학과 게시글 삭제하기 API

    $r->addRoute('DELETE', '/department-comment-idx/{department_comment_idx}/delete', ['DepartmentController', 'deleteDepartmentComment']); // 학과 게시글 댓글 삭제하기 API

    $r->addRoute('GET', '/department_board', ['DepartmentController', 'searchDepartmentDetail']);

    /* *********************************************   동아리 게시판 관련 기능   ********************************************* */

    $r->addRoute('GET', '/circle', ['CircleController', 'getCircle']); // 동아리 게시판 전체 조회 API

    $r->addRoute('GET', '/circle/{circle_name}', ['CircleController', 'getCircleDetail']); // 특정 동아리 게시판 조회 API

    $r->addRoute('GET', '/circle-board-idx/{circle_board_idx}', ['CircleController', 'getCircleBoardDetail']); // 동아리 게시판 특정 게시물 조회 API

    $r->addRoute('GET', '/circle-board-idx/{circle_board_idx}/comment', ['CircleController', 'getCircleBoardComment']); // 동아리 게시글 댓글 조회

    $r->addRoute('POST', '/circle', ['CircleController', 'postCircle']); // 동아리 게시판 글쓰기 API

    $r->addRoute('POST', '/circle-board-idx/{circle_board_idx}/comment', ['CircleController', 'postCircleComment']); // 동아리 게시글에 댓글 달기 API

    $r->addRoute('PATCH', '/circle-board-idx/{circle_board_idx}/like', ['CircleController', 'patchCircleLike']); // 동아리 게시물 좋아요 API

    $r->addRoute('PATCH', '/circle-board-idx/{circle_board_idx}/patch', ['CircleController', 'patchCircle']); // 동아리 게시글 수정하기 API

    $r->addRoute('DELETE', '/circle-board-idx/{circle_board_idx}/delete', ['CircleController', 'deleteCircle']); // 동아리 게시글 삭제하기 API

    $r->addRoute('DELETE', '/circle-comment-idx/{circle_comment_idx}/delete', ['CircleController', 'deleteCircleComment']); // 동아리 게시글 댓글 삭제하기 API


    /* *********************************************   쪽지 관련 기능   ********************************************* */

    $r->addRoute('GET', '/message-room', ['MessageController', 'getMessageRoomAll']); // 내 쪽지함 조회 API

    $r->addRoute('GET', '/message-room-idx/{message_room_idx}', ['MessageController', 'getMessageRoom']); // 내 쪽지함 조회 API

    $r->addRoute('DELETE', '/message-room-idx/{message_room_idx}/delete', ['MessageController', 'delMessageRoom']); // 특정 쪽지함 삭제 API

    $r->addRoute('POST', '/message', ['MessageController', 'postMessage']); // 쪽지 보내기 API

    /* ******************   아주 맛집   ****************** */

    $r->addRoute('GET', '/tastehouse/filter/{filter}', ['TasteHouseController', 'getTasteHouseAll']);

    $r->addRoute('GET', '/tastehouse/filter/{filter}/category/{category}', ['TasteHouseController', 'getTasteHouse']);

    $r->addRoute('GET', '/tastehouse/{tastehouse_idx}', ['TasteHouseController', 'getTasteHouseDetail']);

    $r->addRoute('POST', '/tastehouse', ['TasteHouseController', 'postTasteHouse']);

    $r->addRoute('PATCH', '/tastehouse/{tastehouse_idx}', ['TasteHouseController', 'patchTasteHouse']);

    $r->addRoute('DELETE', '/tastehouse/{tastehouse_idx}', ['TasteHouseController', 'deleteTasteHouse']);

    $r->addRoute('GET', '/tastehouse/{tastehouse_idx}/comment', ['TasteHouseController', 'getTasteHouseComment']);

    $r->addRoute('POST', '/tastehouse/{tastehouse_idx}/comment', ['TasteHouseController', 'postTasteHouseComment']);

    $r->addRoute('DELETE', '/tastehouse/comment/{comment_idx}', ['TasteHouseController', 'deleteTasteHouseComment']);


    /* ******************   중고 거래   ****************** */

    $r->addRoute('GET', '/tradingboard', ['TradingController', 'getTradingBoard']);

    $r->addRoute('GET', '/trading/{trading_board_idx}', ['TradingController', 'getTrading']);

    $r->addRoute('POST', '/trading', ['TradingController', 'postTrading']);

    $r->addRoute('PATCH', '/trading', ['TradingController', 'patchTrading']);

    $r->addRoute('DELETE', '/trading/{trading_idx}', ['TradingController', 'deleteTrading']);

    $r->addRoute('POST', '/trading/comment', ['TradingController', 'postTradingComment']);

    $r->addRoute('DELETE', '/trading/comment{comment_idx}', ['TradingController', 'deleteTradingComment']);

    /* ******************   마이 페이지   ****************** */

    $r->addRoute('GET', '/mypage', ['MyPageController', 'getMyPage']);

    $r->addRoute('PATCH', '/mypage/mynickname', ['MyPageController', 'patchMyNickname']);

    $r->addRoute('PATCH', '/mypage/mypicture', ['MyPageController', 'patchMyPicture']);

    $r->addRoute('GET', '/mypage/like', ['MyPageController', 'getMyPageinLike']);


//    $r->addRoute('GET', '/users', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'JWTController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/JWTController.php';
                break;
            case 'TasteHouseController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/TasteHouseController.php';
                break;
            case 'TradingController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/TradingController.php';
                break;
            case 'DepartmentController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/DepartmentController.php';
                break;
            case 'CircleController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/CircleController.php';
                break;
            case 'MessageController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/MessageController.php';
                break;
            case 'MyPageController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/MyPageController.php';
                break;
            /*case 'EventController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/EventController.php';
                break;
            case 'ProductController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ProductController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;
            case 'ReviewController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ReviewController.php';
                break;
            case 'ElementController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ElementController.php';
                break;
            case 'AskFAQController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/AskFAQController.php';
                break;*/
        }

        break;
}
