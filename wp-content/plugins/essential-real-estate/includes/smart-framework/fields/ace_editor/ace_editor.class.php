<?php
/**
 * Field Ace Editor
 *
 * @package SmartFramework
 * @subpackage Fields
 * @author g5plus
 * @since 1.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Ace_Editor')) {
	class GSF_Field_Ace_Editor extends GSF_Field
	{
		function enqueue()
		{
			wp_enqueue_script('ace_editor', '//cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js', array( 'jquery' ), '1.2.9', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'ace_editor', GSF_PLUGIN_URL . 'fields/ace_editor/assets/ace-editor.js', array('ace_editor'), GSF_VER, true);
		}
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$params = array(
				'minLines' => 8,
				'maxLines' => 20,
				'showPrintMargin' => false
			);
			if (isset($this->params['min_line'])) {
				$params['minLines'] = $this->params['min_line'];
			}
			if (isset($this->params['max_line'])) {
				$params['maxLines'] = $this->params['max_line'];
			}
			if (isset($this->params['js_options']) && is_array($this->params['js_options'])) {
				$params = wp_parse_args( $this->params['js_options'], $params );
			}
			$mode = isset($this->params['mode']) ? $this->params['mode'] : '';
			$theme = isset($this->params['theme']) ? $this->params['theme'] : '';

			$editor_id = $this->get_id() . '__ace_editor';
			?>
			<div class="gsf-field-ace-editor-inner">
				<textarea data-field-control="" name="<?php echo esc_attr($this->get_name()) ?>" class="gsf-hidden-field "
				          data-mode="<?php echo esc_attr($mode); ?>"
				          data-theme="<?php echo esc_attr($theme); ?>"
				          data-options="<?php echo esc_attr(wp_json_encode($params)); ?>"><?php echo esc_textarea($field_value); ?></textarea>
				<pre class="gsf-ace-editor" id="<?php echo esc_attr($editor_id); ?>"><?php echo htmlspecialchars($field_value); ?></pre>
			</div>
		<?php
		}
	}
}