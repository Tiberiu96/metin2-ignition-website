<?php

namespace App\Enums;

enum TransactionType: string
{
    case Stripe = 'stripe';
    case Coupon = 'coupon';
}
