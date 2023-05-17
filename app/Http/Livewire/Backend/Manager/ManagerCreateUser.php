<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\User;

class ManagerCreateUser extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role;
    public $status;

    
    public function mount($userId = 0)
    {
        $this->userId = $userId;

        if ($userId > 0) {
            $user = User::find($userId);

            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = $user->password;
            $this->role = array_search(ucfirst($user->role), USERTYPE);
            $this->status = array_search(ucfirst($user->status), USERSTATUS);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
        ]);

        if ($this->userId > 0){ // update  user

            $user = User::find($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = intVal($this->role);
            $user->password = bcrypt($this->password);
            $user->status = intVal($this->status);
            $user->save();

            session()->flash('success', 'User updated successfully!');
            
        } else { // create new user
           
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => intVal($this->role),
                'status' => intVal($this->status),
                'password' => bcrypt($this->password),
            ]);

            session()->flash('success', 'User created successfully!');
        }
        // Reset input fields
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->password = '';

        return redirect()->route('managerListUser');
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-create-user')
                ->layout('manager.layouts.app');
    }
}
