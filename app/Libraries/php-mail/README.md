# JacksonJeans/Mail v1

A mail composer, phpmailer alternative, and smtp client.

[Install](#install)

​	[Basic Usage](#basicusage)

​	[Usage](#usage)

​	[SMTP](#smtp)

​	[Attachment & Reply](#attachmentsandreply)

​	[Priority and Custom Headers](#priorityandcustomheaders)

​	[Custom Transport](#customtransport)

​	[Export & Import](#exportandimport)
<a name="install"></a>
## Install

The recommended way to install this library is [through Composer](https://getcomposer.org).

```bash
$ composer install
```
<a name="basicusage"></a>
## Basic Usage
```php
$mail = new JacksonJeans\Mail();
$mail->setFrom('example@example.com')
	->setTo('general.julian.tietz@outlook.com')
	->setSubject('Test Mail')
	->setText('Hi, Julian!')
	->send();
```
<a name="usage"></a>
## Usage
```php
// setup mail
$mail = new JacksonJeans\Mail();
$mail->setFrom('example@example.com', 'Example');
 
$mail->to('general.julian.tietz@outlook.com')
	->setSubject('Account activation')
	->setHTML('<html><body><p><b>Your account has activated!</b></p></body></html>', true)
	->send();

// method compose( $toEmail, $subject, $text )	
$mail->compose('admin@example.com', 'New Account', 'http://example.com/useradmin/123')->send();
```
<a name="smtp"></a>
## SMTP
```php
$mail = new JacksonJeans\Mail('smtp', [
	'host' => 'ssl://smtp.office365.com',
    'port'     => 465,
    'username' => 'general.julian.tietz@outlook.com',
    'password' => 'test'
]);

$mail->setFrom('general.julian.tietz@outlook.com')
	->setTo('general.julian.tietz@outlook.com')
	->setSubject('Test SMTP')
	->setText('Outlook SMTP secured server')
	->send();
```
<a name="attachmentsandreply"></a>
## Attachments & Reply
```php
$mail = new JacksonJeans\Mail();
$mail->setFrom('example@example.com')
	->setTo('general.julian.tietz@outlook.com')
	->setSubject('Test attachments')
	->setHTML('<html><body><p>See attached price list.</p><p><img src="logo.jpg" /> Logo</p></body></html>')
	->attach( __DIR__.'/doc/PriceList.pdf')
	->attach( __DIR__.'/images/logo400x300.jpg', 'logo.jpg')
	->setReply('manager@example.com')
	->send();
```
<a name="priorityandcustomheaders"></a>
## Priority & Custom-Headers

You can use your own headers and set the priority of your email. Class constants are provided for this purpose. 
Mail::PRIORITY_NORMAL, Mail::PRIORITY_URGENT, Mail::PRIORITY_NON_URGENT

Further header information is passed as array

```php
$mail = new JacksonJeans\Mail();
$mail->setFrom('example@example.com')
	->setTo('general.julian.tietz@outlook.com')
	->setSubject('WARNING!')
	->setText('SERVER DOWN!')
	->setPriority(Mail::PRIORITY_URGENT)
	->setCustomHeaders(['Cc' => 'admin@exmple.com'])
	->send();
```
<a name="customtransport"></a>
## CustomTransport
```php
$mail = new JacksonJeans\Mail( function( $mail, $encoded ) {
	var_dump( $encoded );	
});
$mail->setFrom('example@example.com')
	->setTo('general.julian.tietz@outlook.com')
	->setSubject('WARNING!')
	->setText('SERVER DOWN!')
	->send();

/*
Array
(
    [from] => example@example.com
    [to] => general.julian.tietz@outlook.com
    [subject] => =?UTF-8?B?V0FSTklORyE=?=
    [message] => SERVER DOWN!
    [headers] => To: general.julian.tietz@outlook.com
Subject: =?UTF-8?B?V0FSTklORyE=?=
X-Mailer: PHP/7.2.14
MIME-Version: 1.0
From: example@example.com
Reply-To: example@example.com
Date: Mon, 18 Feb 2019 13:17:28 +0000
Content-Type: text/plain; charset="UTF-8"
)
*/
```
<a name="exportandimport"></a>

## Export & Import
```
Mail::toArray() - export to array
Mail::fromArray( $data ) - import from assoc array (fabric)
Mail::toJSON() - export to JSON
Mail::fromJSON( $json ) - import from json (fabric)
```


