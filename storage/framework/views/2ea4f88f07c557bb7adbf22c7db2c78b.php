<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo e(__('auth.email_session_reset.title')); ?></title>
    <style>
        <?php echo file_get_contents(resource_path('css/email-session-reset.css')); ?>

    </style>
</head>

<body>
    <div class="header">
        <h1><?php echo e(__('auth.email_session_reset.header')); ?></h1>
    </div>

    <div class="content">
        <p><?php echo __('auth.email_session_reset.greeting', ['name' => $user->getName()]); ?></p>

        <p><?php echo e(__('auth.email_session_reset.detected')); ?></p>

        <p><?php echo e(__('auth.email_session_reset.instruction')); ?></p>

        <div style="text-align: center;">
            <a href="<?php echo e($url); ?>" class="button"><?php echo e(__('auth.email_session_reset.button')); ?></a>
        </div>

        <div class="warning">
            <strong><?php echo e(__('auth.email_session_reset.warning_title')); ?></strong>
            <ul>
                <li><?php echo __('auth.email_session_reset.warning_items.all_devices'); ?></li>
                <li><?php echo e(__('auth.email_session_reset.warning_items.relogin')); ?></li>
                <li><?php echo __('auth.email_session_reset.warning_items.validity', ['minutes' => 60]); ?></li>
                <li><?php echo e(__('auth.email_session_reset.warning_items.ignore')); ?></li>
            </ul>
        </div>

        <p><?php echo e(__('auth.email_session_reset.url_instruction')); ?></p>
        <p style="word-break: break-all; color: #007bff;"><?php echo e($url); ?></p>
    </div>

    <div class="footer">
        <p><?php echo e(__('auth.email_session_reset.footer_auto')); ?></p>
        <p><?php echo e(__('auth.email_session_reset.footer_secure')); ?></p>
    </div>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/emails/session-reset.blade.php ENDPATH**/ ?>