<!DOCTYPE html>
<html>
<head>
    <title>HTML to PDF</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>
</head>
<body>
<!-- HTML 내용 -->
<h1>Hello, World!</h1>
<p>This is an example of converting HTML to PDF using jsPDF.</p>

<!-- Save 버튼 -->
<button onclick="save_pdf()">Save as PDF</button>

<script language="javascript">
    function save_pdf() {
        const doc = new jsPDF({
            //orientation: "landscape",
            orientation: "portrait",
            format: "a4"
            //format: [4, 2]
        });

        doc.html(document.body, {
            callback: function () {
                const pdfName = prompt('Enter PDF file name (without extension):', 'my_document');
                if (pdfName) {
                    const fileName = pdfName + '.pdf';
                    const pdfData = doc.output('blob');
                    saveAs(pdfData, fileName);
                }
            }
        });
    }
</script>
</body>
</html>
