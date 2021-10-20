<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeConfig;

enum MailFormat: string
{
    /** Send emails only in HTML format */
    case Html = 'html';
    /** Send emails only in plain text format */
    case Plain = 'plain';
    /** Send emails in HTML and plain text format */
    case Both = 'both';
}
