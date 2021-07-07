<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <title>Mắt kính Phonthavy</title>
    </head>
    <body>
        <div class="container">
            <!--Header-->
            <div class="row bg-primary text-white align-items-center py-2">
                <div class="col-xs-12 col-sm-2">
                    <h4 class="text-center">Logo</h4>
                </div>
                <div class="col-xs-12 col-sm-10">
                    <div class="d-flex justify-content-center">
                        <a class="btn link-light" href="./">Trang chủ</a>
                        <?php
                        if ($_IS_ADMIN) {
                        ?>
                            <a class="btn link-light" href="index.php?action=cart&method=view_orders">Đơn đặt hàng</a>
                            <a id="logout" class="btn link-light" href="">Đăng xuất (<?=$_USER['last_name']?>)</a>
                        <?php
                        } else {
                        ?>
                            <a class="btn link-light" href="index.php?action=cart&method=index">Giỏ hàng</a>
                            <a class="btn link-light" href="index.php?action=login&method=index">Đăng nhập</a>
                        <?php 
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!--End of Header-->
<script>
    $('#logout').click(function () {
        $.get('index.php?action=login&method=logout').done(function () {
            window.location.href = "index.php?action=glass&method=index";
        });
    });
</script>