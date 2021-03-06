<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) {
  exit('No direct script access allowed');
} ?>
<!DOCTYPE html>
<html lang="<?php echo $this->app->locale; ?>">
  <head>
    <meta charset="utf-8" />
    <title><?php echo $this->app->pageTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Applications/<?php echo __APPNAME__; ?>/ClientSide/css/app/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/library/css/core/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Applications/<?php echo __APPNAME__; ?>/ClientSide/css/addons/toastr.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Applications/<?php echo __APPNAME__; ?>/ClientSide/css/addons/jquery.contextMenu.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/library/css/core/jquery-ui.css" />
    <?php echo $this->app->globalResources["css_files"]; ?>    
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/library/js/core/jquery.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/library/js/core/jquery-ui.js"></script>
<?php echo $this->app->globalResources["js_files_head"]; ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body id="home">
