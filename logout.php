<?php
include 'Utilities/sessionManager.php';
delete_session();
header('Location: index.php'); 
exit();