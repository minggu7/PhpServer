<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

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





// 폼에서 입력된 내용 가져오기


$contract_date = $_POST['contract_date'];

$manager = $_POST['manager'];
$round_num = $_POST['round_num'];
$sales_manager = $_POST['sales_manager'];
$pay = $_POST['pay'];
$how_to_tax = $_POST['how_to_tax'];
$company = $_POST['company'];
$name = $_POST['name'];
$tel = $_POST['tel'];
$mail = $_POST['mail'];
$address = $_POST['address'];
$item_1 = $_POST['item_1'];
$item_1_content = $_POST['item_1_content'];
$item_1_detail = $_POST['item_1_detail'];
$item_2 = $_POST['item_2'];
$item_2_content = $_POST['item_2_content'];
$item_2_detail = $_POST['item_2_detail'];
$item_3 = $_POST['item_3'];
$item_3_content = $_POST['item_3_content'];
$item_3_detail = $_POST['item_3_detail'];

$item_4 = $_POST['item_4'];
$item_4_content = $_POST['item_4_content'];
$item_4_detail = $_POST['item_4_detail'];
$item_5 = $_POST['item_5'];
$item_5_content = $_POST['item_5_content'];
$item_5_detail = $_POST['item_5_detail'];
$item_6 = $_POST['item_6'];
$item_6_content = $_POST['item_6_content'];
$item_6_detail = $_POST['item_6_detail'];
$item_7 = $_POST['item_7'];
$item_7_content = $_POST['item_7_content'];
$item_7_detail = $_POST['item_7_detail'];

$list_1 = $_POST['list_1'];
$list_1_count = $_POST['list_1_count'];
$list_1_vat = $_POST['list_1_vat'];
$list_1_sell = $_POST['list_1_sell'];
$list_1_buy = $_POST['list_1_buy'];
$list_2 = $_POST['list_2'];
$list_2_count = $_POST['list_2_count'];
$list_2_vat = $_POST['list_2_vat'];
$list_2_sell = $_POST['list_2_sell'];
$list_2_buy = $_POST['list_2_buy'];
$list_3 = $_POST['list_3'];
$list_3_count = $_POST['list_3_count'];
$list_3_vat = $_POST['list_3_vat'];
$list_3_sell = $_POST['list_3_sell'];
$list_3_buy = $_POST['list_3_buy'];
$list_4 = $_POST['list_4'];
$list_4_count = $_POST['list_4_count'];
$list_4_vat = $_POST['list_4_vat'];
$list_4_sell = $_POST['list_4_sell'];
$list_4_buy = $_POST['list_4buy'];
$list_5 = $_POST['list_5'];
$list_5_count = $_POST['list_5_count'];
$list_5_vat = $_POST['list_5_vat'];
$list_5_sell = $_POST['list_5_sell'];
$list_5_buy = $_POST['list_5_buy'];




// 입력된 내용을 데이터베이스에 저장
/*
if($searchOption === 'records')
    $sql = "INSERT INTO $searchOption (date, company, registration_number, item, supply_amount, vat, total_amount, remarks)
        VALUES ('$date', '$company', '$registration_number', '$item', '$supply_amount', '$vat', '$total_amount', '$remarks')";
else if($searchOption === 'sell_records') $sql = "INSERT INTO $searchOption (date, name, company, registration_number, item, count, supply_sell_amount, vat, total_amount, calc, section, remarks, deposit)
        VALUES ('$date','$name', '$company', '$registration_number', '$item','$count', '$supply_amount', '$vat', '$total_amount',
        '$calc', '$section', '$remarks', '$deposit')";
*/



if ($conn->query($sql) === true) {
    echo '저장되었습니다.';
} else {
    echo '저장 실패: ' . $conn->error;
}

$conn->close();
?>
