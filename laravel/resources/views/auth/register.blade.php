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
                                        <span class="invalid-feedback" role="alert">
                                            @error('password')
                                                <strong>{{ $message }}</strong>
                                            @enderror
                                        </span>
                                    </div>

                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="showPassword">
                                        <label class="form-check-label" for="showPassword">
                                            {{ __('Show Password') }}
                                        </label>
                                    </div>

                                    <div id="requirementsMessage" class="text-center mb-3"></div>


                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="button" id="verifyRequirements" class="btn btn-primary btn-lg">{{ __('Verify Requirements') }}</button>
                                    </div>
                                    
                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="submit" id="registerButton" class="btn btn-primary btn-lg" disabled>{{ __('Register') }}</button>
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

        function validateName(name) {
        if (!name) {
            return 'Please fill your Full Name';
        }
        return '';
        }

        function validateEmail(email) {
            if (!email) {
                return 'Email is required';
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                return 'Please enter a valid email address';
            }
            return '';
        }

        function validatePassword(password) {
            if (!password) {
                return 'Password is required';
            }
            if (password.length < 12) {
                return 'Password must be at least 12 characters long';
            }
            return '';
        }

        function validatePasswordConfirmation(password, passwordConfirmation) {
            if (!passwordConfirmation) {
                return 'Password confirmation is required';
            }
            if (password !== passwordConfirmation) {
                return 'Password confirmation must match the password';
            }
            return '';
        }

        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('password-confirm').value.trim();

            const errors = {};

            errors.name = validateName(name);
            errors.email = validateEmail(email);
            errors.password = validatePassword(password);
            errors.password_confirmation = validatePasswordConfirmation(password, confirmPassword);

            displayErrors(errors);

            const registerButton = document.getElementById('registerButton');
            registerButton.disabled = Object.values(errors).some(error => error !== '');

            return Object.values(errors).every(error => error === '');
        }

        function displayErrors(errors) {
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = errors[field];
                    }
                }
            });
        }

        document.getElementById('verifyRequirements').addEventListener('click', function() {
            validateForm();
        });

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault(); 
            }
        });

        // Initialize validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            displayErrors({});
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
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
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
    
    </script>
    

</section>
@endsection