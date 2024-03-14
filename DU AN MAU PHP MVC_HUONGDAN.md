Để xây dựng một ứng dụng bán hàng đơn giản theo mô hình MVC (Model-View-Controller) trong PHP, bạn có thể làm theo các bước sau:

1. **Tạo Cấu Trúc Thư Mục**: Một cấu trúc thư mục đơn giản cho mô hình MVC có thể như sau:
   ```
   /app
       /controllers
       /models
       /views
   /public
       /css
       /js
       /images
   /config
   .htaccess
   index.php
   ```

2. **Thiết Lập Router và .htaccess**: Sử dụng file `.htaccess` để viết lại URL, điều hướng mọi yêu cầu qua `index.php`. Trong `index.php`, bạn sẽ phân tích URL và gọi đến controller tương ứng.

3. **Tạo Models**: Models sẽ tương tác với cơ sở dữ liệu. Ví dụ, `ProductModel.php` có thể chứa các hàm để lấy thông tin sản phẩm từ cơ sở dữ liệu.

4. **Tạo Controllers**: Controllers sẽ xử lý logic nghiệp vụ. Ví dụ, `ProductController.php` sẽ xử lý các yêu cầu liên quan đến sản phẩm và gọi đến các model tương ứng.

5. **Tạo Views**: Views là các file PHP chứa HTML và sẽ được gọi bởi controllers để hiển thị giao diện người dùng. Ví dụ, `product_list.php` có thể hiển thị danh sách sản phẩm.

6. **Kết Nối CSDL và Tạo Config File**: Tạo một file cấu hình trong thư mục `/config` để quản lý thông tin kết nối cơ sở dữ liệu.

7. **Thiết Lập Session và Authentication**: Điều này quan trọng cho việc quản lý đăng nhập và bảo mật.

8. **Tạo Frontend**: Sử dụng HTML, CSS, và JavaScript để thiết kế giao diện người dùng trong thư mục `/public`.

9. **Testing và Debugging**: Kiểm tra ứng dụng của bạn trên nhiều trình duyệt và môi trường để đảm bảo nó hoạt động đúng cách.

10. **Deployment**: Cuối cùng, triển khai ứng dụng của bạn lên một máy chủ web.

Đây là một khung làm việc cơ bản và bạn có thể tùy chỉnh theo nhu cầu cụ thể của dự án. Nếu bạn mới làm quen với PHP và MVC, bạn cũng có thể xem xét sử dụng một framework sẵn có như Laravel hoặc CodeIgniter để bắt đầu nhanh chóng.

#CONTINUE

### 1. .htaccess
Để điều hướng tất cả các yêu cầu qua `index.php`:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### 2. index.php
Đây là điểm nhập chính:
```php
<?php
require_once 'config/database.php';
require_once 'app/controllers/ProductController.php';
require_once 'app/controllers/UserController.php';
// Yêu cầu thêm các file cần thiết khác

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Kiểm tra phần đầu tiên của URL để xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';

// Kiểm tra phần thứ hai của URL để xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Kiểm tra xem controller và action có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    // Xử lý không tìm thấy controller
    die('Controller not found');
}

require_once 'app/controllers/' . $controllerName . '.php';

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    // Xử lý không tìm thấy action
    die('Action not found');
}

// Gọi action với các tham số còn lại (nếu có)
call_user_func_array([$controller, $action], array_slice($url, 2));

```

### 3. /config/database.php
Kết nối CSDL:
```php
<?php
class Database {
    private $host = "localhost";
    private $db_name = "your_db_name";
    private $username = "your_username";
    private $password = "your_password";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
```

### 4. /app/models/ProductModel.php
Mô hình cho sản phẩm:
```php
<?php
class ProductModel {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    function readAll() {
        $query = "SELECT id, name, description, price FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
```

### 5. /app/controllers/ProductController.php
Controller cho sản phẩm:
```php
<?php
class ProductController {
    public function listProducts() {
        $database = new Database();
        $db = $database->getConnection();

        $product = new ProductModel($db);
        $stmt = $product->readAll();

        include_once 'app/views/product_list.php';
    }
}
```

### 6. /app/views/product_list.php
View hiển thị danh sách sản phẩm:
```php
<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sản phẩm</title>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>
    <ul>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <li>
            <?php echo $row['name'] . " - " . $row['description'] . " - " . $row['price']; ?>
        </li>
    <?php endwhile; ?>
    </ul>
</body>
</html>
```

#CONTINUE 
Xây dựng một hệ thống CRUD (Create, Read, Update, Delete) đầy đủ cho sản phẩm với tính năng xác thực và vai trò admin trong PHP MVC là một tác vụ lớn, nhưng tôi có thể cung cấp một bản tóm tắt và một số đoạn code mẫu để bạn bắt đầu. Để đơn giản hóa, tôi sẽ giả định rằng bạn đã có cơ sở dữ liệu và bảng `products` (với các cột `id`, `name`, `description`, `price`) và bảng `users` (với các cột `id`, `username`, `password`, `role`).

### 1. Authentication và Role Checking

Đầu tiên, bạn cần một hệ thống xác thực người dùng và kiểm tra vai trò. Điều này có thể được thực hiện thông qua một session PHP.

**/app/helpers/SessionHelper.php**
```php
<?php
session_start();

class SessionHelper {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}
```

### 2. ProductController Update

Bây giờ, cập nhật `ProductController` để bao gồm các hành động CRUD và kiểm tra quyền admin cho các hành động nhất định.

**/app/controllers/ProductController.php**
```php
<?php
// Require SessionHelper and other necessary files

class ProductController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function listProducts() {
        $products = $this->productModel->getProducts();
        include_once 'app/views/products/list.php';
    }

    public function createProduct() {
        if (!SessionHelper::isAdmin()) {
            header('Location: login.php');
            exit;
        }
        // Xử lý tạo sản phẩm
    }

    public function updateProduct($id) {
        if (!SessionHelper::isAdmin()) {
            header('Location: login.php');
            exit;
        }
        // Xử lý cập nhật sản phẩm
    }

    public function deleteProduct($id) {
        if (!SessionHelper::isAdmin()) {
            header('Location: login.php');
            exit;
        }
        // Xử lý xóa sản phẩm
    }
}
```

### 3. Cập nhật Model

Cập nhật `ProductModel` để bao gồm các phương thức cho CRUD.

**/app/models/ProductModel.php**
```php
<?php
class ProductModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getProducts() {
        // Truy vấn lấy tất cả sản phẩm
    }

    public function createProduct($name, $description, $price) {
        // Truy vấn tạo sản phẩm mới
    }

    public function updateProduct($id, $name, $description, $price) {
        // Truy vấn cập nhật sản phẩm
    }

    public function deleteProduct($id) {
        // Truy vấn xóa sản phẩm
    }
}
```

### 4. Views

Tạo các file view tương ứng trong `/app/views/products/` cho các hành động như `list`, `create`, `edit`, và `delete`.

### 5. User Authentication

Cuối cùng, bạn cần một cách để xác thực người dùng. Điều này thường bao gồm việc tạo một trang đăng nhập, xác minh thông tin người dùng từ cơ sở dữ liệu, và thiết lập session.

**/app/controllers/UserController.php**
```php
<?php
class UserController {
    private $userModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

        public function login() {
        // Kiểm tra nếu form đăng nhập đã được submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Xác thực người dùng từ cơ sở dữ liệu
            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                // Thiết lập session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];

                // Chuyển hướng người dùng tới trang chủ hoặc dashboard
                header('Location: index.php');
                exit;
            } else {
                // Hiển thị thông báo lỗi
                $error = "Sai thông tin đăng nhập!";
            }
        }

        // Hiển thị form đăng nhập
        include_once 'app/views/users/login.php';
    }

    public function logout() {
        // Hủy tất cả các session
        session_destroy();

        // Chuyển hướng người dùng về trang đăng nhập
        header('Location: login.php');
        exit;
    }
}

```

# Continue

Trong mô hình MVC, việc kiểm tra ràng buộc nhập dữ liệu được thực hiện cả ở phía client (thông qua JavaScript hoặc HTML) và phía server (trong PHP). Dưới đây là cách bạn có thể thực hiện điều này cho việc lưu sản phẩm:

### Kiểm tra Ràng Buộc ở Phía Client

1. **Sử dụng HTML5**: Để kiểm tra đơn giản như bắt buộc nhập, kiểu dữ liệu, v.v.

   **Ví dụ trong HTML Form:**
   ```html
   <form action="save_product.php" method="POST">
       Tên sản phẩm: <input type="text" name="name" required><br>
       Mô tả: <textarea name="description" required></textarea><br>
       Giá: <input type="number" name="price" required min="0"><br>
       <input type="submit" value="Lưu sản phẩm">
   </form>
   ```

2. **Sử dụng JavaScript**: Để thực hiện kiểm tra phức tạp hơn hoặc kiểm tra tùy chỉnh.

   **Ví dụ trong JavaScript:**
   ```javascript
   document.querySelector("form").addEventListener("submit", function(event) {
       var name = document.querySelector("input[name='name']").value;
       var description = document.querySelector("textarea[name='description']").value;
       var price = document.querySelector("input[name='price']").value;

       if (name == '' || description == '' || price == '') {
           alert("Vui lòng nhập tất cả các trường.");
           event.preventDefault(); // Ngăn chặn form được gửi nếu kiểm tra thất bại
       }
   });
   ```

### Kiểm tra Ràng Buộc ở Phía Server

1. **Kiểm tra Đầu vào trong PHP**: Bạn nên kiểm tra mọi dữ liệu đầu vào trước khi xử lý hoặc lưu chúng vào cơ sở dữ liệu.

   **Trong hàm saveProduct:**
   ```php
   function saveProduct($name, $description, $price) {
       // Kiểm tra ràng buộc đầu vào
       if (empty($name) || empty($description) || !is_numeric($price) || $price < 0) {
           return false; // Thất bại nếu dữ liệu không hợp lệ
       }

       // Còn lại của hàm...
   }
   ```

2. **Sử dụng Prepared Statements**: Điều này không chỉ giúp bảo vệ chống lại SQL Injection mà còn có thể hỗ trợ kiểm tra dữ liệu đầu vào.

### Tổng Kết

- **Phía Client**: Kiểm tra ràng buộc ở phía client giúp tạo trải nghiệm người dùng tốt hơn và giảm bớt tải cho server. Tuy nhiên, bạn không nên dựa hoàn toàn vào kiểm tra ở phía client vì nó có thể bị vô hiệu hóa hoặc bỏ qua.

- **Phía Server**: Mọi kiểm tra ràng buộc quan trọng nhất phải diễn ra ở phía server. Điều này đảm bảo rằng dù có bất kỳ sự cố nào ở phía client, dữ liệu đầu vào vẫn được xác thực và an toàn trước khi xử lý hoặc lưu trữ.

Kết hợp cả hai phương pháp kiểm tra này sẽ giúp ứng dụng của bạn an toàn và thân thiện với người dùng hơn.

# Continue

Để hiển thị thông báo lỗi từ phía server trên client trong mô hình MVC, bạn cần gửi lại thông tin lỗi từ server đến trang web và hiển thị nó cho người dùng. Dưới đây là cách bạn có thể thực hiện điều này:

### Bước 1: Kiểm Tra và Tạo Thông Báo Lỗi ở Server
Trong file PHP xử lý dữ liệu (ví dụ, `save_product.php`), bạn kiểm tra các ràng buộc và tạo thông báo lỗi nếu cần.

```php
function saveProduct($name, $description, $price) {
    // Kiểm tra ràng buộc đầu vào
    $errors = [];
    if (empty($name)) {
        $errors['name'] = 'Tên sản phẩm không được để trống';
    }
    if (empty($description)) {
        $errors['description'] = 'Mô tả không được để trống';
    }
    if (!is_numeric($price) || $price < 0) {
        $errors['price'] = 'Giá sản phẩm không hợp lệ';
    }

    if (count($errors) > 0) {
        return $errors;
    }

    // Còn lại của hàm...
}

// Xử lý yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';

    $result = saveProduct($name, $description, $price);

    if (is_array($result)) {
        // Có lỗi, hiển thị lại form với thông báo lỗi
        $errors = $result;
        include 'path/to/product_form.php'; // Đường dẫn đến file form sản phẩm
    } else {
        // Không có lỗi, chuyển hướng hoặc hiển thị thông báo thành công
    }
}
```

### Bước 2: Hiển Thị Lỗi Trên Form
Trong file form (ví dụ, `product_form.php`), hiển thị thông báo lỗi nếu có.

```html
<form action="save_product.php" method="POST">
    Tên sản phẩm: <input type="text" name="name" value="<?php echo $name ?? ''; ?>">
    <?php if (isset($errors['name'])): ?>
        <div class="error"><?php echo $errors['name']; ?></div>
    <?php endif; ?>

    Mô tả: <textarea name="description"><?php echo $description ?? ''; ?></textarea>
    <?php if (isset($errors['description'])): ?>
        <div class="error"><?php echo $errors['description']; ?></div>
    <?php endif; ?>

    Giá: <input type="number" name="price" value="<?php echo $price ?? ''; ?>">
    <?php if (isset($errors['price'])): ?>
        <div class="error"><?php echo $errors['price']; ?></div>
    <?php endif; ?>

    <input type="submit" value="Lưu sản phẩm">
</form>
```

### Bước 3: Tinh chỉnh Style cho Thông Báo Lỗi
Bạn có thể sử dụng CSS để làm cho các thông báo lỗi nổi bật hơn.

```css
.error {
    color: red;
    font-size: 0.8em;
}
```

### Tổng Kết
Quy trình này đảm bảo rằng:
- Ràng buộc dữ liệu được kiểm tra một cách an toàn ở phía server.
- Thông báo lỗi được truyền lại cho người dùng ở phía client một cách rõ ràng.
- Người dùng nhận được phản hồi ngay lập tức trên form mà họ đang làm việc, giúp họ sửa lỗi một cách thuận tiện. 

Đây là cách tiếp cận chuẩn trong mô hình MVC, nơi controller xử lý logic, model xử lý dữ liệu và view hiển thị thông tin cho người dùng.

# Continue
Để thêm chức năng upload hình ảnh đại diện và nhiều hình ảnh cho sản phẩm trong màn hình thêm sản phẩm, bạn cần thực hiện các bước sau:

### Bước 1: Cập Nhật Form HTML để Chấp Nhận Tải Lên File
Trong form thêm sản phẩm, bạn cần thêm các trường `input` với `type='file'`.

```html
<form action="save_product.php" method="POST" enctype="multipart/form-data">
    <!-- Các trường khác... -->

    Hình ảnh đại diện: <input type="file" name="thumbnail"><br>
    Hình ảnh khác: <input type="file" name="images[]" multiple><br>

    <input type="submit" value="Lưu sản phẩm">
</form>
```
Đảm bảo rằng bạn đã thêm thuộc tính `enctype="multipart/form-data"` để form có thể xử lý tải lên file.

### Bước 2: Xử Lý Tải Lên Hình Ảnh trong PHP
Trong file PHP xử lý dữ liệu (ví dụ, `save_product.php`), bạn cần xử lý việc tải lên hình ảnh.

```php
function uploadImage($file) {
    $targetDirectory = "path/to/uploads/";
    $targetFile = $targetDirectory . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Kiểm tra xem file có phải là hình ảnh thực sự hay không
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Kiểm tra kích thước file
    if ($file["size"] > 500000) { // Ví dụ: giới hạn 500KB
        $uploadOk = 0;
    }

    // Kiểm tra định dạng file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Kiểm tra nếu $uploadOk bằng 0
    if ($uploadOk == 0) {
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            return false;
        }
    }
}

// Xử lý yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Xử lý các trường khác...

    // Xử lý tải lên hình ảnh đại diện
    if (isset($_FILES["thumbnail"])) {
        $uploadResult = uploadImage($_FILES["thumbnail"]);
        if ($uploadResult) {
            // Lưu đường dẫn của hình ảnh đại diện vào CSDL
        } else {
            // Lỗi tải lên
        }
    }

    // Xử lý tải lên nhiều hình ảnh
    if (isset($_FILES["images"])) {
        foreach ($_FILES["images"]["name"] as $key => $value) {
            $fileArray = [
                "name" => $_FILES["images"]["name"][$key],
                "type" => $_FILES["images"]["type"][$key],
                "tmp_name" => $_FILES["images"]["tmp_name"][$key],
                "error" => $_FILES["images"]["error"][$key],
                "size" => $_FILES["images"]["size"][$key]
            ];

            $uploadResult = uploadImage($fileArray);
            if ($uploadResult) {
                // Lưu đường dẫn của hình ảnh vào CSDL
            } else {
                // Lỗi tải lên
            }
        }
    }
}
```

### Lưu Ý Quan Trọng
- **Bảo Mật**: Khi tải lên file, luôn kiểm tra định dạng và kích thước của file để tránh các rủi ro bảo mật.
- **Lưu Trữ**: Cần lưu trữ đường dẫn của các file hình ảnh vào

 cơ sở dữ liệu.
- **Cấu Trúc Thư Mục**: Đảm bảo rằng thư mục lưu trữ hình ảnh có quyền ghi đủ để lưu file.
- **Lỗi và Thông Báo**: Xử lý trường hợp file không tải lên được và hiển thị thông báo phù hợp. 

### Bước 3: Hiển Thị Hình Ảnh Trên Giao Diện
Trong giao diện người dùng, bạn sẽ cần hiển thị các hình ảnh đã được tải lên. Bạn có thể truy vấn CSDL để lấy đường dẫn của hình ảnh và hiển thị chúng thông qua thẻ `img` trong HTML.

Đây là cách cơ bản để thêm chức năng tải lên hình ảnh vào mô hình MVC. Tùy vào yêu cầu cụ thể của dự án, bạn có thể cần tinh chỉnh thêm.