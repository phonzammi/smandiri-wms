<section>
    <header>
        <p class="text-sm">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row">
            <div class="form-group col-md-6">
                <label for="name" class="font-weight-normal">{{ __('Name') }}</label>

                <input id="name" name="name" type="text"
                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}"
                    required autofocus autocomplete="name">

                @error('name')
                    <ul class="invalid-feedback mb-0" role="alert">
                        @foreach ((array) $errors->get('name') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label for="email" class="font-weight-normal">{{ __('Email') }}</label>

                <input id="email" name="email" type="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}"
                    required autocomplete="email">

                @error('email')
                    <ul class="invalid-feedback mb-0" role="alert">
                        @foreach ((array) $errors->get('email') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification"
                                class="text-sm text-primary bg-transparent border-0 rounded-lg">
                                <u> {{ __('Click here to re-send the verification email.') }}</u>
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm text-success">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex">
            <button type="submit" class="btn btn-sm btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="ml-2 my-auto text-sm text-info">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
