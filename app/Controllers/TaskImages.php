<?php
namespace App\Controllers;

use App\Models\TaskImage;

class TaskImages extends BaseController
{
    protected $taskImageModel;

    public function __construct()
    {
        $this->taskImageModel = new TaskImage();
    }

    // Serve image from writable/task_image/ by filename
    public function view($filename)
    {
        $folder = WRITEPATH . 'task_image/';
        $filePath = $folder . $filename;
        if (!is_file($filePath)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Image not found.'
            ]);
        }
        $mime = mime_content_type($filePath);
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
            ->setBody(file_get_contents($filePath));
    }

    // Upload image for a task
    public function upload()
    {
        $taskId = $this->request->getPost('task_id');
        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No file uploaded.'
            ]);
        }
        $folder = WRITEPATH . 'task_image/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        $newName = $file->getRandomName();
        $file->move($folder, $newName);
        $this->taskImageModel->addImage([
            'task_id' => $taskId,
            'file_name' => $file->getClientName(),
            'file_address' => 'task_image/' . $newName,
        ]);
        return $this->response->setJSON([
            'success' => true,
            'file_address' => 'task_image/' . $newName,
            'file_name' => $file->getClientName()
        ]);
    }

    // List all images for a task
    public function list($taskId)
    {
        $images = $this->taskImageModel->getImagesByTask($taskId);
        return $this->response->setJSON([
            'success' => true,
            'images' => $images
        ]);
    }

    // Delete an image by ID
    public function delete($id)
    {
        $image = $this->taskImageModel->getImage($id);
        if ($image) {
            $filePath = WRITEPATH . $image['file_address'];
            if (is_file($filePath)) {
                unlink($filePath);
            }
            $this->taskImageModel->deleteImage($id);
            return $this->response->setJSON([
                'success' => true
            ]);
        }
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Image not found.'
        ]);
    }
}
