<?php

namespace PlistaTest\Orp\medium\Sdk;
use Plista\Orp\Sdk;

require_once __DIR__ . '/../../../bootstrap.php';

class VectorSequenceTest extends \PHPUnit_Framework_TestCase {

	public function testFromJson() {
	// in oder to avoid error messages during testing, remove /n and /t from JSON string example.
		$example = '{
			"type": "impression",
			"context": {
				"simple": {
					"27": 418 ,
					"25": 130731742 ,
					"4": 312613 ,
					"52": 0,
					"14": 31721 ,
					"19": 52193 ,
					"24": 0,
					"6": 431247 ,
					"5": 86 ,
					"47": 654013 ,
					"18": 0,
					"17": 48985 ,
					"22": 65121 ,
					"31": 0,
					"13": 2,
					"9": 26890 ,
					"23": 17 ,
					"57": 1331571080
				},
				"lists": {
					"8": [18841 , 18842 , 48511] ,
					"10": [9 , 10] ,
					"11": [2045611]
				},
				"clusters": {
					"33": {
						"82427": 11 ,
						"8896": 7,
						"33453554": 4,
						"296087": 3,
						"56332": 3 ,
						"689251": 2,
						"27499": 1 ,
						"32941772": 1,
						"70764": 1 ,
						"17128": 0
						},
					"2": [12 , 13 , 42 , 90 , 46 , 29 , 19] ,
					"46": {
						"472419": 255 ,
						"472358": 255 ,
						"472441": 255
					},
					"1": {
						"7": 255
					},
					"3": [43 , 24 , 44 , 105 , 20 , 16]
				}
			},
			"recs": {
						"ints": {
							"3": [130106300 , 84799192]
						}
						"floats": {
							"2": [0.4, 0.1]
						}
			},
			"timestamp": 1372175999641
			}';

		$object = \Plista\Orp\Sdk\VectorSequence::fromJson($example);

		/**
		 * testing Type values
		 */
		$this->assertEquals($object->getType()->getValue(), 'impression');

		/**
		 * testing context values
		 */
		$this->assertEquals($object->getContext()->getGender(), array(7 => 255)); //1
		$this->assertEquals($object->getContext()->getAge(), array(12, 13, 42, 90, 46, 29, 19)); //2
		$this->assertEquals($object->getContext()->getIncome(), array(43, 24, 44, 105, 20, 16)); //3
		$this->assertEquals($object->getContext()->getBrowser(), 312613); //4
		$this->assertEquals($object->getContext()->getIsp(), 86); //5
		$this->assertEquals($object->getContext()->getOs(), 431247); //6
		$this->assertEquals($object->getContext()->getGeo_user(), null); //7
		$this->assertEquals($object->getContext()->getPublisher_filter(), array(18841, 18842, 48511)); //8
		$this->assertEquals($object->getContext()->getGeo_user(), null); //9
		$this->assertEquals($object->getContext()->getChannel(), array(9, 10)); //10
		$this->assertEquals($object->getContext()->getCategory(), array(2045611)); //11
		$this->assertEquals($object->getContext()->getDo_not_track(), 2); //13
		$this->assertEquals($object->getContext()->getSubid(), 52193); //19
		$this->assertEquals($object->getContext()->getPublisher(), 418); //27
		$this->assertEquals($object->getContext()->getKeyword(), array(82427 => 11, 8896 => 7, 33453554 => 4, 296087 => 3, 56332 => 3, 689251 => 2, 27499 => 1, 32941772 => 1, 70764 => 1, 17128 => 0)); //33
		$this->assertEquals($object->getContext()->getCategory_sem(), array(472441 => 255, 472358 => 255, 472419 => 255)); //46

		/**
		 * testing recs values
		 */
		$this->assertEquals($object->getRecs()->getScores(), array(130106300, 84799192));

		/**
		 * testing timestamp values
		 */
		$this->assertEquals($object->getTimestamp()->getTimestampValue(), 1372175999641);
	}
}
