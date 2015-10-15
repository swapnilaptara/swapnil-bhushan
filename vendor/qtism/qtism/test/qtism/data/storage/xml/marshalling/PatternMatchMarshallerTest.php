<?php

use qtism\data\storage\xml\marshalling\Marshaller;
use qtism\data\expressions\ExpressionCollection;
use qtism\data\expressions\operators\PatternMatch;
use qtism\data\expressions\BaseValue;
use qtism\common\enums\BaseType;
use \DOMDocument;

require_once (dirname(__FILE__) . '/../../../../../QtiSmTestCase.php');

class PatternMatchMarshallerTest extends QtiSmTestCase {

	public function testMarshall() {

		$subs = new ExpressionCollection();
		$subs[] = new BaseValue(BaseType::STRING, 'Hello World');
		
		$pattern = "^Hello World$";
		
		$component = new PatternMatch($subs, $pattern);
		$marshaller = $this->getMarshallerFactory()->createMarshaller($component);
		$element = $marshaller->marshall($component);
		
		$this->assertInstanceOf('\\DOMElement', $element);
		$this->assertEquals('patternMatch', $element->nodeName);
		$this->assertEquals($pattern, $element->getAttribute('pattern'));
		$this->assertEquals(1, $element->getElementsByTagName('baseValue')->length);
	}
	
	public function testUnmarshall() {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXML(
			'
			<patternMatch xmlns="http://www.imsglobal.org/xsd/imsqti_v2p1" pattern="^Hello World$">
				<baseValue baseType="string">Hello World</baseValue>
			</patternMatch>
			'
		);
		$element = $dom->documentElement;
		
		$marshaller = $this->getMarshallerFactory()->createMarshaller($element);
		$component = $marshaller->unmarshall($element);
		
		$this->assertInstanceOf('qtism\\data\\expressions\\operators\\PatternMatch', $component);
		$this->assertEquals('^Hello World$', $component->getPattern());
		$this->assertEquals(1, count($component->getExpressions()));
	}
}