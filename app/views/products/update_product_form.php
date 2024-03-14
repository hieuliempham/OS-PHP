<?php
// Lấy thông tin sản phẩm từ cơ sở dữ liệu dựa trên ID
$id = $_GET['id'];
// Code để lấy thông tin sản phẩm theo ID và hiển thị trong form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cập nhật sản phẩm</title>
</head>
<body>
    <h1>Cập nhật sản phẩm</h1>
    <form action="/MVC/product/updateProduct" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <!-- Hiển thị thông tin sản phẩm trong các input của form -->
        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" id="name" value="<?php echo $product['name']; ?>"><br>
        <label for="description">Mô tả:</label>
        <input type="text" name="description" id="description" value="<?php echo $product['description']; ?>"><br>
        <label for="price">Giá:</label>
        <input type="text" name="price" id="price" value="<?php echo $product['price']; ?>"><br>
        <label for="image">Ảnh:</label>
        <input type="file" name="image" id="image"><br>
        <button type="submit" name="update">Cập nhật</button>
    </form>
</body>
</html>
