<?php if (CSite::InDir('/visual/')): ?>
    <?php include('inc_footer_small.php'); ?>
<?php else: ?>
    <?php include('inc_footer.php'); ?>
<?php endif; ?>
<!--include blocks/_callback-->
<? $APPLICATION->ShowHeadStrings(); ?>
<? $APPLICATION->ShowHeadScripts(); ?>
</body>
</html>
