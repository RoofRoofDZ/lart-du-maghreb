<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPForms\Forms\Fields\Traits\NumberField as NumberFieldTrait;

/**
 * Number text field.
 *
 * @since 1.0.0
 */
class WPForms_Field_Number extends WPForms_Field {

	use NumberFieldTrait;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information.
		$this->name  = esc_html__( 'Numbers', 'wpforms-lite' );
		$this->type  = 'number';
		$this->icon  = 'fa-hashtag';
		$this->order = 130;

		$this->hooks();
		$this->number_hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.9.4
	 */
	private function hooks() {

		// Define additional field properties.
		add_filter( 'wpforms_field_properties_number', [ $this, 'field_properties' ], 5, 3 );
	}

	/**
	 * Define additional field properties.
	 *
	 * @since 1.9.4
	 *
	 * @param array|mixed $properties Field properties.
	 * @param array       $field      Field settings.
	 * @param array       $form_data  Form data and settings.
	 *
	 * @return array
	 * @noinspection PhpMissingParamTypeInspection
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function field_properties( $properties, $field, $form_data ): array {

		$properties = (array) $properties;

		if ( is_numeric( $field['min'] ?? null ) ) {
			$properties['inputs']['primary']['attr']['min'] = (float) $field['min'];
		}

		if ( is_numeric( $field['max'] ?? null ) ) {
			$properties['inputs']['primary']['attr']['max'] = (float) $field['max'];
		}

		$properties['inputs']['primary']['attr']['step'] = 'any';

		return $properties;
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field data.
	 */
	public function field_options( $field ) {
		/*
		 * Basic field options.
		 */

		// Options open markup.
		$args = [
			'markup' => 'open',
		];

		$this->field_option( 'basic-options', $field, $args );

		// Label.
		$this->field_option( 'label', $field );

		// Description.
		$this->field_option( 'description', $field );

		// Required toggle.
		$this->field_option( 'required', $field );

		// Options close markup.
		$args = [
			'markup' => 'close',
		];

		$this->field_option( 'basic-options', $field, $args );

		/*
		 * Advanced field options.
		 */

		// Options open markup.
		$args = [
			'markup' => 'open',
		];

		$this->field_option( 'advanced-options', $field, $args );

		// Size.
		$this->field_option( 'size', $field );

		// Placeholder.
		$this->field_option( 'placeholder', $field );

		// Min/Max.
		$this->field_number_option_min_max( $field, [ 'class' => 'wpforms-numbers' ] );

		// Default value.
		$this->field_option( 'default_value', $field );

		// Custom CSS classes.
		$this->field_option( 'css', $field );

		// Hide label.
		$this->field_option( 'label_hide', $field );

		// Options close markup.
		$args = [
			'markup' => 'close',
		];

		$this->field_option( 'advanced-options', $field, $args );
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field data.
	 */
	public function field_preview( $field ) {

		// Define data.
		$placeholder   = ! empty( $field['placeholder'] ) ? $field['placeholder'] : '';
		$default_value = ! empty( $field['default_value'] ) ? $field['default_value'] : '';

		// Label.
		$this->field_preview_option( 'label', $field );

		// Primary input.
		echo '<input type="text" placeholder="' . esc_attr( $placeholder ) . '" value="' . esc_attr( $default_value ) . '" class="primary-input" readonly>';

		// Description.
		$this->field_preview_option( 'description', $field );
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field      Field data.
	 * @param array $deprecated Deprecated, not used.
	 * @param array $form_data  Form data.
	 */
	public function field_display( $field, $deprecated, $form_data ) {

		// Define data.
		$primary = $field['properties']['inputs']['primary'];

		// Primary field.
		printf(
			'<input type="number" %s %s>',
			wpforms_html_attributes( $primary['id'], $primary['class'], $primary['data'], $primary['attr'] ),
			esc_attr( $primary['required'] )
		);
	}

	/**
	 * Validate field on form submit.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $field_id     Field id.
	 * @param string $field_submit Submitted field value (raw data).
	 * @param array  $form_data    Form data.
	 */
	public function validate( $field_id, $field_submit, $form_data ) {

		$form_id = $form_data['id'];

		$value = $this->sanitize_value( $field_submit );

		// If field is marked as required, check for entry data.
		if (
			! empty( $form_data['fields'][ $field_id ]['required'] ) &&
			empty( $value ) &&
			! is_numeric( $value )
		) {
			wpforms()->obj( 'process' )->errors[ $form_id ][ $field_id ] = wpforms_get_required_label();
		}

		// Check if value is numeric.
		if ( ! empty( $value ) && ! is_numeric( $value ) ) {
			/**
			 * Filter the error message for the number field.
			 *
			 * @since 1.0.0
			 *
			 * @param string $message Error message.
			 */
			wpforms()->obj( 'process' )->errors[ $form_id ][ $field_id ] = apply_filters( 'wpforms_valid_number_label', esc_html__( 'Please enter a valid number.', 'wpforms-lite' ) ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
		}
	}

	/**
	 * Format and sanitize field.
	 *
	 * @since 1.3.5
	 *
	 * @param int    $field_id     Field id.
	 * @param string $field_submit Submitted value.
	 * @param array  $form_data    Form data.
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		// Define data.
		$name = ! empty( $form_data['fields'][ $field_id ]['label'] ) ? $form_data['fields'][ $field_id ]['label'] : '';

		// Set final field details.
		wpforms()->obj( 'process' )->fields[ $field_id ] = [
			'name'  => sanitize_text_field( $name ),
			'value' => $this->sanitize_value( $field_submit ),
			'id'    => wpforms_validate_field_id( $field_id ),
			'type'  => $this->type,
		];
	}

	/**
	 * Sanitize the value.
	 *
	 * @since 1.5.7
	 *
	 * @param string $value The number field submitted value.
	 *
	 * @return float|int|string
	 */
	private function sanitize_value( $value ) {

		if ( empty( $value ) && ! is_numeric( $value ) ) {
			return '';
		}

		// Some browsers allow other non-digit/decimal characters to be submitted
		// with the num input, which then trips the is_numeric validation below.
		// To get around this we remove all chars that are not expected.
		$signed_value = preg_replace( '/[^-0-9.]/', '', $value );
		$abs_value    = str_replace( '-', '', $signed_value );

		return $signed_value < 0 ? '-' . $abs_value : $abs_value;
	}
}

new WPForms_Field_Number();
