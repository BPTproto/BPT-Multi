<?php

namespace BPT\pay\idpay;

/**
 * @property int $status
 * @property int $track_id
 * @property string $id
 * @property string $order_id
 * @property int $amount
 * @property object|subWageInterface $wage
 * @property int $date
 * @property object|subPayerInterface $payer
 * @property object|subPaymentInterface $payment
 * @property object|subVerifyInterface $verify
 * @property object|subSettlementInterface $settlement
 */
interface paymentInterface {}


