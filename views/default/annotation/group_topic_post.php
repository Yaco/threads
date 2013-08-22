<?php
/*
 * Embeds an edit link for the annotation
 *
 * @override mod/groups/views/default/annotation/group_topic_post.php
 */

$annotation = elgg_extract('annotation', $vars);

echo elgg_view('annotation/default', $vars);

if ($annotation->canEdit()) {
	$form = elgg_view_form('discussion/reply/save', array(), array_merge(array(
			'entity' => get_entity($annotation->entity_guid),
			'annotation' => $annotation
		), $vars)
	);

	echo "<div class=\"hidden mbm\" id=\"edit-annotation-$annotation->id\">$form</div>";
}

