<?php

require '../config/function.php';

//Getting the id
$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $categoryId = validate($paraResultId);

    $category = getById('categories' ,$categoryId);
    if($category['status'] == 200)
    {
        $response = delete('categories', $categoryId);
        if($response)
        {
            redirect('categories.php','Category Deleted Successfully.');
        }
        else
        {
            redirect('categories.php','Something went wrong.');
        }
    }
    else
    {
        redirect('categories.php',$category['message']);
    }  
}else{
    redirect('categories.php','Something went wrong.');
}

?>