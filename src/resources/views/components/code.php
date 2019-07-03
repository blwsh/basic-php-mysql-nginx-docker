<?php

foreach ($this->vars as $var) {
    preg_match('/(?<=<code><span style="color: #000000">\n).*(?=\n<\/span>\n<\/code>)/s', $var, $matches);
    echo "<pre>" . str_replace('&lt;?php&nbsp;<br />', null, $matches[0]) . "</pre>";
}



