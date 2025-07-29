<?php

namespace QuadLayers\QLWAPP_PRO\Services;

class Customizer_Control extends \WP_Customize_Control {

public $type = 'textarea';

	public function render_content() {

		$control_id = trim( preg_replace( '/[^a-zA-Z0-9]/', '-', $this->id ), '-' );

		$settings = array(
			'textarea_name' => $this->id,
			'media_buttons' => false,
			'quicktags'     => false,
			'tinymce'       => array(
				'setup' => "function (editor) {console.log(editor);
                  var cb = function () {
                    var linkInput = document.getElementById('{$control_id}-link')
                    linkInput.value = editor.getContent()
                    linkInput.dispatchEvent(new Event('change'))
                  }
                  editor.on('Change', cb)
                  editor.on('Undo', cb)
                  editor.on('Redo', cb)
                  editor.on('KeyUp', cb) // Remove this if it seems like an overkill
                }",
			),
		);
		?>
		<label for="<?php echo esc_attr( $control_id ); ?>">
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<input id="<?php echo esc_attr( $control_id ); ?>-link" class="wp-editor-area" type="hidden" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>">
		</label>
		<?php
		wp_editor( stripslashes( $this->value() ), $control_id, $settings );
		do_action( 'admin_footer' );
		do_action( 'admin_print_footer_scripts' );
	}
}
