<?php
class ProductController {
    public function listProducts() {
        $database = new Database();
        $db = $database->getConnection();

        $product = new ProductModel($db);
        $stmt = $product->readAll();

        include_once 'app/views/products/product_list.php';
    }

    public function add($id) {
        include_once 'app/views/products/create.php';
    }

    public function addProduct() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Xử lý thêm sản phẩm vào cơ sở dữ liệu ở đây
            $database = new Database();
            $db = $database->getConnection();
    
            // Kiểm tra xem các trường đã được gửi từ biểu mẫu hay không
            if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_FILES['image'])) {
                $product = new ProductModel($db);
    
                // Lấy thông tin từ biểu mẫu
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
    
                // Xử lý tệp ảnh
                $targetDir = "public/images/";
                $fileName = basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
    
                // Thêm sản phẩm vào cơ sở dữ liệu
                $product->name = $name;
                $product->description = $description;
                $product->price = $price;
                $product->image = $targetFilePath;
    
                if ($product->create()) {
                    // Chuyển hướng về trang hiển thị thông tin sản phẩm
                    header("Location: /MVC/product/listProducts");
                    echo "Sản phẩm đã được thêm thành công.";
                } else {
                    echo "Có lỗi xảy ra khi thêm sản phẩm.";
                }
            } else {
                echo "Vui lòng điền đầy đủ thông tin sản phẩm.";
            }
        } else {
            // Hiển thị biểu mẫu thêm sản phẩm
            include_once 'app/views/products/product_list.php';
        }
    }
    
    public function getProduct($id) {
        $database = new Database();
        $db = $database->getConnection();

        $product = new ProductModel($db);
        $product->id = $id;
        $product->readOne();

        // Tạo logic để hiển thị thông tin chi tiết sản phẩm hoặc chuyển hướng đến trang khác
    }

    public function moveToUpdateProduct() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            // Lấy ID của sản phẩm cần cập nhật
            $id = $_POST['id'];
    
            include_once "app/views/products/update_product_form.php";
            exit();
        }
    }
    
    public function updateProduct() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            // Lấy thông tin sản phẩm cần cập nhật từ biểu mẫu
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image = $_FILES['image']; // Xử lý tệp ảnh tại đây nếu cần
    
            // Gọi đến model để cập nhật thông tin sản phẩm trong database
            $database = new Database();
            $db = $database->getConnection();
    
            $product = new ProductModel($db);
            $product->id = $id;
            $product->name = $name;
            $product->description = $description;
            $product->price = $price;
            $product->image = $image;
    
            // Thực hiện cập nhật sản phẩm
            if ($product->update()) {
                // Cập nhật thành công, có thể chuyển hướng hoặc hiển thị thông báo
                echo "Cập nhật sản phẩm thành công.";
            } else {
                // Cập nhật không thành công, có thể hiển thị thông báo lỗi
                echo "Cập nhật sản phẩm không thành công.";
            }
        }
    }
       
    

    public function deleteProduct() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $database = new Database();
            $db = $database->getConnection();
    
            $product = new ProductModel($db);
            $product->id = $id;
    
            if ($product->delete()) {
                echo "Sản phẩm đã được xóa thành công.";
            } else {
                echo "Xóa sản phẩm không thành công.";
            }
        } else {
            // Xử lý khi không có yêu cầu POST hoặc không có ID
            echo "Yêu cầu không hợp lệ.";
        }
    }
    
}
    
?>