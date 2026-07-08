<?php

 return [
            'id' => [
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
            'area' => [
                "VARCHAR(40)",
                "NOT NULL",
            ],
            'pincode_id' =>[
                "INT",
                "NOT NULL"
            ],
            "taluk_id" =>[
                "INT",
                "NOT NULL"
            ],
            "aka"=>[
                "VARCHAR(100)",
                "NULL",
            ],
        
        ];

      