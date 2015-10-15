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

namespace qtism\runtime\rendering\markup\xhtml;

use qtism\runtime\rendering\AbstractRenderingContext;
use qtism\data\QtiComponent;
use \DOMDocumentFragment;

/**
 * GapChoice renderer, the base class of all renderers that render subclasses of
 * qti:gapChoice (in other words qti:gapText and qti:gapImg). This renderer will 
 * transform the gapChoice into a 'div' element. Rendered elements will also receive
 * the additional 'qti-gapChoice' CSS class.
 *
 * Depending on the value of the qti:choice->showHide attribute and only if
 * a value for qti:choice->templateIdentifier is defined, an additional CSS class with
 * a value of 'qti-show' or 'qti-hide' will be set.
 *
 * Moreover, the following data will be set to the data set of the element
 * with the help of the data-X attributes:
 *
 * * data-identifier = qti:choice->identifier
 * * data-fixed = qti:choice->fixed
 * * data-template-identifier = qti:choice->templateIdentifier (only if qti:choice->templateIdentifier is set).
 * * data-show-hide = qti:choice->showHide (only if qti:choice->templateIdentifier is set).
 * * data-match-max = qti:gapChoice->matchMax
 * * data-match-min = qti:gapChoice->matchMin
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
abstract class GapChoiceRenderer extends ChoiceRenderer {

    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component, $base = '') {
        parent::appendAttributes($fragment, $component, $base);
        $this->additionalClass('qti-gapChoice');
        
        $fragment->firstChild->setAttribute('data-match-max', $component->getMatchMax());
        $fragment->firstChild->setAttribute('data-match-min', $component->getMatchMin());
    }
}