<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PhoneBook MVC</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="container header-flex">
        <div class="logo">
            <a href="<?= app()->route->getUrl('/') ?>">Справочник</a>
        </div>
        
        <nav class="nav-links">
            <a href="<?= app()->route->getUrl('/') ?>">Абоненты</a>
            
            <?php if (!app()->auth::check()): ?>
                <a href="<?= app()->route->getUrl('/login') ?>" class="btn-login">Вход</a>
                <a href="<?= app()->route->getUrl('/signup') ?>" class="btn-signup">Регистрация</a>
            <?php else: ?>
                <span class="user-name"> (<?= app()->auth::user()->first_name ?> <?= app()->auth::user()->last_name ?>)</span>

                
                <?php if(app()->auth::user()->role === 'admin'): ?>
                    <a href="<?= app()->route->getUrl('/subscriber/create') ?>" style="color: #2ecc71;">+ Добавить</a>
                <?php endif; ?>
                
                <a href="<?= app()->route->getUrl('/logout') ?>" class="btn-logout">Выход</a>

            <?php endif; ?>

        </nav>
    </div>

</header>

<main class="container">
    <section class="content-card">
        <?= $content ?? '' ?>
    </section>
</main>


</body>
</html>
