<?php

\yform\usability\Usability::installTableSets($this->getPath('install/tablesets/*.json'));
\yform\usability\Usability::installTableStructure($this->getPath('install/db_structure/*.php'));
\yform\usability\Usability::installModules($this->getPath('install/module/*'));