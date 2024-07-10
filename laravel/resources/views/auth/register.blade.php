@extends('layouts.app')

@section('content')
<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                                <form id="registerForm" method="POST" action="{{ route('register.submit') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input id="name" type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        <span class="invalid-feedback" role="alert">
                                            @error('name')
                                                <strong>{{ $message }}</strong>
                                            @enderror
                                        </span>
                                    </div>

                                    <div class="mb-4">
                                        <label for="email">{{ __('Email Address') }}</label>
                                        <input id="email" type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required autocomplete="email">
                                        <span class="invalid-feedback" role="alert">
                                            @error('email')
                                                <strong>{{ $message }}</strong>
                                            @enderror
                                        </span>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input id="password" type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               name="password" required autocomplete="new-password">
                                        <span class="invalid-feedback" role="alert">
                                            @error('password')
                                                <strong>{{ $message }}</strong>
                                            @enderror
                                        </span>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                        <input id="password-confirm" type="password" 
                                               class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>

                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="showPassword">
                                        <label class="form-check-label" for="showPassword">
                                            {{ __('Show Password') }}
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="submit" class="btn btn-primary btn-lg">{{ __('Register') }}</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
                                    class="img-fluid" alt="Sample image">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @include('components.verification-modal')

    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password-confirm');
            if (this.checked) {
                passwordField.type = 'text';
                confirmPasswordField.type = 'text';
            } else {
                passwordField.type = 'password';
                confirmPasswordField.type = 'password';
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('{{ route('register.submit') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    $('#verificationModal').modal('show');
                    $('#verificationModal').on('hidden.bs.modal', function () {
                        window.location.href = '{{ route('login.form') }}';
                    });
                }
            })
            .catch(error => {
                if (error.errors) {
                    // Clear any previous errors
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                    // Display validation errors
                    for (const [field, messages] of Object.entries(error.errors)) {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = messages.join(', ');
                            }
                        }
                    }
                } else {
                    console.error('Unexpected error:', error);
                }
            });
        });

    
        $(document).ready(function() {
            @if(isset($showModal) && $showModal)
                $('#verificationModal').modal('show');
                $('#verificationModal').on('hidden.bs.modal', function () {
                    window.location.href = '{{ route('login.form') }}';
                });
            @endif
        });
    </script>

</section>
@endsection