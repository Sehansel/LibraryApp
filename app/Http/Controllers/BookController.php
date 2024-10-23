<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function addBook(){
        return view('addBook');
    }

    public function storeBook(Request $request){
        Book::create([
            'bookTitle' => $request->bookTitle,
            'author' => $request->author,
            'stock' => $request->stock,
            'releaseDate' => $request->releaseDate
        ]);

        return redirect('/dashboard');
    }

    public function allBook(){
        $books = Book::all();

        return $books;
    }

    public function dashboard(){
        if(Auth::check()){
            $books = $this->allBook();
            return view('dashboard')->with('books', $books);
        }else{
            return redirect('/');
        }
    }

    public function book($id){
        $book = Book::findOrFail($id);

        return view('bookDetail')->with('book', $book);
    }

    public function buyBook(Request $request){
        $book = Book::find($request->bookId);

        if($book && $book->stock > 0){
            $book->stock -= 1;
            $book->save();

            $purchase = new Purchase();
            $purchase->userId = $request->userId;
            $purchase->bookId = $request->bookId;
            $purchase->save();

            return redirect('/dashboard')->with('success', 'Book purchased successfully');
        }

        return redirect('/dashboard')->with('error', 'Book not found or out of stock');
    }
}
