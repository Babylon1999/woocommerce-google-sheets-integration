<?php

/**
 * Plugin Name: WooCommerce Orders Google Sheet Integration
 * Description: A simple development plugin to send WooCommerce order details to Google Sheet.
 * Version: 1.0.0
 * Author: Saif Hassan
 * Author URI: https://saif-hassan.com
 */

if (!defined('WPINC')) {
	die;
}

require __DIR__ . '/vendor/autoload.php';

class Google_Sheets_Integration
{


	private $client;
	private $service;
	private $spreadsheet_id;

	/**
	 * Constructor for Google_Sheets_Integration class.
	 * Initializes the Google Client and sets up action hooks.
	 */
	public function __construct()
	{

		$this->client = new \Google_Client();
		$this->client->setApplicationName('WooCommerce Orders Google Sheet Integration');
		$this->client->setScopes(array(\Google_Service_Sheets::SPREADSHEETS));
		$this->client->setAccessType('offline');
		$this->client->setAuthConfig(__DIR__ . '/creds.json');

		$this->service = new Google_Service_Sheets($this->client);
		// Add the spreadsheet id here.
		$this->spreadsheet_id = 'your_sheet_id';

		// Hooks for WooCommerce actions
		add_action('woocommerce_order_status_completed', array($this, 'send_order_details_to_sheet'), 9, 1);
	}

	/**
	 * Initializes the plugin.
	 */
	public static function init()
	{
		$class = __CLASS__;
		new $class();
	}

	/**
	 * Sends order details to Google Sheet upon payment completion.
	 *
	 * @param int $order_id WooCommerce order ID.
	 */
	public function send_order_details_to_sheet($order_id)
	{

		$order = wc_get_order($order_id);
		// Add the sheet name below.
		$sheet_name = 'sheet1';

		$values = array(
			array(
				strval($order->get_date_created()),
				$order_id,
				/*
				You can add more order related data, you can find more here: https://www.businessbloomer.com/woocommerce-easily-get-order-info-total-items-etc-from-order-object/
				 */
				$order->get_total(),
				$order->get_total_fees(),
				$order->get_shipping_tax(),
				$order->get_shipping_total(),
				$order->get_subtotal(),
				$order->get_total_discount(),
			),
		);

		$body = new Google_Service_Sheets_ValueRange(
			array(
				'values' => $values,
			)
		);

		$params = array(
			'valueInputOption' => 'RAW',
		);

		try {

			$this->service->spreadsheets_values->append(
				$this->spreadsheet_id,
				$sheet_name,
				$body,
				$params
			);
		} catch (Exception $e) {
			wc_get_logger()->warning('Soemthing went wrong with the Google Sheets integration.');
		}
	}
}

// Initialize the plugin
add_action('plugins_loaded', array('Google_Sheets_Integration', 'init'), 10);
