<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>ixtron 后台管理系统</title>
    <?php echo $__env->yieldContent('extend_css'); ?>
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="fix-header">
<div id="wrapper">
    <?php echo $__env->make('common.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('common.topbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div id="page-wrapper">
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo $__env->make('common.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
    
</div>
<script src="/js/app.js"></script>
<script src="/libs/pace/pace.min.js"></script>
<?php echo $__env->yieldContent('extend_js'); ?>
</body>
</html>
