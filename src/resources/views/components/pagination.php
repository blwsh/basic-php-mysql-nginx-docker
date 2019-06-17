<?php $pages = ceil($count/$perPage); ?>
<div class="pagination">
    <div class="pagination__links pagination__links--previous">
        <a href="<?= url('/'. $scope .'?page=1') ?>" title="First page">&#171;</a>
        <a href="<?= url('/'. $scope .'?page=' . max($page - 1, 1)) ?>" title="Previous page">&#8249;</a>
    </div>

    <div class="pagination__links">
    <?php foreach (range(1, $pages) as $page) echo '<a href="' . url('/'. $scope .'?page=' . $page) . '">' . $page . '</a>'; ?>
    </div>

    <div class="pagination__links pagination__links--next">
        <a href="<?= url('/'. $scope .'?page=' . min($page, $pages)) ?>" title="Next page">&#8250;</a>
        <a href="<?= url('/'. $scope .'?page=' . $pages) ?>" title="Last page">&#187;</a>
    </div>
</div>