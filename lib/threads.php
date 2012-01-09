<?php

function threads_top(int $entity_guid){
	$entity = get_entity($entity_guid);
	if(elgg_instanceof($entity, 'object', 'topicreply')) {
		
		if ($entity->top_guid) {
			return get_entity($entity->top_guid);
		}
		
		$top = current(elgg_get_entities_from_relationship (array(
			'relationship' => 'top',
			'relationship_guid' => $entity_guid,
			'inverse_relationship' => false,
			'limit' => 1
			)));
		return $top;
	} elseif (elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		return $entity;
	}
	return false;
}

function threads_parent(int $entity_guid){
	
	$entity = get_entity($entity_guid);
	
	if(elgg_instanceof($entity, 'object', 'topicreply')) {
		if ($entity->parent_guid) {
			return get_entity($entity->parent_guid);
		}
		$parent = current(elgg_get_entities_from_relationship (array(
			'relationship' => 'parent',
			'relationship_guid' => $entity_guid,
			'inverse_relationship' => false,
			'limit' => 1
			)));
		return $parent;
	}
	return false;
}

function threads_get_replies(int $entity_guid, $options=array()){
	$options['relationship_guid'] = $entity_guid;
	$defaults = array(
		'relationship' => 'parent',
		'inverse_relationship' => true,
		'order_by' => 'e.time_created asc'
	);
	$options = array_merge($default, $options);
	return elgg_get_entities_from_relationship($options);
}

function threads_get_replies_count(int $entity_guid){
	$options = array(
		'relationship_guid' => $entity_guid,
		'relationship' => 'parent',
		'inverse_relationship' => true,
		'count' => true
	);
	return elgg_get_entities_from_relationship($options);
}

function threads_get_all_replies($options){
	$options['relationship_guid'] = $entity_guid;
	$defaults = array(
		'relationship' => 'top',
		'inverse_relationship' => true,
		'order_by' => 'e.time_created asc'
	);
	$options = array_merge($default, $options);
	return elgg_get_entities_from_relationship($options);
}

function threads_get_all_replies_count(int $entity_guid){
		$options = array(
		'relationship_guid' => $entity_guid,
		'relationship' => 'top',
		'inverse_relationship' => true,
		'count' => true
	);
	return elgg_get_entities_from_relationship($options);
}

function threads_has_replies(int $entity_guid){
	return threads_get_all_replies_count($entity_guid) > 0;
}

function threads_list_replies(int $entity_guid, $options=array()){
	$options['relationship_guid'] = $entity_guid;
	$defaults = array(
		'relationship' => 'parent',
		'inverse_relationship' => true,
		'order_by' => 'e.time_created asc'
	);
	$options = array_merge($default, $options);
	return elgg_list_entities_from_relationship($options);
}

function threads_reply(int $parent_guid, $text, $title=""){
	
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
	if($reply->save()){
		$reply->addRelationship($parent_guid, 'parent');
		$reply->addRelationship($topic_guid, 'top');
		return $reply->save();
	} else{
		return false;
	}
}
