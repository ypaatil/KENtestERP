<?php

namespace App\Services;

use App\Libraries\BarcodeGenerator;
use App\Libraries\BarcodeGeneratorHTML;

class BarcodeService
{
    protected $generator;

    public function __construct()
    {
        $this->generator = new BarcodeGeneratorHTML();
    }

    public function generate($code)
    {
        return $this->generator->getBarcode($code, BarcodeGenerator::TYPE_CODE_128);
    }
    public function getBarcode($code, $type)
    {
        return $this->generator->getBarcode($code, $type);
    }

    // Define any constants if necessary
    const TYPE_CODE_128 = BarcodeGeneratorHTML::TYPE_CODE_128; // Make sure this is correct
}
