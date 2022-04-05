<?php
/*
By Ivan Hanloth
本文件为翰络云传文件存储接口文件
2022/4/4
*/
require "./config.php";
require "./common.php";

    if ($_FILES["file"]["error"] > 0){
        echo json_encode( array("code"=>"0","tip"=>"错误".$_FILES["file"]["error"]),  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    }else{
            $db=mysqli_connect($dbpath, $dbaccount, $dbpassword, $dbname);
            $file_name=random(6)."_".$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $file_name);
            $file_url=$domain."upload/".$file_name;
            $file_path=$_SERVER['DOCUMENT_ROOT']."/upload/".$file_name;
            $tillday=time()+864000;//剩余时长
            $tilltime=date('Y-m-d H:i:s', $tillday);
            $times=10;//查看次数
            $check=array(1);//定义进行循环检查
            while ($check[0]>=1){//进行循环检查
                $key=random(4);//获得一个key
                $check=mysqli_query($db,"SELECT count(*) FROM `data` WHERE `gkey` = '{$key}'");//获取数据库中是否存在相同key
                $check=mysqli_fetch_row($check);//sql对象转化为数组
            };
            mysqli_query($db,"INSERT INTO `data` (`id`, `gkey`, `type`, `data`,`path`, `tillday`, `times`) VALUES (NULL, '{$key}', '1', '{$file_url}','{$file_path}', '{$tillday}', '{$times}')");//插入数据
    echo json_encode( array("code"=>"200","tip"=>"文件上传成功","key"=>$key,"tillday"=>$tilltime,"times"=>$times),  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    };
?>