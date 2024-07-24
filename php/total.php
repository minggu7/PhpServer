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
$sellRecordsQuery = "SELECT r.date, r.company, r.item, r.supply_sell_amount, s.supply_amount, r.count, r.name, tr.recordID
        FROM sell_records r
        JOIN records s ON r.date = s.date AND r.company = s.company AND r.item = s.item
        LEFT JOIN total_records tr ON r.date = tr.date AND r.company = tr.company AND r.item = tr.item";

$sellRecordsResult = $conn->query($sellRecordsQuery);

if ($sellRecordsResult->num_rows > 0) {
    echo '<table>
            <tr>
                <th>날짜</th>
                <th>담당자</th>
                <th>거래처명</th>
                <th>품명</th>
                <th>수량</th>
                <th>매출금액</th>
                <th>부가세</th>
                <th>합계</th>
                <th>매입가(수수료)</th>
                <th>매출이익</th>
                <th>이익률</th>
                <th>수금 예정일</th>
                <th>잔액</th>
                <th>세금계산서NO</th>
                <th>품의서 번호</th>
                <th>매입처 및 고객</th>
                <th>비고</th>
            </tr>';

    // total_records 테이블의 데이터 가져오기
    $totalRecordsQuery = "SELECT * FROM total_records";
    $totalRecordsResult = $conn->query($totalRecordsQuery);

    while ($row = $totalRecordsResult->fetch_assoc()) {
        // 필요한 데이터 추출
        $date = $row['date'];
        $name = $row['name'];
        $company = $row['company'];
        $item = $row['item'];
        $count = $row['count'];
        $sellAmount = $row['sell_amount'];
        $vat = $row['vat'];
        $totalAmount = $row['total_amount'];
        $buyAmount = $row['buy_amount'];
        $sellProfit = $row['sell_profit'];
        $profitPercentage = $row['profit'];
        $collect = $row['collect'];
        $balance = $row['balance'];
        $tax_num = $row['tax_num'];
        $round_num = $row['round_num'];
        $buyers = $row['buyers'];
        $remarks = $row['remarks'];

        // 테이블에 데이터 출력
        echo '<tr>';
        echo '<td>' . $date . '</td>';
        echo '<td>' . $name . '</td>';
        echo '<td>' . $company . '</td>';
        echo '<td>' . $item . '</td>';
        echo '<td>' . $count . '</td>';
        echo '<td>' . $sellAmount . '</td>';
        echo '<td>' . $vat . '</td>';
        echo '<td>' . $totalAmount . '</td>';
        echo '<td>' . $buyAmount . '</td>';
        echo '<td>' . $sellProfit . '</td>';
        echo '<td>' . $profitPercentage . '%</td>';
        echo '<td>' . $collect . '</td>';
        echo '<td>' . $balance . '</td>';
        echo '<td>' . $tax_num . '</td>';
        echo '<td>' . $round_num . '</td>';
        echo '<td>' . $buyers . '</td>';
        echo '<td>' . $remarks . '</td>';
        echo '<td><button onclick="openPopup_total(\'' . $row['recordID'] . '\')">수정</button></td>';

        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '조건에 맞는 데이터가 없습니다.';
}

// SQL 쿼리 실행
$sql2 = "INSERT INTO total_records (date, name, company, item, count, sell_amount, vat, total_amount, buy_amount, sell_profit, profit)
        SELECT r.date, r.name, r.company, r.item, r.count, r.supply_sell_amount, s.supply_amount * 0.1, r.supply_sell_amount * r.count, s.supply_amount, (r.supply_sell_amount - s.supply_amount) * r.count, ((r.supply_sell_amount - s.supply_amount) * r.count / r.supply_sell_amount) * 100
        FROM sell_records r
        JOIN records s ON r.date = s.date AND r.company = s.company AND r.item = s.item
        LEFT JOIN total_records tr ON r.date = tr.date AND r.company = tr.company AND r.item = tr.item
        WHERE tr.recordID IS NULL";

$conn->query($sql2);

$conn->close();
?>

<script>
    function openPopup_total(recordID) {
        // 팝업 창 열기
        window.open('update_total.php?recordID=' + encodeURIComponent(recordID), 'update_total_popup', 'width=500,height=400');
    }

    function updateRecord(event) {
        event.preventDefault(); // 폼 제출 기본 동작 방지

        // 폼 데이터 가져오기
        var recordID = document.getElementById('recordID').value;
        var date = document.getElementById('date').value;
        var name = document.getElementById('name').value;
        var company = document.getElementById('company').value;
        var item = document.getElementById('item').value;
        var count = document.getElementById('count').value;
        var sell_amount = document.getElementById('sell_amount').value;
        var vat = document.getElementById('vat').value;
        var total_amount = document.getElementById('total_amount').value;
        var buy_amount = document.getElementById('buy_amount').value;
        var sell_profit = document.getElementById('sell_profit').value;
        var profit = document.getElementById('profit').value;
        var collect = document.getElementById('collect').value;
        var balance = document.getElementById('valance').value;
        var tax_num = document.getElementById('tax_num').value;
        var round_num = document.getElementById('round_num').value;
        var buyers = document.getElementById('buyers').value;
        var remarks = document.getElementById('remarks').value;

        // 새 XMLHttpRequest 객체 생성
        var xhr = new XMLHttpRequest();

        // 요청 준비
        xhr.open('POST', 'update_total_process.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        // 콜백 함수 설정
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // 서버로부터의 응답 처리
                    alert(xhr.responseText);
                } else {
                    alert('서버 요청 실패: ' + xhr.status); // Server request failed
                }
            }
        };

        // 요청 전송
        xhr.send('recordID=' + encodeURIComponent(recordID) +
            '&name=' + encodeURIComponent(name) +
            '&date=' + encodeURIComponent(date) +
            '&company=' + encodeURIComponent(company) +
            '&item=' + encodeURIComponent(item) +
            '&count=' + encodeURIComponent(count) +
            '&sell_amount=' + encodeURIComponent(sell_amount) +
            '&vat=' + encodeURIComponent(vat) +
            '&total_amount=' + encodeURIComponent(total_amount) +
            '&buy_amount=' + encodeURIComponent(buy_amount) +
            '&sell_profit=' + encodeURIComponent(sell_profit) +
            '&profit=' + encodeURIComponent(profit) +
            '&collect=' + encodeURIComponent(collect) +
            '&balance=' + encodeURIComponent(balance) +
            '&tax_num=' + encodeURIComponent(tax_num) +
            '&round_num=' + encodeURIComponent(round_num) +
            '&buyers=' + encodeURIComponent(buyers) +
            '&remarks=' + encodeURIComponent(remarks));
    }
</script>