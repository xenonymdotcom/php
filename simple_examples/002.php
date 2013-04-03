<?

class TestClass
{
	private $a;
	private $b;
	
	public function __construct($a='b')
	{
		$this->$a = 'Set';
	}
}

$obj = new TestClass();
var_dump($obj);
?>

