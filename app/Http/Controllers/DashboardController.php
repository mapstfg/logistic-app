<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'ADMIN' => redirect()->route('admin.users.index'),
            'MEDICO' => redirect()->route('medico.prescriptions.index'),
            'FARMACIA' => redirect()->route('farmacia.delivery.index'),
            'BODEGA' => redirect()->route('bodega.stock.index'),
            default => abort(403),
        };
    }
}
