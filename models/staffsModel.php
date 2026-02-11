<?php

    class staffsModel{
        public function getStaffs($con){
            try{
                $query = "SELECT * FROM restaurant_auth ORDER BY user_id DESC";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                $users = [];
                while($row = mysqli_fetch_assoc($result)){
                    $users[] = $row;
                }
                $stmt->close();
                return $users;
            }catch(Exception $e){
                throw new Exception("Error getting User ". $e->getMessage(), 500);
            }
        }
    }

?>