define([
    'taoQtiItem/qtiCreator/widgets/states/factory', 
    'taoQtiItem/qtiCreator/widgets/interactions/states/Sleep'
], function(stateFactory, SleepState){
   
    var initSleepState = function initSleepState(){
        var widget      = this.widget;
        var interaction = widget.element;
        widget.on('metaChange', function(data){
            if(data.key === 'responsive'){
                if(data.value === true){
                    interaction.addClass('responsive');
                } else {
                    interaction.removeClass('responsive');
                }
            }
        });
    };

    var exitSleepState = function exitSleepState(){};
 
    return stateFactory.extend(SleepState, initSleepState, exitSleepState); 
});
