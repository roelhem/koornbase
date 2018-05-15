<div class="card">
    <div class="card-body">
        <div class="media">
            <user-avatar class="avatar-xxl mr-5" image="{{$user->avatar}}" letters="{{$user->avatar_letters}}"></user-avatar>
            <div class="media-body">
                <h4 class="m-0">
                    {{ $user->name_display }}
                </h4>
                <p class="text-muted mb-0">
                    {{ $user->email }} <span class="small text-muted-dark">(<em>{{ $user->name }}</em>)</span>
                </p>
            </div>
        </div>
    </div>
</div>