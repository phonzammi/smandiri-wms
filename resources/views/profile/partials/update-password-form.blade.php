<section>
    <header>
        <p class="text-sm">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="row">
            <div class="form-group col-md-6">
                <label for="update_password_current_password"
                    class="font-weight-normal">{{ __('Current Password') }}</label>

                <input id="update_password_current_password" name="current_password" type="password"
                    class="form-control @if ($errors->updatePassword->get('current_password')) is-invalid @endif" required
                    autocomplete="current-password">

                @if ($errors->updatePassword->get('current_password'))
                    <ul class="invalid-feedback mb-0" role="alert">
                        @foreach ((array) $errors->updatePassword->get('current_password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label for="update_password_password" class="font-weight-normal">{{ __('New Password') }}</label>

                <input id="update_password_password" name="password" type="password"
                    class="form-control @if ($errors->updatePassword->get('password')) is-invalid @endif" required
                    autocomplete="new-password">

                @if ($errors->updatePassword->get('password'))
                    <ul class="invalid-feedback mb-0" role="alert">
                        @foreach ((array) $errors->updatePassword->get('password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label for="update_password_password_confirmation"
                    class="font-weight-normal">{{ __('Confirm Password') }}</label>

                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="form-control" required autocomplete="new-password">
            </div>
        </div>

        <div class="d-flex">
            <button type="submit" class="btn btn-sm btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="ml-2 my-auto text-sm text-info">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
