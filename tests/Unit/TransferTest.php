<?php

namespace Tests\Unit;

use App\Http\Requests\TransferRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_rules_on_amount_transformation()
    {
        $this->assertEquals([
            'from_amount' => 'required|numeric',
            'from_type' => 'required',
            'from_id' => 'required|numeric',
            'to_amount' => 'required|numeric',
            'to_type' => 'required',
            'to_id' => 'required|numeric',
        ], (new TransferRequest())->rules());
    }
}
