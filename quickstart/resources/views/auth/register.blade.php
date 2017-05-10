<form action="/auth/register" method="post">
    {{ csrf_field() }}
    <div>
        Name
        <input type="text" name="name" id="" value="{{ old('name') }}" />
    </div>
    <div>
        Email
        <input type="email" name="email" id="" value="{{ old('email') }}"/>
    </div>
    <div>
        Password
        <input type="password" name="password" />
    </div>
    <div>
        Confirm Password 
        <input type="password" name="password_confirmation" />
    </div>
    <div>
        <button type="submit">Register Button</button>
    </div>
</form>