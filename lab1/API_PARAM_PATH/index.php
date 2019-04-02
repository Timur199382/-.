<?php
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	require 'Slim/autoload.php';

	$app = new \Slim\App;

	$app->get('/{number}', function (Request $request, Response $response, array $args)
	{
	    $number = (int)$args['number'];

	    $result = number2string($number);

		JSON_save("../lab_1.json", json_encode(array(
			"numb_letter"=> $result
		)));

		return $response->withJSON(
	        ["numb_letter"=> $result],
	        200,
	        JSON_UNESCAPED_UNICODE
    	);
	});


	$app->get('/fibonachchi/{numb}', function (Request $request, Response $response, array $args)
	{
	    $number = (int)$args['numb'];

	    if($number > 0)
		{
			$result =round(pow((sqrt(5)+1) / 2, $number) / sqrt(5));
		}
		else
		{
			$result = null;
		}

		JSON_save("../lab_1.json", json_encode(array(
			'fib_result'=> $result
		)));

		return $response->withJSON(
	        ['fib_result' => $result],
	        200,
	        JSON_UNESCAPED_UNICODE
    	);
	});



	$app->get('/region/{region_code}', function (Request $request, Response $response, array $args)
	{
	    $region_code = $args['region_code'];

	    $regions_data = array
	    (
            array('72', 'Тюмень'),
			array('77', 'г. Москва'),
			array('78', 'Санкт-Петербург'),
			array('66', 'Свердловская область')
	    );

	    for ($j=0; $j < count($regions_data); $j++)
	    { 
	    	if ($regions_data[$j][0] == $region_code)
	    	{
	    		$result = $regions_data[$j][1];
	    		break;
	    	}
	    	else{ $result = "REGION NOT FOUND"; }
	    }

	    JSON_save("../lab_1.json", json_encode(array(
			'region_name'=> $result
		)));
		return $response->withJSON(
	        ['region_name' => $result],
	        200,
	        JSON_UNESCAPED_UNICODE
    	);
	});

	$app->run();





	function JSON_save($filename, $response)
	{
		$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$example = array(
			'url' => $url,
			'response' => $response,
			'method' => $_SERVER['REQUEST_METHOD']);
		$json_example = json_encode($example, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		file_put_contents($filename, $json_example . "\n", FILE_APPEND | LOCK_EX);
	}



	function number2string($number) {
    
   
    static $dic = array(
    
       
        array(
            -2  => 'две',
            -1  => 'одна',
            1   => 'один',
            2   => 'два',
            3   => 'три',
            4   => 'четыре',
            5   => 'пять',
            6   => 'шесть',
            7   => 'семь',
            8   => 'восемь',
            9   => 'девять',
            10  => 'десять',
            11  => 'одиннадцать',
            12  => 'двенадцать',
            13  => 'тринадцать',
            14  => 'четырнадцать' ,
            15  => 'пятнадцать',
            16  => 'шестнадцать',
            17  => 'семнадцать',
            18  => 'восемнадцать',
            19  => 'девятнадцать',
            20  => 'двадцать',
            30  => 'тридцать',
            40  => 'сорок',
            50  => 'пятьдесят',
            60  => 'шестьдесят',
            70  => 'семьдесят',
            80  => 'восемьдесят',
            90  => 'девяносто',
            100 => 'сто',
            200 => 'двести',
            300 => 'триста',
            400 => 'четыреста',
            500 => 'пятьсот',
            600 => 'шестьсот',
            700 => 'семьсот',
            800 => 'восемьсот',
            900 => 'девятьсот'
        ),
        
      
        array(
            array('', '', ''),
            array('тысяча', 'тысячи', 'тысяч'),
            array('миллион', 'миллиона', 'миллионов'),
            array('миллиард', 'миллиарда', 'миллиардов'),
            array('триллион', 'триллиона', 'триллионов'),
            array('квадриллион', 'квадриллиона', 'квадриллионов'),
         
        ),
        
      
        array(
            2, 0, 1, 1, 1, 2
        )
    );
    
  
    $string = array();
    
 
    $number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
    
  
    $parts = array_reverse(str_split($number,3));
    
   
    foreach($parts as $i=>$part) {
        
        
        if($part>0) {
            
            
            $digits = array();
            
           
            if($part>99) {
                $digits[] = floor($part/100)*100;
            }
            
         
            if($mod1=$part%100) {
                $mod2 = $part%10;
                $flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
                if($mod1<20 || !$mod2) {
                    $digits[] = $flag*$mod1;
                } else {
                    $digits[] = floor($mod1/10)*10;
                    $digits[] = $flag*$mod2;
                }
            }
            
           
            $last = abs(end($digits));
            
            
            foreach($digits as $j=>$digit) {
                $digits[$j] = $dic[0][$digit];
            }
            
           
            $digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
            
            
            array_unshift($string, join(' ', $digits));
        }
    }
    
 
    return join(' ', $string);
}


?>
