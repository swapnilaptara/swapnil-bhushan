<div class="qti-interaction qti-blockInteraction qti-associateInteraction" data-serial="{{serial}}" data-qti-class="associateInteraction">
    {{#if prompt}}{{{prompt}}}{{/if}}
    <div class="instruction-container"></div>
    <ul class="choice-area clearfix  none block-listing solid horizontal source" data-eyecatcher=">li">
        {{#choices}}{{{.}}}{{/choices}}
    </ul>
    <ul class="result-area none target clearfix">
    </ul>
    <div class="notification-container"></div>
</div>