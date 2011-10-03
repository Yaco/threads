<?php
/**
 * Post a reply to discussion topic
 *
 */

gatekeeper();

// Get input
$parent_guid = (int) get_input('parent_guid');
$topic_guid = (int) get_input('topic_guid');
$text = get_input('group_topic_post');
$entity_guid = (int) get_input('entity_guid');

// reply cannot be empty
if (empty($text)) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$topic = get_entity($topic_guid);
$parent = get_entity($parent_guid);
if (!$topic || !$parent) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$user = get_loggedin_user();

$group = $topic->getContainerEntity();
if (!$group->canWriteToContainer($user)) {
	register_error(elgg_echo('groups:notmember'));
	forward(REFERER);
}

// if editing a reply, make sure it's valid
if ($entity_guid) {
	$entity = get_entity($entity_guid);
	if (!$entity->canEdit()) {
		register_error(elgg_echo('groups:notowner'));
		forward(REFERER);
	}

	$entity->description = $text;
	if (!$entity->save()) {
		system_message(elgg_echo('groups:forumpost:error'));
		forward(REFERER);
	}
	system_message(elgg_echo('groups:forumpost:edited'));
} else {
	// add the reply to the forum topic
	$entity = new ElggObject();
	$entity->subtype = 'topicreply';
	$entity->title = $title ? $title : "Re:".$topic->title;
	$entity->description = $text;
	$entity->access_id = $topic->access_id;
	$entity->container_guid = $topic->container_guid;
	if($entity->save()){
		$entity->addRelationship($parent_guid, 'parent');
		$entity->addRelationship($topic_guid, 'top');
		$entity->save();
	} else {
		system_message(elgg_echo('groupspost:failure'));
		forward(REFERER);
	}

	//TODO it is not an annotation:
	//add_to_river('river/annotation/group_topic_post/reply', 'reply', $user->guid, $topic->guid, "", 0, $reply_id);
	system_message(elgg_echo('groupspost:success'));
}

forward(REFERER);
