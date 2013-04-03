<?

$a = 'c';
$b = 'd';

function dump($p)
{
	$c = 'q'; $d = 'r';
	echo "dump(\$p = $p) ";
	return $$p;
}

function doLocal($act)
{
	$q = 'First'; $r = 'Second';
	$v = $act('c');
	echo "{$$v}=$v\n";
}

doLocal(function($h) use ($b) {dump($b); return dump($h);} );

?>

