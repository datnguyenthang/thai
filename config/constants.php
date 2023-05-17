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

    define('CUSTOMERTYPE', [0 => "Online", 1 => "Walk-in", 2 => "Admin-Order"]);
  
    define('ORDERSTATUS', [0 => "New Order", 1 => "Waiting for Payment", 2 => "Partial paid", 3 => "Wait Approve", 4 => "Complete", 5 => "Transfered", 6 => "Canceled"]);

    define('PAYMENTTYPE', [0 => "Cash", 1 => "Transfer", 2 => "Card"]);