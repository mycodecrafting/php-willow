<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Register default validators
 */
Willow_Validate::register('contains', 'Willow_Validate_Rule_Contains');
Willow_Validate::register('hasLengthOf', 'Willow_Validate_Rule_HasLengthOf');
Willow_Validate::register('isAnEmailAddress', 'Willow_Validate_Rule_IsAnEmailAddress');
Willow_Validate::register('isAlpha', 'Willow_Validate_Rule_IsAlpha');
Willow_Validate::register('isAlphaNumeric', 'Willow_Validate_Rule_IsAlphaNumeric');

Willow_Validate::register('isEqualTo', 'FlexieIsEqualToValidator');
Willow_Validate::register('isInRange', 'FlexieIsInRangeValidator');

Willow_Validate::register('isNotEmpty', 'Willow_Validate_Rule_IsNotEmpty');
Willow_Validate::register('isNumeric', 'Willow_Validate_Rule_IsNumeric');

Willow_Validate::register('matches', 'FlexieMatchesValidator');
