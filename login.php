<?php session_start(); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:url" content="https://test49admin.iwinv.net/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="49week - admin">
    <meta property="og:site_name" content="49week - admin">
    <meta property="og:image" content="https://test49admin.iwinv.net/img/49week_ci_meta.png">
    <meta property="og:description" content="브랜딩, 웹사이트 구축, 쇼핑몰 구축, 워드프레스, PHP, Python, 편집디자인, CI/BI 디자인, 마케팅기획.">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="./js/pick_me_up/dist/pickmeup.min.js"></script>
    <link rel="stylesheet" href="./js/pick_me_up/css/pickmeup.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/animate.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/style.css" type="text/css" media="all">
    <script src="./js/common.js" async="async"></script>
    <title>49week - admin</title>
</head>

<body class="login-main">
    <!-- wrap -->
    <div class="login-wrap">
        <div class="loginbox">
        <?php if(!isset($_SESSION['user_id']) || !isset($_SESSION['company']) || !isset($_SESSION['user_name'])) { ?>
            <form action="login_ok.php" method="POST">
                <div class="idbox">
                    <label for="user_id">아이디</label>
                    <input name="user_id" type="text">
                </div>
                <div class="pwbox">
                    <label for="user_pw">패스워드</label>
                    <input name="user_pw" type="password">
                </div>
                <div class="loginbtn">
                <button>로그인</button>
            </div>
            </form>
            
            <?php } else {
            $user_id = $_SESSION['user_id'];
            $user_name = $_SESSION['user_name'];
            echo "<p><strong>$user_name</strong>($user_id)님은 이미 로그인하고 있습니다. ";
            echo "<a href=\"index.php\">돌아가기</a> ";
            echo "<a href=\"logout.php\">로그아웃</a></p>";
        } ?>
            <p>문의 : 000-0000-0000 / admin@49week.com</p>
        </div>
    </div>
</body>
</html>
