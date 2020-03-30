@include('auth.header')

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">

        <form action="{{ url('/login') }}" method="post" class="login100-form validate-form flex-sb flex-w">

            <span class="login100-form-title p-b-32">
                SISCAI - SUNAT
            </span>

            {{ csrf_field() }}
            @if (count($errors) > 0)
            <div class="form-group has-error has-feedback">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <span class="txt1 p-b-11">
                USUARIO
            </span>
            <div class="wrap-input100 validate-input m-b-36" data-validate = "Ingrese usuario">
                <input class="input100" type="text" name="login" >
                <span class="focus-input100"></span>
            </div>
        
            <span class="txt1 p-b-11">
                CONTRASEÑA
            </span>
            <div class="wrap-input100 validate-input m-b-12" data-validate = "Ingrese contraseña">
                <span class="btn-show-pass">
                    <i class="fa fa-eye"></i>
                </span>
                <input class="input100" type="password" name="password" >
                <span class="focus-input100"></span>
            </div>

            <div class="flex-sb-m w-full p-b-48">
                <div class="contact100-form-checkbox">
                    <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
                    <label class="label-checkbox100" for="ckb1">
                        Recordarme
                    </label>
                </div>

                <!--div>
                    <a href="{{ url('/password/reset') }}" class="text-muted">¿Olvidó su contraseña?</a>
                </div-->
            </div>

            <div class="container-login100-form-btn">
                <button class="login100-form-btn" type="submit" style="margin-left: 115px;">
                    INGRESAR
                </button>
            </div>

            </form>
        </div>
    </div>
</div>

<div id="dropDownSelect1"></div>
@include('auth.footer')