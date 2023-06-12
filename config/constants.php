<?php
    define('LANGUAGES', ['en', 'tha']);

    define('USERSTATUS', [0 => 'Active', 1 => 'Deactive']);
    define('USERTYPE', [0 => 'User', 1 => 'Admin', 2 => 'Manager', 3 => 'Moderator']);

    define('LOCATIONSTATUS', [0 => "Open", 1 => "Close"]);
    define('AGENTSTATUS', [0 => "Open", 1 => "Close"]);

    define('RIDESTATUS', [0 => "Open", 1 => "Close"]);

    define('TRIPTYPE', [1 => "Oneway", 2 => "Roundtrip"]);
    define('ONEWAY', 1);
    define('ROUNDTRIP', 2);

    define('DEPARTURETICKET', 1);
    define('RETURNTICKET', 2);

    define('CUSTOMERTYPE', [0 => "Online", 1 => "Walk-in", 2 => "Admin-Order"]);

    define('PAYMENTTYPE', [0 => "Cash", 1 => "Transfer", 2 => "Card"]);

    define('PICKUPDONTUSESERVICE', 0);
    define('PICKUPANY', 1);
    define('PICKUPANYOTHER', 2);

    define('DROPOFFDONTUSESERVICE', 0);
    define('DROPOFFANY', 1);
    define('DROPOFFANYOTHER', 2);

    define('CARDPAYMENT', 0);
    define('BANKTRANSFERPAYMENT', 1);

    define('NEWORDER', 0);
    define('UPPLOADTRANSFER', 4);
    define('COMPLETEDORDER', 99);

    define('ORDERSTATUS', [NEWORDER => "New Order",
                            1 => "Waiting for Payment", 
                            2 => "Partial paid", 
                            3 => "Wait Approve", 
                            UPPLOADTRANSFER => "Upload Transfered", 
                            5 => "Transfered", 
                            6 => "Canceled",
                            COMPLETEDORDER => "Completed"]);
