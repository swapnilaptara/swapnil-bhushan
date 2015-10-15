<?php
use oat\tao\helpers\Layout;
?>
<ul id="<?=get_data('actions_id')?>"
    class="plain action-bar <?=get_data('actions_classes')?>">
<?php
$items_array = array('item-search', 'item-filter', 'item-properties', 'item-translate', 'item-authoring' , 'item-preview','test-search', 'test-filter', 'test-properties', 'test-authoring','group-search', 'group-filter', 'group-properties', 'testtaker-search', 'testtaker-filter', 'testtaker-properties' ,'delivery-search', 'delivery-filter', 'delivery-properties', 'results-index', 'results-export');
  
  foreach (get_data('actions') as $action):  
	
	if(in_array($action->getId(), $items_array) )
	{
	?>
	<span class="action <?= get_data('action_classes')?>"
        id="<?=$action->getId()?>"
        title="<?= __($action->getName()) ?>"
        data-context="<?= $action->getContext() ?>"
        data-action="<?= $action->getBinding() ?>">
		<a href="<?= $action->getUrl(); ?>" class="li-inner waves-effect waves-light btn"> <?= Layout::renderIcon( $action->getIcon(), ' icon-magicwand'); ?> <?= __($action->getName()) ?></a>
    </span>
	
	<?php
	}
	
	else
	{
	?>
    <li class="action <?= get_data('action_classes')?>"
        id="<?=$action->getId()?>"
        title="<?= __($action->getName()) ?>"
        data-context="<?= $action->getContext() ?>"
        data-action="<?= $action->getBinding() ?>">
        <a class="li-inner" href="<?= $action->getUrl(); ?>">
            <?= Layout::renderIcon( $action->getIcon(), ' icon-magicwand'); ?> <?= __($action->getName()) ?>
        </a>
		
    </li>
	<?php
	
	}
	?>
	
	
	
    <?php endforeach; ?>
</ul>
