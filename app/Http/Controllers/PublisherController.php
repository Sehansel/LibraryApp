<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    //
    public function addPublisher(){
        return view('addPublisher');
    }

    public function storePublisher(Request $request){
        $validateData = $request->validate([
            'publisherName' => 'required|string|max:255'
        ]);

        Publisher::create([
            'publisherName' => $validateData['publisherName']
        ]);

        return redirect('/dashboard');
    }

    public function allPublisher(){
        $publisher = Publisher::all();

        return view('publisher')->with('publishers', $publisher);
    }

    public function detailPublisher($id){
        $publisher = Publisher::findOrFail($id);

        return view('publisherDetail')->with('publisher', $publisher);
    }

    public function editPublisher($id){
        $publisher = Publisher::findOrFail($id);

        return view('updatePublisher')->with('publisher', $publisher);
    }

    public function updatePublisher($id, Request $request){
        $validateData = $request->validate([
            'publisherName' => 'required|string|max:255'
        ]);

        $publisher = Publisher::findOrFail($id)->update([
            'publisherName' => $validateData['publisherName']
        ]);

        return redirect(route('allPublisher'));
    }

    public function deletePublisher($id){
        Publisher::destroy($id);

        return redirect(route('allPublisher'));
    }
}
