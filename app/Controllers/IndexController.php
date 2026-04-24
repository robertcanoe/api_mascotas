<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\MascotaService;

class IndexController extends BaseController
{
    private MascotaService $mascotaService;

    public function __construct()
    {
        $this->mascotaService = new MascotaService();
    }

    public function homeAction(array $request = []): void
    {
        $mascotas = $this->mascotaService->listMascotas();

        $this->renderHTML('index/home', [
            'title' => 'Protectora de Mascotas',
            'mascotas' => $mascotas,
        ]);
    }
}
