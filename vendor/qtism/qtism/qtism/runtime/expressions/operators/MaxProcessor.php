<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package qtism
 *  
 *
 */
namespace qtism\runtime\expressions\operators;

use qtism\common\datatypes\Integer;
use qtism\common\datatypes\Float;
use qtism\common\enums\BaseType;
use qtism\runtime\common\MultipleContainer;
use qtism\runtime\common\Container;
use qtism\data\expressions\Expression;
use qtism\data\expressions\operators\Max;
use \InvalidArgumentException;

/**
 * The MaxProcessor class aims at processing Max QTI Data Model Expression 
 * objects.
 * 
 * From IMS QTI:
 * 
 * The max operator takes 1 or more sub-expressions which all have numerical base-types 
 * and may have single, multiple or ordered cardinality. The result is a single float, 
 * or, if all sub-expressions are of integer type, a single integer, equal in value to 
 * the greatest of the argument values, i.e. the result is the argument closest to 
 * positive infinity. If the arguments have the same value, the result is that same 
 * value. If any of the sub-expressions is NULL, the result is NULL. If any of the 
 * sub-expressions is not a numerical value, then the result is NULL.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class MaxProcessor extends OperatorProcessor {
	
	public function setExpression(Expression $expression) {
		if ($expression instanceof Max) {
			parent::setExpression($expression);
		}
		else {
			$msg = "The MaxProcessor class only accepts Max QTI Data Model Expression objects to be processed.";
			throw new InvalidArgumentException($msg);
		}
	}
	
	/**
	 * Process the current expression.
	 * 
	 * @return float|integer|null The greatest of the operand values or NULL if any of the operand values is NULL.
	 * @throws OperatorProcessingException
	 */
	public function process() {
		$operands = $this->getOperands();
		
		if ($operands->containsNull() === true) {
			return null;
		}
		
		if ($operands->anythingButRecord() === false) {
			$msg = "The Max operator only accept values with a cardinality of single, multiple or ordered.";
			throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
		}
		
		if ($operands->exclusivelyNumeric() === false) {
			// As per QTI 2.1 spec, If any of the sub-expressions is not a numerical value, then the result is NULL.
			return null;
		}
		
		// As per QTI 2.1 spec,
		// The result is a single float, or, if all sub-expressions are of 
		// integer type, a single integer.
		$integerCount = 0;
		$valueCount = 0;
		$max = -PHP_INT_MAX;
		foreach ($operands as $operand) {
			if (!$operand instanceof Container) {
				$baseType = ($operand instanceof Float) ? BaseType::FLOAT : BaseType::INTEGER;
				$value = new MultipleContainer($baseType, array($operand));
			}
			else {
				$value = $operand;
			}
			
			foreach ($value as $v) {
			    
			    if (is_null($v)) {
			        return null;
			    }
			    
				$valueCount++;
				$integerCount += ($v instanceof Integer) ? 1 : 0;
				
				if ($v->getValue() > $max) {
					$max = $v->getValue();
				}	
			}
		}
		
		return ($integerCount === $valueCount) ? new Integer(intval($max)) : new Float(floatval($max));
	}
}