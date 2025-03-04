<?php

namespace App\Http\Controllers; //menentukan namespace untuk ItemController

use App\Models\Item; //impor Item pada model agar bisa digunakan controller
use Illuminate\Http\Request; //impor request untuk menangani data yang dikirim
use Illuminate\Routing\Controller;

class ItemController extends Controller //mendeklarasikan ItemController, subclass dari controller
{
    //method index()
    public function index()  
    {
        $items = Item::all(); //mengambil semua data dari tabel items
        return view('items.index', compact('items')); //Mengembalikan tampilan items.index dengan data $items
    }

    //method create()
    public function create()
    {
        return view ('items.create'); //mengembalikan items.create yang berisi form untuk menambahkan item baru
    }

    //method store()
    public function store(Request $request)
    {
        //menandai jika name dan description wajib diisi
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Item::create($request->only(['name', 'description'])); //menambah data baru dengan Item::create()
        return redirect()-> route('Items.index')->with('success', 'Item added successfully.'); //alihkan halaman item dengan proses yang berhasil
    }

    //method show()
    public function show(string $id)
    {
        return view('items.show', compact('item')); //Mengembalikan tampilan items.show
    }

    //method edit()
    public function edit(string $id)
    {
        return view('items.edit', compact('item')); //Mengembalikan tampilan items.edit
    }

    //method update()
    public function update(Request $request, Item $item)
    {
        //validasi jika wajib diisi
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $item->update($request->only(['name', 'description'])); //memperbarui data item menggunakan $item->update()
        return redirect()->route('items.index')->with('success', 'item updated successfully.'); //alihkan ke halaman daftar dengan hasil berhasil
    }

    //method destroy()
    public function destroy(Item $item)
    {
        $item->delete(); //menghapus item dari database
        return redirect()->route('items.index')->with('success', 'item deleted successfully.'); //alihkan ke halaman daftar dengan hasil berhasil
    }
}
