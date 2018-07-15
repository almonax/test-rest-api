@if (!empty($errors))
    <div class="alert alert-danger" role="alert">
        {{ $errors['message'] }}
        <ul>
            @foreach($errors['errors'] as $errorsList)
                @foreach($errorsList as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif