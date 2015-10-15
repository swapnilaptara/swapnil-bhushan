<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *   
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * 
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 * 
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package 
 */


namespace qtism\data\expressions\operators;

use qtism\common\enums\Cardinality;
use qtism\data\expressions\ExpressionCollection;

/**
 * From IMS QTI:
 * 
 * The gcd operator takes 1 or more sub-expressions which all have base-type 
 * integer and may have single, multiple or ordered cardinality. The result 
 * is a single integer equal in value to the greatest common divisor (gcd) 
 * of the argument values. If all the arguments are zero, the result is 0, 
 * gcd(0,0)=0; authors should beware of this in calculations which require 
 * division by the gcd of random values. If some, but not all, of the arguments 
 * are zero, the result is the gcd of the non-zero arguments, gcd(0,n)=n if n<>0. 
 * If any of the sub-expressions is NULL, the result is NULL. If any of the 
 * sub-expressions is not a numerical value, then the result is NULL.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class Gcd extends Operator {
	
	public function __construct(ExpressionCollection $expressions) {
		parent::__construct($expressions, 1, -1, array(Cardinality::SINGLE, Cardinality::MULTIPLE, Cardinality::ORDERED), array(OperatorBaseType::INTEGER));
	}
	
	public function getQtiClassName() {
		return 'gcd';
	}
}
