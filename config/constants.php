<?php
    //LANGUAGE
    define('LANGUAGES', ['en', 'tha']);
    define('OMISE_API_VERSION', '2020-11-25');
    define('OMISE_PUBLIC_KEY', env('OMISE_PUBLIC_KEY'));
    define('OMISE_SECRET_KEY', env('OMISE_SECRET_KEY'));

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
    define('CREATOR', 5);
    define('USERTYPE', [
                        USER => 'User',
                        ADMIN => 'Admin', 
                        MANAGER => 'Manager',
                        MODERATOR => 'Moderator',
                        AGENT => 'Agent',
                        CREATOR =>  'Creator'
                    ]);
                    
    //CUSTOMER CHANNEL
    define('FACEBOOK', 1);
    define('GOOGLE', 2);

    //MENU
    define('MENUSTATUS', [
        0 => "Open",
        1 => "Close"
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
    define('TICKETBOOTHAGENT', 4);
    
    define('OTHERSAGENT', 9);
    define('ATYPE', [ 
                        RESORTAGENT => "Resort agent",
                        TRAVELAGENT => "Travel agent",
                        WEBSITEAGENT => "Website agent",
                        TICKETBOOTHAGENT => "Ticket Booth Agent",
                        OTHERSAGENT => "Others",
                    ]);
    define('AGENTPAYMENTTYPE', [
                                    0 => "Cash", 
                                    1 => "Transfer", 
                                    2 => "Card"
                                ]);
    
    define('BOOKED', 1);
    define('NOTBOOKED', 2);
    define('INTRODUCED', 3);
    define('CONTRACTPROCESS', 4);
    define('DONECONTRACT', 5);
    define('BOOKEDUNCONTRACT', 6);

    define('AGENTSTATUSLIST', [
        BOOKED => "Booked", 
        NOTBOOKED => "Not Booked",
        INTRODUCED => "Introduced",
        CONTRACTPROCESS => "Contract Process",
        DONECONTRACT => "Done Contract",
        BOOKEDUNCONTRACT => "Booked/Uncontract",
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
    define('ONLINEPRICE', 0);
    define('LOCALTYPE', 1);
    define('AGENTTYPE', 2);
    define('SPECIALTYPE', 3);

    define('AGENTLOCAL', [
        ONLINEPRICE => "Online Price",
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

    //PAYMENT METHOD
    define('PICKUPDROPOFFSTATUS', [
        0 => "Open",
        1 => "Close"
    ]);

    define('PICKUPDONTUSESERVICE', 0);
    define('PICKUPANY', 1);
    define('PICKUPANYOTHER', 2);

    define('DROPOFFDONTUSESERVICE', 0);
    define('DROPOFFANY', 1);
    define('DROPOFFANYOTHER', 2);

    //ORDER STATUS
    define('ALREADYPAID', 1);

    define('NEWORDER', 0);
    define('UPPLOADTRANSFER', 1);
    define('RESERVATION', 2);
    define('PAIDORDER', 3);
    define('CANCELDORDER', 6);
    define('DECLINEDORDER', 7);
    define('CONFIRMEDORDER', 8);
    define('COMPLETEDORDER', 9);

    define('ORDERSTATUS', [
                            NEWORDER => "New Order",
                            UPPLOADTRANSFER => "Upload Transfered", 
                            RESERVATION => "Reserve",
                            PAIDORDER => 'Paid',
                            CONFIRMEDORDER => "Confirmed", 
                            CANCELDORDER => "Canceled",
                            //COMPLETEDORDER => "Completed"
                        ]);

    //PAYMENT METHOD

    define('PAYMENTMETHODSTATUS', [
                                        0 => "Open",
                                        1 => "Close"
                                    ]);
    define('BANKTRANSFER', 'bank transfer');
    define('CASH', 'cash');
    define('CARD', 'card');
    define('PROMPTPAY', 'promptpay');
    define('ALIPAY', 'alipay');
    define('WECHAT', 'wechat');
    define('PAIDBYAGENT', 'pay by agent');

    //PAYMENT STATUS
    define('NOTPAID', 0);
    define('REFUND', 3);
    define('PAID', 8);
    //define('PAIDBYCASH', 9);
    //define('PAIDBYAGENT', 10);
    define('PAYMENTSTATUS', [
                                NOTPAID => "Not paid",
                                REFUND => "Refund",
                                PAID => "Paid",
                                //PAIDBYCASH => "Paid by cash",
                                //PAIDBYAGENT => "Paid by agent"
                            ]);
    //OMISE 
    define('SUCCESSFUL', 'successful');
    define('PENDING', 'pending');
