<form action="/password/reset" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{ $token }}"/>
    <div>
        <input type="email" name="email" id="" value="{{ old('email') }}"/>
    </div>
    <div>   
        <input type="password" name="password" id="" />
    </div>
    <div>
        <input type="password" name="password_confirmation" id="" />
    </div>
    <div>
        <button type="submit">Reset Button</button>
    </div>
</form>