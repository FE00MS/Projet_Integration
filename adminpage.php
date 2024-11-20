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

$stats = $admin->getStatAdmin();
$loginCounter = $stats['nombreConnexions'];
$offerCounter = $stats['nombreOffresPubliees'];
$applicationCounter = $stats['nombreCandidatures'];

$content = <<<HTML
<link rel="stylesheet" href="styles/counters.css">
<div class="counter-container">
    <div class="counter">
        <h3>Logins</h3>
        <p class="text-4xl font-bold text-gray-800">{$loginCounter}</p>
    </div>
    <div class="counter">
        <h3>Offres</h3>
        <p class="text-4xl font-bold text-gray-800">{$offerCounter}</p>
    </div>
    <div class="counter">
        <h3>Applications</h3>
        <p class="text-4xl font-bold text-gray-800">{$applicationCounter}</p>
    </div>
</div>
HTML;

$content .= <<<HTML
<div class="container mx-auto mt-10 p-5">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord de l'administrateur</h1>
    
    <h2 class="text-2xl font-semibold mb-4">Signalements des utilisateurs</h2>
HTML;

if (!empty($reports)) {
    $content .= '<ul class="bg-white shadow rounded-lg divide-y divide-gray-200">';
    foreach ($reports as $report) {
        $reportId = $report['Id'];
        $reportType = getReportTypeLabel(htmlspecialchars($report['ReportType']));
        $reason = htmlspecialchars($report['Reason']);
        $reportedName = htmlspecialchars($of->GetOffer($report['IdReported'])['Description']);
        $senderName = htmlspecialchars($emp->GetEmployeeByIds($report['IdSender'])[0]['Name']);
        $senderLastName = htmlspecialchars($emp->GetEmployeeByIds($report['IdSender'])[0]['LastName']);
        $isComplete = $report['isComplete'] ? 'Complété' : 'En attente';
        var_dump($reportId);
        $content .= <<<HTML
        <li class="p-4">
            <p><strong>Type de signalement:</strong> $reportType</p>
            <p><strong>Raison:</strong> $reason</p>
            <p><strong>Description du signalé:</strong> $reportedName</p>
            <p><strong>Nom de l'expéditeur:</strong> $senderName $senderLastName</p>
            <p><strong>Statut:</strong> $isComplete</p>
            <div class="flex justify-start gap-4 mt-4">
                <form method="post" action="deleteOfferAdmin.php">
                    <input type="hidden" name="offer" value="{$reportId}">
                    <input type="hidden" name="Sender" value="{$report['IdSender']}">
                    <button type="submit" class="btn btn-error">Supprimer l'offre</button>
                </form>
                <form method="post" action="retireReport.php">
                    <input type="hidden" name="reportId" value="{$reportId}">
                    <button type="submit" class="btn btn-warning">Retire le signalement</button>
                </form>
            </div>
        </li>
HTML;
    }
    $content .= '</ul>';
} else {
    $content .= '<p>Aucun signalement pour l\'instant.</p>';
}

$content .= <<<HTML
</div>
<style>
.counter-container {
    display: flex;
    justify-content: space-around;
    padding: 20px;
    background-color: #f0f4f8;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    max-width: 1200px;
}

.counter {
    text-align: center;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    width: 30%;
}

.counter:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.counter h3 {
    font-size: 1.75rem;
    color: #333333;
    margin-bottom: 15px;
}

.counter p {
    font-size: 3rem;
    font-weight: bold;
    color: #1a202c;
}

</style>
HTML;

include "Views/master.php";
?>