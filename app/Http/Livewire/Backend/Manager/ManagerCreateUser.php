<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\User;
use App\Models\Agent;

class ManagerCreateUser extends Component
{
    public $userId;
    public $name;
    public $email;
    public $agentId;
    public $password;
    public $role = MODERATOR;
    public $status;

    public $listAgent;

    public function mount($userId = 0)
    {
        $this->listAgent = Agent::pluck('name', 'id');
        $this->agentId = $this->listAgent->keys()->first();

        $this->userId = $userId;

        if ($userId > 0) {
            $user = User::find($userId);

            $this->name = $user->name;
            $this->email = $user->email;
            $this->agentId = $user->agentId;
            //$this->password = $user->password;
            $this->role = array_search(ucfirst($user->role), USERTYPE);
            $this->status = array_search(ucfirst($user->status), USERSTATUS);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|numeric',
            'agentId' => [
                    'required_if:role,' . AGENT,
                    'not_in:0',
                ],
        ]);

        if ($this->userId > 0){ // update  user

            $user = User::find($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = intVal($this->role);
            $user->agentId = intVal($this->agentId);

            if ($this->password) $user->password = bcrypt($this->password);

            $user->status = intVal($this->status);
            $user->save();

            session()->flash('success', 'User updated successfully!');
            
        } else { // create new user
           
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => intVal($this->role),
                'agentId' => intVal($this->agentId),
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

    public function updateAgentId(){
        if ($this->role == MODERATOR) $this->agentId = null;
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-create-user')
                ->layout('manager.layouts.app');
    }
}
