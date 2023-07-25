<?php

namespace BPT\pay\crypto;

/**
 * @property string $payment_id
 * @property string $payment_status
 * @property string $pay_address
 * @property float|int $price_amount
 * @property string $price_currency
 * @property float|int $pay_amount
 * @property string $pay_currency
 * @property int|string $order_id
 * @property string $order_description
 * @property string $ipn_callback_url
 * @property string $created_at
 * @property string $updated_at
 * @property string $purchase_id
 * @property float|int $amount_received
 * @property $payin_extra_id
 * @property $smart_contract
 * @property string $network
 * @property $network_precision
 * @property $time_limit
 * @property $burning_percent
 * @property string $expiration_estimate_date
 * @property bool $is_fixed_rate
 * @property bool $is_fee_paid_by_user
 * @property string $valid_until
 * @property string $type
 */
interface invoicePaymentInterface {
}