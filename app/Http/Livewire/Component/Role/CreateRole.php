<?php

namespace App\Http\Livewire\Component\Role;

use Livewire\Component;
use App\Models\Role;

class CreateRole extends Component
{
    public $name;
    public $status;

    public function mount($roleId = 0) {
        $this->roleId = $roleId;
        $this->status = ACTIVE;

        if ($roleId > 0) {
            $role = Role::find($roleId);

            $this->name = $role->name;
            $this->status = $role->status;
        }
    }

    public function save() {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
        ]);

        if ($this->roleId > 0){ // update  role
            $role = Role::find($this->roleId);
            $role->name = $this->name;
            $role->status = intVal($this->status);
            $role->save();

            session()->flash('success', 'Role updated successfully!');
        } else { // create new role
            Role::create([
                'name' => $this->name,
                'status' => intVal($this->status),
            ]);

            session()->flash('success', 'Role created successfully!');
        }
        // Reset input fields
        $this->name = '';
        $this->status = '';

        return redirect()->route('listRole');
    }

    public function render(){
        return view('livewire.component.role.create-role')->layout('admin.layouts.app');
    }
}
