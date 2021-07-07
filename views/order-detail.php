<?php
include __DIR__ . '/layouts/header.php';
$cart = $params['cart'];
?>
<style>
    .glass-image {
        width: 150px;
        height: 150px;
        background-repeat:no-repeat;
        background-size:contain;
    }
</style>
<!--Container-->
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-12 shadow p-4">
        <h1 class="text-center">Đơn đặt hàng của khách hàng</h1>
        <div class="row">
            <div class="col">
                <div>Mã hóa đơn: <?=$cart['id']?></div>
                <div>Tên khách hàng: <?=$cart['name']?></div>
                <div>Số điện thoại: <?=$cart['phone']?></div>
                <div>Địa chỉ: <?=$cart['address']?></div>
                <div>Email: <?=$cart['email']?></div>
                <div>Ngày đặt hóa đơn: <?=$cart['time']?></div>
            </div>
        </div>
        <!--Carts-->
        <div class="shadow my-4">
            <!--Cart-->
            <?php
            foreach ($params['glasses'] as $glass) {
            ?>
                <div class="row shadow-sm m-0 p-2 align-items-center">
                    <div class="col-md-8 col-sm-12">
                        <div class="d-flex align-items-center">
                            <div style="background-image: url('<?='public/images/glasses/glass' . $glass['id']. '.png'?>')" class="glass-image"></div>
                            <div class="m-3">
                                <h4><?=$glass['name']?></h4>
                                <h5><?=$glass['trademark']?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="d-flex flex-column">
                            <div>
                                <h3><?=number_format($glass['price'])?>đ</h3>
                                <h4>Số lượng: <?=$glass['quantity']?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                //$index++;
            }
            ?>
        </div>
        <div class="d-flex justify-content-center">
            <button onclick="verifyOrder(<?=$cart['id']?>)" class="btn btn-primary"><h4>Xác nhận đơn đặt hàng</h4></button>
        </div>
    </div>
</div>
<script>
    function verifyOrder(id) {
        $.get('index.php?action=cart&method=verify_order&id=' + id).done(function(data) {
            if (data) {
                window.location.href = 'index.php?action=cart&method=view_orders';
            }
        });
    }
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>