<?php

namespace App\Modules\Inventory\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(
        public int $productId,
        public int $requested,
        public int $available
    ) {
        parent::__construct(
            "Insufficient stock for Product ID {$productId}. Requested: {$requested}, Available: {$available}."
        );
    }
}
