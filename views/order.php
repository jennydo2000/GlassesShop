<?php
include __DIR__ . '/layouts/header.php';
include_once __DIR__ . '/../functions.php';
?>
<h3 class="text-center">Đơn đặt hàng</h3>
<?php
while($order = $params['data']->fetch_assoc()) {
?>
    <!--Items-->
    <div class="list-group mb-4">
        <div class="list-group-item list-group-item-action">
            <div class="d-flex justify-content-between">
                <div>
                    <span>#<?=$order['id']?></span>
                    <h5 class="mb-3"><?=$order['name']?></h5>
                    <div><i class="bi bi-telephone"></i> <?=$order['phone']?></div>
                    <div><i class="bi bi-geo-alt"></i> <?=$order['address']?></div>
                </div>
                <div class="d-flex flex-column align-items-center">
                    <span class="mb-3"><?=time_elapsed_string($order['time'])?></span>
                    <?php
                    if ($order['is_ordered']) {
                    ?>
                        <div class="text-success">Đơn hàng đã được xác nhận</div>
                        <a href="index.php?action=cart&method=view_order&id=<?=$order['id']?>" class="btn btn-primary">Xem chi tiết đơn hàng</a>
                    <?php
                    } else {
                    ?>
                        <div class="text-danger">Đơn hàng chưa được xác nhận</div>
                        <a href="index.php?action=cart&method=view_order&id=<?=$order['id']?>" class="btn btn-danger">Xác nhận đơn hàng</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </tr>
<?php
}
?>
<script>
    function viewDetail(id) {
        window.location.href = "index.php?action=cart&method=view_order&id=" + id;
    }
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>