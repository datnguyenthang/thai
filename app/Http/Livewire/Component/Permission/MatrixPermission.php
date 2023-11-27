<?php

namespace App\Http\Livewire\Component\Permission;

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

class MatrixPermission extends Component
{
    public $roles;
    public $permissions;
    public $matrix = [];

    public function mount(){
        $this->roles = Role::all();
        $this->permissions = Permission::all();

        // Initialize matrix with current roles and permissions
        foreach ($this->roles as $role) {
            foreach ($this->permissions as $permission) {
                $rolePermission = RolePermission::where('roleId', $role->id)
                                                ->where('permissionId', $permission->id)
                                                ->first();

                // Use $rolePermission to determine if the checkbox should be checked
                $this->matrix[$role->id][$permission->id] = $rolePermission !== null;
            }
        }
    }

    public function saveMatrix(){
        foreach ($this->roles as $role) {
            foreach ($this->permissions as $permission) {

                $value = $this->matrix[$role->id][$permission->id];

                if ($value) {
                    // If the checkbox is checked, insert or update the record
                    $rolePermission = RolePermission::updateOrCreate(
                        ['roleId' => $role->id, 'permissionId' => $permission->id],
                        ['value' => $value]
                    );
                } else {
                    // If the checkbox is unchecked, remove the record if it exists
                    RolePermission::where('roleId', $role->id)
                        ->where('permissionId', $permission->id)
                        ->delete();
                }
            }
        }
        session()->flash('success', 'Permission matrix updated successfully.');
        return redirect()->route('matrixPermission');
    }

    public function render(){
        return view('livewire.component.permission.matrix-permission')->layout('admin.layouts.app');
    }
}
