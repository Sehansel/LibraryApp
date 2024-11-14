<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    //
    public function addBook(){
        if(!Gate::allows('admin')){
            abort(403);
        }
        $publisher = Publisher::all();

        return view('addBook')->with('publishers', $publisher);
    }

    public function storeBook(Request $request){
	if(!$request->isMethod('post')){
		return redirect('/dashboard');
	}
	if(!Gate::allows('admin')){
            abort(403);
	}
        $validateData = $request->validate([
            'bookTitle' => 'required|string|max:255',
            'publisher' => 'required|integer',
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
        try {
            $decrypt = Crypt::decrypt($id);

            $book = Book::findOrFail($decrypt);

            return view('bookDetail')->with('book', $book);
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }
    }

    public function buyBook(Request $request){
        try {
            $decryptBookId = Crypt::decrypt($request->bookId);
            $decryptUserId = Crypt::decrypt($request->userId);
            $book = Book::find($decryptBookId);
            if($book && $book->stock > 0){
                $book->stock -= 1;
                $book->save();
    
                $purchase = new Purchase();
                $purchase->userId = $decryptUserId;
                $purchase->bookId = $decryptBookId;
                $purchase->save();
    
                return redirect('/dashboard')->with('success', 'Book purchased successfully');
            }
            return redirect('/dashboard')->with('error', 'Book not found or out of stock');
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }
    }

    public function ownBook(){
        $userId = Auth::id();

        $book = Book::whereHas('purchases', function ($query) use ($userId){
            $query->where('userId', $userId);
        })->get();

        return view('ownBook')->with('books', $book);
    }

    public function userBook($id){
        try {
            $decrypt = Crypt::decrypt($id);

            $book = Book::findOrFail($decrypt);

            return view('userBookDetail')->with('book', $book);
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }
    }

    public function editBook($id){
        if(!Gate::allows('admin')){
            abort(403);
        }
        try {
            $decrypt = Crypt::decrypt($id);

            $book = Book::findOrFail($decrypt);

            $publisher = Publisher::all();

            return view('updateBook', ['publisher' => $publisher, 'book' => $book]);
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }
    }

    public function updateBook($id, Request $request){
	if(!$request->isMethod('patch')){
                return redirect('/dashboard');
        }    
	if(!Gate::allows('admin')){
            abort(403);
        }
        try {
            $decrypt = Crypt::decrypt($id);
            
            $validateData = $request->validate([
            'bookTitle' => 'required|string|max:255',
            'publisherId' => 'required|integer',
            'author' => 'required|string|max:255',
            'stock' => 'required|integer|min:1',
            'bookDescription' => 'required|string',
            'releaseDate' => 'required|date'
            ]);

            $book = Book::findOrFail($decrypt)->update([
            'bookTitle' => $validateData['bookTitle'],
            'publisherId' => $validateData['publisherId'],
            'author' => $validateData['author'],
            'stock' => $validateData['stock'],
            'bookDescription' => $validateData['bookDescription'],
            'releaseDate' => $validateData['releaseDate']
            ]);

        return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }

    }

    public function deleteBook($id){   
	if(!Gate::allows('admin')){
            abort(403);
        }
        try {
            $decrypt = Crypt::decrypt($id);
            Book::destroy($decrypt);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }

    }
}
