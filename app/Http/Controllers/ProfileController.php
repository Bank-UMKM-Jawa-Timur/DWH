<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private $logActivity;

    public function __construct()
    {
        $this->logActivity = new LogActivitesController;
    }

    /**
     * Display the user's change password form.
     */
    public function changePassword()
    {
        $param['title'] = 'Change Password';
        $param['pageTitle'] = 'Change Password';

        return view('pages.change_password.index', $param);
    }

    
    /**
     * Update the user's profile information.
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|min:8',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8',
        ], [
            'required' => ':attribute harus diisi.',
            'min' => 'Minimal harus mempunyai 8 karakter.',
            'confirmed' => 'Password dan konfirmasi password tidak sesuai.'
        ], [
            'old_password' => 'Password lama',
            'password' => 'Password baru',
            'password_confirmation' => 'Konfirmasi password baru',
        ]);
        try {
            \DB::beginTransaction();
            
            $user = User::find(auth()->user()->id);

            if (\Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->old_password) {
                    return back()->withError('Password baru tidak boleh sama dengan password lama.');
                }
                else {
                    $user->password = \Hash::make($request->password);
                    $user->save();

                    $username = $user->nip ? $user->nip : $user->email;
                    $this->logActivity->store("Pengguna '$username' telah mengubah password.");

                    \DB::commit();

                    return back()->withStatus('Berhasil mengubah password.');
                }
            }
            else {
                \DB::rollback();
                return back()->withError('Password lama tidak sesuai.');
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan');
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollback();
            return back()->withError('Terjadi kesalahan pada database');
        }
    }

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
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
