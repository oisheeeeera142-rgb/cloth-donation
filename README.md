# Cloth Donation Management System 👕

A web-based Cloth Donation Management System developed using **PHP**, **MySQL**, **HTML**, **CSS**, and **XAMPP** to connect donors, beneficiaries, volunteers, and administrators for efficient clothing donation and distribution.

## Features

### Donor

* Register and Login
* Donate Clothes
* Request Pickup
* View Donation History
* Manage Profile

### Beneficiary

* Register and Login
* Request Clothes
* Track Requests
* Manage Profile

### Volunteer

* View Assigned Pickups
* Manage Pickup Activities
* Update Profile Information

### Admin

* Manage Users
* Manage Donations
* Manage Pickup Requests
* Manage Inventory
* Monitor System Activities

## Technologies Used

* PHP
* MySQL
* HTML5
* CSS3
* XAMPP
* Bootstrap

## Project Structure

```text
admin/
auth/
beneficiary/
config/
css/
donor/
volunteer/
index.php
login.php
register.php
clothcare.sql
```

## Database

Import the provided database file:

```text
clothcare.sql
```

into MySQL using phpMyAdmin.

## Installation

1. Install XAMPP.
2. Copy the project folder into:

```text
xampp/htdocs/
```

3. Start Apache and MySQL.
4. Import `clothcare.sql` into phpMyAdmin.
5. Configure database settings in:

```text
config/db.php
```

6. Open your browser and visit:

```text
http://localhost/cloth-donation/
```

## Objectives

The main objective of this project is to reduce clothing waste and help underprivileged people by creating a digital platform that streamlines the donation, collection, and distribution process.

## Future Improvements

* Email Notifications
* SMS Alerts
* Donation Tracking System
* Real-time Volunteer Assignment
* Advanced Reporting Dashboard

## Author

Developed as a Web Technology Project.
