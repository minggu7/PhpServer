<?php
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

// SQL 쿼리 작성
$sql = "SELECT r.date, r.company, r.item, r.supply_sell_amount, s.supply_amount, r.count, r.name
        FROM sell_records r
        JOIN records s ON r.date = s.date AND r.company = s.company AND r.item = s.item";

// 쿼리 실행
$result = $conn->query($sql);


if ($result->num_rows > 0) {


    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        $company = $row['company'];
        $item = $row['item'];
        $supplyAmount = $row['supply_sell_amount'];
        $count = $row['count'];

        $vat = $supplyAmount * 0.1;
        $total = $supplyAmount * $count;
        $buyAmount = $row['supply_amount'];
        $sellProfit = ($supplyAmount - $buyAmount) * $count;
        $profitPercentage = ($sellProfit / $supplyAmount) * 100;


    }


}

$conn->close();


?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>매출 품의서</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        body{
            min-height: 100vh;
            max-width: 850px;
        }

        .container {
            display: flex;
            margin: 20px;
        }

        .container h1 {
            flex: 1;
            text-align: center;
            margin: 20px;
        }

        .container table {
            flex: 1;
            border: 1px solid black;
        }

        .container th,
        .container td {
            text-align: center;
        }

        #tb1 th {
            padding: 5px 0;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
        }

        #tb1 th:last-child {
            border-right: none;
        }

        #tb1 th:nth-child(5) {
            width: 16.67%;
            border-right: 1px solid black;
        }

        #tb1 th:first-child {
            border-left: none;
        }

        .container2 {
            display: flex;
            margin: 20px;
        }

        .container2 table {
            flex: 1;
            border: 1px solid black;
        }

        .container2 th,
        .container2 td {
            text-align: center;
        }

        #tb2 {
            margin: 20px;
        }

        .container3 {
            display: flex;
            margin: 20px;
        }

        .container3 table {
            flex: 1;
            border: 1px solid black;
        }

        .container3 th,
        .container3 td {
            text-align: center;
        }

        #tb3 {
            margin: 20px;
        }

        .container4 {
            display: flex;
            margin: 20px;
        }

        .container4 table {
            flex: 1;
            border: 1px solid black;
        }

        .container4 th,
        .container4 td {
            text-align: center;
        }

        #tb4 {
            margin: 20px;
        }

        .container5 {
            display: flex;
            margin: 20px;
        }

        .container5 table {
            flex: 1;
            border: 1px solid black;
        }

        .container5 th,
        .container5 td {
            text-align: center;
        }

        #tb5 {
            margin: 20px;
        }
    </style>







</head>
<body id = "content">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>

<div class="container">
    <h1>매&nbsp;출&nbsp;품&nbsp;의&nbsp;서</h1>
    <table id="tb1">
        <thead>
        <tr>
            <th style="padding: 5px 0;">담당</th>
            <th style="padding: 5px 0;">과장</th>
            <th style="padding: 5px 0;">차장</th>
            <th style="padding: 5px 0;">이사</th>
            <th style="padding: 5px 0;">부사장</th>
            <th style="padding: 5px 0;">대표</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="border-right: 1px solid black;">-</td>
            <td style="border-right: 1px solid black;">-</td>
            <td style="border-right: 1px solid black;">-</td>
            <td style="border-right: 1px solid black;">-</td>
            <td style="border-right: 1px solid black;">-</td>
            <td>-</td>
        </tr>
        </tbody>
    </table>
</div>
<form action="approval_save.php" method="POST">
    <div class="container2">

        <table id="tb2">
            <tbody>
            <tr>
                <th width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">계약일</th>
                <td style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="contract_date" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">담당자</th>
                <td style="border-bottom: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="manager" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">품의서 번호</th>
                <td style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="round_num" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">영업 담당</th>
                <td style="border-bottom: 1px solid black;">
                    <input type="text" id="sales_manager"  name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <th width="100" height="40" style="border-right: 1px solid black;">결제 방법</th>
                <td style="border-right: 1px solid black;">
                    <input type="text" id="pay" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <th width="100" height="40" style="border-right: 1px solid black;">계산서 발행 방법</th>
                <td>
                    <input type="text" id="how_to_tax" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="container3">
        <table id="tb3">
            <tbody>
            <tr>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">고객사명</th>
                <td style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="company" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">담당자</th>
                <td style="border-bottom: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="name" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">연락처</th>
                <td style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="tel" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">E-Mail</th>
                <td style="border-bottom: 1px solid black;">
                    <input type="text" id="mail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <th width="100" height="40" style="border-right: 1px solid black;">주소</th>
                <td colspan="3">
                    <input type="text" id="address" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class = "container4">
        <table id = "tb4">
            <thead>
            <tr>
                <th width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">항목</th>
                <th width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">내용</th>
                <th width="100" height="40" style="border-bottom: 1px solid black;">상세 내용</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_1" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_1_content" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style=" border-bottom: 1px solid black;">
                    <input type="text" id="item_1_detail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_2" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_2_content" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style=" border-bottom: 1px solid black;">
                    <input type="text" id="item_2_detail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_3" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_3_content" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style=" border-bottom: 1px solid black;">
                    <input type="text" id="item_3_detail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_4" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_4_content" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style=" border-bottom: 1px solid black;">
                    <input type="text" id="item_4_detail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_5" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_5_content" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style=" border-bottom: 1px solid black;">
                    <input type="text" id="item_5_detail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_6" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_6_content" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style=" border-bottom: 1px solid black;">
                    <input type="text" id="item_6_detail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="40" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_7" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="item_7_content" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40" style=" border-bottom: 1px solid black;">
                    <input type="text" id="item_7_detail" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="container5">
        <table id = "tb5">
            <thead>
            <tr>
                <th width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">항목</th>
                <th width="30" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">수량</th>
                <th width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">수수료</th>
                <th width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">매출</th>
                <th width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">매입</th>
                <th width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">마진</th>
                <th width="70" height="40"style=" border-bottom: 1px solid black;">마진률</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_1" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_1_count" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_1_vat" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_1_sell" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_1_buy" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>

            </tr>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_2" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_2_count" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_2_vat" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_2_sell" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_2_buy" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_3" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_3_count" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_3_vat" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_3_sell" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_3_buy" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_4" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_4_count" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_4_vat" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_4_sell" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_4_buy" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_5" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_5_count" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_5_vat" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_5_sell" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_5_buy" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_6" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_6_count" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_6_vat" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_6_sell" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" id="list_6_buy" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    매출금액
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    부 가 세
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 1px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>

            <tr>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 0px solid black;">
                    총합계(부가세포함)
                </td>
                <td width="100" height="40"style="border-right: 1px solid black; border-bottom: 0px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 0px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 0px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 0px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="100" height="40"style="border-right: 1px solid black; border-bottom: 0px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td><td width="70" height="40"style="border-right: 0px solid black; border-bottom: 0px solid black;">
                    <input type="text" name="text" size="10" style="width:90%; border: 0; outline: none;">
                </td>
            </tr>
            </tbody>
        </table>

    </div>
    <button type="submit" class="btn-submit">저장</button>
</form>


<script language = "javascript">
    function save_pdf() {


        const doc = new jsPDF({
            //orientation: "landscape",
            orientation: "portrait",
            format: "a4"
            //format: [4, 2]
        });

        doc.addHTML(document.body, function () {
            doc.save('품의서.pdf');
        });
    }
</script>



<button onclick="save_pdf()">pdf 다운로드</button>

</body>
</html>