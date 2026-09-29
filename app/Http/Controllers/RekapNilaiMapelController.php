<?php

namespace App\Http\Controllers;

use App\Services\RekapNilaiMapelService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekapNilaiMapelController extends ApiController
{
    public function __construct(
        private readonly RekapNilaiMapelService $rekapNilaiMapelService
    ) {
    }

    public function index(Request $request): View
    {
        $data = $this->rekapNilaiMapelService->getIndexData($request);

        return view('rekap-nilai-mapel', $data);
    }
}
