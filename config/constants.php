<?php
    //LANGUAGE
    define('LANGUAGES', ['en', 'tha']);

    define('ACTIVE', 0);
    define('DEACTIVE', 1);

    //USER
    define('USERSTATUS', [
                            0 => 'Active', 
                            1 => 'Deactive'
                        ]);
    define('USER', 0);
    define('ADMIN', 1);
    define('MANAGER', 2);
    define('MODERATOR', 3);
    define('AGENT', 4);
    define('USERTYPE', [
                        USER => 'User',
                        ADMIN => 'Admin', 
                        MANAGER => 'Manager',
                        MODERATOR => 'Moderator',
                        AGENT => 'Agent'
                    ]);

    //LOCATION
    define('LOCATIONSTATUS', [
                                0 => "Open",
                                1 => "Close"
                            ]);

    //PROMOTION
    define('PROMOTIONSTATUS', [
                                0 => "Open",
                                1 => "Close"
                            ]);
    
    //AGENT
    //define('AGENTTYPE', []);
    //define('ATYPE', []);
    define('RESORTAGENT', 1);
    define('TRAVELAGENT', 2);
    define('WEBSITEAGENT', 3);
    define('OTHERSAGENT', 9);
    define('ATYPE', [ 
                        RESORTAGENT => "Resort agent",
                        TRAVELAGENT => "Travel agent",
                        WEBSITEAGENT => "Website agent",
                        OTHERSAGENT => "Others",
                    ]);
    define('AGENTPAYMENTTYPE', [
                                    0 => "Cash", 
                                    1 => "Transfer", 
                                    2 => "Card"
                                ]);
    define('AGENTSTATUS', [
                            0 => "Open", 
                            1 => "Close"
                        ]);

    //CUSTOMER
    define('CUSTOMERTYPESTATUS', [
                                    0 => "Open", 
                                    1 => "Close"
                                ]);
    define('CUSTOMERTYPE', [
                                0 => "Online", 
                                1 => "Walk-in", 
                                2 => "Sale",
                                3 => "Agent"
                            ]);
    define('LOCALTYPE', 0);
    define('AGENTTYPE', 1);
    define('SPECIALTYPE', 2);

    define('AGENTLOCAL', [
        LOCALTYPE => "Local", 
        AGENTTYPE => "Agent",
        SPECIALTYPE => "Special",
    ]);

    //RIDE
    define('RIDESTATUS', [
                            0 => "Open", 
                            1 => "Close"
                        ]);

    //ORDER
    define('ONEWAY', 1);
    define('ROUNDTRIP', 2);
    define('TRIPTYPE', [
                        ONEWAY => "Oneway", 
                        ROUNDTRIP => "Roundtrip"
                    ]);

    define('DEPARTURETICKET', 1);
    define('RETURNTICKET', 2);

    define('PICKUPDONTUSESERVICE', 0);
    define('PICKUPANY', 1);
    define('PICKUPANYOTHER', 2);

    define('DROPOFFDONTUSESERVICE', 0);
    define('DROPOFFANY', 1);
    define('DROPOFFANYOTHER', 2);

    //ORDER STATUS
    define('NEWORDER', 0);
    define('UPPLOADTRANSFER', 1);
    define('CANCELDORDER', 6);
    define('DECLINEDORDER', 7);
    define('CONFIRMEDORDER', 8);
    define('COMPLETEDORDER', 9);

    define('ORDERSTATUS', [
                            NEWORDER => "New Order",
                            UPPLOADTRANSFER => "Upload Transfered", 
                            CONFIRMEDORDER => "Confirmed", 
                            CANCELDORDER => "Canceled",
                            COMPLETEDORDER => "Completed"
                        ]);

    //PAYMENT METHOD
    define('PAYMENTMETHODSTATUS', [
                                        0 => "Open",
                                        1 => "Close"
                                    ]);
    define('BANKTRANSFER', 1);
    define('CARD', 2);
    define('CASH', 3);

    define('PAYMENTMETHOD', [
                                BANKTRANSFER => "Bank transfer",
                                CARD => "Card",
                                CASH => "Cash"
                            ]
                        );

    //PAYMENT STATUS
    define('NOTPAID', 0);
    define('PAID', 8);
    define('PAIDBYCASH', 9);
    define('PAIDBYAGENT', 10);
    define('PAYMENTSTATUS', [
                                NOTPAID => "Not paid",
                                PAID => "Paid",
                                PAIDBYCASH => "Paid by cash",
                                PAIDBYAGENT => "Paid by agent"
                            ]);
    
