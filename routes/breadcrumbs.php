<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

//BOOKING FLOW PAGE
// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Trip
Breadcrumbs::for('trip', function (BreadcrumbTrail $trail) {
    $trail->parent('home', route('home'));
    $trail->push('Trip', route('trip'));
});

// Booking
Breadcrumbs::for('proceedbooking', function (BreadcrumbTrail $trail) {
    $trail->parent('trip', route('trip'));
    //$trail->push('Booking', route('proceedbooking'));
    $trail->push('Booking');
});

// Payment
Breadcrumbs::for('payment', function (BreadcrumbTrail $trail) {
    $trail->parent('proceedbooking', route('proceedbooking'));
    $trail->push('Payment', route('payment', 'code'));
});

//FRONT PAGE
// Policy of customer
Breadcrumbs::for('policycustomer', function (BreadcrumbTrail $trail) {
    $trail->parent('home', route('home'));
    $trail->push('Policy of Customer', route('policycustomer'));
});