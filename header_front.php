<?php 

    $check_query = "SELECT * from types";
    $result=mysqli_query($conn,$check_query);
?>


<div class="container navigation-wrap">
    <nav id="navigation" class="topMenu-front">
        <!-- <a class="nav-item nav-brand" href="./index.html"><img src="./img/49week_ci.png" alt="logo" style="width: 80%;"></a> -->
        <div class="blank-header"></div>
        <ul id="topMenu" class="nav-item menu">

        <?php

                if(mysqli_affected_rows($conn) > 0){



                    while ($row = mysqli_fetch_assoc($result)){

                        if($comp == $row['company']) {
                            ?>
                            <li class="menu-item">
                                <a href="design.php?company=<?php echo $comp; ?>&type=<?php echo $row["name"]; ?>">Type <?php echo $row["name"]; ?></a>
                            </li>
                            <?php
                        } 
                    }
                                                    
                }
            ?>
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
