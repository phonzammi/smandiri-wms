<x-dashboard-layout>
    @section('subtitle', __('Profile'))
    @section('content_header_title', __('Profile'))


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Profile Information') }}</h3>
        </div>
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Update Password') }}</h3>
        </div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Delete Account') }}</h3>
        </div>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>


</x-dashboard-layout>
