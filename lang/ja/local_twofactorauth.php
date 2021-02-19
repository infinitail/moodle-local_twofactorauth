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

$string['pluginname'] = '２要素認証';

$string['generalsettings'] = '一般設定';
$string['providers'] = 'プロバイダ';
$string['applytarget'] = '適用対象';

$string['disabled'] = '無効';
$string['enabled'] = '有効';

$string['runcomposer'] = '"composer install" を local/twofactorauth ディレクトリで実行する必要があります。';

$string['enabletwofactorauth'] = '２要素認証を有効にする';
$string['enabletwofactorauthdesc'] = 'この２要素認証プラグインを有効にします。';

$string['myprofilecategoryname'] = '２要素認証';
$string['otp_app'] = 'OTPアプリ';
$string['google_authenticator'] = 'Google Authenticator';
$string['microsoft_authenticator'] = 'Microsoft Authenticator';

$string['allowotpapp'] = 'OTPアプリを利用可能にする';

$string['allowgoogleauthenticator'] = 'Google Authenticator';
$string['allowgoogleauthenticatordesc'] = 'Google Authenticatorを利用可能にします。';
$string['allowmicrosoftauthenticator'] = 'Microsoft Authenticator';
$string['allowmicrosoftauthenticatordesc'] = 'Microsoft Authenticatorを利用可能にします。';
$string['allowmailonetimecode'] = 'eメール';
$string['allowmailonetimecodedesc'] = 'eメールを利用したワンタイムパスワードを利用可能にします。';

$string['enableemaildefault'] = 'eメールを既定で有効にする';
$string['enableemaildefaultdesc'] = 'eメールプロバイダが有効な場合、各ユーザのeメール２要素認証を既定で有効にします。';

$string['allowtononadmin'] = '管理者以外に利用を許可する';
$string['allowtononadmindesc'] = '管理者以外のユーザに２要素認証の利用を許可します。';

$string['forcetousetarget'] = '使用を強制';
$string['forcetousetargetdesc'] = '対象となるユーザに使用を強制します。';
$string['notforcetoanyone'] = '誰にも強制しない';
$string['forcetoadmins'] = 'サイト管理者に強制する';
$string['forcetoeveryone'] = '全員に強制する';

$string['excludeip'] = '除外IP';
$string['excludeipdesc'] = '２要素認証の利用除外IPアドレスを指定します。';

$string['nosuchprovider'] = 'プロバイダが存在しません。';
$string['providerclassnotfound'] = 'プロバイダのクラスが存在しません！';
$string['notalloweduser'] = 'このユーザには利用が許可されていません。';

$string['twofactorauthentication'] = '２要素認証';
$string['yourtwofactorauthenticationconfigration'] = '２要素認証プロバイダ設定';

$string['providerconfigverified'] = 'プロバイダ設定が確認されました。';
$string['deleteproviderconfig'] = 'プロバイダ設定の削除';
$string['providerconfigdeleted'] = 'プロバイダ設定の削除に成功しました！';
$string['returntoprofile'] = 'ユーザプロファイルに戻る';

$string['provideralreadyinstalled'] = '２要素認証プロバイダ：{$a} は既に設定されています。';

$string['providersetupinstruction_otp_app'] = 'OTPアプリによる２要素認証はまだ有効になっていません。<br/>
スマートフォンにOTPアプリをインストールし、下のQRコードをスキャンしてください。
その後、OTPアプリに表示されたパスコード（ワンタイムパスワード）をフォームに入力して設定を完了してください。';
$string['providersetupformmessage_otp_app'] = 'OTPアプリに表示されたパスコードを入力してください。';
$string['providerverifysuccess_otp_app'] = 'パスコードの確認に成功しました！OTPアプリ２要素認証プロバイダが有効になりました。';
$string['providerverifyerror_otp_app'] = 'パスコードの確認に失敗しました。再度入力するか新しいQRコードを生成してください。';

$string['providersetupinstruction_google_authenticator'] = 'Google Authenticatorによる２要素認証はまだ有効になっていません。<br/>
スマートフォンにGoogle Authenticatorアプリをインストールし、下のQRコードをスキャンしてください。
その後、Google Authenticatorアプリに表示されたワンタイムパスワード（確認コード）をフォームに入力して設定を完了してください。';
$string['providersetupformmessage_google_authenticator'] = 'Google Authenticatorアプリに表示されたワンタイムパスワードを入力してください。';
$string['providerverifysuccess_google_authenticator'] = 'ワンタイムパスワードの確認に成功しました！Google Authenticator２要素認証プロバイダが有効になりました。';
$string['providerverifyerror_google_authenticator'] = 'ワンタイムパスワードの確認に失敗しました。再度入力するか新しいQRコードを生成してください。';

$string['providersetupinstruction_microsoft_authenticator'] = 'Google Authenticatorによる２要素認証はまだ有効になっていません。<br/>
スマートフォンにGoogle Authenticatorアプリをインストールし、下のQRコードをスキャンしてください。
その後、Google Authenticatorアプリに表示されたワンタイムパスワード（確認コード）をフォームに入力して設定を完了してください。';
$string['providersetupformmessage_google_authenticator'] = 'Google Authenticatorアプリに表示されたワンタイムパスワードを入力してください。';
$string['providerverifysuccess_google_authenticator'] = 'ワンタイムパスワードの確認に成功しました！Google Authenticator２要素認証プロバイダが有効になりました。';
$string['providerverifyerror_google_authenticator'] = 'ワンタイムパスワードの確認に失敗しました。再度入力するか新しいQRコードを生成してください。';

$string['providersetupinstruction_email'] = '"{$a}" にワンタイムパスワードが送信されました。メール記載のワンタイムコードを入力してセットアップを完了してください。';
$string['providersetupinstructionerror_email'] = '"{$a}" にメールを送信することができませんでした。';
$string['providersetupemailsubject'] = '"{$a}"のワンタイムパスワードです。';
$string['providersetupemailbody'] = '"{$a->wwwroot}" にログインするためのワンタイムパスワードは以下のとおりです。
ワンタイムパスワード： {$a->otpcode}
このワンタイムパスワードはメール送信から10分間のみ有効です。

もしこのメールに覚えがない場合は、サイト管理者に連絡して下さい。';
$string['providersetupformmessage_email'] = 'メールで送信されたワンタイムパスワードを入力してください。';
$string['providerverifysuccess_email'] = 'ワンタイムパスワードの確認に成功しました！eメール２要素認証プロバイダが有効になりました。';
$string['providerverifyerror_email'] = 'ワンタイムパスワードの確認に失敗しました。再度ワンタイムパスワードを送信するか、ページを再読込してメールを再送信してください。';

$string['otpcode'] = 'ワンタイムパスワード';
$string['verifycode'] = 'コードの確認';
$string['qrcode'] = 'QRコード';

$string['invalidemail'] = '無効なeメールアドレスが設定されています！';
$string['email'] = 'eメール';

$string['selectprovider'] = '２要素認証プロバイダの選択';
$string['selectproviderdesc'] = '利用する２要素認証プロバイダを選択してください。';
$string['noavailableprovider'] = '利用可能な２要素認証プロバイダが見つかりません！';
$string['noavailableproviderdesc'] = '２要素認証プロバイダによる追加の認証が要求されていますが利用可能な認証プロバイダが見つからないためログインを行うことができません。<br />
信頼されたネットワーク内で２要素認証プロバイダの設定を行ってください。';
$string['usethisprovider'] = 'この認証プロバイダを利用する';

$string['challengetwofactorauth'] = '２要素認証の試行';
$string['inputotpcode'] = 'ワンタイムパスワードを入力して認証を行ってください。';
$string['verificationsuccess'] = '認証に成功しました。';
$string['verificationfailure'] = '認証に失敗しました。';
$string['otpcodetransmiterror'] = 'ワンタイムパスワードの送信に失敗しました。システム管理者に連絡してください。';
$string['transmit_code_email'] = 'ワンタイムパスワードを {$a} に送信しました。';
