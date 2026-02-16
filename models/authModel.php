<?php

class authModel{
    public function authUser($con, $email, $password){
        try{
            $query = "SELECT user_id, name, role FROM restaurant_auth WHERE email = ? AND password = ?";
            $stmt = $con->prepare($query);

            if($stmt){
                mysqli_stmt_bind_param($stmt, "ss", $email, $password);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) > 0){
                    $user = mysqli_fetch_assoc($result);
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['logged_in'] = true;
                    
                    mysqli_stmt_close($stmt);
                    return true;
                }else{
                    mysqli_stmt_close($stmt);
                    return false;
                }
            }else{
                throw new Exception("Failed to prepare statement");
            }
        }catch(Exception $e){
            throw new Exception("Error processing login: " . $e->getMessage());
        }
    }
}

?>