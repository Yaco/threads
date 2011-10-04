<?php
/**
 * List replies with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['box']['show_box'] Display reply/edit form or not
 */

$show_box = elgg_extract('show_box', $vars['box'], false);

echo '<div id="group-replies" class="mtl">';

$options = array(
	'relationship_guid' => $vars['entity']->getGUID(),
	'relationship' => 'top',
	'inverse_relationship' => true,
);

$html = elgg_list_entities_from_relationship($options);
if ($html) {
	echo '<h3>' . elgg_echo('group:replies') . '</h3>';
	echo $html;
}

$entity = get_entity((int) $vars['box']['guid']);

if ($show_box && $entity) {
	$form_vars = array('class' => 'mtm');
	
	$vars['entity'] = $entity;
	$vars['reply'] = ($show_box == 'reply');
	
	echo elgg_view_form('discussion/reply/save', $form_vars, $vars);
}

echo '</div>';
