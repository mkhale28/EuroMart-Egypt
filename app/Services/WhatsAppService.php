<?php

namespace App\Services;

class WhatsAppService
{
    protected string $phoneNumber;

    public function __construct()
    {
        $this->phoneNumber = config('services.whatsapp.number', '201234567890');
    }

    public function getWhatsAppLink(string $message): string
    {
        $encoded = urlencode($message);
        return "https://wa.me/{$this->phoneNumber}?text={$encoded}";
    }

    public function generateOrderMessage(
        string $productName,
        float $price,
        int $quantity = 1,
        ?string $customerName = null
    ): string {
        $message = "Hello,\nI want to order:\n\n";
        $message .= "Product Name: {$productName}\n";
        $message .= "Price: " . number_format($price, 2) . " EGP\n";
        $message .= "Quantity: {$quantity}\n";

        if ($customerName) {
            $message .= "Customer Name: {$customerName}\n";
        }

        return $message;
    }

    public function generateOrderConfirmationMessage(array $orderData): string
    {
        $message = "📦 *New Order Request*\n\n";
        $message .= "Order #: {$orderData['order_number']}\n";
        $message .= "Customer: {$orderData['customer_name']}\n";
        $message .= "Phone: {$orderData['customer_phone']}\n";
        $message .= "Address: {$orderData['address']}\n";
        $message .= "Governorate: {$orderData['governorate']}\n";
        $message .= "Payment: {$orderData['payment_method']}\n";
        $message .= "Total: " . number_format($orderData['total'], 2) . " EGP\n";

        return $message;
    }
}
