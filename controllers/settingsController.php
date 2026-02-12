<?php
session_start();

require_once '../includes/config.php';
require_once '../models/settingsModel.php';
require_once '../includes/flashMessages.php';

try{
    $user_id = $_SESSION['user_id'];
    $settingsModel = new settingsModel();
    $users = $settingsModel->getUser($con, $user_id);
}catch(Exception $e){
    throw new Exception("Error: " . $e->getMessage(), 500);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        $name = $_POST['name'];
        $email = $_POST['email'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];


        $hashedPassword = $settingsModel->getCurrentPassword($con, $user_id);

        if (sha1($current_password) === $hashedPassword) {
            
            if ($settingsModel->updateProfile($con, $user_id, $name, $email)) {
                setFlash("success", "Profile Updated Successfully!");
            } else {
                setFlash("errror", "Something Wrong Updating Profile!");
            }

            // Handle password change if new password is provided
            if (!empty($new_password) && !empty($confirm_password)) {
                if ($new_password === $confirm_password) {
                    
                    if ($settingsModel->updatePassword($con, $user_id, $new_password)) {
                        setFlash("success", "Password Updated Successfully!");
                    } else {
                        setFlash("error", "Error Updating Password!");
                    }
                    
                } else {
                    setFlash("warning", "New password and confirm password do not match!");
                }
            }

        } else {
           setFlash("warning", "Incorrect current password. Please try again!");
        }

    }catch(Exception $e){
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header('Location: settings.php');
    exit();
}

?>