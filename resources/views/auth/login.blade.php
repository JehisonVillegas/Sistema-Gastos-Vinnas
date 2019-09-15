@extends('auth.contenido')

@section('login')
<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100">
          <form class="login100-form validate-form" method="POST" action="{{route('login')}}">
            {{csrf_field()}}
            <span class="login100-form-logo">
						<img src="images/marca-01.png" width="300" height="300">
					</span>
          <span class="login100-form-title p-b-34 p-t-27">
						
					</span>
              <div class="wrap-input100 validate-input {{$errors->has('usuario' ? 'is-invalid' : '')}}" >
                <input type="text" name="usuario" id="usuario" class="input100" placeholder="Usuario">
                <span class="focus-input100" data-placeholder="&#xf207;"></span>
                {!!$errors->first('usuario','<span class="login100-form-title p-b-34 p-t-27">::message</span>')!!}
              </div>
              <div class="wrap-input100 validate-input {{$errors->has('password' ? 'is-invalid' : '')}}">
                 <input type="password" name="password" id="password" class="input100" placeholder="Password">
                 <span class="focus-input100" data-placeholder="&#xf191;"></span>
                {!!$errors->first('password','<span class="invalid-feedback">::message</span>')!!}
              </div>
              <div class="row">
                <div class="container-login100-form-btn">
                  <button type="submit" class="login100-form-btn">Acceder</button>
                </div>
              </div>
            </div>
          </form>
          </div>
		</div>
	</div>
	<div id="dropDownSelect1"></div>
  
@endsection
