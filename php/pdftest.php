

<?php
// 파일 생성 및 저장
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

function saveAsPDF($htmlContent, $filename) {
    // jsPDF 객체 생성
    $pdf = new Dompdf();
    // HTML 내용을 로드
    $pdf->loadHtml($htmlContent);

    // PDF 생성
    $pdf->render();

    // PDF 파일 저장 경로 및 이름
    $filepath = 'C:/Apache24/htdocs/php2' . $filename;

    // PDF 파일 저장
    file_put_contents($filepath, $pdf->output());

    // 다운로드 헤더 설정
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
    header('Content-Length: ' . filesize($filepath));

    // 파일 다운로드
    readfile($filepath);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST 요청 처리

    // HTML 내용
    $htmlContent = '<h1>Hello, World!</h1><p>This is an example of converting HTML to PDF using Dompdf.</p>';

    // 사용자로부터 입력 받은 파일 이름
    $filename = $_POST['filename'];

    // 파일 다운로드 함수 호출
    saveAsPDF($htmlContent, $filename);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HTML to PDF</title>
</head>
<body>
<!-- HTML 내용 -->
<h1>Hello, World!</h1>
<p>This is an example of converting HTML to PDF using Dompdf.</p>

<!-- 파일 이름 입력 폼 -->
<form method="POST" action="">
    <label for="filename">Enter PDF file name (without extension):</label>
    <input type="text" name="filename" id="filename" required>
    <button type="submit">Save as PDF</button>
</form>
</body>
</html>
