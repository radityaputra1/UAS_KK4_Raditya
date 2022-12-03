<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListBarang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $barang = ListBarang::all();
        return $barang;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pushBarang(Request $request)
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin"
            ], 404);
        }
        $barang = new ListBarang();
        $barang->nama_barang = $request->input('nama_barang');
        $barang->stok = $request->input('stok');
        $barang->harga = $request->input('harga');
        $barang->alamat = $request->input('alamat');
        $barang->jenis = $request->input('jenis');
        $barang->save();

        return response()->json([
            'status' => 201, 
            'data' => $barang
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $barang = ListBarang::find($id);
        if ($barang) {
            return response()->json([
                'status' => 200, 
                'data' => $barang
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message'=> 'id atas ' . $id . ' tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBarang(Request $request, $id)
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin"
            ], 404);
        }
        $barang = ListBarang::find($id);

        if($barang){
            $barang->nama_barang = $request->input('nama_barang') ? $request->input('nama_barang') : $barang->nama_barang ;
            $barang->stok = $request->input('stok') ? $request->input('stok') : $barang->stok;
            $barang->harga = $request->input('harga') ? $request->input('harga') : $barang->harga ;
            $barang->alamat = $request->input('alamat') ? $request->input('alamat') : $barang->alamat ;
            $barang->jenis = $request->input('jenis') ? $request->input('jenis') : $barang->jenis ;
            $barang->save();
            return response()->json([
                'status' => 200,
                'data' => $barang
            ], 200);
        } else {
            return response()->json([
                'status'=>404,
                'message'=> $id . ' tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin"
            ], 404);
        }
        $barang = ListBarang::where('id', $id)->first();
        if($barang){
            $barang->delete();
            return response()->json([
                'status' => 200, 
                'message'=> 'id ' . $id . ' berhasil di hapus',
                'data' => $barang
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'id' . $id . ' tidak ditemukan'
            ], 404);
        }
    }
}