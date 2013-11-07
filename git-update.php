<html><body><style>
    body {
        font-family: 'Courier New', serif;
        font-size: 12px;
    }
    h1{
        font-size: 16px;
        font-weight: bold;
        border-bottom: #AAA solid 1px;
        margin: 5px 0 10px 0;
        padding: 5px 10px 5px 10px;
        background: #F5F5F5;
    }

    h2{
        font-size: 14px;
        font-weight: bold;
    }

    pre {
        padding: 5px 0 5px 20px;
        font-family: 'Courier New', serif;
        font-size: 12px;
        border-left: #EEE solid 1px;
        display: block;
    }
</style><?php

$repoName = 'dl';

define("START", microtime(1));

/////// GIT ////////////////////////////////////

echo "<h1>Git Update</h1>";

// Current Linux Username
$out = array();
exec("whoami", $out);
$username = isset($out[0]) ? $out[0] : "unknown";

$cmd = array (
    'pull' => 'git pull origin master',
    // 'submoduleupdate' => 'git submodule update --init',
    //'submodules' => 'git submodule foreach git pull origin master',
);

$dir = realpath(dirname(__FILE__));

chdir( $dir );

$out = array();

foreach ( $cmd as $key=>$val ) {
    $out = array();
    exec( $val, $out );
    echo (
        "<p><strong>Command:</strong> {$val}" . "</p>" .
            "<p><strong>Project Directory:</strong> {$dir}" . "</p>" .
            "<pre>" .
            implode("\r\n", $out) .
            "</pre>"
    );
}

/////////  Other  /////////////////////////////////////

echo "<h1>Other</h1>";

echo "<p><strong>Runtime:</strong> " . sprintf("%0.3f", microtime(1) - START) . "</p>";
echo "<p><strong>Finished:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Linux Username:</strong> {$username}</p>";

?></body></html>