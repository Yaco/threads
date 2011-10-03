<?php
/**
 * Discussion topic reply form body
 *
 * @uses $vars['entity'] A discussion topic object
 * @uses $vars['parent'] The discussion topic or reply that we are replying
 * @uses $vars['inline'] Display a shortened form?
 */

if (isset($vars['entity']) && elgg_is_logged_in()) {

	if(empty($vars['parent'])){
		$vars['parent'] = $vars['entity'];
	}

	echo elgg_view('input/hidden', array(
		'name' => 'topic_guid',
		'value' => $vars['entity']->getGUID(),
	));
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent']->getGUID(),
	));

	$inline = elgg_extract('inline', $vars, false);

	$reply = elgg_extract('reply', $vars);
	
	$value = '';

	if ($reply) {
		$value = $reply->description;
		echo elgg_view('input/hidden', array(
			'name' => 'entity_guid',
			'value' => $reply->guid
		));
	}

	if ($inline) {
		echo elgg_view('input/text', array('name' => 'group_topic_post', 'value' => $value));
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
	} else {
?>
	<div>
		<label>
		<?php
			if ($reply) {
				echo elgg_echo('edit');
			} else {
				echo elgg_echo("reply");
			}
		?>
		</label>
		<?php echo elgg_view('input/longtext', array('name' => 'group_topic_post', 'value' => $value)); ?>
	</div>
	<div class="elgg-foot">
<?php
	if ($reply) {
		echo elgg_view('input/submit', array('value' => elgg_echo('save')));
	} else {
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
	}
?>
	</div>
<?php
	}
}
