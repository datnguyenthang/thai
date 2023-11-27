<?php

namespace App\Http\Livewire\Component\Permission;

use Livewire\Component;
use App\Models\Permission;

class CreatePermission extends Component
{
    public $name;
    public $status;

    public function mount($permissionId = 0) {
        $this->permissionId = $permissionId;
        $this->status = ACTIVE;

        if ($permissionId > 0) {
            $permission = Permission::find($permissionId);

            $this->name = $permission->name;
            $this->status = $permission->status;
        }
    }

    public function save() {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permissionId,
        ]);

        if ($this->permissionId > 0){ // update permission
            $permission = Permission::find($this->permissionId);
            $permission->name = $this->name;
            $permission->status = intVal($this->status);
            $permission->save();

            session()->flash('success', 'Permission updated successfully!');
        } else { // create new permission
            Permission::create([
                'name' => $this->name,
                'status' => intVal($this->status),
            ]);

            session()->flash('success', 'Permission created successfully!');
        }
        // Reset input fields
        $this->name = '';
        $this->status = '';

        return redirect()->route('listPermission');
    }

    public function render() {
        return view('livewire.component.permission.create-permission')->layout('admin.layouts.app');
    }
}
