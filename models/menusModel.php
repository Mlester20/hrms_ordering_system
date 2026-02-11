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

    public function addMenus($con, $menu_name, $category, $price, $description, $status) {
        $query = "INSERT INTO menus(menu_name, category, price, description, status) VALUES (?,?,?,?,?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssdss", $menu_name, $category, $price, $description, $status);
        return $stmt->execute();
    }

    public function editMenus($con, $menu_id, $menu_name, $category, $price, $description, $status) {
        $query = "UPDATE menus SET menu_name = ?, category = ?, price = ?, description = ?, status = ? WHERE menu_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssdssi", $menu_name, $category, $price, $description, $status, $menu_id);
        return $stmt->execute();
    }

    public function deleteMenus($con, $menu_id) {
        $query = "DELETE FROM menus WHERE menu_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $menu_id);
        return $stmt->execute();
    }
}

?>