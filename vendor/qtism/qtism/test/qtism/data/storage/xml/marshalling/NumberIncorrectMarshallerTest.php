<?php

use qtism\data\storage\xml\marshalling\Marshaller;
use qtism\data\expressions\NumberIncorrect;
use qtism\common\collections\IdentifierCollection;
use \DOMDocument;

require_once (dirname(__FILE__) . '/../../../../../QtiSmTestCase.php');

class NumberIncorrectMarshallerTest extends QtiSmTestCase {

	public function testMarshall() {

		$sectionIdentifier = 'mySection1';
		$includeCategory = 'cat1';
		$excludeCategory = 'cat2 cat3';
		
		$component = new NumberIncorrect();
		$component->setSectionIdentifier($sectionIdentifier);
		$component->setIncludeCategories(new IdentifierCollection(explode("\x20", $includeCategory)));
		$component->setExcludeCategories(new IdentifierCollection(explode("\x20", $excludeCategory)));
		$marshaller = $this->getMarshallerFactory()->createMarshaller($component);
		$element = $marshaller->marshall($component);
		
		$this->assertInstanceOf('\\DOMElement', $element);
		$this->assertEquals('numberIncorrect', $element->nodeName);
		$this->assertEquals($sectionIdentifier, $element->getAttribute('sectionIdentifier'));
		$this->assertEquals($includeCategory, $element->getAttribute('includeCategory'));
		$this->assertEquals($excludeCategory, $element->getAttribute('excludeCategory'));
	}
	
	public function testUnmarshall() {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXML('<numberIncorrect xmlns="http://www.imsglobal.org/xsd/imsqti_v2p1" sectionIdentifier="mySection1" includeCategory="cat1" excludeCategory="cat2 cat3"/>');
		$element = $dom->documentElement;
		
		$marshaller = $this->getMarshallerFactory()->createMarshaller($element);
		$component = $marshaller->unmarshall($element);
		
		$this->assertInstanceOf('qtism\\data\\expressions\\NumberIncorrect', $component);
		$this->assertEquals($component->getSectionIdentifier(), 'mySection1');
		$this->assertEquals('cat1', implode("\x20", $component->getIncludeCategories()->getArrayCopy()));
		$this->assertEquals('cat2 cat3', implode("\x20", $component->getExcludeCategories()->getArrayCopy()));
	}
}