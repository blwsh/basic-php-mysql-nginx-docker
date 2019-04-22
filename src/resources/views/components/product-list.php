<?php if (!empty($items)): ?>
<div class="product-list">
    <div class="row">
        <?php foreach ($items as $data): ?>
        <div class="col col--<?= $colWidth ?? 4 ?>/12">
            <?= view('components.card', []); ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>