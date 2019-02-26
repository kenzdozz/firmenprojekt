# Firmenprojekt
A project to manage company bills

## Instructions to install
### Requirements
- LAMP/WAMP - Apache, MySQL and PHP 5.6+ on a Linux or Windows Machine.
- Composer - PHP dependency manager [Get Composer](https://getcomposer.org/)

### Installing
To install run 
> `git clone https://github.com/kenzdozz/firmenprojekt.git`

When this is done, run 
> `composer install`

### Setting up
Rename `.env.example` to `.env` and add the Database connection details

To create the required tables, run migration
> `composer run-script migrate:up`

To seed some data into database run
> `composer run-script migrate:seed`

To drop tables at any time, run
> `composer run-script migrate:down`

## Usage
A GET request to `/` takes you to the app.

A POST request to `/create` with form data `company_name, company_id, bill_amount, bill_purpose, payment_date` creates a new record and returns a JSON response object with properties `status` response status code and `data` the newly created record.

A POST request to `/update/:id` with form data: any or all of `company_name, company_id, bill_amount, bill_purpose, payment_date` updates the record with `:id` and returns a JSON response object with properties `status` response status code and `data` the newly updated record.

A GET request to `/delete/:id` will delete the record with `:id` and returns a JSON response object with properties `status` response status code and `message` a success message.

## Licence
MIT
