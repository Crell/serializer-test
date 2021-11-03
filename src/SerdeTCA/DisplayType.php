<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\SequenceField;

class DisplayType
{
    public function __construct(
        // This may be wrong, since $showitem also gets used
        // to define the layout structure of pallets. Which is nutty.
        // The alternative is to make this protected and provide
        // methods that disassemble it into the various options.
        #[SequenceField(implodeOn: ',')]
        public readonly array $showitem = [],
    ) {
    }
}