<?php
/**
 * Reply river view
 */
$object = $vars['item']->getObjectEntity();
// Trick: Annotation id contains the reply guid.
$reply = get_entity($vars['item']->annotation_id);
$excerpt = elgg_get_excerpt($reply->description);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));
