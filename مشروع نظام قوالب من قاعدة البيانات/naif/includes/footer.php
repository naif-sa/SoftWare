<?php
$template->get("footer");
$template->replace("{SITE_COPY}",setting('site_copy'));
$template->run();
?>