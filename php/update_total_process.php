<?php

// 데이터베이스 연결 설정
$config = require 'config.php';
$servername = $config['database']['servername'];
$username = $config['database']['username'];
$password = $config['database']['password'];
$dbname = $config['database']['dbname'];

// 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// POST 요청으로 전달된 수정 내용 가져오기
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST 데이터 가져오기
    $recordID = isset($_POST['recordID']) ? $conn->real_escape_string($_POST['recordID']) : '';
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $date = isset($_POST['date']) ? $conn->real_escape_string($_POST['date']) : '';
    $company = isset($_POST['company']) ? $conn->real_escape_string($_POST['company']) : '';
    $item = isset($_POST['item']) ? $conn->real_escape_string($_POST['item']) : '';
    $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;
    $supply_amount = isset($_POST['supply_amount']) ? floatval($_POST['supply_amount']) : 0;
    $vat = isset($_POST['vat']) ? floatval($_POST['vat']) : 0;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    $buyAmount = isset($_POST['buyAmount']) ? floatval($_POST['buyAmount']) : 0;
    $sellProfit = isset($_POST['sellProfit']) ? floatval($_POST['sellProfit']) : 0;
    $profit = isset($_POST['profit']) ? floatval($_POST['profit']) : 0;
    $collect = isset($_POST['collect']) ? $conn->real_escape_string($_POST['collect']) : '';
    $balance = isset($_POST['balance']) ? floatval($_POST['balance']) : 0;
    $tax_num = isset($_POST['tax_num']) ? $conn->real_escape_string($_POST['tax_num']) : '';
    $round_num = isset($_POST['round_num']) ? intval($_POST['round_num']) : 0;
    $buyers = isset($_POST['buyers']) ? $conn->real_escape_string($_POST['buyers']) : '';
    $remarks = isset($_POST['remarks']) ? $conn->real_escape_string($_POST['remarks']) : '';

    // 유효성 검사
    if (empty($recordID)) {
        die('수정할 레코드 ID가 제공되지 않았습니다.');
    }

    // SQL 쿼리 생성
    $updates = array();

    if (!empty($date)) {
        $updates[] = "date = '$date'";
    }
    if (!empty($name)) {
        $updates[] = "name = '$name'";
    }
    if (!empty($company)) {
        $updates[] = "company = '$company'";
    }
    if (!empty($item)) {
        $updates[] = "item = '$item'";
    }
    if (!empty($quantity)) {
        $updates[] = "quantity = $quantity";
    }
    if (!empty($supply_amount)) {
        $updates[] = "supply_amount = $supply_amount";
    }
    if (!empty($vat)) {
        $updates[] = "vat = $vat";
    }
    if (!empty($total)) {
        $updates[] = "total = $total";
    }
    if (!empty($buyAmount)) {
        $updates[] = "buyAmount = $buyAmount";
    }
    if (!empty($sellProfit)) {
        $updates[] = "sellProfit = $sellProfit";
    }
    if (!empty($profit)) {
        $updates[] = "profitPercentage = $profit"; // 'profit' 필드를 'profitPercentage'로 수정했습니다.
    }
    if (!empty($collect)) {
        $updates[] = "collect = '$collect'";
    }
    if (!empty($balance)) {
        $updates[] = "balance = $balance";
    }
    if (!empty($tax_num)) {
        $updates[] = "tax_num = '$tax_num'";
    }
    if (!empty($round_num)) {
        $updates[] = "round_num = $round_num";
    }
    if (!empty($buyers)) {
        $updates[] = "buyers = '$buyers'";
    }
    if (!empty($remarks)) {
        $updates[] = "remarks = '$remarks'";
    }

    // 업데이트 쿼리 생성
    if (!empty($updates)) {
        $sql = "UPDATE total_records SET " . implode(", ", $updates) . " WHERE recordID = '$recordID'";

        // SQL 쿼리 실행
        if ($conn->query($sql) === TRUE) {
            echo '데이터가 성공적으로 수정되었습니다.';
        } else {
            echo '데이터 수정 중 오류가 발생했습니다: ' . $conn->error;
        }
    } else {
        echo '수정할 데이터가 없습니다.';
    }
}

$conn->close();
?>