@foreach($users as $user)
    <li id="user-id-{{ $user->id }}">
        <div class="media">
            <div class="media-left align-self-center">
                <img class="rounded-circle"
                     src="{{ '/' . $user->getUserAvatar() }}">
            </div>
            <div class="media-body">
                <h4>{{ $user->name }}</h4>
                <p>
                    ID: {{ $user->id }}<br>
                    Email: {{ $user->email }}<br>
                    Phone: +{{ $user->phone }}
                </p>
            </div>
            <div class="media-right align-self-center">
                <a href="mailto:{{ $user->email }}" class="btn btn-default contact-link">Contact Now</a>
            </div>
        </div>
    </li>
@endforeach