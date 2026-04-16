<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

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
            'password' => $request->password,
        ]);

        event(new \Illuminate\Auth\Events\Registered($user));
        auth()->login($user);

        return redirect()->route('verification.notice');
    }

    /**
     * Perfil do usuário logado (rota /perfil)
     */
    public function show()
    {
        $user    = auth()->user();
        $receitas = \App\Models\Receita::with(['ingredientes', 'passos'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('users.show', compact('user', 'receitas'));
    }

    /**
     * Perfil público de qualquer usuário acessado via /u/{user}
     */
    public function publicProfile(User $user)
    {
        // Bloqueia perfis privados para quem não é o dono
        if (! $user->perfil_publico && auth()->id() !== $user->id) {
            abort(403, 'Este perfil é privado.');
        }

        $receitas = \App\Models\Receita::with(['ingredientes', 'passos'])
            ->where('user_id', $user->id)
            ->when(auth()->id() !== $user->id, fn ($q) => $q->where('status', 'publicado'))
            ->latest()
            ->get();

        return view('users.show', compact('user', 'receitas'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user); // opcional se você tiver Policy

        // ── Avatar ───────────────────────────────────────────────────────
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => ['required', 'image', 'max:2048'],
            ], [
                'avatar.image' => 'O arquivo deve ser uma imagem.',
                'avatar.max'   => 'A imagem deve ter no máximo 2 MB.',
            ]);

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);

            return back()->with('success', 'Foto de perfil atualizada!');
        }

        // ── Dados do perfil ──────────────────────────────────────────────
        $request->validate([
            'name'  => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ], [
            'name.required'  => 'O nome é obrigatório.',
            'name.min'       => 'O nome deve ter pelo menos 2 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'email.unique'   => 'Este e-mail já está em uso.',
        ]);

        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'perfil_publico' => $request->boolean('perfil_publico'),
        ]);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário deletado!');
    }
}
