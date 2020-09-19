<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Illuminate\Support\Facades\Validator; //ditambah

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Book::all();

        return response($data);
    }

    
    public function getAll($limit = 10, $offset = 0){
        $data["count"] = Book::count();
        $book = array();

        foreach (Book::take($limit)->skip($offset)->get() as $p) {
            $item = [
                "id"          => $p->id,
                "title"       => $p->title,
                "description" => $p->description,
                //ditambah
                "stok"         => $p->stok,
                "pinjam"        => $p->pinjam,
                
                "created_at"  => $p->created_at,
                "updated_at"  => $p->updated_at
            ];

            array_push($book, $item);
        }
        //ditambah
        $data['sum'] = Book::sum('stok');
        $data['sumpinjam'] = Book::sum('pinjam');
        $data["book"] = $book;
        $data["status"] = 1;
        return response($data);
    }

    public function find(Request $request, $limit = 10, $offset = 0)
    {
        $find = $request->find;
        $book = Book::where("id","like","%$find%")
        ->orWhere("title","like","%$find%")
        ->orWhere("description","like","%$find%");
        $data["count"] = $book->count();
        $books = array(); //
        foreach ($book->skip($offset)->take($limit)->get() as $p) {
          $item = [
            "id" => $p->id,
            "title" => $p->title,
            "description" => $p->description,
            //ditambah
            "stok"         => $p->stok,
            "pinjam"        => $p->pinjam,
            "created_at" => $p->created_at,
            "updated_at" => $p->updated_at
          ];
          array_push($books,$item);
        }
        $data["book"] = $books;
        $data["status"] = 1;
        return response($data);
    }

    public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'title'         => 'required|string|max:255',
			'description'   => 'required|max:255',
			'stok' => 'required|integer',
		]);

		if($validator->fails()){
			return response()->json([
				'status'	=> 0,
				'message'	=> $validator->errors()->toJson()
			]);
		}

		$book = new Book();
		$book->title        = $request->title;
		$book->description  = $request->description;
        $book->stok         = $request->stok;
		$book->pinjam       = 0;        
		$book->save();

		return response()->json([
			'status'	=> '1',
			'message'	=> 'Buku berhasil terregistrasi'
			//'user'		=> $user,
		], 201);
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $data = new Book();
            $data->title = $request->input('title');
            $data->description = $request->input('description');
            $data->save();

            return response()->json([
                'status' => '1',
                'message' => 'Tambah data buku berhasil!',
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Book::where('id', $id)->get();
        return response($data);
    }

    public function update(Request $request) //ubah->update
    {
        try {
        $data = Book::where('id', $request->id)->first();
            $data->title = $request->input('title');
            $data->description = $request->input('description');
            $data->save();

            return response()->json([
                'status' => '1',
                'message' => 'Ubah data buku berhasil!',
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function ubah(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
			'description' => 'required|max:255',
            'stok' => 'required|integer',
		]);

		if($validator->fails()){
			return response()->json([
				'status'	=> '0',
				'message'	=> $validator->errors()
			]);
		}

		//proses update data
		$book = Book::where('id', $request->id)->first();
		$book->title 	        = $request->title;
		$book->description 	    = $request->description;
        $book->stok            = $request->stok; 
		$book->save();

		return response()->json([
			'status'	=> '1',
			'message'	=> 'Buku berhasil diubah'
		], 201);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $data = Book::where('id', $id)->first();
            $data->delete();

            return response()->json([
                'status' => '1',
                'message' => 'Hapus data buku berhasil!',
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }
}
