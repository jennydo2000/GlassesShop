<?php
include __DIR__ . '/layouts/header.php';
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
        <h1 class="text-center">Giỏ hàng</h1>
        <!--Carts-->
        <div class="shadow my-4">
            <!--Cart-->
            <?php
            $index = 0;
            $sum = 0;
            if (!empty($params['carts'])) {
            ?>
            <?php
                foreach ($params['carts'] as $cart) {
                    $sum += ($cart['price'] - ($cart['price'] * $cart['discount'] / 100)) * $_SESSION['carts'][$index]['quantity'];
            ?>
                    <div id="cart-item-<?=$cart['id']?>" class="row shadow-sm m-0 p-2 cart-items">
                        <div class="col-md-8 col-sm-12">
                            <div class="d-flex align-items-center">
                                <div style="background-image: url('<?='public/images/glasses/glass' . $cart['id']. '.png'?>')" class="glass-image"></div>
                                <div class="m-3">
                                    <h4><?=$cart['name']?></h4>
                                    <h5><?=$cart['trademark']?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="d-flex flex-column">
                                <div>
                                    <input class="carts-price" type="hidden" value="<?=$cart['price'] - ($cart['price'] * $cart['discount'] / 100)?>" />
                                    <h3><?=number_format($cart['price'] - ($cart['price'] * $cart['discount'] / 100))?>đ</h3>
                                    <h6 class="text-decoration-line-through"><?=number_format($cart['price'])?>đ</h6>
                                </div>
                                <div class="btn-group mb-3" role="group">
                                    <button type="button" class="btn btn-primary cart-sub" value="<?=$cart['id']?>">-</button>
                                    <button id="cart-quantity-<?=$cart['id']?>" type="button" class="btn btn-light disabled px-5"><?=$_SESSION['carts'][$index]['quantity']?></button>
                                    <button type="button" class="btn btn-primary cart-add" value="<?=$cart['id']?>">+</button>
                                </div>
                                <button class="btn btn-secondary cart-remove" value="<?=$cart['id']?>">Xóa</button>
                            </div>
                        </div>
                    </div>
            <?php
                    $index++;
                }
            } else {
            ?>
                <h3 class="text-center p-5">Giỏ hàng trống</h3>
            <?php
            }
            ?>
            <!--End of cart-->
        </div>
        <h4 id="total-price">Tổng tiền: <?=number_format($sum)?>đ</h4>
        <!--End of carts-->
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#bill"><h4>Đặt hàng</h4></button>
        </div>
    </div>
</div>
<!--End of container-->
<!--Bill-->
<div class="modal fade" id="bill">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Nhập thông tin</div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-2">
                        <span class="input-group-text">Họ và tên</span>
                        <input id="send-bill-name" type="text" class="form-control" placeholder="Nhập Họ và tên" />
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Số điện thoại</span>
                        <input id="send-bill-phone" type="text" class="form-control" placeholder="Nhập số điện thoại" />
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Địa chỉ</span>
                        <input id="send-bill-address" type="text" class="form-control" placeholder="Nhập địa chỉ" />
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Email</span>
                        <input id="send-bill-email" type="text" class="form-control" placeholder="Nhập email(Không bắt buộc)" />
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="send-bill" type="button" class="btn btn-primary">Xác nhận đặt đơn hàng</button>
            </div>
        </div>
    </div>
</div>
<!--End of bill-->
<!--Message modal-->
<div class="modal fade" id="message">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Thông báo</div>
            </div>
            <div class="modal-body" id="message-body">
                Cảm ơn bạn đã đặt đơn đặt hàng, chúng tôi sẽ gửi thông tin đơn đặt hàng cho email của bạn
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!--End of message modal-->
<script>
    //Add or sub cart quantity
    function modifyCart(id, number) {
        $.get('index.php?action=cart&method=buy&id=' + id + '&quantity=' + number).done(function(data) {
            if (data) {
                let sum = 0;
                let cartQuantity =  $('#cart-quantity-' + id);
                let cartsPrice = $('.carts-price');
                cartQuantity.text(data);

                cartsPrice.each(function () {
                    sum += $(this).prop('value') * data;
                });
                $('#total-price').text('Tổng tiền: ' + new Intl.NumberFormat('en-US', {style: 'decimal'}).format(sum) + 'đ');
            }
        });
    }

    //Remove a glass
    $('.cart-remove').each(function (index, elem) {
        $(this).click(function () {
            let id = $(this).attr('value');
            $.post('index.php?action=cart&method=remove&id=' + id).done(function(data) {
            if (data) {
                $('#cart-item-' + id).remove();
            }
        });
        });
    });

    //Sub quantity of a glass
    $('.cart-sub').each(function (index, elem) {
        $(this).click(function () {
            let id = $(this).attr('value');
            modifyCart(id, -1);
        });
    });

    //Add quantity of a glass
    $('.cart-add').each(function (index, elem) {
        $(this).click(function () {
            let id = $(this).attr('value');
            modifyCart(id, 1);
        });
    });

    //Send a bill
    $('#send-bill').click(function() {
        name = $('#send-bill-name').val();
        phone = $('#send-bill-phone').val();
        address = $('#send-bill-address').val();
        email = $('#send-bill-email').val();

        if (name == '') {
            $('#message-body').text('Bạn chưa nhập họ tên');
            $('#message').modal('show');
            return;
        }

        if (phone == '') {
            $('#message-body').text('Bạn chưa nhập số điện thoại');
            $('#message').modal('show');
            return;
        }

        if (address == '') {
            $('#message-body').text('Bạn chưa nhập địa chỉ');
            $('#message').modal('show');
            return;
        }

        data = {
            name: name,
            phone: phone,
            address: address,
            email: email,
        }

        $.post('index.php?action=cart&method=submit', data).done(function(data) {
            if (data) {
                $('#bill').modal('hide');
                $('#message-body').text('Cảm ơn bạn đã đặt đơn đặt hàng, chúng tôi sẽ gửi thông tin đơn đặt hàng cho email của bạn(nếu có) và liên hệ với số điện thoại sớm nhất');
                $('#message').modal('show');
                $('.cart-items').remove();
            }
        });
    });
</script>
<?php
include __DIR__ . '/layouts/footer.php';
?>