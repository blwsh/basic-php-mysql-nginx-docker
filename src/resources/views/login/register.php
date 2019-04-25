<content title="Register" template="layout.default">
    <div class="container">
        <form action="<?= url('/auth/register') ?>" method="post" class="login-container">
            <h1 class="title title--divider text--center">Register</h1>

            <label for="name">
                <span class="label">Name</span>
                <input type="text" name="name" placeholder="John Smith" value="<?= htmlentities($old['name']) ?>" required>
            </label>

            <label for="email">
                <span class="label">Email</span>
                <input type="email" name="email" placeholder="example@email.com" value="<?= htmlentities($old['email']) ?>" required>
            </label>

            <label for="phone">
                <span class="label">Phone</span>
                <input type="tel" name="phone" placeholder="01484422288" value="<?= htmlentities($old['phone']) ?>" required>
            </label>

            <label for="password">
                <span class="label">Password</span>
                <input type="password" name="password" placeholder="At least 8 characters and one capital" required>
            </label>

            <label for="confirm_password">
                <span class="label">Confirm password</span>
                <input type="password" name="confirm_password" placeholder="Same as the one above" required>
            </label>

            <?= view('components.errors', ['errors' => $errors]) ?>

            <div class="text--center">
                <button type="submit" class="btn btn--wide">Register &#187;</button>
                <div><small>or <a href="<?= url('/login') ?>">Login Here</a></small></div>
            </div>
        </form>
    </div>
</content>