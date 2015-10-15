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

use qtism\common\datatypes\Float;
use qtism\common\datatypes\Integer;
use qtism\common\datatypes\Scalar;
use qtism\common\enums\BaseType;
use qtism\runtime\common\MultipleContainer;
use qtism\data\expressions\operators\Product;
use qtism\data\expressions\Expression;
use \InvalidArgumentException;

/**
 * The ProductProcessor class aims at processing Product QTI Data Model Operators.
 * 
 * From IMS QTI:
 * 
 * The product operator takes 1 or more sub-expressions which all have numerical 
 * base-types and may have single, multiple or ordered cardinality. The result is 
 * a single float or, if all sub-expressions are of integer type, a single integer 
 * that corresponds to the product of the numerical values of the sub-expressions. 
 * If any of the sub-expressions are NULL then the operator results in NULL.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class ProductProcessor extends OperatorProcessor {
	
	/**
	 * Set the Product Expression object to be processed.
	 * 
	 * @param Expression $expression A Product object.
	 * @throws InvalidArgumentException If $expression is not an instance of Product.
	 */
	public function setExpression(Expression $expression) {
		if ($expression instanceof Product) {
			parent::setExpression($expression);
		}
		else {
			$msg = "The ProductProcessor class only accepts a Product Operator to be processed.";
			throw new InvalidArgumentException($msg);
		}
	}
	
	/**
	 * Process the Product operator.
	 * 
	 * @throws OperatorProcessingException If invalid operands are given.
	 */
	public function process() {
		
		$operands = $this->getOperands();
		
		if ($operands->containsNull() === true) {
			return null;
		}
		else if ($operands->anythingButRecord() === false) {
			$msg = "The Product operator only accepts operands with a single, multiple or ordered cardinality.";
			throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
		}
		else if ($operands->exclusivelyNumeric() === false) {
			$msg = "The Product operator only accepts operands with integer or float baseType.";
			throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
		}
		
		$returnValue = 1;
		
		foreach ($this->getOperands() as $operand) {
			if ($operand instanceof Scalar) {
				$returnValue *= $operand->getValue();
			}
			else {
				foreach ($operand as $val) {
					$returnValue *= $val->getValue();
				}
			}
		}
		
		return (is_int($returnValue)) ? new Integer($returnValue) : new Float($returnValue);
	}
}