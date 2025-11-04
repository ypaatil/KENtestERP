<?php

namespace Types;

use Barcode;

interface TypeInterface
{
    public function getBarcodeData(string $code): Barcode;
}
