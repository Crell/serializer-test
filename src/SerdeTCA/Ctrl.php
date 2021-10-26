<?php

declare(strict_types=1);

namespace Crell\SerializerTest\SerdeTCA;

use Crell\Serde\Field;
use Crell\Serde\Renaming\Cases;

class Ctrl
{
    public function __construct(
        /* Required */
        public readonly string $label,

        /* Processing */
        public readonly bool $adminOnly = false,
        // List of values, so should maybe be an array?
        public readonly string $copyAfterDuplFields = '',
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
        // Ain't renaming wonderful?
        #[Field(serializedName: 'prependAtCopy')]
        public readonly string $appendAtCopy = '',
        public readonly bool $readOnly = false,
        public readonly CtrlRootLevel $rootLevel = CtrlRootLevel::PageTreeOnly,
        // List of values, so should maybe be an array?
        public readonly string $searchFields = '',
        // List of values, so should maybe be an array?
        public readonly string $shadowColumnsForNewPlaceholders = '',
        #[Field(serializedName: 'sortby')]
        public readonly string $sortBy = '',
        public readonly string $translationSource = '',
        public readonly string $transOrigDiffSourceField = '',
        public readonly string $transOrigPointerField = '',
        public readonly string $tstamp = '',
        public readonly string $type = '',
        #[Field(renameWith: Cases::snake_case)]
        public readonly array $typeiconClasses = [],
        #[Field(renameWith: Cases::snake_case)]
        public readonly string $typeiconColumn = '',
        // List of values, so should maybe be an array?
        public readonly string $useColumnsForDefaultValues = '',
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
        public readonly string $labelAlt = '',
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

