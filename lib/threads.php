<?php

function threads_top($entity_guid){
	$entity = get_entity($entity_guid);
	if(elgg_instanceof($entity, 'object', 'topicreply')) {
		if ($entity->top_guid) {
			return get_entity($entity->top_guid);
		}
		
		$top = current(threads_get_all_replies($entity_guid, array(
			'inverse_relationship' => false,
			'limit' => 1,
		)));
		return $top;
	} elseif (elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return $entity;
	}
	return false;
}

function threads_parent($entity_guid){
	$entity = get_entity($entity_guid);
	
	if(elgg_instanceof($entity, 'object', 'topicreply')) {
		if ($entity->parent_guid) {
			return get_entity($entity->parent_guid);
		}
		$parent = current(threads_get_replies($entity_guid, array(
			'inverse_relationship' => false,
			'limit' => 1
		)));
		return $parent;
	}
	return false;
}

function threads_get_replies($entity_guid, $options=array()){
	$options['relationship_guid'] = $entity_guid;
	$defaults = array(
		'relationship' => 'parent',
		'inverse_relationship' => true,
		'order_by' => 'e.time_created asc'
	);
	$options = array_merge($defaults, $options);
	return elgg_get_entities_from_relationship($options);
}

function threads_get_replies_count($entity_guid){
	return threads_get_replies($entity_guid, array('count' => true));
}

function threads_get_all_replies($options){
	$options['relationship_guid'] = $entity_guid;
	$defaults = array(
		'relationship' => 'top',
		'inverse_relationship' => true,
		'order_by' => 'e.time_created asc'
	);
	$options = array_merge($defaults, $options);
	return elgg_get_entities_from_relationship($options);
}

function threads_get_all_replies_count($entity_guid){
	return threads_get_all_replies($entity_guid, array('count' => true));
}

function threads_has_replies($entity_guid){
	return threads_get_all_replies_count($entity_guid) > 0;
}

function threads_list_replies($entity_guid, $options = array()){
	$options['relationship_guid'] = $entity_guid;
	return elgg_view_entity_list(threads_get_replies($entity_guid, $options), $options);
}

function threads_get_last_topic_reply($topic_guid) {
	return current(threads_get_all_replies($topic_guid, array('limit' => 1)));
}

function threads_create($guid, $pars){
	if (empty($guid)) {
		$topic = new ElggObject();
		$topic->subtype = 'groupforumtopic';
	} else {
		// load original file object
		$topic = new ElggObject($guid);
		if (!$topic || !$topic->canEdit()) {
			register_error(elgg_echo('discussion:topic:notfound'));
			forward(REFERER);
		}
	}

	// save parameters
	foreach($pars as $key => $value) {
		$topic->$key = $value;
	}

	return $topic->save();
}

function threads_reply($parent_guid, $text, $title="", $pars=null){
	
	$topic = threads_top($parent_guid);
	$topic_guid = $topic->guid;
	
	// add the reply to the forum topic
	$reply = new ElggObject();
	$reply->subtype = 'topicreply';
	$reply->title = $title ? $title : "Re:" . $topic->title;
	$reply->description = $text;
	$reply->access_id = $topic->access_id;
	$reply->container_guid = $topic->container_guid;
	$reply->parent_guid = $parent_guid;
	$reply->top_guid = $topic_guid;

	// save parameters
	if ($pars) {
		foreach($pars as $key => $value) {
			$reply->$key = $value;
		}
	}

	if($reply->save()){
		$reply->addRelationship($parent_guid, 'parent');
		$reply->addRelationship($topic_guid, 'top');
		return $reply->save();
	} else{
		return false;
	}
}
