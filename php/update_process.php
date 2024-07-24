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
    $name = $_POST['name'];
    $registration_number = $_POST['registration_number'];
    $item = $_POST['item'];
    $count = $_POST['count'];
    $supply_sell_amount = $_POST['supply_sell_amount'];
    $vat = $_POST['vat'];
    $total_amount = $_POST['total_amount'];
    $calc = $_POST['calc'];
    $section = $_POST['section'];
    $remarks = $_POST['remarks'];
    $deposit = $_POST['deposit'];

    // 유효성 검사
    if (empty($recordID)) {
        die('수정할 레코드 ID가 제공되지 않았습니다.'); // Record ID to be updated is not provided.
    }

    // SQL 쿼리 생성
    $sql = "UPDATE sell_records SET";

    $updates = array();
    if (!empty($date)) {
        $updates[] = "date = '$date'";
    }
    if (!empty($company)) {
        $updates[] = "company = '$company'";
    }
    if (!empty($name)) {
        $updates[] = "name = '$name'";
    }
    if (!empty($item)) {
        $updates[] = "item = '$item'";
    }
    if (!empty($count)) {
        $updates[] = "count = $count";
    }
    if (!empty($supply_sell_amount)) {
        $updates[] = "supply_sell_amount = $supply_sell_amount";
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
    if (!empty($calc)) {
        $updates[] = "calc = '$calc'";
    }
    if (!empty($section)) {
        $updates[] = "section = '$section'";
    }
    if (!empty($deposit)) {
        $updates[] = "deposit = '$deposit'";
    }
    if (!empty($registration_number)) {
        $updates[] = "registration_number = $registration_number";
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
?>
