<div>
    <h2>Edit Profile</h2>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="updateProfile">
        <div class="form-outline mb-4">
            <label class="form-label">Name:</label>
            <input type="text" wire:model="name" class="form-control w-50">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        <div class="form-outline mb-4">
            <label class="form-label">Email:</label>
            <input type="email" wire:model="email" class="form-control w-50" disabled>
            @error('email') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        <div class="form-outline mb-4">
            <label class="form-label">New Password:</label>
            <input type="password" wire:model="password" class="form-control w-50">
            @error('password') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        <div class="form-outline mb-4">
            <label class="form-label">Confirm New Password:</label>
            <input type="password" wire:model="password_confirmation" class="form-control w-50">
        </div>
        <button type="submit" class="btn btn-success">Update Profile</button>
    </form>
</div>
