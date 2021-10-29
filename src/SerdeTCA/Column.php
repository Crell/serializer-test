<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\DictionaryField;
use Crell\Serde\Field;
use Crell\Serde\Renaming\Cases;
use Crell\Serde\SequenceField;
use Crell\Serde\StaticTypeMap;

class Column
{
    public function __construct(
        public readonly string $label,
        public readonly TypeConfig $config,
        public readonly string $description = '',
        // This is a union type, so... no idea what to do here.
        //public readonly string|array $displayCond = [],
        public readonly bool $exclude = false,
        #[Field(serializedName: 'l10n_display')]
        public readonly string $localizationDisplay = '',
        #[Field(serializedName: 'l10n_mode')]
        public readonly string $localizationMode = '',
        public readonly OnChange $onChange = OnChange::None,
    ) {}
}

enum OnChange: string
{
    case None = '';
    case Reload = 'reload';
}

#[StaticTypeMap('type', [
    'none' => NoneConfig::class,
    'input' => InputConfig::class,
    'select' => SelectConfig::class,
])]
interface TypeConfig
{

}

class NoneConfig implements TypeConfig
{
    public function __construct(
        public readonly int $cols = 30,
        public readonly string $format = '',

        // This one is tricky. The format. field is
        // dependent on the format field to determine
        // its type. That's a type map, but with an
        // external key.  I... don't know how to do that.
        //#[Field(serializedName: 'format.')]
        //public readonly NoneConfigFormat $formatConfig = new NoneConfigFormat(),
        #[Field(renameWith: Cases::snake_case)]
        public readonly bool $passContent = false,
        public readonly int $size = 30,

    ) {}
}

class NoneConfigFormat
{
    public function __construct(
        // Docs are unclear on this one.
        //public readonly DateFormat $date

    ) {}

}


class InputConfig implements TypeConfig
{
    public function __construct(
        /* Common fields. */
        public readonly ?int $autoSizeMax = null,
        public readonly Behaviour $behavor = new Behaviour(),
        // Technically should be string|int, but Serde doesn't support
        // unions yet. Does this mean we have to? :-(
        //public readonly ?string $default = null,

        #[SequenceField(implodeOn: ',')]
        public readonly array $dontRemapTablesOnCopy = [],
        public readonly FieldControl $fieldControl = new FieldControl(),
        // Array seems like an odd type, but that's what the docs say it is.
        public readonly array $fieldInformation = [],

        // And more Common fields I am not going to model now because they're huge...


    ) {}
}

class FieldControl
{
    public function __construct(
        public readonly FieldControlAddRecord $addRecord = new FieldControlAddRecord(),
        public readonly FieldControlEditPopup $editPopup = new FieldControlEditPopup(),
        public readonly FieldControlListModule $listModule = new FieldControlListModule(),
        public readonly FieldControlResetSelection $resetSelection = new FieldControlResetSelection(),
    ) {}
}

class FieldControlResetSelection
{
    public function __construct(
        // There's no documentation on what this is, so just grab it all.
        #[Field(flatten: true)]
        public readonly array $options = [],
    ) {}
}

class FieldControlListModule
{
    public function __construct(
        public readonly bool $disabled = true,
        public readonly FieldControlListModuleOptions $options = new FieldControlListModuleOptions(),
    ) {}
}

class FieldControlListModuleOptions
{
    public function __construct(
        public readonly string $pid = '###CURRENT_PID###',
        public readonly string $table  = '',
        public readonly string $title  = 'LL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.list',
    ) {}
}


class FieldControlEditPopup
{
    public function __construct(
        public readonly bool $disabled = true,
        public readonly FieldControlEditPopupOptions $options = new FieldControlEditPopupOptions(),
    ) {}
}

class FieldControlEditPopupOptions
{
    public function __construct(
        public readonly string $title = 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.edit',
        #[DictionaryField(implodeOn: ',', joinOn: '=')]
        public readonly array $windowOpenParameters = [
            'height' => 800,
            'width' => 600,
            'status' => 0,
            'menubar' => 0,
            'scrollbars'=> 1,
        ],
    ) {}
}

class FieldControlAddRecord
{
    public function __construct(
        public readonly bool $disabled = true,
        public readonly FieldControlAddRecordOptions $options = new FieldControlAddRecordOptions(),
    ) {}
}

class FieldControlAddRecordOptions
{
    public function __construct(
        public readonly string $pid = '###CURRENT_PID###',
        public readonly string $table  = '',
        public readonly string $title  = 'LL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.createNew',
        public readonly SetValue $setValue  = SetValue::append,
    ) {}
}

enum SetValue
{
    case set;
    case prepend;
    case append;
}

interface RenderType {}

class ColorPicker implements RenderType
{
    public const TypeName = 'colorpicker';

    public function __construct(
        public readonly int $size = 10,
    ) {}
}

class LinkField implements RenderType
{
    public const TypeName = 'inputLink';

    public function __construct(

    ) {}
}

// UK spelling?  Really?  What is the world coming to???
class Behaviour
{
    public function __construct(
         public readonly bool $allowLanguageSynchronization = true,
    ) {}
}

class SelectConfig implements TypeConfig
{
    public function __construct(
        public readonly bool $readOnly
    ) {}
}
