<?php
$pageTitle = 'الصفحات الخاصة';
include_once"includes/header.php";
$PAGE_CONTENT = "
<?php echo 
block_template('عنوان الصفحة','محتوى الصفحة'); 
?>";
$template->get("page");
$BLOCKS_RIGHT = "<?php echo block(1); ?>";
$BLOCKS_LEFT = "<?php echo block(3); ?>";
$template->replace("{BLOCKS_RIGHT}",$BLOCKS_RIGHT);
$template->replace("{PAGE_CONTENT}",$PAGE_CONTENT);
$template->replace("{BLOCKS_LEFT}",$BLOCKS_LEFT);
$template->run();
include_once"includes/footer.php";
?>