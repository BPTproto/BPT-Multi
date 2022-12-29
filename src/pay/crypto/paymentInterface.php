<?php

namespace BPT\pay\crypto;

/**
 * @property $payment_id
 * @property $payment_status
 * @property $pay_address
 * @property $price_amount
 * @property $price_currency
 * @property $pay_amount
 * @property $actually_paid
 * @property $pay_currency
 * @property $order_id
 * @property $order_description
 * @property $purchase_id
 * @property $created_at
 * @property $updated_at
 * @property $outcome_amount
 * @property $outcome_currency
 */
interface paymentInterface {}



/**
 * @param array $params Array of options
 *    $params = [
 *      'price_amount'			=> (int|float) Required. The fiat equivalent of the price to be paid in crypto.
 *      'price_currency'			=> (string) Required. The fiat currency in which the price_amount is specified (usd, eur, etc)
 *      'pay_currency'			=> (string) Required. The crypto currency in which the pay_amount is specified (btc, eth, etc)
 *      'pay_amount'				=> (int|float) Optional. The amount that users have to pay for the order stated in crypto
 *      'ipn_callback_url'		=> (string) Optional. URL to receive callbacks, should contain "http" or "https", eg. "https://nowpayments.io"
 *      'order_id'				=> (string) Optional. Inner store order ID, e.g. "RGDBP-21314"
 *      'order_description'		=> (string) Optional. Inner store order description, e.g. "Apple Macbook Pro 2019 x 1"
 *      'purchase_id'			=> (string) Optional. ID of purchase for which you want to create aother payment, only used for several payments for one order
 *      'payout_address'			=> (string) Optional. Usually the funds will go to the address you specify in your Personal account
 *      'payout_currency'		=> (string) Optional. Currency of your external payout_address, required when payout_adress is specified
 *      'payout_extra_id'		=> (string) Optional. Extra ID or memo or tag for external payout_address
 *      'fixed_rate'				=> (bool) Optional. Required for fixed-rate exchanges
 *    ]
 */