<?php
/*
 * This file is part of the MonstrumSymfonyDropZone.
 *
 * (c) Erwin Eu <eu.erwin@gmail.com>
 */
namespace Monstrum\SymfonyJQueryPluginBundle\Composer;

use Composer\Script\Event;
use Mopa\Bridge\Composer\Util\ComposerPathFinder;
use Monstrum\SymfonyDropZoneBundle\Command\SymfonyDropZoneSymlinkCommand;

/**
 * Script for Composer, create symlink to symfony-dropzone lib into the MonstrumSymfonyJQueryPluginBundle.
 */
class ScriptHandler
{
    public static function postInstallSymlinkSymfonyDropZone(Event $event)
    {
        $IO = $event->getIO();
        $composer = $event->getComposer();
        $cmanager = new ComposerPathFinder($composer);
        $options = array(
            'targetSuffix' => self::getTargetSuffix(),
            'sourcePrefix' => self::getSourcePrefix()
        );
        list($symlinkTarget, $symlinkName) = $cmanager->getSymlinkFromComposer(
            SymfonyDropZoneSymlinkCommand::$monstrumSymfonyDropZoneBundleName,
            SymfonyDropZoneSymlinkCommand::$dropzoneName,
            $options
        );
        $symlinkTarget .= !empty(SymfonyDropZoneSymlinkCommand::$sourceSuffix) ? DIRECTORY_SEPARATOR . SymfonyDropZoneSymlinkCommand::$sourceSuffix : '';

        $IO->write("Checking Symlink", FALSE);
        if (false === SymfonyDropZoneSymlinkCommand::checkSymlink($symlinkTarget, $symlinkName, true)) {
            $IO->write("Creating Symlink: " . $symlinkName, FALSE);
            SymfonyDropZoneSymlinkCommand::createSymlink($symlinkTarget, $symlinkName);
        }
        $IO->write(" ... <info>OK</info>");
    }

    public static function postInstallMirrorSymfonyDropZoneBundle(Event $event)
    {
        $IO = $event->getIO();
        $composer = $event->getComposer();
        $cmanager = new ComposerPathFinder($composer);
        $options = array(
            'targetSuffix' =>  self::getTargetSuffix(),
            'sourcePrefix' => self::getSourcePrefix()
        );
        list($symlinkTarget, $symlinkName) = $cmanager->getSymlinkFromComposer(
            SymfonyDropZoneSymlinkCommand::$monstrumSymfonyDropZoneBundleName,
            SymfonyDropZoneSymlinkCommand::$dropzoneName,
            $options
        );
        $symlinkTarget .= !empty(SymfonyDropZoneSymlinkCommand::$sourceSuffix) ? DIRECTORY_SEPARATOR . SymfonyDropZoneSymlinkCommand::$sourceSuffix : '';

        $IO->write("Checking Mirror", FALSE);
        if (false === SymfonyDropZoneSymlinkCommand::checkSymlink($symlinkTarget, $symlinkName)) {
            $IO->write("Creating Mirror: " . $symlinkName, FALSE);
            SymfonyDropZoneSymlinkCommand::createMirror($symlinkTarget, $symlinkName);
        }
        $IO->write(" ... <info>OK</info>");
    }

    protected static function getTargetSuffix($end = "")
    {
        return DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "public" . $end;
    }

    protected static function getSourcePrefix($end = "")
    {
        return '..' . DIRECTORY_SEPARATOR . $end;
    }
}
