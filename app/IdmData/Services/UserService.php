<?php

namespace App\IdmData\Services;

use App\Exceptions\User\UserAlreadyHasAnImageException;
use App\Facades\Repository;
use App\IdmData\Contracts\Services\UserService as ServiceContract;
use Awesome\Filesystem\Contracts\FileService;
use Illuminate\Database\Eloquent\Model;
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
        if (!empty(Repository::user()->getById($id)->image_id)) {
            throw new UserAlreadyHasAnImageException();
        }

        $fileModel = $this->fileService->create($file, $this->keyPath);

        if ($this->updateUserImage($id, $fileModel->id)) {
            return $fileModel;
        }

        return null;
    }

    private function updateUserImage(string $id, ?string $imageId): bool
    {
        return Repository::user()->update($id, [
            'image_id' => $imageId
        ]);
    }
}
