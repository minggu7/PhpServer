<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


// 마지막 활동 시간을 세션에 저장합니다.
$_SESSION['last_activity'] = time();

// 세션 유효 시간을 10분으로 설정합니다.
$session_expiration = 10 * 60; // 10분 (단위: 초)

if (isset($_GET['logout'])) {
    header("Location: logout.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>파일 목록</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // 팝업 창 열기
        function openPopup() {
            window.open('input.php', 'input_popup', 'width=500,height=400');
        }
        //팝업 창 여는 함수 openPopup 선언


        // 팝업 창 열기 (품의서)
        function open_approvalPopup() {
            window.open('approval.php', 'proposal_popup', 'width=1000,height=1200');
        }

        // 품의서 작성
        function create_approval() {
            var proposalContent = $('#proposal_content').val();

            // 서버로 품의서 내용을 전송하고 PDF 파일로 저장하는 요청을 보냅니다.
            $.ajax({
                url: 'approval_save.php',
                type: 'POST',
                data: { content: proposalContent },
                success: function (response) {
                    if (response.success) {
                        alert('품의서가 저장되었습니다.');
                    } else {
                        alert('품의서 저장에 실패했습니다.');
                    }
                },
                error: function () {
                    alert('품의서 저장에 실패했습니다.');
                }
            });
        }





        // 매입 기록 리스트 출력
        function fetchRecords() {
            $.ajax({
                url: 'get_recode.php',
                success: function (data) {
                    $('#recordTable').html(data);
                }
            });
        }








        // 페이지 로드 시 저장된 내용 가져오기
        $(document).ready(function () {
            fetchRecords();
        });

        function updateTable() {
            setInterval(function () {
                fetchRecords();
            }, 1000);
        }
        updateTable();


        //매출 기록 리스트 출력
        function fetchRecords_sell() {
            $.ajax({
                url: 'get_sell_record.php',
                success: function (data) {
                    $('#recordTable_sell').html(data);
                }
            });
        }


        // 페이지 로드 시 sell_저장된 내용 가져오기
        $(document).ready(function () {
            fetchRecords_sell();
        });
        function updateTable_sell() {
            setInterval(function () {
                fetchRecords_sell();
            }, 1000);
        }
        updateTable_sell();


        //매출 통계 기록 리스트 출력
        function fetchRecords_total() {
            $.ajax({
                url: 'total.php',
                success: function (data) {
                    $('#recordTable_total').html(data);
                }
            });
        }


        // 페이지 로드 시 sell_저장된 내용 가져오기
        $(document).ready(function () {
            fetchRecords_total();
        });
        function updateTable_total() {
            setInterval(function () {
                fetchRecords_total();
            }, 1000);
        }
        updateTable_total();


        // 검색 결과 가져오기
        function fetchSearchResults() {
            var searchQuery = $('#search_query').val();

            var searchOption = $('[name="search_option"]').val();
            $.ajax({
                url: 'search.php',
                type: 'POST',
                data: { search_query: searchQuery, search_option: searchOption },
                success: function (data) {
                    $('#searchTable').html(data);
                }
            });
        }



        // 검색 폼 제출 시 검색 결과 가져오기
        $('form').submit(function (e) {
            e.preventDefault();
            fetchSearchResults();
        });


        $(document).ready(function() {
            $('#searchForm').submit(function(e) {
                e.preventDefault(); // 기본 폼 제출 동작 방지
                var searchQuery = $('#searchQuery').val();
                var searchOption = $('#searchOption').val();

                $.ajax({
                    url: 'search.php',
                    method: 'GET',
                    data: {
                        search_query: searchQuery,
                        search_option: searchOption
                    },
                    success: function(response) {
                        $('#searchResults').html(response);
                    }
                });
            });
        });


        $(function() {
            $(".tab_content").hide();
            $(".tab_content:first").show();
            $("ul.tabs li").click(function() {
                $("ul.tabs li").removeClass("active").css("color", "#333");

                $(this).addClass("active").css("color", "darkred");
                $(".tab_content").hide()
                var activeTab = $(this).attr("rel");
                $("#" + activeTab).fadeIn()
            });
        });

        // 매입 기록 삭제
        function deleteRecord(recordId) {

            console.log('삭제할 등록번호:', recordId);
            $.ajax({
                url: 'delete_record.php',
                type: 'POST',
                data: { record_id: recordId },
                success: function (response) {
                    fetchRecords();
                }
        });
        }

        function deleteRecord_sell(recordId) {

            console.log('삭제할 등록번호:', recordId);
            $.ajax({
                url: 'delete_record_sell.php',
                type: 'POST',
                data: {record_id: recordId},
                success: function (response) {
                    fetchRecords();
                }
            });
        }
// 사용자 활동 감지를 위한 변수들을 초기화합니다.
var sessionTimeout;

function resetSessionTimeout() {
    // 세션 유효 시간을 10분으로 설정합니다.
    var sessionExpiration = <?php echo $session_expiration; ?> * 1000; // 밀리초로 변환

    // 세션 유효 시간이 경과하면 로그아웃 처리를 수행합니다.
    sessionTimeout = setTimeout(function() {
        window.location.href = "logout.php";
    }, sessionExpiration);
}

function clearSessionTimeout() {
    // 세션 유효 시간을 초기화합니다.
    clearTimeout(sessionTimeout);
}

// 페이지가 로드되었거나 사용자가 활동했을 때 세션 유효 시간을 재설정합니다.
document.onload = resetSessionTimeout();
document.onmousemove = resetSessionTimeout();
document.onkeypress = resetSessionTimeout();

// 사용자의 활동을 감지하면 세션 유효 시간을 초기화합니다.
document.addEventListener("mousemove", clearSessionTimeout);
document.addEventListener("keypress", clearSessionTimeout);



        //인쇄용 창
        function openPopup2() {

            $.ajax({
                url: 'get_total_data.php',
                success: function(data) {

            var popupWindow = window.open('list.php', 'input_popup', 'width=1000,height=1200 ,  resizable=no');

            // 팝업 창이 로드된 후 실행되는 함수
            popupWindow.onload = function() {
                // 팝업 창 내의 1번 코드에서 우상단에 인쇄 버튼 생성
                var printButton = popupWindow.document.createElement('button');
                printButton.innerText = '인쇄';
                printButton.style.position = 'fixed';
                printButton.style.top = '10px';
                printButton.style.right = '10px';
                printButton.style.zIndex = '9999';

                // 인쇄 버튼 클릭 시 인쇄 작업 실행
                printButton.addEventListener('click', function() {
                    popupWindow.print(); // 현재 페이지가 인쇄됩니다.
                });

                // 1번 코드의 body 요소에 인쇄 버튼 추가
                popupWindow.document.body.appendChild(printButton);
            };


                }
            });


        }



    </script>


<style>
    ul.tabs {
        margin: 0;
        padding: 0;
        float: left;
        list-style: none;
        height: 32px;
        border-bottom: 1px solid #eee;
        border-left: 1px solid #eee;
        width: 100%;
        font-family:"dotum";
        font-size:12px;
    }
    ul.tabs li {
        float: left;
        text-align:center;
        cursor: pointer;
        width:82px;
        height: 31px;
        line-height: 31px;
        border: 1px solid #eee;
        border-left: none;
        font-weight: bold;
        background: #fafafa;
        overflow: hidden;
        position: relative;
    }
    ul.tabs li.active {
        background: #FFFFFF;
        border-bottom: 1px solid #FFFFFF;
    }
    .tab_container {
        border: 1px solid #eee;
        border-top: none;
        clear: both;
        float: left;
        width: 80%;
        background: #FFFFFF;
    }
    .tab_content {
        padding: 5px;
        font-size: 15px;
        display: none;
    }
    .tab_container .tab_content ul {
        width:100%;
        margin:0px;
        padding:0px;
    }
    .tab_container .tab_content ul li {
        padding:5px;
        list-style:none
    }
    ;
    #container {
        width: 249px;
        margin: 0 auto;
    }
</style>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        h2 {
            text-align: center;
        }

        #searchForm {
            text-align: left;
            float: left;
            width: 66%;
            height: 50px;
        }

        #searchForm input[type="text"] {
            width: 200px;
            padding: 5px;
            font-size: 14px;
        }

        #searchForm select {
            padding: 5px;
            font-size: 14px;
        }

        #searchForm button[type="submit"] {
            padding: 5px 10px;
            font-size: 14px;
        }

        #searchResults {
            margin-top: 20px;
        }

        #container {
            width: 100%;
            margin: 0 auto;
        }

        ul.tabs {
            margin: 0;
            padding: 0;
            float: left;
            list-style: none;
            height: 32px;
            border-bottom: 1px solid #eee;
            border-left: 1px solid #eee;
            width: 100%;
            font-family: "dotum";
            font-size: 12px;
        }

        ul.tabs li {
            float: left;
            text-align: center;
            cursor: pointer;
            width: 82px;
            height: 31px;
            line-height: 31px;
            border: 1px solid #eee;
            border-left: none;
            font-weight: bold;
            background: #fafafa;
            overflow: hidden;
            position: relative;
        }

        ul.tabs li.active {
            background: #FFFFFF;
            border-bottom: 1px solid #FFFFFF;
        }

        .tab_container {
            border: 1px solid #eee;
            border-top: none;
            clear: both;
            float: left;
            width: 80%;
            background: #FFFFFF;
        }

        .tab_content {
            padding: 5px;
            font-size: 15px;
            display: none;
        }

        .tab_container .tab_content ul {
            width: 100%;
            margin: 0px;
            padding: 0px;
        }

        .tab_container .tab_content ul li {
            padding: 5px;
            list-style: none;
        }

        #recordTable,
        #recordTable_sell,
        #recordTable_total {
            font-size: 15px;
            width: 100%;
            text-align: center;
        }

        #recordTable td,
        #recordTable_sell td,
        #recordTable_total td {
            padding: 5px;
        }

        #recordTable th,
        #recordTable_sell th,
        #recordTable_total th {
            padding: 5px;
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .float-right {
            text-align: right;
            float: right;
            width: 33%;
            height: 50px;
        }

        .float-right button {
            font-size: 15px;
        }
    </style>

</head>
<body>





<h2>검색
    <div style = "float: right; width: 10%; font-size: 15px;">
        <a href="login.php">로그아웃</a>
    </div> </h2>
<div style = "text-align: left; float: left; width: 66%; height: 50px;">
<form id="searchForm"  >
    <input type="text" id="searchQuery" required>
    <select id="searchOption">
        <option value="year">날짜-년</option>
        <option value="month">날짜-월</option>
        <option value="day">날짜-일</option>
        <option value="company">상호</option>
        <option value="item">품목</option>
        <option value="registration_number">등록번호</option>
        <!-- style="display: none;" -->
        <option value="supply_amount"
                style="display: none;">공급가액</option>
        <option value="vat"
                style="display: none;">부가세</option>
        <option value="total_amount"
                style="display: none;">합계금액</option>
        <option value="remark"
                style="display: none;">비고</option>
    </select>
    <button type="submit">검색</button>

</form>


</div>
<div class="float-right"; style = "text-align: right; float: right; width: 33%; height: 50px">
    <button onclick="openPopup()" >입력</button>
</div>
<div id="searchResults"></div>






<div id="container">
    <ul class="tabs">
        <li class="active" rel="tab1">매입 list</li>
        <li rel="tab2">매출 list</li>
        <li rel="tab3">매출 통계</li>
        <li rel="tab4">품의서</li>
    </ul>
    <div class="tab_container">
        <div id="tab1" class="tab_content">

            <table id="recordTable" style="font-size: 15px; width: 100%; text-align: center;">
            </table>
        </div>
        <div id="tab2" class="tab_content">

            <table id="recordTable_sell" style="font-size: 15px; width: 100%; text-align: center;">
            </table>

        </div>
        <div id="tab3" class="tab_content">

            <table id="recordTable_total" style="font-size: 15px; width: 100%; text-align: center;">
            </table>

        </div>

        <div id="tab4" class="tab_content">

            <table id="" style="font-size: 15px; width: 100%; text-align: center;">

                <button onclick="create_approval()">품의서 저장</button>
                <button onclick="open_approvalPopup()">품의서 인쇄</button>
            </table>

        </div>


    </div>
</div>




</body>
</html>
