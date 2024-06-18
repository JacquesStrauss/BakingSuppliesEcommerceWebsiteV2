<?php
require('db.php')

 try {


 
if (isset($_POST['category_id'])) {
    $categoryId = $_GET['category_id'];
    $status = false;

   $stmt = $pdo->prepare($"UPDATE categories SET IsActive = ? WHERE category_id = ?";);
    // Update products table to set category_id to null for products linked to this category

    if($stmt->execute([ $status , $categoryId]);)
    {
         echo json_encode(['success' => true]);
    }
 
    $sql_update_products = "UPDATE products SET category_id = ? WHERE category_id = ?";
    $stmt_update_products = $pdo->prepare($sql_update_products);


    if($stmt_update_products->execute([$categoryId]))
    {
        // Return success response
        echo json_encode(['success' => true]);
    }
    
} else {
    // Return error response
    echo json_encode(['success' => false]);
}
}
catch(Exception $ex){
    $code = $ex->getCode();
    $message = $ex->getMessage();
    $file = $ex->getFile();
    $line = $ex->getLine();
    echo "Exception thrown in $file on line $line: [Code $code]
    $message";


}

?>
