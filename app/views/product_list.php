<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sản phẩm</title>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Ảnh</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><img src="../<?php echo $row['image']; ?>" alt="Product Image" style="max-width: 100px;"></td>
                <td>

                    <!-- Form để chuyển hướng đến trang cập nhật sản phẩm -->
                    <form action="/MVC/product/moveToUpdateProduct" method="post">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="update">Cập nhật</button>
                    </form>
              
                    <button onclick="deleteProduct(<?php echo $row['id']; ?>)">Xóa</button>
                   
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <!-- Nút để hiển thị/ẩn form thêm sản phẩm -->
    <button onclick="toggleAddForm()">Thêm sản phẩm</button>
    <!-- Form để thêm sản phẩm mới -->
    <div id="addProductForm" style="display: none;">
        <h2>Thêm sản phẩm mới</h2>
        <form action="/MVC/product/addProduct" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Tên sản phẩm">
            <input type="text" name="description" placeholder="Mô tả">
            <input type="text" name="price" placeholder="Giá">
            <input type="file" name="image" accept=".jpg, .png" multiple>
            <button type="submit" name="add">Thêm</button>
        </form>
    </div>

    <!-- Script -->
    <script>
        function toggleAddForm() {
            var form = document.getElementById("addProductForm");
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

        function deleteProduct(productId) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            // Gửi yêu cầu xóa sản phẩm bằng AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/MVC/product/deleteProduct?id=" + productId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Xóa thành công, reload trang
                    location.reload();
                }
            };
            xhr.send();
        }
    }
        function updateProduct(productId) {
            window.location.href = "update_product.php?id=" + productId;
        }
    </script>
</body>
</html>
