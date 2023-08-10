<div class="profile-container">
    <h2>Profile</h2>
    <ul class="profile-list">
        <li><strong>Name:</strong> {{ $user->name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Role:</strong> {{ $user->role }}</li>
        @if($user->agentId)
            <li><strong>Agent:</strong> {{ $listAgent[$user->agentId] }}</li>
        @endif
        <li><strong>Status:</strong> {{ $user->status }}</li>
        <li><strong>Created Date:</strong> {{ $user->created_at }}</li>
        <li><strong>Updated Date:</strong> {{ $user->updated_at }}</li>
    </ul>
</div>
