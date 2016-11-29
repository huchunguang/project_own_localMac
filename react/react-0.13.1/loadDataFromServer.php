<?php
header('content-type:text/html;charset=utf-8');
if(!empty($_GET['username'])&&trim($_GET['username'])=='huchunguang')
{
    $pre_array=[];
    $pre_array=[['author'=>'phper','body'=>'this is a php code transfer information']];
}
else{
    $pre_array=[['author'=>$_POST['author'],'body'=>$_POST['body']]];
}
echo json_encode($pre_array);