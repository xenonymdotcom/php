<?php

interface Item
{
	public function show();	
	public function save();	
}

/// this is the interface for factories that create items.
interface ItemFactory
{
	public function create($args);	
}

/// this is the interface to the builders
/// these take a list of items to build and return a list of items
interface Creator
{
	public function build($list);
}

/// this is the interface holding the item names
interface ItemTag
{
	const HTML_PAGE = "htmlpage";
	const PHP_PAGE = "phppage";
}

class htmlpageFactory implements ItemFactory
{
	public function create($args)
	{
		return new HtmlPage($args);
	}
}

class phppageFactory implements ItemFactory
{
	public function create($args)
	{
		return new PhpPage($args);
	}
}

class HtmlPage implements Item
{
	private $name;
	
	function __construct($args)
	{
		$this->name = @$args['name'];
		if (!$this->name) $this->name = "(default)";
	}

	public function show()
	{
		return "html page (".$this->name.")";
	}

	public function save()
	{
		return "{ 'class':'htmlpage', 'name':'".$this->name."' }";
	}
}

class PhpPage implements Item
{
	private $type;
	
	function __construct($args)
	{
		$this->type = @$args['type'];
		if (!$this->type) $this->type = "(default)";
	}
	
	public function show()
	{
		return "php page (".$this->type.")";
	}
	
	public function save()
	{
		return "{ 'class':'phppage', 'type':'".$this->type."' }";
	}
}

class MapCreator implements Creator
{
	// has to be public so we can create it outside the class
	// which we need to do if we want to call constructors.
	public static $buildMap;

	public function build($list)
	{
		$result = array();
		$builder = self::$buildMap;
		foreach( $list as $item )
		{
			echo "\n".$item['class']."->";
			$creater = $builder[$item['class']];
			if ( $creater )
			{
				$result[] = $creater->create($item['params']);
			}
		}
		return $result;

	}
}
// WE HAVE TO INIT THIS OUTSIDE THE CLASS ....
MapCreator::$buildMap = array( ItemTag::HTML_PAGE => new htmlpageFactory()
						      , ItemTag::PHP_PAGE  =>  new phppageFactory() );
class CreatorMap
{
	function htmlpage()
	{
		return new htmlpageFactory();
	}
	
	function phppage()
	{
		return new phppageFactory();
	}
}

class MapCreator2 extends MapCreator
{
	public function build($list)
	{
		$result = array();
		$builder = new CreatorMap();
		foreach( $list as $item )
		{
			$field = $item['class'];
			$class = $builder->$field();
			$result[] = $class->create($item['params']);
		}
		return $result;
	}
}

class StringCreator implements Creator
{
	public function build($list)
	{
		$result = array();
		foreach( $list as $item )
		{
			$idx = $item['class'] . "Factory";
			$obj = new $idx();
			$result[] = $obj->create($item['params']);
		}
		return $result;
	}
}

class StringCreator2 implements Creator
{
	private static $valid = array(ItemTag::HTML_PAGE => "HtmlPage", ItemTag::PHP_PAGE => "PhpPage");
	public function build($list)
	{
		$result = array();
		$builder = MapCreator::$buildMap;
		foreach( $list as $item )
		{
			$idx = $item['class'];
			if ( array_key_exists($idx, self::$valid) )
			{
				$classname = self::$valid[$idx];
				$result[] = new $classname($item['params']);
			}
		}
		return $result;
	}
}

function serialise($str, $item)
{
	$save = $item->save();
	return $str ? $str .", " . $save : $save;
}

function dump( $list )
{
	if ( !$list) 
	{
		echo "MT list\n";
		return;
	}
	foreach( $list as $item )
	{
		echo $item->show();
		echo "\n";
	};
	$saved = "[".array_reduce($list, "serialise" )."]";
	echo "saved = $saved\n";
}

function main()
{
	$list = array( array('class' => 'htmlpage', 'params' => array( 'name' => 'MyHtmlPage' ) )
				 , array('class' => 'phppage', 'params' => array( 'type' => 'myPhp' ) )
				 , array('class' => 'phppage', 'params' => array( 'type' => 'overloaded', 'name' => 'fishy' ) )
				 );
	$bad = array( array('class' => 'phppage(); echo"***BAD CODE"; new htmlpage', 'params' => array( 'type' => 'BAD', 'name' => 'BAD' ) )
				, array('class' => 'phppageFactory(); echo"***BAD CODE"; new htmlpage', 'params' => array( 'type' => 'BAD', 'name' => 'BAD' ) )
				);

	try {
	echo "MapCreator\n";
	$creator = new MapCreator();
	dump( $creator->build( $list ) );
	dump( $creator->build( $bad ) );
	} catch (Exception $e) {
		echo "*ERROR* - ".$e->getMessage();
	}

	echo "\nMapCreator2\n";
	$creator = new MapCreator2();
	dump( $creator->build( $list ) );
	// dump( $creator->build( $bad ) ); // crashes

	echo "\nStringCreator\n";
	$creator = new StringCreator();
	dump( $creator->build( $list ) );
	// dump( $creator->build( $bad ) ); // crashes

	echo "\nStringCreator2\n";
	$creator = new StringCreator2();
	dump( $creator->build( $list ) );
	dump( $creator->build( $bad ) );
}

main();

?>
-- TEST OVER --
