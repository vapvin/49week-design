<?php 

session_start();

?>

<div class="container navigation-wrap">
    <nav id="navigation">
        <!-- <a class="nav-item nav-brand" href="./index.html"><img src="./img/49week_ci.png" alt="logo" style="width: 80%;"></a> -->
        <div class="blank-header"></div>
        <ul id="topMenu" class="nav-item menu">
            <li class="menu-item">
                <a href="index.php">이미지 관리</a>
            </li>
            <li class="menu-item">
                <a href="member_list.php">회원관리</a>
                <ul class="sub-menu">
                    <li class="menu-item"><a href="member_list.php">회원목록</a></li>
                    <li class="menu-item"><a href="member_group.php">업체관리</a></li>
                </ul>
            </li>
        </ul>
        <ul class="nav-item nav-user">
            <li class="username"><a href="#" class="modal-btn" data-target=".modal.reply-userinfo"><span class="custom-ico user_icon"></span><?php echo $_SESSION['company']; ?></a></li>
            <li class="logout"><a href="logout.php">로그아웃</a></li>
        </ul>
    </nav>
</div>
<script type="text/javascript" async="async">
    $(document).ready(function() {
        $('li.menu-item').on('mouseenter', function() {
            if ($('li.menu-item').has('ul.sub-menu')) {
                $(this).addClass('menu-visible');
            }
        });
        $('li.menu-item').on('mouseleave', function() {
            $(this).removeClass('menu-visible');
        });
    });

</script>
