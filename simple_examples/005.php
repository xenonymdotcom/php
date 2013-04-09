<?

// this is wrong as it will recur and then return 2 after it finds a value that is not 2.
function pickServer_a()
{
    $num = rand(1, 4);
    if ($num == 2)
    {
        pickServer_a();
    }
    return $num;
}

function pickServer_b()
{
    $num = rand(1, 4);
    if ($num == 2)
    {
        return pickServer_b();
    }
    return $num;
}

function pickServer_c()
{
  $num = rand(1,4);
  if($num == 2)
  {
    $num = pickServer_c();
  }
  return $num;
}

function pickServer_while() 
{
    do 
    {
        $num = rand(1,4);
    } 
    while ($num == 2);
    return $num;
}

function pickServer_3state_a()
{
  $num = rand(1,3);
  if($num > 1)
  {
    $num = $num + 1;
  }
  return $num;
}

function pickServer_3state_aa()
{
  $num = rand(1,3);
  return ($num > 1) ? $num +1 : $num;
}

function pickServer_3state_b()
{
  $num = rand(1,3);
  if($num > 1)
  {
    return $num + 1;
  }
  return $num;
}

/* if remapping */
function pickServer_if_a() 
{
    $server = rand(1,3);
    if ($server == 2) 
    {
        $server = 4;
    }
    return $server;
}

function pickServer_if_b()
{
    $varr = rand(2,4);
    if($varr == 2)
    {
        return 1;
    }
    return $varr;
}

/* array remapping */
function pickServer_array_a()
{
    $remap = array(1, 3, 4);
    return $remap[rand(1,3)];
}

function pickServer_array_b()
{
	$servers = array(1,3,4);
	return $servers[rand(1,count($servers))]; 
}

?>

