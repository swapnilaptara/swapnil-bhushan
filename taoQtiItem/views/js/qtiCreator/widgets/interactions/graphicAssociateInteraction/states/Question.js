/**
 * @author Bertrand Chevrier <bertrand@taotesting.com>
 */
define([
    'jquery',
    'lodash',
    'i18n',
    'taoQtiItem/qtiCommonRenderer/helpers/Graphic',
    'taoQtiItem/qtiCreator/widgets/states/factory',
    'taoQtiItem/qtiCreator/widgets/interactions/blockInteraction/states/Question',
    'taoQtiItem/qtiCreator/widgets/interactions/helpers/graphicInteractionShapeEditor',
    'taoQtiItem/qtiCreator/widgets/interactions/helpers/imageSelector',
    'taoQtiItem/qtiCreator/widgets/helpers/formElement',
    'taoQtiItem/qtiCreator/widgets/interactions/helpers/formElement',
    'taoQtiItem/qtiCreator/widgets/helpers/identifier',
    'tpl!taoQtiItem/qtiCreator/tpl/forms/interactions/graphicAssociate',
    'tpl!taoQtiItem/qtiCreator/tpl/forms/choices/associableHotspot',
    'taoQtiItem/qtiCreator/helper/dummyElement',
    'taoQtiItem/qtiCreator/helper/panel'
], function($, _, __, GraphicHelper, stateFactory, Question, shapeEditor, imageSelector, formElement, interactionFormElement,  identifierHelper, formTpl, choiceFormTpl, dummyElement, panel){

    /**
     * Question State initialization: set up side bar, editors and shae factory
     */
    var initQuestionState = function initQuestionState(){

        var widget      = this.widget;
        var interaction = widget.element;
        var options     = widget.options; 
        var paper       = interaction.paper;

        if(!paper){
            return;
        }

        var $choiceForm  = widget.choiceForm;
        var $formInteractionPanel = $('#item-editor-interaction-property-bar');
        var $formChoicePanel = $('#item-editor-choice-property-bar');

        var $left, $top, $width, $height;

        //instantiate the shape editor, attach it to the widget to retrieve it during the exit phase
        widget._editor = shapeEditor(widget, {
            shapeCreated : function(shape, type){
                var newChoice = interaction.createChoice({
                    shape  : type === 'path' ? 'poly' : type,
                    coords : GraphicHelper.qtiCoords(shape) 
                });

                //link the shape to the choice
                shape.id = newChoice.serial;
            },
            shapeRemoved : function(id){
                interaction.removeChoice(id);
            },
            enterHandling : function(shape){
                enterChoiceForm(shape.id);
            },
            quitHandling : function(){
                leaveChoiceForm();
            },
            shapeChange : function(shape){
                var bbox;
                var choice = interaction.getChoice(shape.id);
                if(choice){
                    choice.attr('coords', GraphicHelper.qtiCoords(shape));
    
                    if($left && $left.length){
                        bbox = shape.getBBox();
                        $left.val(parseInt(bbox.x, 10)); 
                        $top.val(parseInt(bbox.y, 10));
                        $width.val(parseInt(bbox.width, 10));
                        $height.val(parseInt(bbox.height, 10));                         
                    }         
                }
            }
        });
    
        //and create an instance
        widget._editor.create();

        /**
         * Set up the choice form
         * @private
         * @param {String} serial - the choice serial
         */
        function enterChoiceForm(serial){
            var choice = interaction.getChoice(serial);
            var element, bbox;

            if(choice){
                
                //get shape bounding box
                element = interaction.paper.getById(serial);
                bbox = element.getBBox();

                $choiceForm.empty().html(
                    choiceFormTpl({
                        identifier  : choice.id(),
                        fixed       : choice.attr('fixed'),
                        serial      : serial,
                        matchMin    : choice.attr('matchMin'),
                        matchMax    : choice.attr('matchMax'),
                        choicesCount: _.size(interaction.getChoices()),
                        x           : parseInt(bbox.x, 10), 
                        y           : parseInt(bbox.y, 10),
                        width       : parseInt(bbox.width, 10),
                        height      : parseInt(bbox.height, 10)                         
                    })
                );

                formElement.initWidget($choiceForm);

                //init data validation and binding
                var callbacks = formElement.getMinMaxAttributeCallbacks($choiceForm, 'matchMin', 'matchMax');
                callbacks.identifier = identifierHelper.updateChoiceIdentifier;
                callbacks.fixed = formElement.getAttributeChangeCallback();

                formElement.setChangeCallbacks($choiceForm, choice, callbacks);

                $formChoicePanel.show();
                panel.openSections($formChoicePanel.children('section'));
                panel.closeSections($formInteractionPanel.children('section'));
                
                //change the nodes bound to the position fields
                $left   = $('input[name=x]', $choiceForm);
                $top    = $('input[name=y]', $choiceForm);
                $width  = $('input[name=width]', $choiceForm);
                $height = $('input[name=height]', $choiceForm);
            }
        }
        
        /**
         * Leave the choice form
         * @private
         */
        function leaveChoiceForm(){
            if($formChoicePanel.css('display') !== 'none'){
                panel.openSections($formInteractionPanel.children('section'));
                $formChoicePanel.hide();
                $choiceForm.empty();
            }
        }
    };

    /**
     * Exit the question state, leave the room cleaned up
     */
    var exitQuestionState = function initQuestionState(){
        var widget      = this.widget;
        var interaction = widget.element;
        var paper       = interaction.paper;

        if(!paper){
            return;
        }
        
        $(window).off('resize.changestate');

        if(widget._editor){
            widget._editor.destroy();
        }
    };
    
    /**
     * The question state for the graphicAssociate interaction
     * @extends taoQtiItem/qtiCreator/widgets/interactions/blockInteraction/states/Question
     * @exports taoQtiItem/qtiCreator/widgets/interactions/graphicAssociateInteraction/states/Question
     */
    var GraphicAssociateInteractionStateQuestion = stateFactory.extend(Question, initQuestionState, exitQuestionState);

    /**
     * Initialize the form linked to the interaction
     */
    GraphicAssociateInteractionStateQuestion.prototype.initForm = function(){

        var widget = this.widget;
        var options = widget.options;
        var interaction = widget.element;
        var $form = widget.$form;

        /**
         * Get the maximum number of pairs regarding the number of choices: f(n) = n(n-1)/2
         * @param {Number} choices - the number of choices
         * @returns {Number} the number of possible pairs
         */ 
        var getMaxPairs = function getMaxPairs(choices){
            var pairs = 0;
            if(choices > 0){
                return Math.round((choices * (choices - 1)) / 2);
            } 
            return pairs;
        };

        $form.html(formTpl({
            baseUrl         : options.baseUrl,
            maxAssociations : parseInt(interaction.attr('maxAssociations'), 10),
            minAssociations : parseInt(interaction.attr('minAssociations'), 10),
            choicesCount    : getMaxPairs(_.size(interaction.getChoices())),
            data            : interaction.object.attr('data'),
            width           : interaction.object.attr('width'),
            height          : interaction.object.attr('height'),
            type            : interaction.object.attr('type')
        }));

        imageSelector($form, options); 

        formElement.initWidget($form);
        
        //init data change callbacks
        var callbacks = formElement.getMinMaxAttributeCallbacks($form, 'minAssociations', 'maxAssociations');
        callbacks.data = function(inteaction, value){
            interaction.object.attr('data', value);
            widget.rebuild({
                ready:function(widget){
                    widget.changeState('question');
                }
            });
        };
        callbacks.width = function(inteaction, value){
            interaction.object.attr('width', value);
        };
        callbacks.height = function(inteaction, value){
            interaction.object.attr('height', value);
        };
        callbacks.type = function(inteaction, value){
            if(!value || value === ''){
                interaction.object.removeAttr('type');
            } else {
                interaction.object.attr('type', value);
            }
        };
        formElement.setChangeCallbacks($form, interaction, callbacks, { validateOnInit : false });
        
        interactionFormElement.syncMaxChoices(widget, 'minAssociations', 'maxAssociations');
    };

    return GraphicAssociateInteractionStateQuestion;
});
