<?php

return [
    'id'             => [
        "INT",
        "NOT NULL",
        "AUTO_INCREMENT",
        "PRIMARY KEY",
    ],
    'slug'       => [
        "VARCHAR(50)",
        "NOT NULL",
        'UNIQUE',
    ],
    'bname'       => [
        "VARCHAR(50)",
        "NOT NULL",
    ],
     'description'       => [
        "VARCHAR(250)",
        "NOT NULL",
    ],
    'image' => [
        "VARCHAR(255)",
        "DEFAULT NULL",
    ],

    'category_id' => [
        "INTEGER",
        "NULL",
    ],
    'subcategory_id' => [
        "INTEGER",
        "NULL",
    ],

    'contact' => [
        "VARCHAR(20)",
        "DEFAULT NULL",
    ],

    'whatsapp' => [
        "VARCHAR(20)",
        "DEFAULT NULL",
    ],

    'website' => [
        "VARCHAR(255)",
        "DEFAULT NULL",
    ],

    'opening_hours' => [
        "VARCHAR(255)",
        "DEFAULT NULL",
    ],
   'location_id'=>[
        "INT",
        "NULL"
   ],
    'created_by' => [
        "INT",
        "NULL",
        "DEFAULT 1"
    ],
    'updated_by' => [
        "INT",
        "NULL",
        "DEFAULT 1"
    ],
    'deleted_by' => [
        "INT",
        "NULL",
    ],
    'created_at'     => [
        "TIMESTAMP",
        "DEFAULT CURRENT_TIMESTAMP",
    ],
    'updated_at'     => [
        "TIMESTAMP",
        "DEFAULT CURRENT_TIMESTAMP",
        "ON UPDATE CURRENT_TIMESTAMP",
    ],
      'deleted_at'     => [
        "DATETIME",
        "NULL",
    ],
];
