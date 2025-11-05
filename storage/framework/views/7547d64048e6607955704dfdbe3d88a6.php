<?php
    use Knuckles\Scribe\Tools\Utils as u;
?>
# <?php echo e(u::trans("scribe::headings.auth")); ?>


<?php if(!$isAuthed): ?>
<?php echo u::trans("scribe::auth.none"); ?>

<?php else: ?>
<?php echo $authDescription; ?>


<?php echo $extraAuthInfo; ?>

<?php endif; ?>
<?php /**PATH /Users/malahmed/Development/The Lobby/main_server/the_lobby/vendor/knuckleswtf/scribe/src/../resources/views//markdown/auth.blade.php ENDPATH**/ ?>