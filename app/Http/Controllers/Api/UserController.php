<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  public function index()
  {
    return DB::table('users')->orderByDesc('id')->get();
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'full_name' => 'required|string|max:255',
      'cedula' => 'required|string|max:50|unique:users,cedula',
      'cargo' => 'nullable|string|max:255',
      'oficina' => 'nullable|string|max:255',
      'embedding' => 'nullable|array',
      'photo_path' => 'nullable|string|max:500',
    ]);

    $id = DB::table('users')->insertGetId([
      'full_name' => $data['full_name'],
      'cedula' => $data['cedula'],
      'cargo' => $data['cargo'] ?? null,
      'oficina' => $data['oficina'] ?? null,
      'embedding' => isset($data['embedding']) ? json_encode($data['embedding']) : null,
      'photo_path' => $data['photo_path'] ?? null,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    return response()->json(['id' => $id], 201);
  }
}

