<?php

namespace Jewelry\Models;

use Jewelry\Domain\Customer;
use Jewelry\Domain\Customer\CustomerFactory;
use Jewelry\Exceptions\NotFoundException;

class CustomerModel extends AbstractModel{
    public function get(int $userId):Customer{
        $query = 'SELECT * FROM customer WHERE id = user';
    }
}