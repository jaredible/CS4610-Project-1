<div class="ui top attached menu">
    <a id="sidebar-menu-item" class="item"><i class="sidebar icon"></i>Tables</a>
    <div class="right menu">
        <a id="login-menu-item" class="item" onclick="log<?php echo isset($_SESSION['logged-in']) && $_SESSION['logged-in'] ? 'out' : 'in' ?>()"><i class="sign <?php echo isset($_SESSION['logged-in']) && $_SESSION['logged-in'] ? 'out' : 'in' ?> icon"></i><?php echo 'Log ' . (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] ? 'Out' : 'In') ?></a>
        <a id="help-menu-item" class="item" onclick="show_help()"><i class="help icon"></i>Help</a>
    </div>
</div>