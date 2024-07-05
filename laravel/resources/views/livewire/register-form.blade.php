<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-5">
              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
  
                  <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
  
                  <form wire:submit="register">
                    @csrf
                
                    <div class="mb-4">
                        <label for="name">{{ __('Name') }}</label>
                        <input id="name" type="text" wire:model="name" 
                               class="form-control" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        <div>
                          @error('name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label for="email">{{ __('Email Address') }}</label>
                        <input id="email" type="email" wire:model="email" 
                               class="form-control" 
                               name="email" value="{{ old('email') }}" required autocomplete="email">
                        <div>
                          @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" wire:model="password" 
                               class="form-control" 
                               name="password" required autocomplete="new-password">
                        <div>
                          @error('password')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" wire:model="password_confirmation" 
                               class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <div>
                          @error('password_confirmation')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
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
</section>

