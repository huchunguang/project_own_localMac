<form action="/password/email" method="post">
    {{ csrf_field() }}
    <div>   
        <input type="email" name="email" id="" value="{{ old('email') }}" />
    </div>
    <div>
        <button type="submit">
            Send Password Reset Link
        </button>
    </div>
</form>