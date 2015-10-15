<?php

use qtism\data\content\InlineStaticCollection;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\TextRun;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;

require_once (dirname(__FILE__) . '/../../../../../QtiSmTestCase.php');

class ChoiceInteractionMarshallerTest extends QtiSmTestCase {

	public function testMarshall() {
		
        $choice1 = new SimpleChoice('choice_1');
        $choice1->setContent(new FlowStaticCollection(array(new TextRun('Choice #1'))));
        $choice2 = new SimpleChoice('choice_2');
        $choice2->setContent(new FlowStaticCollection(array(new TextRun('Choice #2'))));
        $choices = new SimpleChoiceCollection(array($choice1, $choice2));
        
        $component = new ChoiceInteraction('RESPONSE', $choices);
        $prompt = new Prompt();
        $prompt->setContent(new FlowStaticCollection(array(new TextRun('Prompt...'))));
        $component->setPrompt($prompt);
        
        $marshaller = $this->getMarshallerFactory()->createMarshaller($component);
        $element = $marshaller->marshall($component);
        
        $dom = new DOMDocument('1.0', 'UTF-8');
        $element = $dom->importNode($element, true);
        $this->assertEquals('<choiceInteraction responseIdentifier="RESPONSE"><prompt>Prompt...</prompt><simpleChoice identifier="choice_1">Choice #1</simpleChoice><simpleChoice identifier="choice_2">Choice #2</simpleChoice></choiceInteraction>', $dom->saveXML($element));
	}
	
	public function testUnmarshall() {
        $element = $this->createDOMElement('
            <choiceInteraction responseIdentifier="RESPONSE"><prompt>Prompt...</prompt><simpleChoice identifier="choice_1">Choice #1</simpleChoice><simpleChoice identifier="choice_2">Choice #2</simpleChoice></choiceInteraction>
        ');
        
        $marshaller = $this->getMarshallerFactory()->createMarshaller($element);
        $component = $marshaller->unmarshall($element);
        
        $this->assertInstanceOf('qtism\\data\\content\\interactions\\ChoiceInteraction', $component);
        $this->assertEquals('RESPONSE', $component->getResponseIdentifier());
        $this->assertFalse($component->mustShuffle());
        $this->assertEquals(Orientation::VERTICAL, $component->getOrientation());
        $this->assertTrue($component->hasPrompt());
        
        $prompt = $component->getPrompt();
        $content = $prompt->getContent();
        $this->assertEquals('Prompt...', $content[0]->getContent());
        
        $simpleChoices = $component->getSimpleChoices();
        $this->assertEquals(2, count($simpleChoices));
	}
}