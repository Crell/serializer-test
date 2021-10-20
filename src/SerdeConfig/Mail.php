<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

use Crell\Serde\Field;
use Crell\Serde\Renaming\Cases;

/**
 * Mail configuration.
 */
class Mail
{
    public function __construct(
        /**
         * The Mailer API allows to send out templated emails, which can be configured on a system-level to send out HTML-based emails or plain text emails, or emails with both variants.
         */
        public readonly MailFormat $format = MailFormat::Both,
        /**
         * List of paths to look for layouts for templated emails. Should be specified as .txt and .html files.
         */
        public readonly array $layoutRootPaths = [
            0 => 'EXT:core/Resources/Private/Layouts/',
            10 => 'EXT:backend/Resources/Private/Layouts/',
        ],
        /**
         * List of paths to look for partials for templated emails. Should be specified as .txt and .html files.
         */
        public readonly array $partialRootPaths = [
            0 => 'EXT:core/Resources/Private/Partials/',
            10 => 'EXT:backend/Resources/Private/Partials/',
        ],
        /**
         * List of paths to look for template files for templated emails. Should be specified as .txt and .html files.
         */
        public readonly array $templateRootPaths = [
            0 => 'EXT:core/Resources/Private/Templates/Email/',
            10 => 'EXT:backend/Resources/Private/Templates/Email/',
        ],
        /**
         * List of validators used to validate an email address. <br>Available validators are <code>\Egulias\EmailValidator\Validation\DNSCheckValidation</code>, <code>\Egulias\EmailValidator\Validation\SpoofCheckValidation</code>, <code>\Egulias\EmailValidator\Validation\NoRFCWarningsValidation</code> or by implementing a custom validator.
         */
        public readonly array $validators = [
            \Egulias\EmailValidator\Validation\RFCValidation::class,
        ],
        /**
         * <dl><dt>smtp</dt><dd>Sends messages over the (standardized) Simple Message Transfer Protocol. It can deal with encryption and authentication. Most flexible option, requires a mail server and configurations in transport_smtp_* settings below. Works the same on Windows, Unix and MacOS.</dd><dt>sendmail</dt><dd>Sends messages by communicating with a locally installed MTA - such as sendmail. See setting transport_sendmail_command bellow.<dd><dt>dsn</dt><dd>Sends messages with the Symfony Mailer. Configure [MAIL][dsn] setting below.</dd><dt>mbox</dt><dd>This doesn''t send any mail out, but instead will write every outgoing mail to a file adhering to the RFC 4155 mbox format, which is a simple text file where the mails are concatenated. Useful for debugging the mail sending process and on development machines which cannot send mails to the outside. Configure the file to write to in the ''transport_mbox_file'' setting below</dd><dt>&lt;classname&gt;</dt><dd>Custom class which implements \Symfony\Component\Mailer\Transport\TransportInterface. The constructor receives all settings from the MAIL section to make it possible to add custom settings.</dd></dl>
         */
        public readonly string $transport = 'sendmail',
        /**
         * <em>only with transport=smtp</em>: &lt;server:port> of mailserver to connect to. &lt;port> defaults to "25".
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $transportSmtpServer = 'localhost:25',
        /**
         * <em>only with transport=smtp</em>: Connect to the server using SSL/TLS (disables STARTTLS which is used by default if supported by the server). Must not be enabled when connecting to port 587, as servers will use STARTTLS (inner encryption) via SMTP instead of SMTPS. It will automatically be enabled if port is 465.
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly bool $transportSmtpEncrypt = false,
        /**
         * <em>only with transport=smtp</em>: If your SMTP server requires authentication, enter your username here.
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $transportSmtpUsername = '',
        /**
         * <em>only with transport=smtp</em>: If your SMTP server requires authentication, enter your password here.
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $transportSmtpPassword = '',
        /**
         * <em>only with transport=sendmail</em>: The command to call to send a mail locally.
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $transportSendmailCommand = '',
        /**
         * <em>only with transport=mbox</em>: The file where to write the mails into. This file will be conforming the mbox format described in RFC 4155. It is a simple text file with a concatenation of all mails. Path must be absolute.
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $transportMboxFile = '',
        /**
         * <dl><dt>file</dt><dd>Messages get stored to the file system till they get sent through the command mailer:spool:send.</dd><dt>memory</dt><dd>Messages get sent at the end of the running process.</dd><dt>&lt;classname&gt;</dt><dd>Custom class which implements the \TYPO3\CMS\Core\Mail\DelayedTransportInterface interface.</dd></dl>
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $transportSpoolType = '',
        /**
         * <em>only with transport_spool_type=file</em>: Path where messages get temporarily stored. Ensure that this is stored outside of your webroot.
         */
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $transportSpoolFilepath = '',
        /**
         * <em>only with transport=dsn</em>: The DSN configuration of the Symfony mailer (eg. smtp://user:pass@smtp.example.com:25). For 3rd party transports you have to add additional dependencies. See https://symfony.com/doc/current/mailer.html for more details.
         */
        public readonly string $dsn = '',
        /**
         * This default email address is used when no other "from" address is set for a TYPO3-generated email. You can specify an email address only (eg. info@example.org).
         */
        public readonly string $defaultMailFromAddress = '',
        /**
         * This default name is used when no other "from" name is set for a TYPO3-generated email.
         */
        public readonly string $defaultMailFromName = '',
        /**
         * This default email address is used when no other "reply-to" address is set for a TYPO3-generated email. You can specify an email address only (eg. info@example.org).
         */
        public readonly string $defaultMailReplyToAddress = '',
        /**
         * This default name is used when no other "reply-to" name is set for a TYPO3-generated email.
         */
        public readonly string $defaultMailReplyToName = '',
    ) {}
}
