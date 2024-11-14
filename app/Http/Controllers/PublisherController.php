<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;

class PublisherController extends Controller
{
    //
    public function addPublisher(){
        if(!Gate::allows('admin')){
            abort(403);
        }
        return view('addPublisher');
    }

    public function storePublisher(Request $request){
	if(!$request->isMethod('post')){
                return redirect('/dashboard');
        }
        if(!Gate::allows('admin')){
            abort(403);
        }
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
        try {
            $decrypt = Crypt::decrypt($id);

            $publisher = Publisher::findOrFail($decrypt);

            return view('publisherDetail')->with('publisher', $publisher);
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }
    }

    public function editPublisher($id){
        if(!Gate::allows('admin')){
            abort(403);
        }
        try {
            $decrypt = Crypt::decrypt($id);

            $publisher = Publisher::findOrFail($decrypt);

            return view('updatePublisher')->with('publisher', $publisher);
        } catch (\Exception $e) {
            return redirect('/dashboard');  
        }
    }

    public function updatePublisher($id, Request $request){
	if(!$request->isMethod('patch')){
                return redirect('/dashboard');
        }    
	if(!Gate::allows('admin')){
            abort(403);
        }
        try {
            $decrypt = Crypt::decrypt($id);

            $validateData = $request->validate([
                'publisherName' => 'required|string|max:255'
            ]);
    
            $publisher = Publisher::findOrFail($decrypt)->update([
                'publisherName' => $validateData['publisherName']
            ]);
    
            return redirect(route('allPublisher'));
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }
    }

    public function deletePublisher($id){  
	if(!Gate::allows('admin')){
            abort(403);
        }
        try {
            $decrypt = Crypt::decrypt($id);

            Publisher::destroy($decrypt);

            return redirect(route('allPublisher'));
        } catch (\Exception $e) {
            return redirect('/dashboard');
        }
        
    }
}
