<content title="Login" template="layout.default">
    <div class="container">
        <form action="<?= url('/auth/login') . ($next ? "?next=$next" : null) ?>" method="post" class="login-container">
            <h1 class="title title--divider text--center">Login</h1>

            <label for="email">
                <span class="label">Email</span>
                <input type="email" name="email" value="<?= $old['email'] ?>" required>
            </label>

            <label for="password">
                <span class="label">Password</span>
                <input type="password" name="password" required>
            </label>

            <?= view('components.errors', ['errors' => $errors]) ?>

            <div class="text--center">
                <button type="submit" class="btn btn--wide">Login &#187;</button>
                <div><small>or <a href="<?= url('/register') ?>">Register Here</a></small></div>
            </div>
        </form>
    </div>
</content>