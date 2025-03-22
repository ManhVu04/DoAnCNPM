<?php
require_once 'app/models/CustomerModel.php';
require_once 'app/helpers/SessionHelper.php';

class AuthController {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new CustomerModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ email và mật khẩu';
                include 'app/views/auth/login.php';
                return;
            }

            $customer = $this->customerModel->login($email);
            
            if ($customer && password_verify($password, $customer['password'])) {
                SessionHelper::setCustomer($customer);
                header('Location: /tets1/Movie/list');
                exit();
            } else {
                $error = 'Email hoặc mật khẩu không chính xác';
                include 'app/views/auth/login.php';
            }
        } else {
            include 'app/views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'phone_number' => $_POST['phone_number'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];

            // Validate dữ liệu
            $errors = [];
            if (empty($data['first_name'])) $errors[] = 'Vui lòng nhập họ';
            if (empty($data['last_name'])) $errors[] = 'Vui lòng nhập tên';
            if (empty($data['email'])) $errors[] = 'Vui lòng nhập email';
            if (empty($data['password'])) $errors[] = 'Vui lòng nhập mật khẩu';
            if (strlen($data['password']) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            if (empty($data['phone_number'])) $errors[] = 'Vui lòng nhập số điện thoại';

            if (!empty($errors)) {
                include 'app/views/auth/register.php';
                return;
            }

            // Mã hóa mật khẩu
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $result = $this->customerModel->register($data);
            
            if (isset($result['error'])) {
                $error = $result['error'];
                include 'app/views/auth/register.php';
            } else {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                header('Location: /tets1/Auth/login');
                exit();
            }
        } else {
            include 'app/views/auth/register.php';
        }
    }

    public function logout() {
        SessionHelper::destroy();
        header('Location: /tets1/Auth/login');
        exit();
    }

    public function profile() {
        SessionHelper::requireLogin();
        
        $customer = $this->customerModel->getCustomerById(SessionHelper::get('customer_id'));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $_POST['first_name'] ?? $customer['first_name'],
                'last_name' => $_POST['last_name'] ?? $customer['last_name'],
                'phone_number' => $_POST['phone_number'] ?? $customer['phone_number'],
                'address' => $_POST['address'] ?? $customer['address']
            ];

            $result = $this->customerModel->updateProfile($customer['customer_id'], $data);
            
            if (isset($result['error'])) {
                $error = $result['error'];
            } else {
                $success = 'Cập nhật thông tin thành công!';
                $customer = array_merge($customer, $data);
            }
        }

        include 'app/views/auth/profile.php';
    }
} 