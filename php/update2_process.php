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
    $date = $_POST['date'];
    $company = $_POST['company'];
    $registration_number = $_POST['registration_number'];
    $item = $_POST['item'];
    $supply_amount = $_POST['supply_amount'];
    $vat = $_POST['vat'];
    $total_amount = $_POST['total_amount'];
    $remarks = $_POST['remarks'];

    // 유효성 검사
    if (empty($recordID) || empty($registration_number)) {
        die('필수 필드를 입력해야 합니다.'); // Required fields must be filled in.
    }

    // SQL 쿼리 생성
    $updates = [];
    if (!empty($date)) {
        $updates[] = "date = '$date'";
    }
    if (!empty($company)) {
        $updates[] = "company = '$company'";
    }
    if (!empty($item)) {
        $updates[] = "item = '$item'";
    }
    if (!empty($supply_amount)) {
        $updates[] = "supply_amount = $supply_amount";
    }
    if (!empty($vat)) {
        $updates[] = "vat = $vat";
    }
    if (!empty($total_amount)) {
        $updates[] = "total_amount = $total_amount";
    }
    if (!empty($remarks)) {
        $updates[] = "remarks = '$remarks'";
    }

    $updateFields = implode(", ", $updates);

    // SQL 쿼리 실행
    $sql = "UPDATE records SET $updateFields WHERE recordID = $recordID";

    if ($conn->query($sql) === true) {
        echo '데이터가 성공적으로 수정되었습니다.'; // Data has been successfully updated.
    } else {
        echo '데이터 수정 중 오류가 발생했습니다: ' . $conn->error; // An error occurred during data update.
    }
}

$conn->close();
?>