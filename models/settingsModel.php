<?php

class settingsModel{
    
    public function getUser($con, $user_id){
        try{
            $query = "SELECT user_id, name, email FROM restaurant_auth WHERE user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error getting user: " . $e->getMessage(), 500);
        }   
    }

    public function getCurrentPassword($con, $user_id){
        try{
            $hashedPassword = null; // Initialize to avoid undefined variable warning
            $query = "SELECT password FROM restaurant_auth WHERE user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            $stmt->close();
            return $hashedPassword;
        }catch(Exception $e){
            throw new Exception("Error retrieving password: " . $e->getMessage(), 500);
        }
    }

    public function updateProfile($con, $user_id, $name, $email){
        try{
            $query = "UPDATE restaurant_auth SET name = ?, email = ? WHERE user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssi", $name, $email, $user_id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error updating profile: " . $e->getMessage(), 500);
        }
    }

    public function updatePassword($con, $user_id, $new_password){
        try{
            // Use sha1 for password hashing
            $hashed_password = sha1($new_password);
            $query = "UPDATE restaurant_auth SET password = ? WHERE user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("si", $hashed_password, $user_id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error updating password: " . $e->getMessage(), 500);
        }
    }
}

?>