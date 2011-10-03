<?php
/*
 * Embeds an edit link for the annotation
 */

$entity = elgg_extract('entity', $vars);

$owner = get_entity($entity->owner_guid);
if (!$owner) {
	return true;
}
$icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = "<a href=\"{$owner->getURL()}\">$owner->name</a>";

$menu = elgg_view_menu('reply', array(
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz right elgg-menu-annotation',
));

$text = elgg_view("output/longtext", array("value" => $entity->description));

$friendlytime = elgg_view_friendly_time($entity->time_created);

$body = <<<HTML
<div class="mbn">
	$menu
	$owner_link
	<span class="elgg-subtext">
		$friendlytime
	</span>
	$text
</div>
HTML;

echo elgg_view_image_block($icon, $body);

/*if ($annotation->canEdit()) {
	$form = elgg_view_form('discussion/reply/save', array(), array_merge(array(
			'entity' => get_entity($annotation->entity_guid),
			'reply' => $entity
		), $vars)
	);

	echo "<div class=\"hidden mbm\" id=\"edit-annotation-$annotation->id\">$form</div>";
}*/

