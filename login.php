
<!doctype html>
<html>
<head>
    <title>Мойсклад утилиты</title>
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
    <script
      src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="semantic/dist/semantic.min.js"></script>
</head>
<body>
    <div class="ui container">
        <style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      width: 450px;
    }
</style>
<div class="ui middle aligned center aligned grid">
    <!-- particles -->
    <div class="ui column" style="width:450px;">
        <h2 class="ui teal huge image header">
            <!-- <img src="/logo.png" class="image" width="300px"> -->
            <div class="content">
                Вход
            </div>
        </h2>

        <!-- <form class="ui large form" method="POST" action="{{ route('login') }}"> -->
        <form class="ui large form" method="POST" action="index.php">
            <div class="ui stacked segment">
                <div class="field">
                    <div class="ui left icon input">
                        <i class="mail icon"></i>
                        <input type="text" placeholder="Логин Мойсклад" name="user" required>
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <input type="password" name="password" placeholder="Пароль"  required>
                    </div>
                </div>
                <button type="submit" class="ui fluid large teal submit button">Войти</buttom>
            </div>
            <div class="ui error message"></div>
        </form>
    </div>

</div>

    </div>
</body>
</html>
