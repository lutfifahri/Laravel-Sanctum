<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** nampilkan datanya */
        $data = product::orderBy('name', 'asc')->paginate(5);
        return response()->json([
            'status'  => true,
            'message' => 'data ditemukan',
            'data'    => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new product();
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'gagal masukan data',
                'data'  => $validator->errors()
            ], 401);
        }
        $data->name = $request->name;
        $data->price = $request->price;
        $data->description = $request->description;
        $data->save();

        return response()->json([
            'status' => true,
            'message'   => 'berhasil memasukan data'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        /** Menampilkan data satu per satu */
        $data = product::find($id);
        if ($data) {
            return response()->json([
                'status'    => true,
                'message'   => 'Data Ditemukan',
                'data'      => $data 
            ], 200);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak di temukan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /** Update data sesuai yang igin di update */
        $data = product::find($id);
        if (empty($data)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak di temukan'
            ], 404);
        }

        $rules = [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'gagal masukan Update data',
                'data'  => $validator->errors()
            ], 401);
        }
        $data->name = $request->name;
        $data->price = $request->price;
        $data->description = $request->description;
        $data->save();

        return response()->json([
            'status' => true,
            'message'   => 'berhasil memasukan Update data'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /** Menghapus data nya  */
        $data = product::find($id);
        if (empty($data)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak di temukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Sukses melakukan  delete data'
        ]);
    }
}
