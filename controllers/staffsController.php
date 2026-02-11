<?php
session_start();

require_once '../models/staffsModel.php';
require_once '../includes/flashMessages.php';

    $users = new staffsModel();
    $user = $users->getStaffs($con);

?>