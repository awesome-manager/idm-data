<?php

namespace App\IdmData\Services;

use App\Facades\Repository;
use App\IdmData\Contracts\Services\UserService as ServiceContract;
use Awesome\Filesystem\Contracts\FileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\File;

class UserService implements ServiceContract
{
    private FileService $fileService;
    private string $keyPath = 'user_image';

    public function __construct(FileService $service)
    {
        $this->fileService = $service;
    }

    public function updateUserInfo(string $id, array $parameters): bool
    {
        return Repository::user()->update($id, [
            'name' => $parameters['name'],
            'surname' => $parameters['surname'],
            'second_name' => $parameters['second_name'] ?? null,
            'phone' => $parameters['phone'],
            'email' => $parameters['email'] ?? null
        ]);
    }

    public function createUserImage(string $id, File $file): ?Model
    {
        $user = Repository::user()->getById($id);
        if (!empty($user?->image_id)) {
            $this->fileService->update($user->image_id, $file);

            return $user->image;
        }

        $fileModel = $this->fileService->create($file, $this->keyPath);
        if ($this->updateUserImageId($id, $fileModel->id)) {
            return $fileModel;
        }

        return null;
    }

    public function deleteUserImage(string $id): bool
    {
        $userImageId = Repository::user()->getById($id)?->image_id;
        if (!empty($userImageId)) {
            $this->deleteUserImageFile($userImageId);
        }

        return $this->updateUserImageId($id, null);
    }

    private function updateUserImageId(string $id, ?string $imageId): bool
    {
        return Repository::user()->update($id, [
            'image_id' => $imageId
        ]);
    }

    private function deleteUserImageFile(string $imageId): void
    {
        try {
            $this->fileService->delete($imageId);
        } catch (\Throwable $e) {
            Log::error('Cant delete user image from file storage: ', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
