<?php 
    include 'dbconnection.php';
    session_start();
    
    if (isset($_SESSION['email']) && !empty($_SESSION['email'])){
        if($_SESSION['role_id'] == '1'){
            header("Location: admin/clientList");    
        }
        else{
            header("Location: audit_admin/dashboard");
        }
    }
    if(!isset($_POST['email']) && empty($_POST['email']) && !isset($_POST['password']) && empty($_POST['password'])){
        header('Location: index');
    }

    $email = trim($_POST["email"]);
    $pass = trim($_POST["password"]);
    $pass = md5($pass);
    $users = $con->query("SELECT * FROM user WHERE email= '$email' and password= '$pass'");
    
    
    if ($users->num_rows != 0) {
        $usersrow = $users->fetch_assoc();
        if($usersrow['active'] == 1){
            $_SESSION['id'] = $usersrow['id'];
            $_SESSION['name'] = $usersrow['name'];
            $_SESSION['email'] = $usersrow['email'];
            $_SESSION['role'] = $usersrow['role_id'];
            $_SESSION['reg_date'] = $usersrow['reg_date'];
            $_SESSION['signoff'] = $usersrow['signoff_init'];
            if($usersrow['role_id'] == '1'){
                header('Location: admin/clientList');
            }
            else{
                header('Location: audit_admin/dashboard.php');
            }
        }
        else{
            echo "Denied";
        }
    }

?>