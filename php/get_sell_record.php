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


// 저장된 내용 가져오기
$sql = "SELECT * FROM sell_records";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<tr>
    <th>날짜</th>
    <th>거래처명</th>
    <th>담당자</th>
    <th>등록번호</th>
    <th>품명</th>
    <th>수량</th>
    <th>매출금액</th>
    <th>부가세</th>
    <th>합계금액</th>
    <th>세금계산서NO</th>
    <th>구분</th>
    <th>비고</th>
    <th>입금일</th>
        </tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
        <td>' . $row['date'] . '</td>
        <td>' . $row['company'] . '</td>
        <td>' . $row['name'] . '</td>
        <td>' . $row['registration_number'] . '</td>
        <td>' . $row['item'] . '</td>
        <td>' . $row['count'] . '</td>
        <td>' . $row['supply_sell_amount'] . '</td>
        <td>' . $row['vat'] . '</td>
        <td>' . $row['total_amount'] . '</td>
        <td>' . $row['calc'] . '</td>
        <td>' . $row['section'] . '</td>
        <td>' . $row['remarks'] . '</td>
        <td>' . $row['deposit'] . '</td>
                <td><button onclick="openPopup_sell(\'' . $row['recordID'] . '\')">수정</button></td>
                <td><button onclick=deleteRecord_sell(' . $row['registration_number'] . ')>삭제</button></td>
            </tr>';
    }
} else {
    echo '<tr><td colspan="8">저장된 내용이 없습니다.</td></tr>';
}

$conn->close();
?>
<script>
    function openPopup_sell(recordID) {
        // 팝업 창 열기
        window.open('update.php?recordID=' + encodeURIComponent(recordID), 'update_sell_popup', 'width=500,height=400');
    }

    function updateRecord(event) {
        event.preventDefault(); // 폼 제출 기본 동작 방지

        // 폼 데이터 가져오기
        var recordID = document.getElementById('recordID').value;
        var registration_number = document.getElementById('registration_number').value;
        var date = document.getElementById('date').value;
        var company = document.getElementById('company').value;
        var name = document.getElementById('name').value;
        var item = document.getElementById('item').value;
        var count = document.getElementById('count').value;
        var supply_sell_amount = document.getElementById('supply_sell_amount').value;
        var vat = document.getElementById('vat').value;
        var total_amount = document.getElementById('total_amount').value;
        var calc = document.getElementById('calc').value;
        var section = document.getElementById('section').value;
        var remarks = document.getElementById('remarks').value;
        var deposit = document.getElementById('deposit').value;

        // 새 XMLHttpRequest 객체 생성
        var xhr = new XMLHttpRequest();

        // 요청 준비
        xhr.open('POST', 'update_process.php', true);
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
            '&registration_number=' + encodeURIComponent(registration_number) +
            '&name=' + encodeURIComponent(name) +
            '&date=' + encodeURIComponent(date) +
            '&company=' + encodeURIComponent(company) +
            '&item=' + encodeURIComponent(item) +
            '&count=' + encodeURIComponent(count) +
            '&supply_sell_amount=' + encodeURIComponent(supply_amount) +
            '&vat=' + encodeURIComponent(vat) +
            '&total_amount=' + encodeURIComponent(total_amount) +
            '&calc=' + encodeURIComponent(calc) +
            '&section=' + encodeURIComponent(section) +
            '&remarks=' + encodeURIComponent(remarks) +
            '&deposit=' + encodeURIComponent(deposit));
    }


</script>