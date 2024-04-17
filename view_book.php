<?php
session_start();
include("../dbcon.php");
include("config.php");
include("../authentication.php");

if (isset($_SESSION['auth_user'])) {
    if (isset($_GET['ebook_id'])) {
        $ebook_id = $_GET['ebook_id'];
        // Query to fetch the details of the selected ebook
        $query = "SELECT e.ebook_pdf, b.book_image FROM tbl_ebook e 
                  INNER JOIN tbl_book b ON e.ebook_id = b.ebook_id
                  WHERE e.ebook_id = $ebook_id";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>View Book</title>
                <style>
                    body, html {
                        margin: 0;
                        padding: 0;
                        height: 100%;
                        overflow: hidden;
                    }
                    canvas {
                        display: block;
                        margin: auto;
                        max-width: 100%;
                        max-height: 100%;
                    }
                </style>
            </head>
            <body>
                <?php
                $pdf_path = "../seller/digital_books/" . $row['ebook_pdf'];
                ?>
                <!-- Canvas to display the PDF content -->
                <canvas id="pdf-viewer"></canvas>
                
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const pdfPath = "<?php echo $pdf_path; ?>";
                        const canvas = document.getElementById('pdf-viewer');
                        
                        // Asynchronously download PDF
                        pdfjsLib.getDocument(pdfPath).promise.then(pdf => {
                            // Fetch the first 10 pages
                            const totalPages = pdf.numPages;
                            const pagesToRender = Math.min(10, totalPages);
                            const promises = [];
                            for (let pageNum = 1; pageNum <= pagesToRender; pageNum++) {
                                promises.push(pdf.getPage(pageNum));
                            }
                            return Promise.all(promises);
                        }).then(pages => {
                            pages.forEach((page, index) => {
                                // Prepare canvas for each page
                                const viewport = page.getViewport({ scale: 1.5 });
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                
                                // Render PDF page into canvas context
                                const context = canvas.getContext('2d');
                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                page.render(renderContext);
                            });
                        });
                    });
                </script>
            </body>
            </html>
            <?php
        } else {
            echo "<p>Ebook not found.</p>";
        }
    } else {
        echo "<p>Ebook ID not provided.</p>";
    }
} else {
    $_SESSION["message"] = "Please log in to view this page.";
    header("Location:../login.php");
    exit();
}
?>
