<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'auth.php';
require_once 'config.php'; // your DB connection

use Mpdf\Mpdf;

if (!isset($_SESSION['user_id'])) {
    die('Unauthorized access');
}

$user_id = $_SESSION['user_id'];

// Prepare any data needed by monthly_report.php here, for example:
$query = "SELECT * FROM transactions WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);

// Make $transactions available in included file
// e.g. by defining it here, so monthly_report.php can use it

ob_start();
include(__DIR__ . '/../pages/monthly_report.php');
$html = ob_get_clean();

try {
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Monthly_Report.pdf', 'D');
} catch (\Mpdf\MpdfException $e) {
    echo "PDF generation error: " . $e->getMessage();
}
