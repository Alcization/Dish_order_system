<?php
    function getAllProducts()
    {
        require_once(__DIR__."./../connectdb.php");
        $query = "  SELECT 
                        product_item.id AS product_item_id, 
                        product.name AS name, 
                        color.color_name AS color_name, 
                        product_item.price, 
                        product_item.product_image, 
                        product_item.quantity_in_stock,
                        product.id AS product_id
                    FROM 
                        (product 
                        JOIN product_item ON product.id = product_item.product_id) 
                        JOIN color ON product_item.color_id = color.id 
                    GROUP BY 
                        product_item.size_id, 
                        product_item.id";
        $product_info = mysqli_query(mysql: $DBConnect, query: $query);
        if (!$product_info) 
        {
            $message = 'Invalid query: ' . mysqli_error(mysql: $DBConnect) . "<br>";
            $message .= 'Whole query: ' . $query;
            die($message);
        }
        $data = array();
        while ($row = mysqli_fetch_assoc(result: $product_info)) 
        {
            $data[] = $row;
        }
        return json_encode(value: $data);
    }

    function getProductsByColor($color_name)
    {
        require_once(__DIR__."./../connectdb.php");
        $query = "  SELECT 
                        product_item.id AS product_item_id,  
                        product.name AS name, 
                        color.color_name AS color_name, 
                        product_item.price, 
                        product_item.product_image, 
                        product_item.quantity_in_stock,
                        product.id AS product_id 
                    FROM 
                        (product 
                        JOIN product_item ON product.id = product_item.product_id) 
                        JOIN color ON product_item.color_id = color.id 
                    WHERE color.color_name = '$color_name'
                    GROUP BY 
                        product_item.size_id, 
                        product_item.id";
        $product_info = mysqli_query(mysql: $DBConnect, query: $query);
        if (!$product_info) 
        {
            $message = 'Invalid query: ' . mysqli_error(mysql: $DBConnect) . "<br>";
            $message .= 'Whole query: ' . $query;
            die($message);
        }
        $data = array();
        while ($row = mysqli_fetch_assoc(result: $product_info)) 
        {
            $data[] = $row;
        }
        return json_encode(value: $data);
    }

    function getProductsByPrice($min, $max)
    {
        require_once(__DIR__."./../connectdb.php");
        $query = "  SELECT 
                        product_item.id AS product_item_id, 
                        product.name AS name, 
                        color.color_name AS color_name, 
                        product_item.price, 
                        product_item.product_image, 
                        product_item.quantity_in_stock,
                        product.id AS product_id
                    FROM 
                        (product 
                        JOIN product_item ON product.id = product_item.product_id) 
                        JOIN color ON product_item.color_id = color.id 
                    WHERE price BETWEEN $min AND $max
                    GROUP BY 
                        product_item.size_id, 
                        product_item.id";
        $product_info = mysqli_query(mysql: $DBConnect, query: $query);
        if (!$product_info) 
        {
            $message = 'Invalid query: ' . mysqli_error(mysql: $DBConnect) . "<br>";
            $message .= 'Whole query: ' . $query;
            die($message);
        }
        $data = array();
        while ($row = mysqli_fetch_assoc(result: $product_info)) 
        {
            $data[] = $row;
        }
        return json_encode(value: $data);
    }


    function getOneProduct($product_item_id)
    {
        require_once(__DIR__."./../connectdb.php");
        $query = "  SELECT
                        product.name AS name, 
                        product_item.price,
                    FROM 
                        product 
                        JOIN product_item ON product.id = product_item.product_id
                    WHERE product_item.id = $product_item_id";
        $product_info = mysqli_query(mysql: $DBConnect, query: $query);
        if (!$product_info) 
        {
            $message = 'Invalid query: ' . mysqli_error(mysql: $DBConnect) . "<br>";
            $message .= 'Whole query: ' . $query;
            die($message);
        }
        
    }





    
    // !
    function getCategory()
    {
        require_once(__DIR__."./../connectdb.php");
        $query = "  SELECT * FROM category WHERE parent_category_id IS NOT NULL";
        $product_info = mysqli_query(mysql: $DBConnect, query: $query);
        if (!$product_info) 
        {
            $message = 'Invalid query: ' . mysqli_error(mysql: $DBConnect) . "<br>";
            $message .= 'Whole query: ' . $query;
            die($message);
        }
        $data = array();
        while ($row = mysqli_fetch_assoc(result: $product_info)) 
        {
            $data[] = $row;
        }
        return json_encode(value: $data);
    }


    






//     static function getOne($id)
//     {
//         $db = new Dbhandler();
//         $req = $db->conn->query("SELECT product_item.id, 'name', color_name, price, product_image, size_id, quantity_in_stock 
//         FROM (product JOIN product_item ON product.id = product_item.product_id) JOIN color ON product_item.color_id = color.id 
//         WHERE product_item.id = $id");
//         $result = $req->fetch_assoc();
//         $product = new Product(
//             $result['id'],
//             $result['name'],
//             $result['color_name'],
//             $result['price'],
//             $result['product_image'],
//             $result['quantity_in_stock']
//         );
//         return $product;
//     }

//     static function getByCategory($category_name)
//     {
//         $db = new Dbhandler();
//         $category_id = $db->conn->query("SELECT id FROM category WHERE category_name = '$category_name'")->fetch_assoc()['id'];
//         $req = $db->conn->query("SELECT product_item.id, 'name', color_name, price, product_image, size_id, quantity_in_stock 
//         FROM ((product JOIN product_item ON product.id = product_item.product_id) JOIN color ON product_item.color_id = color.id) 
//         JOIN product_category ON product_item.product_id = product_category.product_id
//         WHERE category_id = $category_id GROUP BY size_id, product_item.id");
//         $products = [];
//         foreach ($req->fetch_all(MYSQLI_ASSOC) as $product)
//         {
//             $products[] = new Product(
//                 $product['id'],
//                 $product['name'],
//                 $product['color_name'],
//                 $product['price'],
//                 $product['product_image'],
//                 $product['quantity_in_stock']
//             );
//         }
//         return $products;
//     }


//     static function addToCart($user_id, $product_item_id, $color_name, $size, $quantity)
//     {
//         $db = new Dbhandler();
//         $product_id = $db->conn->query("SELECT product_id FROM product_item WHERE id = $product_item_id")->fetch_assoc()['product_id'];
//         $color_id = $db->conn->query("SELECT id FROM color WHERE color_name = '$color_name'")->fetch_assoc()['id'];
//         $size_id = $db->conn->query("SELECT id FROM size WHERE size_value = '$size'")->fetch_assoc()['id'];
//         $product_item_id_add = $db->conn->query("SELECT id FROM product_item WHERE product_id = $product_id AND color_id = $color_id AND size_id = $size_id")->fetch_assoc()['id'];
//         $sql = "INSERT INTO cart (user_id, product_item_id, quantity) VALUES ($user_id, $product_item_id_add, $quantity)";
//         if ($db->conn->query($sql) === TRUE)
//         {
//             return true;
//         }
//         else
//         {
//             return false;
//         }
//     }
// }

