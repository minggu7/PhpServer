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
    $recordID = $_POST['recordID'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $company = $_POST['company'];
    $item = $_POST['item'];
    $count = $_POST['count'];
    $sell_amount = $_POST['sell_amount'];
    $vat = $_POST['vat'];
    $total_amount = $_POST['total_amount'];
    $buy_amount = $_POST['buy_amount'];
    $sell_profit = $_POST['sell_profit'];
    $profit = $_POST['profit'];
    $collect = $_POST['collect'];
    $balance = $_POST['balance'];
    $tax_num = $_POST['tax_num'];
    $round_num = $_POST['round_num'];
    $buyers = $_POST['buyers'];
    $remarks = $_POST['remarks'];

    // 유효성 검사
    if (empty($recordID)) {
        die('수정할 레코드 ID가 제공되지 않았습니다.'); // Record ID to be updated is not provided.
    }

    // SQL 쿼리 생성
    $sql = "UPDATE total_records SET";

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
    if (!empty($count)) {
        $updates[] = "count = $count";
    }
    if (!empty($sell_amount)) {
        $updates[] = "sell_amount = $sell_amount";
    }
    if (!empty($vat)) {
        $updates[] = "vat = $vat";
    }
    if (!empty($total_amount)) {
        $updates[] = "total_amount = $total_amount";
    }
    if (!empty($buy_amount)) {
        $updates[] = "buy_amount = $buy_amount";
    }
    if (!empty($sell_profit)) {
        $updates[] = "sell_profit = $sell_profit";
    }
    if (!empty($profit)) {
        $updates[] = "profit = $profit";
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
        $updates[] = "round_num = '$round_num'";
    }
    if (!empty($buyers)) {
        $updates[] = "buyers = '$buyers'";
    }
    if (!empty($remarks)) {
        $updates[] = "remarks = '$remarks'";
    }
    

    $sql .= " " . implode(", ", $updates);
    $sql .= " WHERE recordID = $recordID";

    // SQL 쿼리 실행
    if ($conn->query($sql) === true) {
        echo '데이터가 성공적으로 수정되었습니다.'; // Data has been successfully updated.
    } else {
        echo '데이터 수정 중 오류가 발생했습니다: ' . $conn->error; // An error occurred during data update.
    }
}

$conn->close();

