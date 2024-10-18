<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UploadFileService
{
    public function upload($file, $folder)
    {
        $folderPath = $folder . now()->format('Y-m-d');
        $filename = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uploadedFile = Cloudinary::upload($file->getRealPath(), [
            'folder' => $folderPath,
            'public_id' => $filename,
        ]);
        $uploadedFileUrl = $uploadedFile->getSecurePath();

        return [
            'file' => $uploadedFile,
            'url' => $uploadedFileUrl
        ];
    }

    // xóa ảnh khi gọi function bị lỗi
    public function destroy($url, $file)
    {
        if (isset($url)) {
            $uploadedFilePublicId = $file->getPublicId();
            Cloudinary::destroy($uploadedFilePublicId);
        }

        return;
    }

    // xóa ảnh khi mà update ảnh
    public function destroyImage($url)
    {
        Cloudinary::destroy($url);
        return;
    }
}
