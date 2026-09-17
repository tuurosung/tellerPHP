<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Checkout;

use Tuurosung\Teller\Exceptions\InvalidRequestException;
use Tuurosung\Teller\Support\Amount;
use Tuurosung\Teller\Support\Reference;


final readonly class CheckoutRequest
{
    private function __construct(
        public string $transactionId,
        public Amount $amount,
        public string $description,
        public string $redirectUrl,
        public string $email,
    ){}


    public static function for(
        string $transactionId,
        Amount $amount,
        string $description,
        string $redirectUrl,
        string $email
    ): self {

        Reference::assetValid($transactionId);

        if (mb_strlen($description) < 10) {
            throw InvalidRequestException::shortDescription(mb_strlen($description));
        }

        if (filter_var($redirectUrl, FILTER_VALIDATE_URL) === false) {
            throw InvalidRequestException::badUrl('redirect_url', $redirectUrl);
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw InvalidRequestException::badEmail($email);
        }

        return new self(
            $transactionId,
            $amount,
            $description,
            $redirectUrl,
            $email
        );
    }


    public static function forPesewas(
        int $pesewas,
        string $description,
        string $redirectUrl,
        string $email
        ): self {

       return self::for(
        $transactionId ?? Reference::generate(),
        Amount::fromPesewas($pesewas),
        $description,
        $redirectUrl,
        $email
       );
    }


    /**
     * @return array <string, string>
     * @param string $merchantId
     */
    public function toPayload(string $merchantId): array
    {
        return [
            'merchant_id'=> $merchantId,
            'transaction_id'=> $this->transactionId,
            'desc' => $this->description,
            'redirect_url'=> $this->redirectUrl,
            'email'=> $this->email
        ];
    }
}