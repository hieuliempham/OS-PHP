<?php
    class AccountController{
        private $db;
        private $accountModel;

        public function __construct() {
            $this->db = (new Database())->getConnection();
            $this->accountModel = new AccountModel($this->db);
        }
        function register(){
            include_once("app/views/account/register.php");
        }
        function save(){
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $email = $_POST['email'] ?? '';
                $name = $_POST['name'] ?? '';
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirmPassword'] ?? '';

                $errors = [];
                if(empty($email)){
                    $errors['email'] = 'Vui long nhap Email';
                }
                if(empty($name)){
                    $errors['name'] = 'Vui long nhap Full Name';
                }
                if(empty($password)){
                    $errors['password'] = 'Vui long nhap Password';
                }
                if(empty($confirmPassword)){
                    $errors['confirmPassword'] = 'Mật khẩu và xác nhân MK phải giống nhau';
                    
                }

                // kiểm tra EMAIL đã tồn tại trong CSDL chưa
                $emailExist = $this->accountModel->getAccountByEmail($email);
                if($emailExist){
                    $errors['ExistEmail'] = "Email tài khoản đã tồn tại!";
                }

                if(count(($errors)) > 0){
                    
                    include_once("app/views/account/register.php");
                }else{
                    // ma hoa mat khau
                    
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    $result = $this->accountModel->createAccount($email, $name, $hashedPassword);
                    if($result){
                        header('Location: /mvc/account/login');
                    }else{
                        $errors['sql'] = "Lỗi Server không thể truy vấn";
                        include_once("app/views/account/register.php");
                    }
                }
            }
        }

        function login(){
            include_once "app/views/account/login.php";
        }

        function checkLogin(){
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';

                $errors = [];
                if(empty($email)){
                    $errors['email'] = 'Vui long nhap Email';
                }
                
                if(empty($password)){
                    $errors['password'] = 'Vui long nhap Password';
                }
                

                // kiểm tra EMAIL đã tồn tại trong CSDL chưa
                $account = $this->accountModel->getAccountByEmail($email);
                if($account && password_verify($password, $account->password)){
                    //dung tai khoan
                    $_SESSION['username'] = $account->email;
                    $_SESSION['role'] = $account->role;
                    $_SESSION['name'] = $account->name;
                   
                    header("Location: /mvc");
                
                }else{
                    $errors['ExistEmail'] = "Tai khoan khong ton tai";
                    include_once("app/views/account/login.php");
                }
            }
        }

        function logout(){
            unset($_SESSION["username"]);
            unset($_SESSION["role"]);
            unset($_SESSION["name"]);
            include_once "app/views/account/login.php";
        }
    }
?>