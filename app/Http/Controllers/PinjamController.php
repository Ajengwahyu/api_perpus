<?php

namespace App\Http\Controllers;
use App\Pinjam;   
use App\Book;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PinjamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($limit = 10, $offset = 0){
        $data["count"] = Pinjam::count();
        $pinjam = array();
        foreach (Pinjam::take($limit)->skip($offset)->get() as $p) {
            $item = [
                "id"           => $p->id,
                "id_peminjam"   => $p->id_peminjam,
                "id_buku"       => $p->id_buku,
                "name"         => $p->name,
                "jumlah_pinjam"=> $p->jumlah_pinjam,
                "tanggal_pinjam"=> $p->tanggal_pinjam,
                "tanggal_kembali"=> $p->tanggal_kembali,
                "status"       =>$p->status,
                "denda"        =>$p->denda,
                "created_at"   => $p->created_at,
                "updated_at"   => $p->updated_at,
            ];
            

            array_push($pinjam, $item);
        }
        $data["pinjam"] = $pinjam;
        $data["status"] = 1;
        // $temp=$data["stock"];
        // $data["stock_sum"] = array_sum($temp);
        return response($data);
    }
    public function index(Request $request)
    {
        $userid = $request->peminjam; 
        $user                   = User::where('id', $userid)->first();
        $nameuser               = $user->name;
        $book                   = Book::where('id', $request->id)->first();
        $terpinjam              = $book->stok - $request->jumlah_pinjam;
        $book->pinjam         = $book->pinjam + $request->jumlah_pinjam;
        if($book->stok > $request->jumlah_pinjam ){ 
            $book->stok = $book->stok - $request->jumlah_pinjam;
            $book->save();
            $pinjam = new Pinjam();
            $pinjam->id_peminjam 	    = $userid;
            $pinjam->id_buku      	    = $request->id;
            $pinjam->name 	            = $nameuser;
            $pinjam->jumlah_pinjam      = $request->jumlah_pinjam;
            $pinjam->status             = "pinjam";
            $pinjam->tanggal_pinjam     = date("y/m/d");
            $pinjam->denda              = 0; 
            $pinjam->tanggal_kembali    = null;
            $pinjam->save();
            

            return response()->json([
                'status'	=> '1',
                'message'	=> 'Buku berhasil pinjam'
            ], 201);
        }
        else{
            return response()->json([
                'status'	=> '0',
                'message'	=> ' tidak cukup'
            ], 201);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kembali(Request $request)
    {
        $pinjam = Pinjam::where('id', $request->id)->first();
        $book   = Book::where('id', $pinjam->idbuku)->first();
        $pinjam->status = "Kembali";
        $book->stok = $book->stok + $pinjam->jumlah_pinjam;
        $book->pinjam = $book->pinjam - $pinjam->jumlah_pinjam;
        $book->save();
        $pinjam->save();
        return response()->json([
            'status'	=> '1',
            'message'	=> 'Buku berhasil dikembalikan'
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
