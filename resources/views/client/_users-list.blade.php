@foreach($users as $user)
    <tr>
        <td>
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
            <a href="{{ route('clientEdit', ['id' => $user->id]) }}" class="user-link">{{ $user->name }}</a>
        </td>
        <td>
            {{ date('Y/m/d', strtotime($user->created_at)) }}
        </td>
        <td>
            {{ $user->email }}
        </td>
        <td class="text-center">
            <a href="{{ route('clientEdit', ['id' => $user->id]) }}" class="table-link">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
            </a>
            <a href="{{ route('clientDelete', ['id' => $user->id]) }}"
               class="table-link danger confirmation">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </td>
    </tr>
@endforeach