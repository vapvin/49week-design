<?php
    if ( !isset($_POST['user_id']) || !isset($_POST['user_pw']) ) {
        header("Content-Type: text/html; charset=UTF-8");
        echo "<script>alert('아이디 또는 비밀번호가 빠졌거나 잘못된 접근입니다.');";
        echo "window.location.replace('login.php');</script>";
        exit;
    }
    $user_id = $_POST['user_id'];
    $user_pw = $_POST['user_pw'];

    include 'db.php';

    $check_query = "SELECT * from user WHERE email = '{$user_id}'";
    $result=mysqli_query($conn,$check_query);


    $row = mysqli_fetch_array($result);

    $password = $row["password"];
    $company = $row["company"];


    if($user_pw == $password){
        /* If success */
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $row['username'];
        $_SESSION['company'] = $row['company'];

        if($company == "49week"){
            echo '<script>alert("로그인 되었습니다."); location.href="index.php";</script>';

        } else {
            echo '<script>alert("로그인 되었습니다."); location.href="design.php?company='.$company.'&type=A&title=main";</script>';
            
        }
    } else {
        echo '<script>alert("비밀번호가 일치하지 않습니다."); history.back();</script>';
    }
 
  
?>