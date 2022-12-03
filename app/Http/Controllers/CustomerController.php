<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListBarang;
use App\Models\Customer;

class CustomerController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin"
            ], 404);
        }
        $customer = Customer::all();
        return $customer;
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
    public function pushCustomer(Request $request)
    {
        $customerByName = ListBarang::where('nama_barang', $request['barang_dibeli'])->first();
        if (!$customerByName){
            return response()->json([
                'status' => 400, 
                'message' => "Barang tidak di temukan"
            ], 400);
        }
        $customer = new Customer();
        $customer->nama = $request->input('nama');
        $customer->email = $request->input('email');
        $customer->alamat = $request->input('alamat');
        $customer->no_hp = $request->input('no_hp');
        $customer->barang_dibeli = $request->input('barang_dibeli');
        $customer->jml_dibeli = $request->input('jml_dibeli');
        $customer->harus_dibayar = $customerByName->harga * $request->input('jml_dibeli');
        $customer->save();

        return response()->json([
            'status' => 201, 
            'data' => $customer
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
        $customer = Customer::find($id);
        if ($customer) {
            return response()->json([
                'status' => 200, 
                'data' => $customer
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message'=> 'id atas ' . $id , ' tidak ditemukan'
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
    public function updateCustomer(Request $request, $id)
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'user'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan user"
            ], 404);
        }
        $customerByName = ListBarang::where('id', $request['id_barang'])->first();
        $customer = Customer::find($id);

        if($customer){
            $customer->nama = $request->input('nama') ? : $customer->nama;
            $customer->email = $request->input('email') ? : $customer->email;
            $customer->alamat = $request->input('alamat') ? : $customer->alamat;
            $customer->no_hp = $request->input('no_hp') ? : $customer->no_hp;
            $customer->jml_dibeli = $request->input('jml_dibeli') ? $request->input('jml_dibeli') : $customer->jml_dibeli ;
            $customer->harus_dibayar = $request->input('jml_dibeli') ? $customerByName->harga * $request->input('jml_dibeli') : $customer->harus_dibayar;
            $customer->save();
            return response()->json([
                'status' => 200,
                'data' => $customer
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

        $customer = Customer::where('id', $id)->first();
        if($customer){
            $customer->delete();
            return response()->json([
                'status' => 200, 
                'message'=> 'id ' . $id . ' berhasil di hapus',
                'data' => $customer
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'id' . $id . ' tidak ditemukan'
            ], 404);
        }
    }
}
