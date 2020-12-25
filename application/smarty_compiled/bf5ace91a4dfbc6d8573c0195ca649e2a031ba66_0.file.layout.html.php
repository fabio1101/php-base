<?php
/* Smarty version 3.1.36, created on 2020-12-25 22:07:28
  from '/home/fabio/public_html/base/public/_tpl/layout.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_5fe662a092eaa7_53588183',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bf5ace91a4dfbc6d8573c0195ca649e2a031ba66' => 
    array (
      0 => '/home/fabio/public_html/base/public/_tpl/layout.html',
      1 => 1608934046,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fe662a092eaa7_53588183 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Triadify Inc.">
    <title><?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
</title>
    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- Scripts -->
    <?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"><?php echo '</script'; ?>
>
</head>
<body cz-shortcut-listen="true">
    <div class="round-border navbar navbar-inverse navbar-fixed-top text-center" role="navigation">
        <div class="container">
            <p class="navbar-text navbar-left h4"><a href="/"><?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
</a></p>
        </div>
    </div>
    <div id="header" class="col-md-12 col-sm-12 col-xs-12"></div>
    <div class="body col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="content"><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 text-center footer round-border navbar-static-bottom" style="margin-top:15px;">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <a href="http://triadify.com" target="_blank" class="footer">
                Powered by triadify.com
            </a>
            <br>
            <?php echo $_smarty_tpl->tpl_vars['year']->value;?>

        </div>
        <div class="clearfix"></div>
    </div>
</body>
</html>
<?php }
}
