<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Invoice') }} - #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #0a5ed4; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info td { padding: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.items th { background: #f5f5f5; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
        table.items td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { margin-top: 20px; text-align: right; }
        .total .row { padding: 5px 0; }
        .total .grand { font-size: 18px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; }
        .footer { text-align: center; margin-top: 50px; color: #999; font-size: 11px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>{{ __('Premium European Products') }}</p>
            <h2>{{ __('INVOICE') }}</h2>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td><strong>{{ __('Order') }}:</strong> #{{ $order->order_number }}</td>
                    <td><strong>{{ __('Date') }}:</strong> {{ $order->created_at->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('Customer') }}:</strong> {{ $order->customer_name }}</td>
                    <td><strong>{{ __('Phone') }}:</strong> {{ $order->customer_phone }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('Address') }}:</strong> {{ $order->governorate }}, {{ $order->address }}</td>
                    <td><strong>{{ __('Payment') }}:</strong> {{ str_replace('_', ' ', ucfirst($order->payment_method)) }}</td>
                </tr>
            </table>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('SKU') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Qty') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->product_sku }}</td>
                        <td>{{ number_format($item->price, 2) }} EGP</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->subtotal, 2) }} EGP</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <div class="row">{{ __('Subtotal') }}: {{ number_format($order->subtotal, 2) }} EGP</div>
            @if($order->coupon_discount > 0)
                <div class="row">{{ __('Discount') }} ({{ $order->coupon_code }}): -{{ number_format($order->coupon_discount, 2) }} EGP</div>
            @endif
            <div class="row">{{ __('Shipping') }}: {{ $order->shipping > 0 ? number_format($order->shipping, 2) . ' EGP' : 'Free' }}</div>
            <div class="row grand">{{ __('Total') }}: {{ number_format($order->total, 2) }} EGP</div>
        </div>

        <div class="footer">
            <p>{{ __('Thank you for shopping with') }} {{ config('app.name') }}!</p>
            <p>{{ __('For any inquiries, contact us at') }} info@euromartegypt.com</p>
        </div>
    </div>
</body>
</html>
