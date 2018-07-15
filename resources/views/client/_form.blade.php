<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <form action="{{ isset($user) ? route('clientUpdate', ['id' => $user->id]): route('clientStore') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="full_name" class="col-sm-2 col-form-label">Full name</label>
                    <div class="col-sm-10">
                        <input id="name"
                               name="name"
                               class="form-control"
                               value="{{ (isset($user)) ? $user->name: old('name') }}"
                               placeholder="Name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input id="email"
                               name="email"
                               type="email"
                               class="form-control"
                               value="{{ (isset($user)) ? $user->email: old('email') }}"
                               placeholder="Email">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <input id="phone"
                               name="phone"
                               class="form-control"
                               value="{{ (isset($user)) ? $user->phone: old('phone') }}"
                               placeholder="Phone"
                               minlength="11"
                               maxlength="11">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-sm-10">
                                <input type="file" name="avatar" class="form-control" id="avatar" placeholder="Avatar">
                            </div>
                            <div class="col-sm-2">
                                @isset($user)
                                    <img src="{{ $user->avatar }}"
                                         width="100px"
                                         class="pull-right user-avatar"
                                         alt="{{ $user->name }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pull-right top-page-ui">
                    <button type="submit" class="btn btn-success pull-right">
                        {{ isset($user) ? 'Save': 'Create' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>