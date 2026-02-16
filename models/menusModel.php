<?php

class menusModel {
    public function getMenus($con) {
        try {
            $query = "SELECT * FROM menus ORDER BY menu_id DESC";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $menus = [];
            while($row = mysqli_fetch_assoc($result)) {
                $menus[] = $row;
            }
            $stmt->close();
            return $menus;
        } catch(Exception $e) {
            throw new Exception("Error getting menus ". $e->getMessage(), 500);
        }
    }

    public function getMenuById($con, $menu_id) {
        $query = "SELECT * FROM menus WHERE menu_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addMenus($con, $menu_name, $category, $price, $product_image, $description, $status) {
        try{
            $query = "INSERT INTO menus(menu_name, category, price, product_image, description, status) VALUES (?,?,?,?,?,?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssdsss", $menu_name, $category, $price, $product_image, $description, $status);
            return $stmt->execute();
        }catch(Exception $e){
            throw new Exception("Error adding menus " . $e->getMessage(),500);
        }
    }

    public function editMenus($con, $menu_id, $menu_name, $category, $price, $product_image, $description, $status) {
        try{
            $query = "UPDATE menus SET menu_name = ?, category = ?, price = ?, product_image = ?, description = ?, status = ? WHERE menu_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssdsssi", $menu_name, $category, $price, $product_image, $description, $status, $menu_id);
            return $stmt->execute();
        }catch(Exception $e){
            throw new Exception("Error editing menus " . $e->getMessage(),500);
        }
    }

    public function deleteMenus($con, $menu_id) {
        try{
            // Get the image path before deleting
            $menuData = $this->getMenuById($con, $menu_id);
            
            $query = "DELETE FROM menus WHERE menu_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $menu_id);
            $result = $stmt->execute();
            
            // Delete the image file if exists
            if ($result && !empty($menuData['product_image'])) {
                $imagePath = "../uploads/" . $menuData['product_image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            return $result;
        }catch(Exception $e){
            throw new Exception("Error " . $e->getMessage(), 500);
        }
    }
}

?>