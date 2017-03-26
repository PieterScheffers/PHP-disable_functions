<?php

$subjects = [
	'command_execution' => [
		[ 'name' => 'exec' ],
		[ 'name' => 'passthru' ],
		[ 'name' => 'system' ],
		[ 'name' => 'shell_exec' ],
		[ 'name' => 'popen' ],
		[ 'name' => 'proc_open' ],
		[ 'name' => 'pcntl_exec' ],
		[ 'name' => 'url_exec' ],
	],
	'curl' => [
		[ 'name' => 'curl_exec' ],
		[ 'name' => 'curl_multi_exec' ],
	],
	'php_code_execution' => [
		[ 'name' => 'eval' ],
		[ 'name' => 'assert' ],
		[ 'name' => 'create_name' ],
		[ 'name' => 'preg_replace' ],
		[ 'name' => 'include' ],
		[ 'name' => 'include_once' ],
		[ 'name' => 'require' ],
		[ 'name' => 'require_once' ],
		[ 'name' => 'invoke' ],
	],
	'callback_names' => [
		[ 'name' => 'ob_start' ],
		[ 'name' => 'array_diff_uassoc' ],
		[ 'name' => 'array_diff_ukey' ],
		[ 'name' => 'array_filter' ],
		[ 'name' => 'array_intersect_uassoc' ],
		[ 'name' => 'array_intersect_ukey' ],
		[ 'name' => 'array_map' ],
		[ 'name' => 'array_reduce' ],
		[ 'name' => 'array_udiff_assoc' ],
		[ 'name' => 'array_udiff_uassoc' ],
		[ 'name' => 'array_udiff' ],
		[ 'name' => 'array_uintersect_assoc' ],
		[ 'name' => 'array_uintersect_uassoc' ],
		[ 'name' => 'array_uintersect' ],
		[ 'name' => 'array_walk_recursive' ],
		[ 'name' => 'array_walk' ],
		[ 'name' => 'assert_options' ],
		[ 'name' => 'uasort' ],
		[ 'name' => 'uksort' ],
		[ 'name' => 'usort' ],
		[ 'name' => 'preg_replace_callback' ],
		[ 'name' => 'spl_autoload_register' ],
		[ 'name' => 'iterator_apply' ],
		[ 'name' => 'call_user_func' ],
		[ 'name' => 'call_user_func_array' ],
		[ 'name' => 'register_shutdown_function' ],
		[ 'name' => 'register_tick_function' ],
		[ 'name' => 'set_error_handler' ],
		[ 'name' => 'set_exception_handler' ],
		[ 'name' => 'session_set_save_handler' ],
		[ 'name' => 'sqlite_create_aggregate' ],
		[ 'name' => 'sqlite_create_function' ],
	],
	'information_disclosure' => [
		[ 'name' => 'phpinfo' ],
		[ 'name' => 'posix_mkfifo' ],
		[ 'name' => 'posix_getlogin' ],
		[ 'name' => 'posix_ttyname' ],
		[ 'name' => 'getenv' ],
		[ 'name' => 'get_current_user' ],
		[ 'name' => 'proc_get_status' ],
		[ 'name' => 'get_cfg_var' ],
		[ 'name' => 'disk_free_space' ],
		[ 'name' => 'disk_total_space' ],
		[ 'name' => 'diskfreespace' ],
		[ 'name' => 'getcwd' ],
		[ 'name' => 'getlastmo' ],
		[ 'name' => 'getmygid' ],
		[ 'name' => 'getmyinode' ],
		[ 'name' => 'getmypid' ],
		[ 'name' => 'getmyuid' ],
	],
	'other' => [
		[ 'name' => 'extract' ],
		[ 'name' => 'parse_str' ],
		[ 'name' => 'putenv' ],
		[ 'name' => 'ini_set' ],
		[ 'name' => 'parse_ini_file' ],
		[ 'name' => 'mail' ],
		[ 'name' => 'header' ],
		[ 'name' => 'proc_nice' ],
		[ 'name' => 'proc_terminate' ],
		[ 'name' => 'proc_close' ],
		[ 'name' => 'pfsockopen' ],
		[ 'name' => 'fsockopen' ],
		[ 'name' => 'apache_child_terminate' ],
		[ 'name' => 'posix_kill' ],
		[ 'name' => 'posix_mkfifo' ],
		[ 'name' => 'posix_setpgid' ],
		[ 'name' => 'posix_setsid' ],
		[ 'name' => 'posix_setuid' ],
		[ 'name' => 'dl' ],
		[ 'name' => 'show_source' ],
		[ 'name' => 'apache_note' ],
		[ 'name' => 'apache_setenv' ],
		[ 'name' => 'closelog' ],
		[ 'name' => 'debugger_off' ],
		[ 'name' => 'debugger_on' ],
		[ 'name' => 'define_syslog_variables' ],
		[ 'name' => 'escapeshellarg' ],
		[ 'name' => 'escapeshellcmd' ],
		[ 'name' => 'ini_restore' ],
		[ 'name' => 'openlog' ],
		[ 'name' => 'pclose' ],
		[ 'name' => 'syslog' ],
		[ 'name' => 'pcntl_signal' ],
		[ 'name' => 'pcntl_alarm' ],
	],
	'base64' => [
		[ 'name' => 'base64_encode' ],
		[ 'name' => 'base64_decode' ],
	],
	'exhaust_memory' => [
		[ 'name' => 'str_repeat' ],
		[ 'name' => 'unserialize' ],
		[ 'name' => 'register_tick_function' ],
		[ 'name' => 'register_shutdown_function' ],
	],
];

$functions = array_reduce($subjects, function($arr, $subject) {
	return array_merge($arr, array_map(function($fn) { return $fn['name']; }, $subject));
}, []);

function tokensGetName($tokens) {
	return array_map(function($token) {
		if( is_array($token) ) {
			if( isset($token[0]) && is_int($token[0]) ) {
				$token[0] = token_name($token[0]);
			}
		}
		return $token;
	}, $tokens);
}

function hasBackTick($tokens)
{
	return in_array($tokens, '`');
}

function getInfoPHPFile($file)
{
	global $functions;

	$tokens = tokensGetName(token_get_all(file_get_contents($file)));

	$f = array_reduce($tokens, function($arr, $token) use ($functions, $file) {
		if( is_array($token) && isset($token[1]) && in_array($token[1], $functions) ) {
			$arr[] = [ 'name' => $token[1], 'line' => isset($token[2]) ? $token[2] : '?', 'file' => $file ];
		}
		return $arr;
	}, []);

	return $f;
}

function processDirectory($dir)
{
	$entries = array_map(
		function($file) use ($dir) { return $dir . '/' . $file; }, 
		array_filter(scandir($dir), function($f) { return !in_array($f, [ '.', '..']); })
	);

	$dirs = array_filter($entries, 'is_dir');

	$phpFiles = array_filter($entries, function($path) {
		return is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php';
	});

	unset($entries);

	$infos = array_reduce($phpFiles, function($arr, $file) { return array_merge($arr, getInfoPHPFile($file)); }, []);

	unset($phpFiles);

	foreach ($dirs as $dir)
	{
		$infos = array_merge($infos, processDirectory($dir));
	}

	return $infos;
}

$usedFunctions = array_reduce(processDirectory(getcwd()), function($arr, $entry) {
	$name = $entry['name'];

	if( !isset($arr[$name]) ) $arr[$name] = [];

	unset($entry['name']);
	$arr[$name][] = $entry;

	return $arr;
}, []);

echo "\nCurrently used 'dangerous' functions: \n";
foreach( $usedFunctions as $name => $files )
{
	echo $name . "\n";

	foreach( $files as $file )
	{
		echo $file['file'] . " - " . $file['line'] . "\n";
	}

	echo "\n";
}
echo "\n";

$used = array_keys($usedFunctions);
$notUsed = array_filter($functions, function($f) use ($used) { return !in_array($f, $used); });

echo "Add to php.ini: \n";
echo 'disable_functions = "' . implode(', ', $notUsed) . '"' . "\n";

