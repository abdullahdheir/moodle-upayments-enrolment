# UPayments Enrolment Plugin for Moodle

![UPayments Logo](https://upayments.com/wp-content/uploads/2021/01/UPayments-Logo.svg)

[UPayments Official Website](https://upayments.com)

## Overview
The UPayments enrolment plugin for Moodle enables course enrolment through the UPayments payment gateway. This plugin provides a secure and seamless way to handle course payments and enrolments.

## Features
- Secure payment processing through UPayments gateway
- Support for both production and sandbox environments
- Dynamic course costs set by teachers and administrators
- Comprehensive transaction logging
- Admin interface for transaction management
- Support for multiple currencies
- Detailed transaction history and reporting

## Requirements
- Moodle 4.0 or later (tested up to Moodle 4.3)
- PHP 7.4 or later
- UPayments merchant account
- SSL certificate (recommended for production)

## Installation
1. Download the plugin files
2. Extract the contents to the `enrol/upayment` directory of your Moodle installation
3. Visit the Site Administration page to complete the installation
4. Configure the plugin settings with your UPayments credentials

## Configuration
### Production Settings
- API Token: Your UPayments production API token
- API URL: UPayments production API endpoint (default: https://uapi.upayments.com/api/v1/)

### Sandbox Settings
- Enable Sandbox Mode: Toggle for testing environment
- Sandbox Token: Your UPayments sandbox API token
- Sandbox API URL: UPayments sandbox API endpoint (default: https://sandbox.uapi.upayments.com/api/v1/)

### Cost Settings
- Default Cost: Set default course enrolment cost
- Currency: Select the currency for payments

## Usage
### For Administrators
1. Configure the plugin settings in Site Administration
2. Monitor transactions through the transaction log
3. Manage course costs and enrolment settings

### For Teachers
1. Set course-specific costs
2. Monitor student enrolments
3. View payment status for enrolled students

### For Students
1. Select the course for enrolment
2. Complete payment through UPayments gateway
3. Access course content upon successful payment

## Transaction Management
- View all transactions in the admin interface
- Filter transactions by date, status, or user
- Export transaction data in various formats
- View detailed transaction information

## Security
- All API communications are encrypted
- Secure token-based authentication
- Sandbox environment for testing
- Comprehensive error logging

## Support
For support and assistance:
- Author: Abdullah Dheir
- GitHub: [abdullahdheir](https://github.com/abdullahdheir)
- Email: abdullah.dheir@gmail.com
- Documentation: [Documentation URL]
- Issue Tracker: [GitHub Issues](https://github.com/abdullahdheir/moodle-enrol_upayment/issues)

## License
This plugin is licensed under the GNU General Public License v3 or later.

## Copyright
© 2025 Abdullah Dheir. All rights reserved.

## Version History
- 1.0.0 (2024-03-15)
  - Initial stable release
  - Complete UPayments integration
  - Transaction logging system
  - Admin interface
  - Sandbox support

## Author
**Abdullah Dheir**
- GitHub: [abdullahdheir](https://github.com/abdullahdheir)
- Email: abdullah.dheir@gmail.com 