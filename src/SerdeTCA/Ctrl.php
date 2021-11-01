<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\DictionaryField;
use Crell\Serde\Field;
use Crell\Serde\Renaming\Cases;
use Crell\Serde\SequenceField;

class Ctrl
{
    public function __construct(
        /* Required */
        public readonly string $label,

        /* Processing */
        public readonly bool $adminOnly = false,
        #[SequenceField(implodeOn: ', ')]
        public readonly array $copyAfterDuplFields = [],
        public readonly string $crdate = 'crdate',
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $cruserId = 'cruser_id',
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $defaultSortby = '',
        public readonly string $delete = '',
        public readonly string $editlock = '',
        #[Field(serializedName: 'enablecolumns')]
        public readonly CtrlEnableColumns $enableColumns = new CtrlEnableColumns(),
        // Big blob of stuff.
        public readonly array $EXT = [],
        public readonly bool $hideAtCopy = false,
        public readonly bool $hideTable = false,
        #[Field(serializedName: 'iconfile')]
        public readonly string $iconFile = '',
        // This is deprecated in the docs; not clear if the whole field is or just some uses.
        public readonly string $languageField = '',
        public readonly string $origUid = '',
        // Docs say this is named wrong, so let's fix that.
        #[Field(serializedName: 'prependAtCopy')]
        public readonly string $appendAtCopy = '',
        public readonly bool $readOnly = false,
        public readonly CtrlRootLevel $rootLevel = CtrlRootLevel::PageTreeOnly,
        #[SequenceField(implodeOn: ',')]
        public readonly array $searchFields = [],
        #[SequenceField(implodeOn: ',')]
        public readonly array $shadowColumnsForNewPlaceholders = [],
        #[Field(serializedName: 'sortby')]
        public readonly string $sortBy = '',
        public readonly string $translationSource = '',
        public readonly string $transOrigDiffSourceField = '',
        public readonly string $transOrigPointerField = '',
        public readonly string $tstamp = '',
        public readonly string $type = '',
        #[Field(renameWith: Cases::snake_case)]
        #[DictionaryField]
        public readonly array $typeiconClasses = [],
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $typeiconColumn = '',
        #[SequenceField(implodeOn: ',')]
        public readonly array $useColumnsForDefaultValues = [],
        public readonly bool $versioningWS = true,

        /* Display */
        // @todo This should probably have defined classes.
        public readonly array $container = [],
        public readonly string $descriptionColumn = '',
        #[Field(serializedName: 'formattedLabel_userFunc')]
        public readonly string $formattedLabelUserFunc = '',
        #[Field(serializedName: 'formattedLabel_userFunc_options')]
        public readonly array $formattedLabelUserFuncOptions = [],
        // List of values, so should maybe be an array?
        #[Field(renameWith: Cases::snake_case)]
        #[SequenceField(implodeOn: ',')]
        public readonly array $labelAlt = [],
       #[Field(renameWith: Cases::snake_case)]
        public readonly bool $labelAltForce = false,
        #[Field(serializedName: 'label_userFunc')]
        public readonly string $labelUserFunc = '',
        #[Field(serializedName: 'label_userFunc_options')]
        public readonly string $labelUserFuncOptions = '',
        public readonly CtrlSecurity $security = new CtrlSecurity(),
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $seliconField = '',
        public readonly string $title = '',

        /* Special */
        public readonly string $groupName = '',
        #[Field(renameWith: Cases::snake_case)]
        public readonly bool $isStatic = false,
        #[Field(serializedName: 'versioningWS_alwaysAllowLiveEdit')]
        public readonly bool $versioningWsAlwaysAllowLiveEdit = true,
    ) {}
}

