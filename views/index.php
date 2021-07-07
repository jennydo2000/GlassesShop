<?php
include __DIR__ . '/layouts/header.php';
?>
<style>
    .glass-image {
        height: 200px;
        background-repeat:no-repeat;
        background-size:contain;
    }
</style>
<!--Store name-->
<div class="row justify-content-center">
    <div class="col text-center">
        <h1>Cửa hàng mắt kính Phonthavy</h1>
    </div>
</div>
<!--End of store name -->
<!--Searching-->
<div class="row align-items-center justify-content-center">
    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        <input id="input-search" class="form-control mb-2" name="search" value="<?=$params['keyword']?>" onclick="this.setSelectionRange(0, this.value.length)" />
        <div class="d-flex justify-content-center">
            <button onclick="search()" class="btn btn-primary">Tìm kiếm</button>
        </div>
    </div>
</div>
<!--End of Searching-->
<!--Admin Tool-->
<?php
if ($_IS_ADMIN) {
?>
<div class="row">
    <div class="col">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">Thêm mắt kính mới</button>
    </div>
</div>
<?php
}
?>
<!--End of admin tool-->
<!--Glasses-->
<div class="row">
    <?php
    while($glass = $params['data']->fetch_assoc()) {
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4">
        <div class="d-flex flex-column shadow-sm p-4 text-truncate">
            <div style="background-image: url('<?='public/images/glasses/glass' . $glass['id'] . '.png'?>')" class="w-100 glass-image"></div>
            <h5 class="text-truncate"><?=$glass['name']?></h5>
            <h5 class="text-danger"><?=number_format($glass['price'] - ($glass['price'] * $glass['discount'] / 100))?>đ</h5>
            <div class="text-decoration-line-through"><?=number_format($glass['price'])?>đ</div>
            <a href="index.php?action=glass&method=show&id=<?=$glass['id']?>" class="btn btn-primary">Xem chi tiết</a>
        </div>
    </div>
    <?php
    }
    ?>
</div>
<!--End of glasses-->
<!--Add glass modal-->
<div class="modal fade" id="add">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Thêm mắt kính mới</div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-2">
                    <span class="input-group-text">Tên kính</span>
                    <input id="add-glass-name" type="text" class="form-control" placeholder="Nhập tên kính" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Nhẵn hiệu</span>
                    <input id="add-glass-trademark" type="text" class="form-control" placeholder="Nhập nhãn hiệu" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Màu sắc</span>
                    <input id="add-glass-color" type="color" class="form-control form-control-color" id="exampleColorInput" value="#563d7c" title="Chọn màu sắc">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Số lượng</span>
                    <input id="add-glass-quantity" type="number" class="form-control" placeholder="Nhập số lượng" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Giá</span>
                    <input id="add-glass-price" type="number" class="form-control" placeholder="Nhập giá mắt kính" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Giảm giá</span>
                    <input id="add-glass-discount" type="number" class="form-control" placeholder="Nhập giảm giá (%)" />
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">Ảnh</span>
                    <input id="add-glass-image" type="file" accept="image/png, image/jpeg" class="form-control" placeholder="Chọn ảnh..." />
                </div>
                <div class="form-check">
                    <input id="add-glass-is-opened" class="form-check-input" type="checkbox" checked>
                    <label class="form-check-label" for="flexCheckDefault">Mở kinh doanh</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="add-glass" type="button" class="btn btn-primary">Thêm mắt kính mới</button>
            </div>
        </div>
    </div>
</div>
<!--End of add new glass-->
<script>
    //Add a new glass
    $("#input-search").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            search();
        }
    });

    function search() {
        keyword = $('#input-search').val();
        window.location.href = 'index.php?action=glass&method=index&keyword=' + keyword;
    }

    $('#add-glass').click(function () {
        name = $('#add-glass-name').val();
        trademark = $('#add-glass-trademark').val();
        color = $('#add-glass-color').val();
        quantity = $('#add-glass-quantity').val();
        price = $('#add-glass-price').val();
        discount = $('#add-glass-discount').val();
        image = $('#add-glass-image').prop('files')[0];
        isOpened = $('#add-glass-is-opened').prop('checked') ? 1 : 0;

        data = new FormData();
        data.append('name', name);
        data.append('trademark', trademark);
        data.append('color', color);
        data.append('quantity', quantity);
        data.append('price', price);
        data.append('discount', discount);
        data.append('image', image);
        data.append('is_opened', isOpened);

        $.ajax({
            url: 'index.php?action=glass&method=store',
            dataType: 'script',
            cache: false,
            contentType: false,
            processData: false,
            data: data,                         
            type: 'post',
            success: function(data) {
                if (data) {
                    window.location.href = 'index.php?action=glass&method=index';
                }
            }
        });
    });
</script>
<!--End of add glass modal-->
<?php
include __DIR__ . '/layouts/footer.php';
?>