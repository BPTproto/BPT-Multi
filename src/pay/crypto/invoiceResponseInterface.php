<?php

namespace BPT\pay\crypto;

/**
 * @property string $id
 * @property string $token_id
 * @property int|string $order_id
 * @property string $order_description
 * @property string $price_amount
 * @property string $price_currency
 * @property string|null $pay_currency
 * @property string|null $ipn_callback_url
 * @property string|null $invoice_url
 * @property string|null $success_url
 * @property string|null $cancel_url
 * @property string|null $partially_paid_url
 * @property $payout_currency
 * @property string $created_at
 * @property string $updated_at
 * @property bool $is_fixed_rate
 * @property bool $is_fee_paid_by_user
 */
interface invoiceResponseInterface {}