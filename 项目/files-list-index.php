<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>列出当前文件夹下所有文件/文件夹</title>
</head>
<body>
    <h1>List File/Forder</h1>
    <ul>
        <?php  
        $dirname = __DIR__;
        $dir = opendir( $dirname );
        $file_list=""; 
        while( false != ( $file = readdir( $dir ) ) )
        {
        if( ( $file != "." ) && ( $file != ".." ) &&  ( $file != basename(__FILE__) ))
        {
        $file_list .= "<li><a href=\"$file\">$file</li>";
        }
        }
        closedir( $dir );
        echo  $file_list ;
        ?>
    </ul>
</body>
</html>