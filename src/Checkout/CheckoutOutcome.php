<?php

declare(strict_types= 1);

namespace Tuurosung\Teller\Checkout;

enum CheckoutOutcome: string
{
    /** Upstream accepted the request and returned a checkout URL. */
    case Initiated = 'initiated';

    /** Upstream answered clearly and refused. Safe to show the customer. */
    case Declined = 'declined';

    /**
     * No clear answer. The request may or may not have landed upstream.
     * Never present this to a customer as a failure. Resolve it by querying
     * the status endpoint with the same reference.
     */
    case Indeterminate = 'interminate';
}