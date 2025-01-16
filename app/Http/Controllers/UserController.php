<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'id_sucursal' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_sucursal' => $request->id_sucursal,
        ]);

        $user->assignRole($request->role);

        return redirect(route('dashboard', absolute: false));
    }

    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'id_sucursal' => ['required', 'string', 'max:255'],
            ]);

            if ($request->password == null) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'id_sucursal' => $request->id_sucursal,
                ]);
            } else {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'id_sucursal' => $request->id_sucursal,
                ]);
            }
            $user->syncRoles($request->role);

            return redirect(route('user.index', absolute: false));
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }
}
