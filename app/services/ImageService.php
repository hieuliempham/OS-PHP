<?php
class ImageService {
    private $uploadDir = 'public/images/';

    public function uploadImage($file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadFile = $this->uploadDir . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                return $uploadFile;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function deleteImage($filePath) {
        if (file_exists($filePath)) {
            unlink($filePath);
            return true;
        } else {
            return false;
        }
    }

    // Các phương thức xử lý tệp ảnh khác có thể được thêm vào ở đây
}
?>
