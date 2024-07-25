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

// $sql 쿼리의 조건을 만족하지 못하는 데이터 삭제
$sqlDelete = "DELETE FROM total_records
            WHERE NOT EXISTS (
                SELECT 1
                FROM sell_records r
                JOIN records s ON r.date = s.date AND r.company = s.company AND r.item = s.item
                WHERE total_records.date = r.date
                AND total_records.company = r.company
                AND total_records.item = r.item
            )";
$conn->query($sqlDelete);

// SQL 쿼리 작성
$sql = "SELECT r.date, r.company, r.item, r.supply_amount, s.supply_amount, r.count, r.name
        FROM sell_records r
        JOIN records s ON r.date = s.date AND r.company = s.company AND r.item = s.item";

// 쿼리 실행
$result = $conn->query($sql);

$tableData = '';
if ($result->num_rows > 0) {
    $tableData .= '<table>';
    $tableData .= '<tr>
                        <th>날짜</th>
                        <th>거래처명</th>
                        <th>품명</th>
                        <th>수량</th>
                        <th>매출금액</th>
                        <th>부가세</th>
                        <th>합계</th>
                        <th>매입가(수수료)</th>
                        <th>매출이익</th>
                        <th>이익률</th>
                    </tr>';

    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        $company = $row['company'];
        $item = $row['item'];
        $supplyAmount = $row['supply_amount'];
        $count = $row['count'];

        $vat = $supplyAmount * 0.1;
        $total = $supplyAmount * $count;
        $buyAmount = $row['supply_amount'];
        $sellProfit = ($supplyAmount - $buyAmount) * $count;
        $profitPercentage = ($sellProfit / $supplyAmount) * 100;

        $tableData .= '<tr>';
        $tableData .= '<td>' . $date . '</td>';
        $tableData .= '<td>' . $company . '</td>';
        $tableData .= '<td>' . $item . '</td>';
        $tableData .= '<td>' . $count . '</td>';
        $tableData .= '<td>' . $supplyAmount . '</td>';
        $tableData .= '<td>' . $vat . '</td>';
        $tableData .= '<td>' . $total . '</td>';
        $tableData .= '<td>' . $buyAmount . '</td>';
        $tableData .= '<td>' . $sellProfit . '</td>';
        $tableData .= '<td>' . $profitPercentage . '%</td>';
        $tableData .= '</tr>';
    }

    $tableData .= '</table>';
}

$conn->close();

echo $tableData;
?>
