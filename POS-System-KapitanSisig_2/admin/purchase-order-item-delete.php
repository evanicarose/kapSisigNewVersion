<?php
require '../config/function.php';

$paramResult = checkParam('index');

if(is_numeric($paramResult)){

    $indexValue = validate($paramResult);

    if(isset($_SESSION['ingredientItems']) && isset($_SESSION['ingredientItemIds'])){
        unset($_SESSION['ingredientItems'][$indexValue]);
        unset($_SESSION['ingredientItemIds'][$indexValue]);

        redirect('purchase-order-create.php', 'Item Removed!');
    }else{
        redirect('purchase-order-create.php', 'There is no item.');
    }

}else{
    redirect('purchase-order-create.php', 'param not numeric');
}
?>