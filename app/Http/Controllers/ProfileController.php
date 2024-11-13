<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile picture.
     */
    public function pictureUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = auth()->user();
    
        if ($request->hasFile('profile_photo')) {
            try {
                $uploadedFile = $request->file('profile_photo');
    
                if (!$uploadedFile->isValid()) {
                    throw new \Exception('Invalid file uploaded');
                }
    
                if ($user->image) {
                    Storage::delete($user->image);
                }
    
                $path = $uploadedFile->store('profile_photos');
    
                $user->image = $path;
    
                $user->save();
    
                return redirect()->route('profile.edit')->with('success', 'profile-updated');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
    
                return redirect()->route('profile.edit')->with('error', 'Failed to update profile picture. Please try again.');
            }
        }
    
        return redirect()->route('profile.edit')->with('error', 'No file uploaded');
    }

    /**
     * Serve the user's profile photo.
     */
    public function showProfilePhoto($encryptedId)
    {
        try {
            $userId = Crypt::decrypt($encryptedId);
            
            $user = User::findOrFail($userId);
    
            if ($user->image && Storage::exists($user->image)) {
                return response()->file(storage_path("app/private/{$user->image}"));
            }
            
            return response()->file(public_path('default.jpg'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {   
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
