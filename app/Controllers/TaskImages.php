<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TaskImage;

class TaskImages extends BaseController
{

    // Serve image from writable/task_image/ by filename
    public function view($filename)
    {
        $folder = WRITEPATH . 'task_image/';
        $filePath = $folder . $filename;
        if (!is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Image not found");
        }
        $mime = mime_content_type($filePath);
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
            ->setBody(file_get_contents($filePath));
    }

    public function upload()
    {
        $taskId = $this->request->getPost('task_id');
        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'No file uploaded.']);
        }
        $folder = WRITEPATH . 'task_image/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        $newName = $file->getRandomName();
        $file->move($folder, $newName);
        $model = new TaskImage();
        $model->insert([
            'task_id' => $taskId,
            'file_name' => $file->getClientName(),
            'file_address' => 'task_image/' . $newName,
        ]);
        return $this->response->setJSON(['success' => true, 'file_address' => 'task_image/' . $newName, 'file_name' => $file->getClientName()]);
    }

    public function list($taskId)
    {
        $model = new TaskImage();
        $images = $model->where('task_id', $taskId)->findAll();
        return $this->response->setJSON(['success' => true, 'images' => $images]);
    }

    public function delete($id)
    {
        $model = new TaskImage();
        $image = $model->find($id);
        if ($image) {
            $filePath = WRITEPATH . $image['file_address'];
            if (is_file($filePath)) {
                unlink($filePath);
            }
            $model->delete($id);
            return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Image not found.']);
    }
}
