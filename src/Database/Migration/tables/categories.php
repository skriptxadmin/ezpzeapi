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
    'title'       => [
        "VARCHAR(50)",
        "NOT NULL",
    ],
    'parent_id'          => [
        "INTEGER",
        "NULL",
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
