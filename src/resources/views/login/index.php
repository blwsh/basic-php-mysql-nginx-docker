<content title="Login" template="layout.default">
    <div class="container">
        <form action="/auth/login" method="post" class="login-container">
            <h1 class="title title--divider text--center">Login</h1>

            <label for="email">
                <span class="label">Email</span>
                <input type="email" name="email">
            </label>

            <label for="password">
                <span class="label">Password</span>
                <input type="password" name="password">
            </label>

            <?php if ($errors): ?>
            <ul class="errors text--center">
                <?php foreach ($errors[0] as $error): ?>
                <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <? endif; ?>

            <div class="text--center">
                <button type="submit" class="btn btn--wide">Login &#187;</button>
                <div><small>or <a href="/register">Register Here</a></small></div>
            </div>
        </form>
    </div>
</content>