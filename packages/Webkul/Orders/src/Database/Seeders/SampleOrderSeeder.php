<?php

namespace Webkul\Orders\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Orders\Models\Order;

/**
 * Demo data mirroring the BaseLinker order list, so the console renders
 * populated during development. Idempotent on (channel, channel_order_number).
 * ponytail: throwaway dev data — delete once real ingestion lands.
 */
class SampleOrderSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->orders() as $data) {
            $items = $data['items'];
            unset($data['items']);

            $order = Order::updateOrCreate(
                ['channel' => $data['channel'], 'channel_order_number' => $data['channel_order_number']],
                $data,
            );

            $order->items()->delete();
            $order->items()->createMany($items);
        }
    }

    private function orders(): array
    {
        $waw = ['name' => 'Adam Makówka', 'street' => 'Szwankowskiego 7/74', 'zip' => '01-318', 'city' => 'Warszawa', 'country' => 'Polska'];

        return [
            [
                'channel' => 'allegro', 'channel_order_number' => '345643666', 'source' => 'dywania_pl',
                'status' => 'new', 'customer_name' => 'Artem Starinshchak', 'customer_login' => 'Client:66140457',
                'currency' => 'PLN', 'total' => 130.80, 'paid' => 130.80, 'payment_method' => 'Allegro Finance',
                'delivery_method' => 'Allegro Kurier DHL (AD)', 'delivery_cost' => 12.99, 'smart' => true,
                'ordered_at' => '2026-09-02 10:49:00', 'status_changed_at' => '2026-09-02 10:50:00',
                'items' => [['name' => 'WYKŁADZINA DYWANOWA EVENTOWA TARGOWA REWIND FLAT CHARCOAL 1M', 'qty' => 12, 'price' => 10.90, 'tax_percent' => 23]],
            ],
            [
                'channel' => 'allegro', 'channel_order_number' => '345642369', 'source' => 'dywania_pl',
                'status' => 'new', 'customer_name' => 'Dariusz Drag', 'customer_login' => 'DarekGoro1991',
                'currency' => 'PLN', 'total' => 274.80, 'paid' => 274.80, 'payment_method' => 'Allegro Finance',
                'delivery_method' => 'Kurier DPD', 'delivery_cost' => 14.99,
                'ordered_at' => '2026-09-02 10:42:00', 'status_changed_at' => '2026-09-02 10:43:00',
                'items' => [['name' => 'WYKŁADZINA DYWANOWA DOMOWA GRAND 2 019-025 2M', 'qty' => 12, 'price' => 22.90, 'tax_percent' => 23]],
            ],
            [
                'channel' => 'allegro', 'channel_order_number' => '345633082', 'source' => 'dywania_pl',
                'status' => 'error', 'customer_name' => 'Dawid Konkel', 'customer_login' => 'james97',
                'currency' => 'PLN', 'total' => 42.79, 'paid' => 42.79, 'payment_method' => 'Allegro Finance',
                'delivery_method' => 'Allegro Kurier DPD (AD)', 'delivery_cost' => 14.99, 'smart' => true,
                'ordered_at' => '2026-09-02 09:59:00', 'status_changed_at' => '2026-09-02 10:00:00',
                'items' => [['name' => 'SZTUCZNA TRAWA ASCOT', 'qty' => 2, 'price' => 13.90, 'tax_percent' => 23, 'ean' => '5905602123377', 'sku' => 'SZTUCZNA.TRAWA.ASCOT.5MM']],
            ],
            [
                'channel' => 'allegro', 'channel_order_number' => '345631141', 'source' => 'dywania_pl',
                'status' => 'new', 'customer_name' => 'Adam Makówka', 'customer_login' => 'adam1974m',
                'customer_email' => 'yrw70vb0ex+571294497@allegromail.pl', 'customer_phone' => '+48 789 100 099',
                'currency' => 'PLN', 'total' => 56.69, 'paid' => 56.69, 'payment_method' => 'Allegro Finance',
                'delivery_method' => 'Allegro Kurier DPD (AD)', 'delivery_cost' => 14.99, 'smart' => true, 'cod' => false,
                'external_transaction_id' => '73044b11-a6a2-11f1-9e3f-495e64859847',
                'delivery_address' => $waw, 'invoice_address' => $waw,
                'ordered_at' => '2026-09-02 09:48:00', 'status_changed_at' => '2026-09-02 09:50:00',
                'items' => [['name' => 'SZTUCZNA TRAWA ASCOT', 'external_product_id' => '369546119', 'ean' => '5905602123377', 'sku' => 'SZTUCZNA.TRAWA.ASCOT.5MM', 'qty' => 3, 'price' => 13.90, 'tax_percent' => 23, 'weight' => 1.000]],
            ],
            [
                'channel' => 'allegro', 'channel_order_number' => '345630574', 'source' => 'dywania_pl',
                'status' => 'sent', 'customer_name' => 'Lucyna Pitucha', 'customer_login' => 'iiwonka81',
                'currency' => 'PLN', 'total' => 262.80, 'paid' => 262.80, 'payment_method' => 'Allegro Finance',
                'delivery_method' => 'Kurier DPD', 'delivery_cost' => 14.99,
                'symfonia_document_number' => 'FS 1245/09/2026',
                'ordered_at' => '2026-09-02 09:46:00', 'status_changed_at' => '2026-09-02 11:06:00',
                'items' => [['name' => 'WYKŁADZINA DYWANOWA DOMOWA NA FILCU NOVA BEŻOWA 4M', 'qty' => 12, 'price' => 21.90, 'tax_percent' => 23]],
            ],
            [
                'channel' => 'presta', 'channel_order_number' => '345628605', 'shop_order_number' => '1248', 'source' => 'PRESTA',
                'status' => 'new', 'customer_name' => 'Małgorzata Maliszewska',
                'currency' => 'PLN', 'total' => 477.00, 'paid' => 477.00, 'payment_method' => 'Przelew',
                'delivery_method' => 'Kurier ROHLIG',
                'ordered_at' => '2026-09-02 09:36:00', 'status_changed_at' => '2026-09-02 09:37:00',
                'items' => [['name' => 'WYKŁADZINA PCV DOMOWA EKO LASTRYKO WZÓR KAMIENIA LASTRIKO 2M', 'qty' => 20, 'price' => 23.85, 'tax_percent' => 23]],
            ],
            [
                'channel' => 'allegro', 'channel_order_number' => '345627474', 'source' => 'dywania_pl',
                'status' => 'new', 'customer_name' => 'Justyna Krause', 'customer_login' => 'Client:101967828',
                'currency' => 'PLN', 'total' => 438.90, 'paid' => 0, 'payment_method' => 'Za pobraniem',
                'delivery_method' => 'Kurier DPD pobranie', 'delivery_cost' => 19.99, 'cod' => true,
                'ordered_at' => '2026-09-02 09:30:00', 'status_changed_at' => '2026-09-02 09:31:00',
                'items' => [['name' => 'DYWAN RABBIT BELLAROSSA NOWOCZESNY ZIELONY 160x230 Bellarossa', 'qty' => 1, 'price' => 438.90, 'tax_percent' => 23]],
            ],
        ];
    }
}
