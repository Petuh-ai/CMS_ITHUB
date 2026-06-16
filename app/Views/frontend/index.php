<?php
$pageTitle = 'Главная';
ob_start();
?>

<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">Онлайн-знакомства</p>
        <h1>Найди нового друга или половинку</h1>
        <p class="hero-text">Добро пожаловать на сайт, где каждое знакомство начинается с доверия и взаимного интереса.</p>
        <div class="hero-actions">
            <a href="#profiles" class="btn btn-primary">Посмотреть анкеты</a>
            <a href="#signup" class="btn btn-secondary">Заполнить анкету</a>
        </div>
    </div>
    <div class="hero-illustration">
        <div class="hero-card">
            <span>Лучшие знакомства</span>
            <h2>Скорее начните</h2>
        </div>
    </div>
</section>

<section id="features" class="features">
    <div class="container-grid">
        <article class="feature-card">
            <h3>Безопасность</h3>
            <p>Мы заботимся о том, чтобы ваше общение было комфортным и защищённым.</p>
        </article>
        <article class="feature-card">
            <h3>Конфиденциальность</h3>
            <p>Личные данные не передаются третьим лицам без вашего согласия.</p>
        </article>
        <article class="feature-card">
            <h3>Любой возраст</h3>
            <p>Знакомства для людей от 18 до 88 лет.</p>
        </article>
        <article class="feature-card">
            <h3>Обоюдное согласие</h3>
            <p>Каждый контакт подтверждается обеими сторонами.</p>
        </article>
    </div>
</section>

<section id="profiles" class="profiles-section">
    <div class="section-header">
        <h2>Анкеты участников</h2>
        <p>Ознакомьтесь с самыми популярными профилями и найдите подходящего человека.</p>
    </div>
    <div class="profiles-grid">
        <?php foreach (array_slice($posts, 0, 4) as $post): ?>
            <article class="profile-card">
                <div class="profile-image"></div>
                <div class="profile-info">
                    <h3><?php echo Security::escape($post['title']); ?></h3>
                    <p><?php echo Security::escape(substr($post['excerpt'] ?: $post['content'], 0, 120)); ?>...</p>
                    <a href="/post/<?php echo Security::escape($post['slug']); ?>" class="btn btn-tertiary">Подробнее</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="form-section" id="signup">
    <div class="form-container">
        <div>
            <h2>Заполните свою анкету</h2>
            <p>Оставьте заявку, и мы поможем найти подходящую пару.</p>
        </div>
        <form action="/" method="GET" class="lead-form">
            <label>
                Имя
                <input type="text" name="name" placeholder="Введите имя">
            </label>
            <label>
                Email
                <input type="email" name="email" placeholder="mail@mail.ru">
            </label>
            <label>
                Телефон
                <input type="tel" name="phone" placeholder="8 900 000 00 00">
            </label>
            <button type="submit" class="btn btn-primary">Отправить</button>
            <p class="form-note">Нажимая на кнопку, вы принимаете <a href="#">Положение</a> и <a href="#">Согласие</a> на обработку персональных данных.</p>
        </form>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/frontend.php';
?>
