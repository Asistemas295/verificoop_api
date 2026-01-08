<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
  public function index(Request $request)
  {
    // filtros opcionales: ?from=2026-01-01&to=2026-01-08
    $q = DB::table('attendance as a')
      ->join('users as u', 'u.id', '=', 'a.user_id')
      ->select(
        'a.id','a.timestamp','a.type',
        'u.id as user_id','u.full_name','u.cedula','u.cargo','u.oficina'
      )
      ->orderByDesc('a.id');

    if ($request->filled('from')) $q->where('a.timestamp', '>=', $request->input('from'));
    if ($request->filled('to'))   $q->where('a.timestamp', '<=', $request->input('to'));

    return $q->get();
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'user_id' => 'required|integer|exists:users,id',
      'timestamp' => 'required|date',
      'type' => 'required|in:IN,OUT',
    ]);

    $id = DB::table('attendance')->insertGetId([
      'user_id' => $data['user_id'],
      'timestamp' => $data['timestamp'],
      'type' => $data['type'],
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    return response()->json(['id' => $id], 201);
  }
}

