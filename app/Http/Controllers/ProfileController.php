<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user  = auth()->user();

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'   => 'nullable|min:6|confirmed',
        ];

        if ($user->isDoctor()) {
            $rules['specialization']   = 'required|string|max:150';
            $rules['consultation_fee'] = 'required|numeric|min:0';
            $rules['biography']        = 'nullable|string|max:1000';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete('photos/' . $user->photo);
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('photos', $filename, 'public');
            $user->photo = $filename;
        }

        $user->first_name = $validated['first_name'];
        $user->last_name  = $validated['last_name'];
        $user->phone      = $validated['phone'] ?? null;
        if (!empty($validated['password'])) $user->password = Hash::make($validated['password']);
        $user->save();

        if ($user->isDoctor() && $user->doctor) {
            $user->doctor->update([
                'specialization'   => $validated['specialization'],
                'consultation_fee' => $validated['consultation_fee'],
                'biography'        => $validated['biography'] ?? null,
            ]);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
