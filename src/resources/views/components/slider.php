<?php if (!empty($slides)): ?>
<div id="home-slider" class="slider">
    <?php foreach ($slides as $slide): ?>
        <div class="slide">
        <img src="<?= url($slide->img) ?>">
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>