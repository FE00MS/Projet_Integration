<?php
require_once 'Utilities/sessionManager.php';
require_once 'Models/admin.php';
require_once 'Models/employee.php';
require_once 'Models/offer.php';

if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['AccountType'] !== 'A') {
    header('Location: login.php');
    exit();
}

$admin = new Admin();
$reports = $admin->getReport();
$emp = new Employee();
$of = new Offer();
function getReportTypeLabel($type) {
    switch ($type) {
        case 'S':
            return 'Spam';
        case 'F':
            return 'Fausse offre';
        case 'I':
            return 'Contenu inapproprié';
        default:
            return 'Type inconnu';
    }
}

$content = <<<HTML
<div class="container mx-auto mt-10 p-5">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord de l'administrateur</h1>
    
    <h2 class="text-2xl font-semibold mb-4">Signalements des utilisateurs</h2>
HTML;

if (!empty($reports)) {
    $content .= '<ul class="bg-white shadow rounded-lg divide-y divide-gray-200">';
    foreach ($reports as $report) {
        $reportId = $report['Id'];
        $reportType =getReportTypeLabel(htmlspecialchars($report['ReportType']));
        $reason = htmlspecialchars($report['Reason']);
        $reportedName = htmlspecialchars($of->GetOffer($report['IdReported'])['Description'] );
        $senderName = htmlspecialchars($emp->GetEmployeeByIds($report['IdSender'])[0]['Name']);
        $senderLastName = htmlspecialchars($emp->GetEmployeeByIds($report['IdSender'])[0]['LastName']);
        $isComplete = $report['isComplete'] ? 'Complété' : 'En attente';
        
        $content .= <<<HTML
        <li class="p-4">
            <p><strong>Type de signalement:</strong> $reportType</p>
            <p><strong>Raison:</strong> $reason</p>
            <p><strong>Description du signalé:</strong> $reportedName</p>
            <p><strong>Nom de l'expéditeur:</strong> $senderName $senderLastName</p>
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
