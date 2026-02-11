<?php   
session_start();

require_once '../includes/config.php';
require_once '../models/authModel.php';
require_once '../includes/flashMessages.php';

$auth = new authModel();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $con->real_escape_string(trim($_POST['email']));
        $password = $con->real_escape_string(sha1(trim($_POST['password'])));

        try{
            if($auth->authUser($con, $email, $password)){
                // Check role and redirect
                if($_SESSION['role'] === 'admin'){
                    header("Location: ../admin/dashboard.php");
                    exit();
                }else{
                    header("Location: ../staffs/home.php");
                    exit(); 
                }
            }else{
                setFlash("error", "Invalid credentials, Try Again.");
                header("Location: ../index.php"); 
                exit();
            }
        }catch(Exception $e){
            setFlash("error", "System error. Please try again.");
            header("Location: ../index.php");
            exit();
        }
    }else{
        header("Location: ../index.php");
        exit();
    }

?>