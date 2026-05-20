# Custom Payment Method - Magento 2

This module creates a custom offline payment method in Magento 2 using KnockoutJS checkout rendering.

   - I have followed - vendor/magento/module-offline-payments (must read - READ.md Always for vendor module)

---

# Module Information

- Module Name: `Custom_PaymentMethod`
- Payment Code: `custompaymentmethod`
- Payment Type: Offline Payment Method

---

# Features

- Custom offline payment method
- Admin configuration support
- Checkout payment renderer integration
- KnockoutJS based frontend
- Compatible with Magento 2 checkout flow

---

# Module Structure

app/code/Custom/PaymentMethod
│
├── etc
│   ├── module.xml
│   ├── config.xml
|   |-- payment.xml (here not need - just kept)
│   └── adminhtml
│       └── system.xml
│
├── Model
│   └── CustomPayment.php
│
├── view
│   └── frontend
│       ├── layout
│       │   └── checkout_index_index.xml
│       │
│       ├── web
│       │   ├── js
│       │   │   └── view
│       │   │       └── payment
│       │   │           ├── custom-payment-method-renderer.js
│       │   │           └── method-renderer
│       │   │               └── custom-payment-method.js
│       │   │
│       │   └── template
│       │       └── payment
│       │           └── custom-payment-method-template.html
│
├── registration.php
└── composer.json

---

# Installation


## Issue :
   
    - checkout_index_index.xml

            : <item name="custom-payment-method-renderer" xsi:type="array"> (earlier not added so = it override the core methods)
            : naming issue (renderer)
            : always check :- line by line code - which actually needed (remove unwanted - understood the code - single line)



## Enable Module

Run:

```bash
- php bin/magento module:enable Custom_PaymentMethod
