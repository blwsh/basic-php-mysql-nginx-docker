<?php

if ($errors) {
    echo '<ul class="errors text--center">';
    foreach ($errors as $error) echo "<li>$error</li>";
    echo '</ul>';
}