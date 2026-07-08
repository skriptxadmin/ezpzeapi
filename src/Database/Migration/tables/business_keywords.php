<?php

 return [
            'id' => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY",
            ],
            'bid' => [
                "INT",
                "NOT NULL",
            ],
            'kid' => [
                "INT",
                "NOT NULL",
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