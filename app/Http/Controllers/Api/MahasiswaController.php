<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function createMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|min:3|max:100',
            'npm' => [
                'required',
                'string',
                'size:11',
                'regex:/^[0-9]+$/'
            ],
            'alamat' => 'required|string',
            'program_studi' => [
                'required',
                'string',
            ]
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',

            'npm.required' => 'NPM wajib diisi',
            'npm.size' => 'NPM harus 11 karakter',
            'npm.regex' => 'NPM hanya boleh berisi angka',

            'alamat.required' => 'Alamat wajib diisi',

            'program_studi.required' => 'Program studi wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 422
            ], 422);
        }

        if (MahasiswaModel::where('npm', $request->npm)->exists()) {
            return response([
                'message' => 'error',
                'data' => 'NPM sudah terdaftar',
                'status' => 400
            ]);
        } else {
            $mahasiswa = MahasiswaModel::create([
                'nama' => $request->nama,
                'npm' => $request->npm,
                'alamat' => $request->alamat,
                'program_studi' => $request->program_studi,
            ]);
        }

        return response([
            'message' => 'success',
            'data' => [
                'id' => $mahasiswa->id,
                'nama' => $mahasiswa->nama,
                'npm' => $mahasiswa->npm,
                'alamat' => $mahasiswa->alamat,
                'program_studi' => $mahasiswa->program_studi,
                'tanggal_input' => $mahasiswa->created_at,
            ],
            'status' => 201
        ]);
    }

    public function getAllMahasiswa()
    {
        $mahasiswa = MahasiswaModel::paginate(10);

        if ($mahasiswa->isNotEmpty()) {
            return response([
                'message' => 'success',
                'data' => $mahasiswa
            ]);
        } else {
            return response([
                'message' => 'error',
                'data' => 'Tidak ada data mahasiswa'
            ]);
        }
    }

    public function getMahasiswa($npm, Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        $mahasiswa = MahasiswaModel::where('npm', $npm)->first();
        if ($mahasiswa) {
            return response([
                'message' => 'success',
                'data' => $mahasiswa,
                'status' => 200
            ]);
        } else {
            return response([
                'message' => 'error',
                'data' => 'Data tidak ditemukan',
                'status' => 404
            ]);
        }
    }

    public function updateMahasiswa(Request $request, $npm)
    {
        try {
            $mahasiswa = MahasiswaModel::where('npm', $npm)->first();

            if (!$mahasiswa) {
                return response()->json([
                    'message' => 'error',
                    'data' => 'Mahasiswa tidak ditemukan',
                    'status' => 404
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|min:3|max:100',
                'npm' => [
                    'required',
                    'string',
                    'size:11',
                    'regex:/^[0-9]+$/'
                ],
                'alamat' => 'required|string',
                'program_studi' => [
                    'required',
                    'string',
                ]
            ], [
                'nama.required' => 'Nama mahasiswa wajib diisi',

                'npm.required' => 'NPM wajib diisi',
                'npm.size' => 'NPM harus 11 karakter',
                'npm.regex' => 'NPM hanya boleh berisi angka',

                'alamat.required' => 'Alamat wajib diisi',

                'program_studi.required' => 'Program studi wajib diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                    'status' => 422
                ], 422);
            }

            if (MahasiswaModel::where('npm', $request->npm)->where('npm', '!=', $npm)->exists()) {
                return response()->json([
                    'message' => 'error',
                    'data' => 'NPM sudah terdaftar',
                    'status' => 400
                ], 400);
            } else {
                $mahasiswa->nama = $request->nama;
                $mahasiswa->npm = $request->npm;
                $mahasiswa->alamat = $request->alamat;
                $mahasiswa->program_studi = $request->program_studi;
                $mahasiswa->save();
            }

            return response()->json([
                'message' => 'success',
                'data' => $mahasiswa,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error',
                'data' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function deleteMahasiswa($id)
    {
        try {
            $mahasiswa = MahasiswaModel::find($id);
            if (!$mahasiswa) {
                return response()->json([
                    'message' => 'error',
                    'data' => 'Mahasiswa tidak ditemukan',
                    'status' => 404
                ], 404);
            }
            $mahasiswa->delete();
            return response()->json([
                'message' => 'success',
                'data' => 'Mahasiswa berhasil dihapus',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error',
                'data' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
