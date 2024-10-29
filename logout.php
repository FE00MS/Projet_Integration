<?php
include 'Utilities/sessionManager.php';
delete_session();
header('Location: homepage.php'); 
exit();