<?php
include_once 'app/views/share/header.php'
?>

<div class="row row-cols-1 row-cols-md-3 g-4">

<?php while ($row = $products->fetch(PDO::FETCH_ASSOC)) : ?>
    <div class="col">
        <div class="card">
            <img src="<?=$row['thumnail'];?>" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?=$row['name']?></h5>
                <p class="card-text"><?=$row['description']?></p>
                <p class="card-text">Giá: <?=$row['price']?></p>
                <a href="#" class="btn btn-primary">Mua Ngay</a>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

<?php
include_once 'app/views/share/footer.php'
?>