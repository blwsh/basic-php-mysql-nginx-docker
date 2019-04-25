<?php if (!empty($slides)): ?>
<div id="home-slider" class="slider">
    <?php foreach ($slides as $slide): ?>
        <div class="slide">
        <img src="<?= $slide->img ?>">
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>