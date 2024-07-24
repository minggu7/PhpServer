<?php
session_start();

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

// login handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    //DB 불러와서 비교
    $result = $conn->query($sql);
    //비교한 값 result 에 저장
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = '로그인 실패';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>로그인</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-container label {
            display: block;
            margin-bottom: 10px;
            margin-left: -10px;
            font-weight: bold;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            margin-left: -10px;
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-container button[type="submit"] {
            background-color: skyblue;
            color: #fff;
            border: none;
            padding: 10px 16px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;

        }
        .btn_center {
            margin-top: 10px;
            text-align: center;

        }
        .login-container button[type="submit"]:hover {
            background-color: #45a049;

        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h1>로그인</h1>

<div class="login-container">
    <?php if (isset($error)) : ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="username">사용자명:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">비밀번호:</label>
        <input type="password" id="password" name="password" required><br>
        <div class = "btn_center">
            <button type="submit">로그인</button>
        </div>

    </form>
</div>

<p>계정이 없으신가요? <a href="register.php">회원가입</a></p>
</body>
</html>
