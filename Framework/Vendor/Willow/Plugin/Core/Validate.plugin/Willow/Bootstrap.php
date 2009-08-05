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
Willow_Validate::register('isNotEmpty', 'Willow_Validate_Rule_IsNotEmpty');
Willow_Validate::register('isNumeric', 'Willow_Validate_Rule_IsNumeric');
Willow_Validate::register('isUrl', 'Willow_Validate_Rule_IsUrl');
Willow_Validate::register('matches', 'Willow_Validate_Rule_Matches');
Willow_Validate::register('isIdenticalTo', 'Willow_Validate_Rule_IsIdenticalTo');
Willow_Validate::register('isEqualTo', 'Willow_Validate_Rule_IsEqualTo');
Willow_Validate::register('isTrue', 'Willow_Validate_Rule_IsTrue');
Willow_Validate::register('isCreditCardNumber', 'Willow_Validate_Rule_IsCreditCardNumber');

/**
 * @todo
 */
//Willow_Validate::register('isInRange', 'FlexieIsInRangeValidator');
