<section>
    <header>
        <p class="text-sm">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirm-user-deletion">
        {{ __('Delete Account') }}
    </button>

    @if ($errors->userDeletion->get('password'))
        <ul class="mt-1 text-sm text-danger mb-0" role="alert">
            @foreach ((array) $errors->userDeletion->get('password') as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirm-user-deletion-label">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <form method="post" action="{{ route('profile.destroy') }}" class="p-2">
                        @csrf
                        @method('delete')

                        <h2 id="confirm-user-deletion-label" class="text-lg">
                            {{ __('Are you sure you want to delete your account?') }}
                        </h2>

                        <p class="mt-1 text-sm">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mt-4">
                            <label for="password" class="sr-only">{{ __('Password') }}</label>
                            <input id="password" name="password" type="password"
                                class="form-control w-75 @if ($errors->userDeletion->get('password')) is-invalid @endif"
                                placeholder="{{ __('Password') }}">
                        </div>

                        @if ($errors->userDeletion->get('password'))
                            <ul class="mt-1 text-sm text-danger mb-0" role="alert">
                                @foreach ((array) $errors->userDeletion->get('password') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-dismiss="modal">{{ __('Cancel') }}</button>

                            <button type="submit"
                                class="btn btn-sm btn-danger ml-2">{{ __('Delete Account') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
