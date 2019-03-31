<?php
function writeIsValidFile(string $file): bool
{
    if (file_exists($file) === false) {
        $return = false;
        ?>
        <div class="alert alert-danger">
            File does not exist.
        </div>
        <?php
    } elseif (is_readable($file) === false) {
        $return = false;
        ?>
        <div class="alert alert-danger alert-condensed">
            File exist but is not readable.
        </div>
        <?php
    } else {
        $return = true;
    }

    return $return;
}

function writeInfosIcon(bool $isValid): void
{
    if ($isValid) {
        ?>
        <i class="glyphicon glyphicon-ok glyphicon-success" title="File exist and is readable"></i>
    <?php } else { ?>
        <i class="glyphicon glyphicon-exclamation-sign glyphicon-danger"></i>
        <?php
    }
}

function writePath(string $path): void
{
    $realpath = realpath($path);
    ?>
    <span title="<?=($realpath === false ? 'Realpath not found.' : $realpath)?>"><?=$path?></span>
    <?php
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="author" content="Steevan BARBOYON" />
        <title>Composer autoload informations</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

        <style type="text/css">
            body {
                cursor: default;
                margin: 15px;
            }
            .alert-condensed {
                margin-bottom: 0px;
                padding: 3px 5px;
            }
            .glyphicon-danger {
                color: #E25D4F;
            }
            .glyphicon-success {
                color: #5CB85C;
            }
            table thead tr th {
                background-color: #DDDDDD;
            }
            .no-margin-bottom {
                margin-bottom: 0px;
            }
            th.infos {
                width: 1px;
            }
            .panel-config a,
            .panel-config a:hover,
            .panel-config a:visited {
                color: white !important;
            }
            .alert-info a,
            .alert-info a:hover,
            .alert-info a:visited {
                text-decoration: underline;
            }
            .show-hide-content {
                margin-top: 20px;
            }
            .panel-config {
                border-color: #7f33b7;
            }
            .panel-config .panel-heading {
                background-color: #7f33b7;
                color: white;
            }
        </style>
    </head>
    <body>
        <?php
        $vendorDirs = [
            'vendor/composer',
            'vendor/composer/composer',
            '../vendor/composer',
            '../vendor/composer/composer',
            '../../composer',
            '../vendor'
        ];

        $autoloadFiles = null;
        $loader = null;
        foreach ($vendorDirs as $dir) {
            if (file_exists($dir . '/autoload_files.php')) {
                $autoloadFiles = require_once($dir . '/autoload_files.php');
            }
            if (file_exists($dir . '/autoload.php')) {
                /** @var Composer\Autoload\ClassLoader $loader */
                $loader = require_once($dir . '/autoload.php');
            }
        }
        if ($loader === null) {
            throw new \Exception('autoload.php not found.');
        }

        $reflection = new ReflectionProperty(get_class($loader), 'missingClasses');
        $reflection->setAccessible(true);
        $missingClasses = $reflection->getValue($loader);
        $reflection->setAccessible(false);
        ?>

        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-config">
                    <div class="panel-heading text-center">
                        <a href="https://getcomposer.org/doc/04-schema.md#include-path" target="_blank">Use include path</a>
                    </div>
                    <div class="panel-body text-center">
                        <?php if ($loader->getUseIncludePath()) { ?>
                            <span class="label label-warning">Yes</span>
                        <?php } else { ?>
                            <span class="label label-success">No</span>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel panel-config">
                    <div class="panel-heading text-center">
                        <a href="https://getcomposer.org/doc/articles/autoloader-optimization.md#optimization-level-2-a-authoritative-class-maps" target="_blank">Authoritative class maps</a>
                    </div>
                    <div class="panel-body text-center">
                        <?php if ($loader->isClassMapAuthoritative()) { ?>
                            <span class="label label-warning">Yes</span>
                        <?php } else { ?>
                            <span class="label label-success">No</span>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel panel-config">
                    <div class="panel-heading text-center">
                        <a href="https://getcomposer.org/doc/articles/autoloader-optimization.md#optimization-level-2-b-apcu-cache" target="_blank">APCu prefix</a>
                    </div>
                    <div class="panel-body text-center">
                        <span class="label label-<?=($loader->getApcuPrefix() === null ? 'default' : 'success')?>">
                            <?=($loader->getApcuPrefix() === null ? 'NULL' : $loader->getApcuPrefix())?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel panel-config">
                    <div class="panel-heading text-center">
                        <a href="https://getcomposer.org/doc/04-schema.md#files" target="_blank">Autoloaded files</a>
                    </div>
                    <div class="panel-body text-center">
                        <span class="label label-<?=($autoloadFiles === null ? 'warning' : 'success')?>">
                            <?=($autoloadFiles === null ? '0' : count($autoloadFiles))?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel panel-config">
                    <div class="panel-heading text-center">
                        <a href="https://getcomposer.org/doc/04-schema.md#classmap" target="_blank">ClassMap</a>
                    </div>
                    <div class="panel-body text-center">
                        <span class="label label-success"><?=count($loader->getClassMap())?></span>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel panel-config">
                    <div class="panel-heading text-center">
                        Missing classes
                    </div>
                    <div class="panel-body text-center">
                        <span class="label label-<?=(count($missingClasses) === 0 ? 'success' : 'danger')?>">
                            <?=count($missingClasses)?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                #1 autoload_files.php
                <?php if (is_array($autoloadFiles)) { ?>
                    <span class="badge badge-default"><?=count($autoloadFiles)?></span>
                <?php } ?>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    autoload_files.php is included by composer before anything.
                    <br />
                    You can add files in <a href="https://getcomposer.org/doc/04-schema.md#files" target="_blank">composer.json</a>.
                </div>
                <?php if ($autoloadFiles === null) { ?>
                    <div class="alert alert-warning no-margin-bottom">
                        autoload_files.php not found.
                        <br />
                        I looked into <?php implode(', ', $vendorDirs) ?>.
                    </div>
                <?php } elseif (count($autoloadFiles) === 0) { ?>
                    <div class="alert alert-info no-margin-bottom">
                        No files in autoload_files.php.
                    </div>
                <?php } else { ?>
                    <button class="btn btn-info" data-show-hide="autoloaded-files">Show autoloaded files</button>
                    <div class="hide show-hide-content" data-show-hide-content="autoloaded-files">
                        <table class="table table-hover table-striped table-bordered table-condensed no-margin-bottom">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th class="infos"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($autoloadFiles as $file) {
                                    $isValid = true;
                                    ?>
                                    <tr>
                                        <td>
                                            <?=$file?>
                                            <?php $isValid = writeIsValidFile($file) ?>
                                        </td>
                                        <td>
                                            <?php writeInfosIcon($isValid) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                #2 ClassMap
                <span class="badge badge-default"><?=count($loader->getClassMap())?></span>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    ClassMap map a class who is not in a namespace, to a file.
                    <br />
                    When you <a href="https://getcomposer.org/doc/articles/autoloader-optimization.md" target="_blank">optimize Composer autoloader</a>,
                    all classes (except those are written in autoload_files.php) are in ClassMap.
                </div>
                <?php if (count($loader->getClassMap()) === 0) { ?>
                    <div class="alert alert-info">
                        No classes in ClassMap.
                    </div>
                <?php } else { ?>
                    <button class="btn btn-info" data-show-hide="classmap">Show ClassMap</button>
                    <div class="hide show-hide-content" data-show-hide-content="classmap">
                        <table class="table table-hover table-striped table-bordered table-condensed no-margin-bottom">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>File</th>
                                    <th class="infos"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($loader->getClassMap() as $class => $file) {
                                    $isValid = true;
                                    ?>
                                    <tr>
                                        <td><?=$class?></td>
                                        <td>
                                            <?php writePath($file) ?>
                                            <?php $isValid = writeIsValidFile($file) ?>
                                        </td>
                                        <td>
                                            <?php writeInfosIcon($isValid) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                PSR-0 prefixes
                <span class="badge badge-default"><?=count($loader->getPrefixes())?></span>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    PSR-0 prefixes are used to optimize file search.
                </div>
                <?php if (count($loader->getPrefixes()) === 0) { ?>
                    <div class="alert alert-info">
                        No PSR-0 prefixes.
                    </div>
                <?php } else { ?>
                    <button class="btn btn-info" data-show-hide="psr-0-prefixes">Show PSR-0 prefixes</button>
                    <div class="hide show-hide-content" data-show-hide-content="psr-0-prefixes">
                        <table class="table table-hover table-striped table-bordered table-condensed no-margin-bottom">
                            <thead>
                                <tr>
                                    <th>Prefix</th>
                                    <th>Directory</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($loader->getPrefixes() as $prefix => $dirs) {
                                    $isValid = true;
                                    ?>
                                    <tr>
                                        <td><?=$prefix?></td>
                                        <td>
                                            <?php foreach ($dirs as $dir) { ?>
                                                <?php writePath($dir) ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                PSR-4 prefixes
                <span class="badge badge-default"><?=count($loader->getPrefixesPsr4())?></span>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    PSR-4 prefixes are used to optimize file search.
                </div>
                <?php if (count($loader->getPrefixesPsr4()) === 0) { ?>
                    <div class="alert alert-info">
                        No PSR-4 prefixes.
                    </div>
                <?php } else { ?>
                    <button class="btn btn-info" data-show-hide="psr-4-prefixes">Show PSR-4 prefixes</button>
                    <div class="hide show-hide-content" data-show-hide-content="psr-4-prefixes">
                        <table class="table table-hover table-striped table-bordered table-condensed no-margin-bottom">
                            <thead>
                            <tr>
                                <th>Prefix</th>
                                <th>Directory</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($loader->getPrefixesPsr4() as $prefix => $dirs) {
                                $isValid = true;
                                ?>
                                <tr>
                                    <td><?=$prefix?></td>
                                    <td>
                                        <?php foreach ($dirs as $dir) { ?>
                                            <?php writePath($dir) ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script
            src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g="
            crossorigin="anonymous">
        </script>
        <script type="text/javascript">
            $('button[data-show-hide]').on('click', function(event) {
                var showHide = $(event.currentTarget).attr('data-show-hide');
                var elShowHide = $('div[data-show-hide-content="' + showHide + '"');
                console.log(elShowHide);

                if (elShowHide.hasClass('hide')) {
                    elShowHide.removeClass('hide');
                } else {
                    elShowHide.addClass('hide');
                }
            })
        </script>
    </body>
</html>

