<?php
function ValidatePost($postName,$errorMessage="This field is required.",& $errors){
    if (empty($_POST[$postName])){
        $errors[]=$errorMessage;
        return null;
    } else {
        return mysql_real_escape_string($_POST[$postName]);
    }
}
?>