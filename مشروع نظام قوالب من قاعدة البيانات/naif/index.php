<?php
include_once"includes/header.php";
$template->get("home_page");
$BLOCKS_RIGHT = "<?php echo block(1); ?>";
$BLOCKS_CENTER = "<?php echo block(2); ?>";
$BLOCKS_LEFT = "<?php echo block(3); ?>";
$template->replace("{BLOCKS_RIGHT}",$BLOCKS_RIGHT);
$template->replace("{BLOCKS_CENTER}",$BLOCKS_CENTER);
$template->replace("{BLOCKS_LEFT}",$BLOCKS_LEFT);
$template->run();
include_once"includes/footer.php";
?>