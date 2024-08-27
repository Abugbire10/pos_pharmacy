<?php

require '../config/function.php';

//Getting the id
$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $adminId = validate($paraResultId);

    $admin = getById('admins' ,$adminId);
    if($admin['status'] == 200)
    {
        $adminDeleteRes = delete('admins', $adminId);
        if($adminDeleteRes)
        {
            redirect('admins.php','Admin Deleted Successfully.');
        }
        else
        {
            redirect('admins.php','Something went wrong.');
        }
    }
    else
    {
        redirect('admins.php',$admin['message']);
    }
    //echo $adminId;

}else{
    redirect('admins.php','Something went wrong.');
}

?>