<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Retorna a pagina da lista de usuarios
    public function index()
    {
        $users = User::all(); // Pega os usuarios do banco de dados
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    // Apenas o método store — adicione ao seu UserController existente

public function store(Request $request)
{
    $request->validate([
        'name'     => ['required', 'string', 'min:2', 'max:100'],
        'email'    => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ], [
        'name.required'      => 'O nome é obrigatório.',
        'name.min'           => 'O nome deve ter pelo menos 2 caracteres.',
        'name.max'           => 'O nome pode ter no máximo 100 caracteres.',
        'email.required'     => 'O e-mail é obrigatório.',
        'email.email'        => 'Informe um e-mail válido.',
        'email.unique'       => 'Este e-mail já está cadastrado.',
        'password.required'  => 'A senha é obrigatória.',
        'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
        'password.confirmed' => 'As senhas não coincidem.',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => $request->password, // o cast 'hashed' cuida do bcrypt
    ]);

    // Envia o e-mail de verificação
    event(new \Illuminate\Auth\Events\Registered($user));

    // Loga o usuário automaticamente após cadastro
    auth()->login($user);

    return redirect()->route('verification.notice');
}
    public function show(User $user)
    {
        $user = auth()->user(); // ou como você estiver pegando o usuário

    $receitas = \App\Models\Receita::where('user_id', $user->id)->get();

    return view('users.show', compact('user', 'receitas'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário deletado!');
    }
}
