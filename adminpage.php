<?php
require_once 'Utilities/sessionManager.php';
require_once 'Models/admin.php';

if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['AccountType'] !== 'A') {
    header('Location: login.php');
    exit();
}

$admin = new Admin();
$reports = $admin->getReport();

$content = <<<HTML
<div class="container mx-auto mt-10 p-5">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord de l'administrateur</h1>
    
    <h2 class="text-2xl font-semibold mb-4">Signalements des utilisateurs</h2>
HTML;

if (!empty($reports)) {
    $content .= '<ul class="bg-white shadow rounded-lg divide-y divide-gray-200">';
    foreach ($reports as $report) {
        $reportId = $report['Id'];
        $reportType = htmlspecialchars($report['ReportType']);
        $reason = htmlspecialchars($report['Reason']);
        $reportedId = htmlspecialchars($report['IdReported']);
        $senderId = htmlspecialchars($report['IdSender']);
        $isComplete = $report['isComplete'] ? 'Complété' : 'En attente';
        
        $content .= <<<HTML
        <li class="p-4">
            <p><strong>Type de signalement:</strong> $reportType</p>
            <p><strong>Raison:</strong> $reason</p>
            <p><strong>ID Signalé:</strong> $reportedId</p>
            <p><strong>ID Expéditeur:</strong> $senderId</p>
            <p><strong>Statut:</strong> $isComplete</p>
        </li>
HTML;
    }
    $content .= '</ul>';
} else {
    $content .= '<p>Aucun signalement pour l\'instant.</p>';
}

$content .= <<<HTML
</div>
HTML;

include "Views/master.php";
