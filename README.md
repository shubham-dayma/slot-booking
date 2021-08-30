## About Slot Booking
Simple program to book dynamic slots for events. It also validates requested slots based on event's start on/off timings, along with any breaks that happens during the event.

## API Doc
https://documenter.getpostman.com/view/17006779/U16bwV1R

Two APIs
1. Retrive future events along with its defination
2. Add dynamic slots with Email ID

## Installation
git clone https://github.com/shubham-dayma/slot-booking.git
1. composer update
2. php artisan key:generate
3. creat a database and configure it in .env 
3. php artisan migrate
4. php artisan db:seed

## Version
1. PHP 7.3.29
2. PHP Laravel 8
3. MySQL 5+
