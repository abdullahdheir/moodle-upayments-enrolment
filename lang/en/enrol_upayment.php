<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language strings for the Upayments enrolment plugin.
 *
 * @package    enroll_upayment
 * @copyright  2025 Abdullah Dheir
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Upayments enrolment';
$string['pluginname_desc'] = 'Enrol users using Upayments payment gateway';
$string['upayment:config'] = 'Configure Upayments enrolment instances';
$string['upayment:manage'] = 'Manage enrolled users';
$string['upayment:unenrol'] = 'Unenrol users from course';
$string['upayment:unenrolself'] = 'Unenrol self from the course';
$string['upayment:viewtransactions'] = 'View transaction logs';
$string['upayment:setteachercost'] = 'Set course cost for teachers';

$string['status'] = 'Allow Upayments enrolments';
$string['status_desc'] = 'Allow users to use Upayments to enrol into a course by default.';
$string['cost'] = 'Enrolment cost';
$string['costerror'] = 'The enrolment cost is not numeric';
$string['costorkey'] = 'Please choose one of the following methods of enrolment.';
$string['currency'] = 'Currency';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during Upayments enrolments';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'Enrolment end date cannot be earlier than start date';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['expiredaction'] = 'Enrolment expiration action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['expirymessageenrollersubject'] = 'Upayments enrolment expiry notification';
$string['expirymessageenrollerbody'] = 'Upayments enrolment in the course \'{$a->course}\' will expire within the next {$a->threshold} for the following users:

{$a->users}

To extend their enrolment, go to {$a->extendurl}';
$string['expirymessageenrolledsubject'] = 'Upayments enrolment expiry notification';
$string['expirymessageenrolledbody'] = 'Dear {$a->user},

This is a notification that your enrolment in the course \'{$a->course}\' is due to expire on {$a->timeend}.

If you need help, please contact {$a->enroller}.';
$string['sendpaymentbutton'] = 'Send payment via Upayments';
$string['paymentrequired'] = 'You must pay {$a->currency} {$a->cost} to enrol in this course.';
$string['upaymentaccepted'] = 'Upayments payments accepted';
$string['upayment:config'] = 'Configure Upayments enrol instances';
$string['upayment:manage'] = 'Manage enrolled users';
$string['upayment:unenrol'] = 'Unenrol users from course';
$string['upayment:unenrolself'] = 'Unenrol self from the course';
$string['upayment:viewtransactions'] = 'View transaction logs';
$string['upayment:setteachercost'] = 'Set course cost for teachers';

// Transaction log strings
$string['transactions'] = 'Transaction Log';
$string['transactionid'] = 'Transaction ID';
$string['userid'] = 'User ID';
$string['courseid'] = 'Course ID';
$string['amount'] = 'Amount';
$string['currency'] = 'Currency';
$string['status'] = 'Status';
$string['timestamp'] = 'Timestamp';
$string['paymentid'] = 'Payment ID';
$string['error'] = 'Error';
$string['no_transactions'] = 'No transactions found';
$string['view_transactions'] = 'View Transactions';
$string['transaction_details'] = 'Transaction Details';
$string['transaction_success'] = 'Transaction successful';
$string['transaction_failed'] = 'Transaction failed';
$string['transaction_pending'] = 'Transaction pending';
$string['transaction_cancelled'] = 'Transaction cancelled';
$string['transaction_refunded'] = 'Transaction refunded';
$string['transaction_unknown'] = 'Unknown status';
$string['transaction_date'] = 'Date';
$string['transaction_time'] = 'Time';
$string['transaction_user'] = 'User';
$string['transaction_course'] = 'Course';
$string['transaction_amount'] = 'Amount';
$string['transaction_status'] = 'Status';
$string['transaction_paymentid'] = 'Payment ID';
$string['transaction_error'] = 'Error Message';
$string['transaction_actions'] = 'Actions';
$string['transaction_view'] = 'View';
$string['transaction_export'] = 'Export';
$string['transaction_export_csv'] = 'Export to CSV';
$string['transaction_export_excel'] = 'Export to Excel';
$string['transaction_export_pdf'] = 'Export to PDF';
$string['transaction_export_all'] = 'Export All';
$string['transaction_export_selected'] = 'Export Selected';
$string['transaction_export_none'] = 'No transactions to export';
$string['transaction_export_error'] = 'Error exporting transactions';
$string['transaction_export_success'] = 'Transactions exported successfully';
$string['transaction_export_filename'] = 'transactions_{$a}';
$string['transaction_export_dateformat'] = 'Y-m-d_H-i-s';
$string['transaction_export_header'] = 'Transaction Log Export';
$string['transaction_export_footer'] = 'Generated on {$a}';
$string['transaction_export_columns'] = 'Transaction ID,User ID,Course ID,Amount,Currency,Status,Timestamp,Payment ID,Error';
$string['transaction_export_delimiter'] = ',';
$string['transaction_export_enclosure'] = '"';
$string['transaction_export_escape'] = '\\';
$string['transaction_export_newline'] = "\n";
$string['transaction_export_bom'] = true;
$string['transaction_export_encoding'] = 'UTF-8';
$string['transaction_export_mimetype'] = 'text/csv';
$string['transaction_export_extension'] = 'csv';
$string['transaction_export_contenttype'] = 'application/octet-stream';
$string['transaction_export_disposition'] = 'attachment';
$string['transaction_export_filename_csv'] = 'transactions_{$a}.csv';
$string['transaction_export_filename_excel'] = 'transactions_{$a}.xlsx';
$string['transaction_export_filename_pdf'] = 'transactions_{$a}.pdf';
$string['transaction_export_mimetype_csv'] = 'text/csv';
$string['transaction_export_mimetype_excel'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
$string['transaction_export_mimetype_pdf'] = 'application/pdf';
$string['transaction_export_contenttype_csv'] = 'text/csv';
$string['transaction_export_contenttype_excel'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
$string['transaction_export_contenttype_pdf'] = 'application/pdf';
$string['transaction_export_disposition_csv'] = 'attachment; filename="transactions_{$a}.csv"';
$string['transaction_export_disposition_excel'] = 'attachment; filename="transactions_{$a}.xlsx"';
$string['transaction_export_disposition_pdf'] = 'attachment; filename="transactions_{$a}.pdf"';
$string['transaction_export_header_csv'] = 'Transaction Log Export (CSV)';
$string['transaction_export_header_excel'] = 'Transaction Log Export (Excel)';
$string['transaction_export_header_pdf'] = 'Transaction Log Export (PDF)';
$string['transaction_export_footer_csv'] = 'Generated on {$a}';
$string['transaction_export_footer_excel'] = 'Generated on {$a}';
$string['transaction_export_footer_pdf'] = 'Generated on {$a}';
$string['transaction_export_columns_csv'] = 'Transaction ID,User ID,Course ID,Amount,Currency,Status,Timestamp,Payment ID,Error';
$string['transaction_export_columns_excel'] = 'Transaction ID,User ID,Course ID,Amount,Currency,Status,Timestamp,Payment ID,Error';
$string['transaction_export_columns_pdf'] = 'Transaction ID,User ID,Course ID,Amount,Currency,Status,Timestamp,Payment ID,Error';
$string['transaction_export_delimiter_csv'] = ',';
$string['transaction_export_delimiter_excel'] = "\t";
$string['transaction_export_delimiter_pdf'] = ',';
$string['transaction_export_enclosure_csv'] = '"';
$string['transaction_export_enclosure_excel'] = '"';
$string['transaction_export_enclosure_pdf'] = '"';
$string['transaction_export_escape_csv'] = '\\';
$string['transaction_export_escape_excel'] = '\\';
$string['transaction_export_escape_pdf'] = '\\';
$string['transaction_export_newline_csv'] = "\n";
$string['transaction_export_newline_excel'] = "\n";
$string['transaction_export_newline_pdf'] = "\n";
$string['transaction_export_bom_csv'] = true;
$string['transaction_export_bom_excel'] = false;
$string['transaction_export_bom_pdf'] = false;
$string['transaction_export_encoding_csv'] = 'UTF-8';
$string['transaction_export_encoding_excel'] = 'UTF-8';
$string['transaction_export_encoding_pdf'] = 'UTF-8';

// Teacher cost strings
$string['teachercost'] = 'Teacher Cost';
$string['teachercost_desc'] = 'Cost set by teacher for this course';
$string['teachercost_help'] = 'The cost set by the teacher for enrolling in this course. This will override the default cost if set.';
$string['teachercost_not_set'] = 'No teacher cost set';
$string['teachercost_set'] = 'Teacher cost set to {$a}';
$string['teachercost_updated'] = 'Teacher cost updated to {$a}';
$string['teachercost_deleted'] = 'Teacher cost deleted';
$string['teachercost_error'] = 'Error setting teacher cost';
$string['teachercost_invalid'] = 'Invalid teacher cost';
$string['teachercost_required'] = 'Teacher cost is required';
$string['teachercost_numeric'] = 'Teacher cost must be numeric';
$string['teachercost_positive'] = 'Teacher cost must be positive';
$string['teachercost_max'] = 'Teacher cost cannot exceed {$a}';
$string['teachercost_min'] = 'Teacher cost cannot be less than {$a}';
$string['teachercost_default'] = 'Default cost';
$string['teachercost_default_desc'] = 'Default cost for courses when no teacher cost is set';
$string['teachercost_default_help'] = 'The default cost for courses when no teacher cost is set. This will be used if no teacher cost is set for a course.';
$string['teachercost_default_not_set'] = 'No default cost set';
$string['teachercost_default_set'] = 'Default cost set to {$a}';
$string['teachercost_default_updated'] = 'Default cost updated to {$a}';
$string['teachercost_default_deleted'] = 'Default cost deleted';
$string['teachercost_default_error'] = 'Error setting default cost';
$string['teachercost_default_invalid'] = 'Invalid default cost';
$string['teachercost_default_required'] = 'Default cost is required';
$string['teachercost_default_numeric'] = 'Default cost must be numeric';
$string['teachercost_default_positive'] = 'Default cost must be positive';
$string['teachercost_default_max'] = 'Default cost cannot exceed {$a}';
$string['teachercost_default_min'] = 'Default cost cannot be less than {$a}';

// UPayments API settings
$string['token'] = 'API Token';
$string['token_desc'] = 'Your UPayments API token for production';
$string['merchantid'] = 'Merchant ID';
$string['merchantid_desc'] = 'Your UPayments merchant ID';
$string['apikey'] = 'API Key';
$string['apikey_desc'] = 'Your UPayments API key';
$string['apisecret'] = 'API Secret';
$string['apisecret_desc'] = 'Your UPayments API secret';
$string['sandbox'] = 'Use Sandbox';
$string['sandbox_desc'] = 'Use UPayments sandbox environment for testing';
$string['apiurl'] = 'API URL';
$string['apiurl_desc'] = 'UPayments API URL (leave empty for default)';

// Payment processing strings
$string['processing_payment'] = 'Processing payment...';
$string['redirecting_to_payment'] = 'Redirecting to payment gateway...';
$string['payment_processing'] = 'Your payment is being processed. Please do not close this window.';
$string['payment_success'] = 'Payment successful! Redirecting to course...';
$string['payment_error'] = 'An error occurred while processing your payment. Please try again.';
$string['payment_cancelled'] = 'Payment was cancelled. Please try again if you wish to enroll in this course.';
$string['payment_timeout'] = 'Payment processing timed out. Please try again.';
$string['payment_verification'] = 'Verifying payment...';

$string['payment_id'] = 'Payment ID';
$string['track_id'] = 'Track ID';
$string['back_to_transactions'] = 'Back to Transactions';
$string['datefrom'] = 'Date From';
$string['dateto'] = 'Date To';
$string['search'] = 'Search';
$string['filter'] = 'Filter';
$string['all'] = 'All';

// API Settings
$string['api_settings'] = 'API Configuration';
$string['api_settings_desc'] = 'Configure your UPayments API settings';
$string['cost_settings'] = 'Default Cost Settings';
$string['cost_settings_desc'] = 'Configure default cost settings for courses';

// Production Settings
$string['prod_settings'] = 'Production Settings';
$string['prod_settings_desc'] = 'Configure production API settings for real transactions';

// Sandbox Settings
$string['sandbox_settings'] = 'Sandbox Settings';
$string['sandbox_settings_desc'] = 'Configure sandbox API settings for testing';
$string['sandbox'] = 'Enable Sandbox Mode';
$string['sandbox_desc'] = 'Enable sandbox mode for testing payments without real transactions';
$string['sandbox_token'] = 'Sandbox Token';
$string['sandbox_token_desc'] = 'Your UPayments sandbox API token for testing';
$string['sandbox_apiurl'] = 'Sandbox API URL';
$string['sandbox_apiurl_desc'] = 'UPayments sandbox API URL (leave empty for default)';
$string['sandbox_mode_enabled'] = 'Sandbox mode is enabled. All transactions will be test transactions.';
$string['sandbox_mode_disabled'] = 'Sandbox mode is disabled. All transactions will be real transactions.';
