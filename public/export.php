<?php
session_start();
require_once '../src/db.php';
require_once '../lib/fpdf.php';

if (!isset($_SESSION['user'])) {
    die('No session');
}

$winnerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$winnerId) {
    die('Invalid id');
}
$stmt = $pdo->prepare('SELECT comments.text, users.name FROM comments JOIN users ON comments.user_id = users.id WHERE comments.id = ?');
$stmt->execute([$winnerId]);
$winner = $stmt->fetch();
if (!$winner) {
    die('Winner not found');
}
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Ganador del sorteo');
$pdf->Ln(20);
$pdf->SetFont('Arial','',14);
$pdf->Cell(0,10,'Nombre: '.$winner['name']);
$pdf->Ln(10);
$pdf->MultiCell(0,10,'Comentario: '.$winner['text']);
$pdf->Output('D','ganador.pdf');
?>
