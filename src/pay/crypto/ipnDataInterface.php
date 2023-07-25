<?php

namespace BPT\pay\crypto;

/**
 * @property int $payment_id
 * @property int $invoice_id
 * @property string $payment_status
 * @property string $pay_address
 * @property $payin_extra_id
 * @property float|int $price_amount
 * @property string $price_currency
 * @property float|int $pay_amount
 * @property float|int $actually_paid
 * @property float|int $actually_paid_at_fiat
 * @property string $pay_currency
 * @property string|int $order_id
 * @property string $order_description
 * @property string $purchase_id
 * @property string $created_at
 * @property string $updated_at
 * @property float|int $outcome_amount
 * @property string $outcome_currency
 */
interface ipnDataInterface extends paymentInterface {
}
