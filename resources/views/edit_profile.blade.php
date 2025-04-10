
<form action="editProfile" method="POST" class="form-example">
    @csrf
    <div class="form-example">
        <label for="name">Введите новое имя: </label>
        @error('name')
        <label style="color: red">{{ $message }}</label>
        @enderror
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required />
    </div>
    <div class="form-example">
        <label for="email">Введите новый email: </label>
        @error ('email')
        <label style="color: red">{{ $message }}</label>
        @enderror
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
    </div>
    <div class="form-example">
        <label for="password">Введите новый пароль: </label>
        <input type="password" name="password" id="password" required>
    </div>


    <div class="form-example">
        <label for="password_confirmation">Подтвердите новый пароль: </label>
        <input type="password" name="password_confirmation" id="password_confirmation">
    </div>
    <div class="form-example">
        <input type="submit" value="Изменить" />
    </div>
</form>

<style>
    form.form-example {
        display: table;
    }

    div.form-example {
        display: table-row;
    }

    label,
    input {
        display: table-cell;
        margin-bottom: 10px;
    }

    label {
        padding-right: 10px;
    }
</style>
