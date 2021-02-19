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
 * @package    local_twofactorauth
 * @copyright
 * @license    http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

$string['pluginname'] = 'Two Factor Authentication';

/* system section */
$string['disabled'] = 'Disabled';
$string['enabled'] = 'Enabled';

$string['nosuchprovider'] = 'No such provider';
$string['providerclassnotfound'] = 'Provider class not found!';
$string['notalloweduser'] = 'Not allowed for this user';

$string['runcomposer'] = 'You must run "composer install" at local/twofactorauth directory';

/* admin settings section */
$string['generalsettings'] = 'General Settings';
$string['providers'] = 'Providers';
$string['applytarget'] = 'Apply Target';

$string['enabletwofactorauth'] = 'Enable 2 Factor Auth';
$string['enabletwofactorauthdesc'] = 'Enable this plugin';

$string['myprofilecategoryname'] = 'Two factor authentication';
$string['otp_app'] = 'OTP app';
$string['google_authenticator'] = 'Google Authenticator';
$string['microsoft_authenticator'] = 'Microsoft Authenticator';

$string['allowgoogleauthenticator'] = 'Google Authenticator';
$string['allowgoogleauthenticatordesc'] = 'Allow to use Google Authenticator';
$string['allowmicrosoftauthenticator'] = 'Microsoft Authenticator';
$string['allowmicrosoftauthenticatordesc'] = 'Allow to use Microsoft Authenticator';
$string['allowmailonetimecode'] = 'email';
$string['allowmailonetimecodedesc'] = 'Allow to use mail onetime code';

$string['allowotpapp'] = 'Allow OTP App';
$string['allowotpappdesc'] = 'Allow to Use Timebased OTP app';

$string['enableemaildefault'] = 'Enable email default';
$string['enableemaildefaultdesc'] = 'Enable email provider as user\'s default when email provider is allowed';

$string['allowtononadmin'] = 'Allow to non admin users';
$string['allowtononadmindesc'] = 'Allow to use for non admin users';

$string['trustdevice'] = 'Trust Device';
$string['trustdevicedesc'] = 'Enable trust device function';

$string['forcetousetarget'] = 'Force to use';
$string['forcetousetargetdesc'] = 'Force to use for targeted users.';
$string['notforcetoanyone'] = 'Not force to anyone';
$string['forcetoadmins'] = 'Force to site administrators';
$string['forcetoeveryone'] = 'Force to everyone';

$string['excludeip'] = 'Exclude IP';
$string['excludeipdesc'] = 'Exclude IPs from this plugin';

/* user profile section */
$string['twofactorauthentication'] = 'Two factor authentication';
$string['yourtwofactorauthenticationconfigration'] = 'Your Two factor authentication provider configuration';

$string['providerconfigverified'] = 'Provider config is verified.';
$string['deleteproviderconfig'] = 'Delete provider config';
$string['providerconfigdeleted'] = 'Provider config was successfully deleted!';
$string['returntoprofile'] = 'Return to User profile';

$string['provideralreadyinstalled'] = '{$a} is already installed!';

/* provider setup section */
// OTP App
$string['providersetupinstruction_otp_app'] = 'OTP app two factor authentication is not activated.<br/>
Install OTP app in your smart phone and scan QR code displayed below.
And enter PIN code which displayed in OTP app for confirmation for complete setup.';
$string['providersetupformmessage_otp_app'] = 'Please enter code displayed in OTP app';
$string['providerverifysuccess_otp_app'] = 'OTP code verification success! OTP app two factor authentication is enabled.';
$string['providerverifyerror_otp_app'] = 'Failed to verify OTP code! Try again or generate new QR code.';

// Google Authenticator
$string['providersetupinstruction_google_authenticator'] = 'Google Authenticator two factor authentication is not activated.<br/>
Install "Google Authenticator" application in your smart phone and scan QR code displayed below.
And enter OTP code which displayed in Google Authenticator for confirmation for complete setup.';
$string['providersetupformmessage_google_authenticator'] = 'Please enter code displayed in Google Authenticator application';
$string['providerverifysuccess_google_authenticator'] = 'OTP code verification success! Google Autenticator two factor authentication is enabled.';
$string['providerverifyerror_google_authenticator'] = 'Failed to verify OTP code! Try again or generate new QR code.';

// Microsoft Authenticator
$string['providersetupinstruction_microsoft_authenticator'] = 'Microsoft Authenticator two factor authentication is not activated.<br/>
Install "Microsoft Authenticator" application in your smart phone and launch it. Tap "+" and select "Other account (Google, Facebook, etc.)", then scan QR code displayed below.
And enter OTP code which displayed in Google Authenticator for confirmation for complete setup.';
$string['providersetupformmessage_microsoft_authenticator'] = 'Please enter code displayed in Microsoft Authenticator application';
$string['providerverifysuccess_microsoft_authenticator'] = 'OTP code verification success! Google Autenticator two factor authentication is enabled.';
$string['providerverifyerror_microsoft_authenticator'] = 'Failed to verify OTP code! Try again or generate new QR code.';

// email
$string['providersetupinstruction_email'] = 'An e-mail was sent to "{$a}". Please check this and enter OTP code for complete setup.';
$string['providersetupinstructionerror_email'] = 'Can not send e-mail to "{$a}"';
$string['providersetupemailsubject'] = 'Your One Time Passsowd(OTP) code for "{$a}"';
$string['providersetupemailbody'] = 'Someone request to issue OTP code for login "{$a->wwwroot}".
Your OTP code is: {$a->otpcode}
This OTP code is avaliable only in 10 mins.

If you are innocent about this mail. Please contact to site administrator!';
$string['providersetupformmessage_email'] = 'Please enter code displayed in received e-mail.';
$string['providerverifysuccess_email'] = 'OTP code verification success! e-mail two factor authentication is enabled.';
$string['providerverifyerror_email'] = 'Failed to verify OTP code! Try again or reload this page for send new e-mail.';

$string['otpcode'] = 'OTP code';
$string['verifycode'] = 'Verify code';
$string['qrcode'] = 'QR code';

$string['invalidemail'] = 'Invalid email address!';
$string['email'] = 'e-mail';

/* authentication section */
$string['selectprovider'] = 'Select two factor authentication provider';
$string['selectproviderdesc'] = 'Select two factor authentication provider which you want to use.';
$string['noavailableprovider'] = 'No avaliable two factor authentication provider found!';
$string['noavailableproviderdesc'] = 'You can not continue login process because additional authentication is required using two factor authenticator.
First, you must configure two factor authentication at trusted networks.';
$string['usethisprovider'] = 'Use this authentication provider';

$string['challengetwofactorauth'] = 'Challenge Two factor authentication';
$string['inputotpcode'] = 'Please input OTP code for verification.';
$string['verificationsuccess'] = 'Verification Success';
$string['verificationfailure'] = 'Verification Failure!';
$string['otpcodetransmiterror'] = 'Failed to transmit OTP code! Please contact system admin.';
$string['transmit_code_email'] = 'OTP code is transmitted to {$a}. Check your email.';

