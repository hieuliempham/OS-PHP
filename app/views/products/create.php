<?php
include_once 'app/views/share/header.php'
?>

<div class="row">

    <h1>
        <?php if(isset($errors))
                var_dump($errors);
            ?>
    </h1>

<form action="/mvc/product/save" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="name">Name </label>
    <input type="text" class="form-control" id="name" name="name">
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" id="description" name="description">
  </div>
  <div class="form-group">
    <label for="price">Price</label>
    <input type="number" class="form-control" id="price" name="price">
  </div>

  <div class="form-group">
    <label for="thumbnail">Thumnail</label>
    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
  </div>

  <br>
  <button type="submit" class="btn btn-primary">Save</button>
</form>
</div>

<?php
include_once 'app/views/share/footer.php'
?>