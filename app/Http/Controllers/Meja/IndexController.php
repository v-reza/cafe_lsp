<?php

namespace App\Http\Controllers\Meja;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function renderJson()
    {
        $data = Meja::where('status', 'kosong')->get();
        return $this->resJson(false, ['data' => $data], 200);
    }
}
