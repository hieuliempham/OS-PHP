<?php
    
    if (isset($_SESSION["username"]) && $_SESSION["username"]){
        echo"<li class='nav-link'>".$_SESSION["username"]."</li>";
        echo "<li><a  class='nav-link btn btn-secondary' href = '/mvc/account/logout'>Logout</a></li>";

    }else{
        echo "<li><a  class='nav-link btn btn-secondary' href = '/mvc/account/register'>Đăng ký</a></li>";
        echo "<li><a  class='nav-link btn btn-secondary' href = '/mvc/account/login'>Đăng nhập</a></li>";

 

    }

?>