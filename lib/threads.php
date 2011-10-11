<?php

function threads_top(int $entity_guid){
	$top = elgg_get_entities_from_relationship (array(
		'relationship' => 'top',
		'relationship_guid' => $entity_guid,
		'inverse_relationship' => false,
		'limit' => 1
	));
	$top = $top[0];
	return $top;
}

function threads_parent(int $entity_guid){
	
}

function threads_get_replies(int $options){
	
}

function threads_get_replies_count(int $entity_guid){
	
}

function threads_get_all_replies($options){
	
}

function threads_get_all_replies_count(int $entity_guid){
	
}

function threads_has_replies(int $entity_guid){
	
}

function threads_list_all_replies($options){
	
}

function threads_reply(int $parent_guid, $text, $title=""){
	
}
