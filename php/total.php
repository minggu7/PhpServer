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

// 1단계: `total_records` 테이블에 삽입할 데이터 생성
$insertQuery = "
INSERT INTO total_records (
    recordID, 
    date,
    name,
    company, 
    item, 
    quantity, 
    supply_amount, 
    vat, 
    total, 
    buyAmount, 
    sellProfit, 
    profitPercentage, 
    collect, 
    balance, 
    tax_num, 
    round_num, 
    buyers, 
    remarks
)
SELECT 
    IFNULL(r.recordId, 'Unknown') AS recordID,  -- sell_records의 recordId를 사용. 없으면 'Unknown'
    CURDATE() AS date,  -- 현재 날짜
    IFNULL(r.name, 'Unknown') AS name,  -- sell_records의 name. 없으면 'Unknown'
    IFNULL(r.company, 'Unknown') AS company,  -- sell_records의 company. 없으면 'Unknown'
    IFNULL(r.item, 'Unknown') AS item,  -- sell_records의 item. 없으면 'Unknown'
    IFNULL(r.count, 0) AS quantity,  -- sell_records의 count 값. 없으면 0
    IFNULL(s.supply_amount, 0) - IFNULL(r.supply_amount, 0) AS supply_amount,  -- records의 supply_amount - sell_records의 supply_amount. 없으면 0
    (IFNULL(r.supply_amount, 0) * 0.1) - (IFNULL(s.supply_amount, 0) * 0.1) AS vat,  -- sell_records의 supply_amount 10% - records의 supply_amount 10%. 없으면 0
    (IFNULL(s.total_amount, 0) - IFNULL(r.total_amount, 0)) AS total,  -- records의 total_amount - sell_records의 total_amount. 없으면 0
    IFNULL(s.total_amount, 0) AS buyAmount,  -- records의 total_amount. 없으면 0
    IFNULL(r.total_amount, 0) AS sellProfit,  -- sell_records의 total_amount. 없으면 0
    ABS((IFNULL(s.total_amount, 0) - IFNULL(r.total_amount, 0)) / IFNULL(s.total_amount, 1) * 100) AS profitPercentage,  -- 절대값으로 이익률 계산. 0으로 나누지 않도록 1로 대체
    DATE_ADD(CURDATE(), INTERVAL 10 DAY) AS collect,
    IFNULL(s.supply_amount, 0) AS balance,  
    IFNULL(r.calc, 'Unknown') AS tax_num, 
    0 AS round_num, 
    'empty' AS buyers, 
    '' AS remarks 
FROM 
    sell_records r
JOIN 
    records s ON r.date = s.date AND r.company = s.company AND r.item = s.item
LEFT JOIN 
    total_records tr ON r.recordId = tr.recordID
WHERE 
    tr.recordID IS NULL";

if ($conn->query($insertQuery) === FALSE) {
    die('데이터 삽입 실패: ' . $conn->error);
}

// 2단계: 삽입된 데이터를 `total_records` 테이블에서 조회
$totalRecordsQuery = "SELECT * FROM total_records";

$totalRecordsResult = $conn->query($totalRecordsQuery);

if ($totalRecordsResult === false) {
    die('쿼리 실행 실패: ' . $conn->error);
}

if ($totalRecordsResult->num_rows > 0) {
    echo '<table border="1">
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

    while ($row = $totalRecordsResult->fetch_assoc()) {
        // 테이블에 데이터 출력
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['company']) . '</td>';
        echo '<td>' . htmlspecialchars($row['item']) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($row['supply_amount']) . '</td>';
        echo '<td>' . htmlspecialchars($row['vat']) . '</td>';
        echo '<td>' . htmlspecialchars($row['total']) . '</td>';
        echo '<td>' . htmlspecialchars($row['buyAmount']) . '</td>';
        echo '<td>' . htmlspecialchars($row['sellProfit']) . '</td>';
        echo '<td>' . htmlspecialchars($row['profitPercentage']) . '%</td>';
        echo '<td>' . htmlspecialchars($row['collect']) . '</td>';
        echo '<td>' . htmlspecialchars($row['balance']) . '</td>';
        echo '<td>' . htmlspecialchars($row['tax_num']) . '</td>';
        echo '<td>' . htmlspecialchars($row['round_num']) . '</td>';
        echo '<td>' . htmlspecialchars($row['buyers']) . '</td>';
        echo '<td>' . htmlspecialchars($row['remarks']) . '</td>';
        echo '<td><button onclick="openPopup_total(\'' . htmlspecialchars($row['recordID']) . '\')">수정</button></td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '조건에 맞는 데이터가 없습니다.';
}

// 연결 종료
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
        var quantity = document.getElementById('quantity').value;
        var supply_amount = document.getElementById('supply_amount').value;
        var vat = document.getElementById('vat').value;
        var total = document.getElementById('total').value;
        var buyAmount = document.getElementById('buyAmount').value;
        var sellProfit = document.getElementById('sellProfit').value;
        var profit = document.getElementById('profit').value;
        var collect = document.getElementById('collect').value;
        var balance = document.getElementById('balance').value;
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
                    alert('서버 요청 실패: ' + xhr.status); // 서버 요청 실패
                }
            }
        };

        // 요청 전송
        xhr.send('recordID=' + encodeURIComponent(recordID) +
            '&name=' + encodeURIComponent(name) +
            '&date=' + encodeURIComponent(date) +
            '&company=' + encodeURIComponent(company) +
            '&item=' + encodeURIComponent(item) +
            '&quantity=' + encodeURIComponent(quantity) +
            '&supply_amount=' + encodeURIComponent(supply_amount) +
            '&vat=' + encodeURIComponent(vat) +
            '&total=' + encodeURIComponent(total) +
            '&buyAmount=' + encodeURIComponent(buyAmount) +
            '&sellProfit=' + encodeURIComponent(sellProfit) +
            '&profit=' + encodeURIComponent(profit) +
            '&collect=' + encodeURIComponent(collect) +
            '&balance=' + encodeURIComponent(balance) +
            '&tax_num=' + encodeURIComponent(tax_num) +
            '&round_num=' + encodeURIComponent(round_num) +
            '&buyers=' + encodeURIComponent(buyers) +
            '&remarks=' + encodeURIComponent(remarks));
    }
</script>