<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Debitor;
use App\Models\Abladestelle;
use App\Models\Rechtegruppe;
use Illuminate\Validation\Rule;

class UserVerwaltung extends Component
{
    public $users;
    public $search = '';

    public $showUser = false;
    public $isEditUser = false;
    public $confirmingDelete = false;
    public $deleteUserId = null;

    public $userId;
    public $name;
    public $email;
    public $password;
    public $debitor_nr;
    public $abladestelle_id;
    public $rechtegruppe_id;

    public $debitors;
    public $abladestellen;
    public $rechtegruppen;
    public $legende;

    public function mount()
    {
        $this->debitors = Debitor::all();
        $this->abladestellen = []; // Abladestelle::all();
        $this->rechtegruppen = Rechtegruppe::all();
        $this->loadUsers();
        $this->legende = \App\Models\Berechtigung::orderBy('bezeichnung')->get();
    }

    public function render()
        {
            return view('livewire.user-verwaltung',['legende' => $this->legende ])->layout('layouts.app');
        }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::with(['debitor', 'abladestelle', 'rechtegruppe'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->get();
    }

    public function editUser($doCreate, $id = null)
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'debitor_nr', 'abladestelle_id', 'rechtegruppe_id']);

        $this->isEditUser = !$doCreate;

        if (!$doCreate) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->debitor_nr = $user->debitor_nr;
            $this->abladestelle_id = $user->abladestelle_id;
            $this->rechtegruppe_id = $user->rechtegruppe_id;
        }

        $this->showUser = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->userId)],
            'debitor_nr' => 'nullable|exists:debitoren,nr',
            'abladestelle_id' => 'nullable|exists:abladestellen,id',
            'rechtegruppe_id' => 'nullable|exists:rechtegruppe,id',
        ];

        if (!$this->isEditUser) {
            $rules['password'] = 'required|string|min:6';
        }

        $this->validate($rules);

        $user = $this->userId ? User::findOrFail($this->userId) : new User();

        $user->name = $this->name;
        $user->email = $this->email;

        if (!$this->userId && $this->password) {
            $user->password = bcrypt($this->password);
        }

        $user->debitor_nr = $this->debitor_nr;
        $user->abladestelle_id = $this->abladestelle_id;
        $user->rechtegruppe_id = $this->rechtegruppe_id;

        $user->save();

        $this->showUser = false;
        $this->loadUsers();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->deleteUserId = $id;
    }

    public function deleteUser()
    {
        User::findOrFail($this->deleteUserId)->delete();
        $this->confirmingDelete = false;
        $this->deleteUserId = null;
        $this->loadUsers();
    }


    public function updated($propertyName, $value)
{
    \Log::info('Property updated: ' . $propertyName . ' = ' . $value);
    if ($propertyName === 'debitor_nr') {
        $this->abladestellen = \App\Models\Abladestelle::where('debitor_nr', $value)->get();
        $this->abladestelle_id = null;
    }
}

    public function updatedDebitorNr($value)
        {
            \Log::info('Debitor changed to: ' . $value);
            $this->abladestellen = \App\Models\Abladestelle::where('debitor_nr', $value)->get();
            $this->abladestelle_id = null;
        }

    public function updatedEmail($value)
        {
            \Log::info('Email changed to: ' . $value);
        }


}
