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
 * @property int|float $actually_paid
 * @property int|float $actually_paid_at_fiat
 * @property string $pay_currency
 * @property int|string $order_id
 * @property string $order_description
 * @property int $purchase_id
 * @property string $created_at
 * @property string $updated_at
 * @property float|int $outcome_amount
 * @property string $outcome_currency
 * @property $payout_hash
 * @property $payin_hash
 * @property $burning_percent
 * @property string $type
 */
interface paymentInterface {}