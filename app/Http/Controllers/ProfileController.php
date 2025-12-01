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
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Solo admin puede entrar
        if ($user->role_id !== 2) {
            abort(404);
        }

        return view('profile.edit', [
            'users'  => \App\Models\User::orderBy('name')->get(),
            'firmas' => \App\Models\User::getUniqueSigns(),
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
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateFirmas(Request $request)
    {
        $auth = $request->user();

        // Solo admin
        if ($auth->role_id !== 2) {
            abort(404);
        }

        $usersInput = $request->input('users', []);

        foreach ($usersInput as $userId => $data) {

            $user = User::find($userId);
            if (! $user) {
                continue;
            }

            $rawSign = $data['sign'] ?? null;

            if (empty($rawSign)) {
                // Si viene vacío, limpiamos la firma
                $user->sign = null;
            } else {
                // Ejemplo de $rawSign: "["CI","cis.png"]"
                $decoded = json_decode($rawSign, true);

                if (json_last_error() === JSON_ERROR_NONE
                    && is_array($decoded)
                    && count($decoded) === 2) {

                    // Normalizamos el JSON antes de guardar
                    $user->sign = json_encode($decoded);

                } else {
                    // Si por alguna razón no es JSON válido, lo guardamos tal cual
                    // (o podrías simplemente hacer continue; para saltarlo)
                    $user->sign = $rawSign;
                }
            }

            $user->save();
        }

        return redirect()
            ->back()
            ->with('status', 'Firmas actualizadas correctamente.');
    }

    public function createFirma(User $user)
    {
        // Solo admin, igual que en edit
        if (auth()->user()->role_id !== 2) {
            abort(404);
        }

        return view('profile.firma-create', compact('user'));
    }

    public function storeFirma(Request $request, User $user)
    {
        if (auth()->user()->role_id !== 2) {
            abort(404);
        }

        $request->validate([
            'initials' => 'required|string|max:5',
            'file' => 'required|image|max:2048',
        ]);

        $initials = strtoupper($request->initials);

        // Extensión original (png, jpg, etc.)
        $extension = $request->file('file')->getClientOriginalExtension();

        // Nuevo nombre: INICIALES.extension  → ej: CI.png
        $filename = $initials . '.' . $extension;

        // Ruta final absoluta en /public
        $destination = public_path($filename);

        // Mover archivo directamente a /public
        $request->file('file')->move(public_path(), $filename);

        // Guardar en la BD como JSON: ["CI", "CI.png"]
        $user->sign = json_encode([
            $initials,
            $filename
        ]);

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Firma agregada correctamente.');
    }


}
