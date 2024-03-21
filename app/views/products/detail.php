<?php
include_once 'app/views/share/header.php'
?>

<div class="row">
    
    <h5 class="card-title"><?= $product->name ?></h5>
    <img src="/mvc/<?= $product->thumnail ?>" />
    <p class="card-text"><?= $product->description ?></p>
    <p class="card-text">Giá: <?= $product->price ?></p>
    <a href="#" class="btn btn-primary">Mua Ngay</a>

    <a href="/mvc/product/edit/<?=$product->id ?>" class="btn btn-warning">Sửa</a>
    

</div>

<?php
include_once 'app/views/share/footer.php'
?>