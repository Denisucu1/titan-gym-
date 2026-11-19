<?php
namespace App\Models;

class Payment extends BaseModel
{
    protected int $userId;
    protected string $stripePaymentId;
    protected float $amount;
    protected string $currency = 'ron';
    protected string $status = 'succeeded';
    protected string $itemType = 'membership';
    protected int $itemId;

    public function toArray(): array
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->userId,
            'stripe_payment_id' => $this->stripePaymentId,
            'amount'            => $this->amount,
            'currency'          => $this->currency,
            'status'            => $this->status,
            'item_type'         => $this->itemType,
            'item_id'           => $this->itemId
        ];
    }
}
