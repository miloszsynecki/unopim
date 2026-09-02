<?php

return [
    'menu' => [
        'orders'     => 'Zamówienia',
        'all-orders' => 'Wszystkie zamówienia',
    ],

    'acl' => [
        'orders' => 'Zamówienia',
        'view'   => 'Podgląd zamówienia',
    ],

    'index' => [
        'title'       => 'Wszystkie zamówienia',
        'all'         => 'Wszystkie',
        'marketplace' => 'Marketplace',
    ],

    'statuses' => [
        'new'       => 'Nowe',
        'sent'      => 'Wysłane',
        'cancelled' => 'Anulowane',
        'error'     => 'Błędne zamówienie',
    ],

    'datagrid' => [
        'number'     => 'Numer',
        'customer'   => 'Imię nazwisko (źródło)',
        'items'      => 'Przedmioty',
        'amount'     => 'Kwota',
        'delivery'   => 'Sposób wysyłki',
        'status'     => 'Status',
        'ordered-at' => 'Data złożenia',
        'view'       => 'Podgląd',
    ],

    'view' => [
        'title'    => 'Zamówienie :number',
        'no-items' => 'Brak pozycji w zamówieniu.',
        'yes'      => 'Tak',
        'no'       => 'Nie',

        'item' => [
            'product-id' => 'ID prod.',
            'name'       => 'Nazwa produktu',
            'qty'        => 'Ilość',
            'price'      => 'Cena',
            'vat'        => 'VAT',
            'weight'     => 'Waga',
        ],

        'info' => [
            'title'           => 'Informacje o zamówieniu',
            'paid'            => 'Zapłacono',
            'customer'        => 'Klient (login)',
            'email'           => 'E-mail',
            'phone'           => 'Telefon',
            'source'          => 'Źródło',
            'delivery-method' => 'Sposób wysyłki',
            'cod'             => 'Pobranie',
            'delivery-cost'   => 'Koszt wysyłki',
            'payment-method'  => 'Sposób płatności',
            'ordered-at'      => 'Data złożenia',
            'status-at'       => 'Data w statusie',
        ],

        'symfonia' => [
            'title'       => 'Symfonia',
            'document'    => 'Numer dokumentu',
            'transaction' => 'ID transakcji',
            'not-created' => 'Nie utworzono',
            'hint'        => 'To zamówienie jest tylko podglądem. Dokument źródłowy powstaje w Symfonii i to on aktualizuje stany magazynowe.',
        ],

        'address' => [
            'delivery' => 'Adres dostawy',
            'invoice'  => 'Dane do faktury',
            'pickup'   => 'Odbiór w punkcie',
        ],
    ],
];
