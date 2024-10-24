<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function addBook(){
        $publisher = Publisher::all();

        return view('addBook')->with('publishers', $publisher);
    }

    public function storeBook(Request $request){

        $validateData = $request->validate([
            'bookTitle' => 'required|string|max:255',
            'publisher' => 'required',
            'author' => 'required|string|max:255',
            'stock' => 'required|integer|min:1',
            'bookDescription' => 'required|string',
            'releaseDate' => 'required|date'
        ]);

        Book::create([
            'bookTitle' => $validateData['bookTitle'],
            'publisherId' => $validateData['publisher'],
            'author' => $validateData['author'],
            'stock' => $validateData['stock'],
            'bookDescription' => $validateData['bookDescription'],
            'releaseDate' => $validateData['releaseDate']
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

    public function ownBook(){
        $userId = Auth::id();

        $book = Book::whereHas('purchases', function ($query) use ($userId){
            $query->where('userId', $userId);
        })->get();

        return view('ownBook')->with('books', $book);
    }

    public function userBook($id){
        $book = Book::findOrFail($id);

        return view('userBookDetail')->with('book', $book);
    }

    public function editBook($id){
        $book = Book::findOrFail($id);
        $publisher = Publisher::all();

        return view('updateBook', ['publisher' => $publisher, 'book' => $book]);
    }

    public function updateBook($id, Request $request){

        $validateData = $request->validate([
            'bookTitle' => 'required|string|max:255',
            'publisherId' => 'required',
            'author' => 'required|string|max:255',
            'stock' => 'required|integer|min:1',
            'bookDescription' => 'required|string',
            'releaseDate' => 'required|date'
        ]);

        $book = Book::findOrFail($id)->update([
            'bookTitle' => $validateData['bookTitle'],
            'publisherId' => $validateData['publisherId'],
            'author' => $validateData['author'],
            'stock' => $validateData['stock'],
            'bookDescription' => $validateData['bookDescription'],
            'releaseDate' => $validateData['releaseDate']
        ]);

        return redirect('/dashboard');
    }

    public function deleteBook($id){
        Book::destroy($id);

        return redirect('/dashboard');
    }
}
