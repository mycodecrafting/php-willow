<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Versioning that is based on the NumVersion Struct
 *
 * Has the following revision indicators:
 *
 *     <tt>[major revision].[minor revision].[bug revision]-[stage][stage revision]</tt>
 *
 * <strong>Major revision</strong>
 *      - Increments indicate significant changes and/or jumps in functionality and/or
 *        programming.
 *      - The major revision number begins at 1 and increments from there.
 *      - The major revision number may not jump or skip any revisions.
 *
 * <strong>Minor revision</strong>
 *      - Increments indicate minor feature changes and/or significant bug fixes.
 *      - The minor revision number is limited to a single digit, 0 - 9.
 *      - The minor revision number may jump or skip revisions if there are significant changes,
 *        but not significant enough to warrant a major revision increment. As a general guide,
 *        the jumping should be done in increments of 5 revisions, thus allowing only one jump per
 *        major revision before the major revision must be incremented. Minor revision releases may
 *        be referred to as "point releases".
 *
 * <strong>Bug revision</strong>
 *      - Increments indicate minor bug fixes.
 *      - The bug revision number is limited to a single digit, 0 - 9.
 *      - The bug revision number may not jump or skip any revisions. If there are significant
 *        enough fixes to warrant such a revision jump, the minor revision should be incremented
 *        instead.
 *
 * <strong>Stage indicator</strong>
 *      - Consists of a string belonging to one of "dev", "alpha", "beta", "rc", or "final", where:
 *          - "dev" stages are internal private releases
 *          - "alpha" stages are very early releases that may or may not be plublic releases
 *          - "beta" stages are public releases intended for early adopters and other "beta testers"
 *          - "rc" stages are release candidates indended for more widespread testing
 *          - "final" stages are stable releases and should be "production worthy"
 *
 * <strong>Stage revision</strong>
 *      - The stage revision number begins at 1 and increments from there.
 *      - The stage revision number may not jump or skip any revisions.
 *
 * In the case of final stage releases, the stage and stage revision indicators are omitted.
 *
 * Only the final release stage may increment the other revisions.
 *
 * When a revision indicator's number increments, all lower revision indicators should roll back
 * to their starting position.
 */
final class Willow_Application_Version
{

    const APP_NAME  = 'Willow Application';
    const MAJOR_REV = 1;
    const MINOR_REV = 0;
    const BUG_REV   = 0;
    const STAGE     = 'dev';
    const STAGE_REV = 1;
    const REL_DATE  = '$Date$';

    public static function getVersion()
    {
        if (self::STAGE === 'final')
        {
            return self::APP_NAME . ' ' . self::MAJOR_REV . '.' . self::MINOR_REV . '.' .
                self::BUG_REV;
        }

        return self::APP_NAME . ' ' . self::MAJOR_REV . '.' . self::MINOR_REV . '.' .
            self::BUG_REV . '-' . self::STAGE . self::STAGE_REV;
    }

    public static function getDate()
    {
        preg_match('/\d{4}-\d{2}-\d{2}/', self::REL_DATE, $matches);
        return $matches[0];
    }

    public static function getVersionWithDate()
    {
        return self::getVersion() . ' (' . self::getDate() . ')';
    }

}
