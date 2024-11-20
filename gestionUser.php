<?php
require_once 'Models/admin.php';
require_once 'BD/BD.php';
include 'Utilities/sessionManager.php';

$admin = new Admin();
$allAccounts = $admin->GetAllAccount();

$content = <<<HTML
<div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto">


    <h1 class="text-2xl font-bold mb-4">Gestion des Utilisateurs</h1>

    <table class="min-w-full divide-y divide-gray-200 overflow-x-auto">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nom et Email
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Role
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>

                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">


HTML;

foreach ($allAccounts as $account) {
    if ($account['AccountType'] != 'A') {
        $id = htmlspecialchars($account['Id'] ?? '');
        $name = htmlspecialchars($account['Name'] ?? '');
        $email = htmlspecialchars($account['Email'] ?? '');
        $type = htmlspecialchars($account['AccountType'] ?? '');
        $isBlocked = htmlspecialchars($account['IsBlocked'] ?? '');

        $typeText = "";
        if ($type == 'C') {
            $typeText = 'Companie';
        } elseif ($type == 'E') {
            $typeText = 'Employée';
        }

        $isBlockedText = "";
        $BlockedAction = "";
        if ($isBlocked == 0) {
            $isBlockedText = <<<HTML
                       <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Autorisé
                </span>
            HTML;
            $BlockedAction = <<<HTML
                <a href="#" onclick="BloquedAction($id,1)" class="ml-2 text-red-600 hover:text-red-900">Bloquer</a>
            HTML;
        } else {
            $isBlockedText = <<<HTML
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Bloquer
                </span>
            HTML;
            $BlockedAction = <<<HTML
                <a href="#" onclick="BloquedAction($id,0)" class="ml-2 text-red-600 hover:text-red-900">Débloquer</a>
            HTML;
        }

        $content .= <<<HTML
            <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                   
                    <div >
                        <div class="text-sm font-medium text-gray-900">
                            $name
                        </div>
                        <div class="text-sm text-gray-500">
                           $email
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">$typeText</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                $isBlockedText
            </td>
         
            <td class="px-6 py-4 whitespace-nowrap  text-sm font-medium">
                $BlockedAction
            </td>
        </tr>
HTML;
    }

}

$content .= <<<HTML

</tbody>
</table>

HTML;


include "Views/master.php";
?>
<script>

function BloquedAction(id,action){
    window.location.href = 'bloqueAction.php?id='+id + '&action=' + action;
}




/* À changer
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function filterAccounts() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase().trim();
        const searchCriteria = document.getElementById('searchCriteria').value;
        const accounts = document.querySelectorAll('.account');

        accounts.forEach(account => {
            let textToSearch = '';
            let showAccount = false;

            switch (searchCriteria) {
                case 'id':
                    textToSearch = account.querySelector('.account__id').textContent.toLowerCase();
                    break;
                case 'name':
                    textToSearch = account.querySelector('.account__name').textContent.toLowerCase();
                    break;
                case 'email':
                    textToSearch = account.querySelector('.account__email').textContent.toLowerCase();
                    break;
                case 'account-type':
                    textToSearch = account.querySelector('.account__type').textContent.toLowerCase();
                    break;
                case 'is-blocked':
                    textToSearch = account.querySelector('.account__status').textContent.toLowerCase();
                    break;
                default:
                    break;
            }

            showAccount = textToSearch.includes(searchInput) || searchInput === '';

            account.style.display = showAccount ? 'block' : 'none';
        });
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        filterAccounts();
    }
const debouncedFilterAccounts = debounce(filterAccounts, 300);

document.getElementById('searchInput').addEventListener('input', debouncedFilterAccounts);
document.getElementById('searchCriteria').addEventListener('change', () => {
    document.getElementById('searchInput').value = '';
    filterAccounts();
});

*/


</script>
