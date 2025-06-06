<?php
/**
 * Class Payment_Information
 *
 * @package WooCommerce\Payments
 */

namespace WCPay;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WCPay\Constants\Payment_Method;
use WCPay\Constants\Payment_Type;
use WCPay\Constants\Payment_Initiated_By;
use WCPay\Constants\Payment_Capture_Type;
use WCPay\Exceptions\Invalid_Payment_Method_Exception;
use WCPay\Payment_Methods\CC_Payment_Gateway;

/**
 * Mostly a wrapper containing information on a single payment.
 */
class Payment_Information {
	/**
	 * Key used to indicate that an error occurred during the payment method creation in the client.
	 *
	 * @type string
	 */
	const PAYMENT_METHOD_ERROR = 'woocommerce_payments_payment_method_error';

	/**
	 * The ID of the payment method used for this payment.
	 *
	 * @var string
	 */
	private $payment_method;

	/**
	 * The order object.
	 *
	 * @var ?\WC_Order
	 */
	private $order;

	/**
	 * The payment token used for this payment.
	 *
	 * @var ?\WC_Payment_Token
	 */
	private $token;

	/**
	 * The CVC confirmation used for this payment.
	 *
	 * @var string
	 */
	private $cvc_confirmation;

	/**
	 * Indicates whether the payment is merchant-initiated (true) or customer-initiated (false).
	 *
	 * @var ?Payment_Initiated_By
	 */
	private $payment_initiated_by;

	/**
	 * Indicates whether the payment will be only authorized (true) or captured immediately (false).
	 *
	 * @var ?Payment_Capture_Type
	 */
	private $manual_capture;

	/**
	 * The type of the payment. `single`, `recurring`, etc.
	 *
	 * @var Payment_Type
	 */
	private $payment_type;

	/**
	 * Indicates whether the payment method should be saved to the store.
	 *
	 * @var bool
	 */
	private $save_payment_method_to_store = false;

	/**
	 * Indicates whether the payment method should be saved to the platform.
	 *
	 * @var bool
	 */
	private $save_payment_method_to_platform = false;

	/**
	 * Indicates whether user is changing payment method for subscriptions order.
	 *
	 * @var bool
	 */
	private $is_changing_payment_method_for_subscription = false;

	/**
	 * The attached fingerprint.
	 *
	 * @var string
	 */
	private $fingerprint = '';

	/**
	 * The Stripe ID of the payment method used for this payment.
	 *
	 * @var string
	 */
	private $payment_method_stripe_id;

	/**
	 * The WCPay Customer ID that owns the payment token.
	 *
	 * @var string
	 */
	private $customer_id;

	/**
	 * Will be set if there was an error during setup.
	 *
	 * @var ?\WP_Error
	 */
	private $error = null;

	/**
	 * Payment information constructor.
	 *
	 * @param string               $payment_method The ID of the payment method used for this payment.
	 * @param \WC_Order            $order The order object.
	 * @param Payment_Type         $payment_type The type of the payment.
	 * @param \WC_Payment_Token    $token The payment token used for this payment.
	 * @param Payment_Initiated_By $payment_initiated_by Indicates whether the payment is merchant-initiated or customer-initiated.
	 * @param Payment_Capture_Type $manual_capture Indicates whether the payment will be only authorized or captured immediately.
	 * @param string               $cvc_confirmation The CVC confirmation for this payment method.
	 * @param string               $fingerprint The attached fingerprint.
	 * @param string               $payment_method_stripe_id The Stripe ID of the payment method used for this payment.
	 * @param string               $customer_id The WCPay Customer ID that owns the payment token.
	 *
	 * @throws Invalid_Payment_Method_Exception When no payment method is found in the provided request.
	 */
	public function __construct(
		string $payment_method,
		?\WC_Order $order = null,
		?Payment_Type $payment_type = null,
		?\WC_Payment_Token $token = null,
		?Payment_Initiated_By $payment_initiated_by = null,
		?Payment_Capture_Type $manual_capture = null,
		?string $cvc_confirmation = null,
		string $fingerprint = '',
		?string $payment_method_stripe_id = null,
		?string $customer_id = null
	) {
		if ( empty( $payment_method ) && empty( $token ) && ! \WC_Payments::is_network_saved_cards_enabled() ) {
			// If network-wide cards are enabled, a payment method or token may not be specified and the platform default one will be used.
			throw new Invalid_Payment_Method_Exception(
				esc_html__( 'Invalid or missing payment details. Please ensure the provided payment method is correctly entered.', 'woocommerce-payments' ),
				'payment_method_not_provided'
			);
		}
		$this->payment_method           = $payment_method;
		$this->order                    = $order;
		$this->token                    = $token;
		$this->payment_initiated_by     = $payment_initiated_by ?? Payment_Initiated_By::CUSTOMER();
		$this->manual_capture           = $manual_capture ?? Payment_Capture_Type::AUTOMATIC();
		$this->payment_type             = $payment_type ?? Payment_Type::SINGLE();
		$this->cvc_confirmation         = $cvc_confirmation;
		$this->fingerprint              = $fingerprint;
		$this->payment_method_stripe_id = $payment_method_stripe_id;
		$this->customer_id              = $customer_id;
	}

	/**
	 * Returns true if payment was initiated by the merchant, false otherwise.
	 *
	 * @return bool True if payment was initiated by the merchant, false otherwise.
	 */
	public function is_merchant_initiated(): bool {
		return $this->payment_initiated_by->equals( Payment_Initiated_By::MERCHANT() );
	}

	/**
	 * Returns the payment method ID.
	 *
	 * @return string The payment method ID.
	 */
	public function get_payment_method(): string {
		// Use the token if we have it.
		if ( $this->is_using_saved_payment_method() ) {
			return $this->token->get_token();
		}

		return $this->payment_method;
	}

	/**
	 * Returns the order object.
	 *
	 * @return ?\WC_Order The order object.
	 */
	public function get_order(): ?\WC_Order {
		return $this->order;
	}

	/**
	 * Returns the payment token.
	 *
	 * TODO: Once php requirement is bumped to >= 7.1.0 change return type to ?\WC_Payment_Token
	 * since the return type is nullable, as per
	 * https://www.php.net/manual/en/functions.returning-values.php#functions.returning-values.type-declaration
	 *
	 * @return ?\WC_Payment_Token The payment token.
	 */
	public function get_payment_token(): ?\WC_Payment_Token {
		return $this->token;
	}

	/**
	 * Update the payment token associated with this payment.
	 *
	 * @param \WC_Payment_Token $token The new payment token.
	 */
	public function set_token( \WC_Payment_Token $token ) {
		$this->token = $token;
	}

	/**
	 * Returns true if the payment token is not empty, false otherwise.
	 *
	 * @return bool True if payment token is not empty, false otherwise.
	 */
	public function is_using_saved_payment_method(): bool {
		return ! empty( $this->token );
	}

	/**
	 * Returns true if the payment should be only authorized, false if it should be captured immediately.
	 *
	 * @return bool True if the payment should be only authorized, false if it should be captured immediately.
	 */
	public function is_using_manual_capture(): bool {
		return $this->manual_capture->equals( Payment_Capture_Type::MANUAL() );
	}

	/**
	 * Payment information constructor.
	 *
	 * @param array                $request Associative array containing payment request information.
	 * @param \WC_Order            $order The order object.
	 * @param Payment_Type         $payment_type The type of the payment.
	 * @param Payment_Initiated_By $payment_initiated_by Indicates whether the payment is merchant-initiated or customer-initiated.
	 * @param Payment_Capture_Type $manual_capture Indicates whether the payment will be only authorized or captured immediately.
	 * @param string               $payment_method_stripe_id The Stripe ID of the payment method used for this payment.
	 *
	 * @throws \Exception - If no payment method is found in the provided request.
	 */
	public static function from_payment_request(
		array $request,
		?\WC_Order $order = null,
		?Payment_Type $payment_type = null,
		?Payment_Initiated_By $payment_initiated_by = null,
		?Payment_Capture_Type $manual_capture = null,
		?string $payment_method_stripe_id = null
	): Payment_Information {
		$payment_method   = self::get_payment_method_from_request( $request );
		$token            = self::get_token_from_request( $request );
		$cvc_confirmation = self::get_cvc_confirmation_from_request( $request );
		$fingerprint      = self::get_fingerprint_from_request( $request );

		if ( isset( $request['is_woopay'] ) && $request['is_woopay'] ) {
			$order->add_meta_data( 'is_woopay', true, true );
			$order->save_meta_data();
		}
		$payment_information = new Payment_Information( $payment_method, $order, $payment_type, $token, $payment_initiated_by, $manual_capture, $cvc_confirmation, $fingerprint, $payment_method_stripe_id );

		if ( self::PAYMENT_METHOD_ERROR === $payment_method ) {
			$error_message = $request['wcpay-payment-method-error-message'] ?? __( "We're not able to process this payment. Please try again later.", 'woocommerce-payments' );
			$error_code    = $request['wcpay-payment-method-error-code'] ?? 'unknown-error';
			$error         = new \WP_Error( $error_code, $error_message );
			$payment_information->set_error( $error );
		}

		return $payment_information;
	}

	/**
	 * Extracts the payment method from the provided request.
	 *
	 * @param array $request Associative array containing payment request information.
	 *
	 * @return string
	 */
	public static function get_payment_method_from_request( array $request ): string {
		foreach ( [ 'wcpay-payment-method', 'wcpay-payment-method-sepa' ] as $key ) {
			if ( ! empty( $request[ $key ] ) ) {
				$normalized = wc_clean( $request[ $key ] );
				return is_string( $normalized ) ? $normalized : '';
			}
		}
		return '';
	}

	/**
	 * Extract the payment token from the provided request.
	 *
	 * TODO: Once php requirement is bumped to >= 7.1.0 set return type to ?\WC_Payment_Token
	 * since the return type is nullable, as per
	 * https://www.php.net/manual/en/functions.returning-values.php#functions.returning-values.type-declaration
	 *
	 * @param array $request Associative array containing payment request information.
	 *
	 * @return \WC_Payment_Token|NULL
	 */
	public static function get_token_from_request( array $request ) {
		$payment_method    = $request['payment_method'] ?? null;
		$token_request_key = 'wc-' . $payment_method . '-payment-token';
		if (
			! isset( $request[ $token_request_key ] ) ||
			'new' === $request[ $token_request_key ]
		) {
			return null;
		}

		//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$token = \WC_Payment_Tokens::get( wc_clean( $request[ $token_request_key ] ) );

		// If the token doesn't belong to this gateway or the current user it's invalid.
		if ( ! $token || $payment_method !== $token->get_gateway_id() || $token->get_user_id() !== get_current_user_id() ) {
			return null;
		}

		return $token;
	}

	/**
	 * Extract the payment CVC confirmation from the provided request.
	 *
	 * @param array $request Associative array containing payment request information.
	 *
	 * @return string|NULL
	 */
	public static function get_cvc_confirmation_from_request( array $request ) {
		$payment_method = $request['payment_method'] ?? null;
		if ( null === $payment_method ) {
			return null;
		}

		$cvc_request_key = 'wc-' . $payment_method . '-payment-cvc-confirmation';
		if (
			! isset( $request[ $cvc_request_key ] ) ||
			'new' === $request[ $cvc_request_key ]
		) {
			return null;
		}

		return $request[ $cvc_request_key ];
	}

	/**
	 * Extracts the fingerprint data from the provided request.
	 *
	 * @param array $request Associative array containing payment request information.
	 *
	 * @return string
	 */
	public static function get_fingerprint_from_request( array $request ) {
		if ( ! empty( $request['wcpay-fingerprint'] ) ) {
			$normalized = wc_clean( $request['wcpay-fingerprint'] );
			return is_string( $normalized ) ? $normalized : '';
		}

		return '';
	}

	/**
	 * Changes the type of the payment.
	 *
	 * @param Payment_Type $type The new type.
	 */
	public function set_payment_type( $type ) {
		$this->payment_type = $type;
	}

	/**
	 * Retrieves the type of the payment.
	 *
	 * @return Payment_Type The payment type.
	 */
	public function get_payment_type() {
		return $this->payment_type;
	}

	/**
	 * Forces the payment method to be saved to merchant store when the payment gets processed.
	 */
	public function must_save_payment_method_to_store() {
		$this->save_payment_method_to_store = true;
	}

	/**
	 * Forces the payment method to be saved to platform when the payment gets processed.
	 */
	public function must_save_payment_method_to_platform() {
		$this->save_payment_method_to_platform = true;
	}

	/**
	 * Indicates whether the payment method needs to be saved to the store for later usage.
	 *
	 * @return bool The flag.
	 */
	public function should_save_payment_method_to_store() {
		return ! $this->is_using_saved_payment_method() && $this->save_payment_method_to_store;
	}

	/**
	 * Indicates whether the payment method needs to be saved to the platform for later usage.
	 *
	 * @return bool The flag.
	 */
	public function should_save_payment_method_to_platform() {
		return ! $this->is_using_saved_payment_method() && $this->save_payment_method_to_platform;
	}

	/**
	 * Mark that we are changing payment for subscriptions order or not.
	 *
	 * @param bool $is_changing_payment_method_for_subscription Whether or not we are changing payment for subscriptions order.
	 */
	public function set_is_changing_payment_method_for_subscription( bool $is_changing_payment_method_for_subscription ) {
		$this->is_changing_payment_method_for_subscription = $is_changing_payment_method_for_subscription;
	}

	/**
	 * Returns the flag of whether or not we are changing payment for subscriptions order.
	 *
	 * @return bool Whether or not we are changing payment for subscriptions order.
	 */
	public function is_changing_payment_method_for_subscription(): bool {
		return $this->is_changing_payment_method_for_subscription;
	}

	/**
	 * Returns the payment method CVC confirmation.
	 *
	 * @return string|NULL The payment method CVC confirmation.
	 */
	public function get_cvc_confirmation() {
		return $this->cvc_confirmation;
	}

	/**
	 * Returns the attached fingerprint.
	 *
	 * @return string The attached fingerprint.
	 */
	public function get_fingerprint() {
		return $this->fingerprint;
	}

	/**
	 * Returns the Stripe ID of payment method.
	 *
	 * @return string The Stripe ID of payment method.
	 */
	public function get_payment_method_stripe_id() {
		return $this->payment_method_stripe_id;
	}

	/**
	 * Returns the WCPay Customer ID that owns the payment token.
	 *
	 * @return string The WCPay Customer ID.
	 */
	public function get_customer_id() {
		return $this->customer_id;
	}


	/**
	 * Sets the error data.
	 *
	 * @param \WP_Error $error The error to be set.
	 * @return void
	 */
	public function set_error( \WP_Error $error ) {
		$this->error = $error;
	}

	/**
	 * Returns the error data.
	 *
	 * @return ?\WP_Error
	 */
	public function get_error() {
		return $this->error;
	}

	/**
	 * Returns true if the payment method is an offline payment method, false otherwise.
	 *
	 * @return bool True if the payment method is an offline payment method, false otherwise.
	 */
	public function is_offline_payment_method(): bool {
		return in_array( $this->payment_method_stripe_id, Payment_Method::OFFLINE_PAYMENT_METHODS, true );
	}
}
