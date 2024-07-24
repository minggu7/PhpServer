
<!DOCTYPE html>
<html>
<head>
    <title>수정</title>
</head>
<body>
    <h1>수정</h1>

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

        // 선택된 registration_number에 해당하는 데이터 가져오기
        if (isset($_GET['recordID'])) {
            $recordID = $_GET['recordID'];

            // 값이 존재하는 경우에만 SQL 쿼리 실행
            $sql = "SELECT * FROM total_records WHERE recordID = $recordID";
            $result = $conn->query($sql);

            if ($result !== false && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
    ?>
                <form action="update_total_process.php" method="POST">
                    <input type="hidden" name="recordID" value="<?php echo $row['recordID']; ?>">

                    <label for="date">날짜:</label>
                    <input type="text" id="date" name="date" value="<?php echo $row['date']; ?>" required><br>
                    
                    <label for="name">담당자:</label>
                    <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required><br>

                    <label for="company">거래처명:</label>
                    <input type="text" id="company" name="company" value="<?php echo $row['company']; ?>" required><br>

                    <label for="item">품명:</label>
                    <input type="text" id="item" name="item" value="<?php echo $row['item']; ?>" required><br>

                    <label for="count">수량:</label>
                    <input type="text" id="count" name="count" value="<?php echo $row['count']; ?>" required><br>

                    <label for="sell_amount">매출금액:</label>
                    <input type="text" id="sell_amount" name="sell_amount" value="<?php echo $row['sell_amount']; ?>" required><br>

                    <label for="vat">부가세:</label>
                    <input type="text" id="vat" name="vat" value="<?php echo $row['vat']; ?>" required><br>

                    <label for="total_amount">합계:</label>
                    <input type="text" id="total_amount" name="total_amount" value="<?php echo $row['total_amount']; ?>" required><br>

                    <label for="buy_amountt">매입가(수수료):</label>
                    <input type="text" id="buy_amount" name="buy_amount" value="<?php echo $row['buy_amount']; ?>" required><br>

                    <label for="sell_profit">매출이익:</label>
                    <input type="text" id="sell_profit" name="sell_profit" value="<?php echo $row['sell_profit']; ?>" required><br>

                    <label for="profit">이익률:</label>
                    <input type="text" id="profit" name="profit" value="<?php echo $row['profit']; ?>" required><br>

                    <label for="collect">수금 예정일:</label>
                    <input type="text" id="collect" name="collect" value="<?php echo $row['collect']; ?>"><br>

                    <label for="balance">잔액:</label>
                    <input type="text" id="balance" name="balance" value="<?php echo $row['balance']; ?>"><br>

                    <label for="tax_num">세금계산서NO:</label>
                    <input type="text" id="tax_num" name="tax_num" value="<?php echo $row['tax_num']; ?>"><br>

                    <label for="round_num">품의서 번호:</label>
                    <input type="text" id="round_num" name="round_num" value="<?php echo $row['round_num']; ?>"><br>

                    <label for="buyerss">매입처 및 고객:</label>
                    <input type="text" id="buyers" name="buyers" value="<?php echo $row['buyers']; ?>"><br>

                    <label for="remarks">비고:</label>
                    <input type="text" id="remarks" name="remarks" value="<?php echo $row['remarks']; ?>"><br>
                    

                    <button type="submit">저장</button>
                </form>
    <?php
            }  else {
                if ($result === false) {
                    die('SQL 쿼리 실행 오류: ' . $conn->error);
                } else {
                    echo '해당하는 데이터를 찾을 수 없습니다.';
                }
            }
        } else {
            echo '정보를 가져오자 못했습니다.';
        }

        $conn->close();
    ?>

    <script>
        function updateRecord(event) {
            event.preventDefault(); // 폼 제출 기본 동작 방지

            //폼 데이터 가져오기
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
            '&profit=' + encodeURIComponet(profitPercentage) +
            '&collect=' + encodeURIComponet(collect) +
            '&balance=' + encodeURIComponet(balance) +
            '&tax_num=' + encodeURIComponet(tax_num) +
            '&round_num=' + encodeURIComponet(round_num) +
            '&buyers=' + encodeURIComponet(buyers) +
            '&remarks=' + encodeURIComponet(remarks));
    }
    </script>
</body>
</html>