<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7deaa09936fb532beef6cbd0b38e2d43
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'P' => 
        array (
            'PhpOption\\' => 10,
        ),
        'G' => 
        array (
            'GrahamCampbell\\ResultType\\' => 26,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'PhpOption\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption',
        ),
        'GrahamCampbell\\ResultType\\' => 
        array (
            0 => __DIR__ . '/..' . '/graham-campbell/result-type/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PhpToken' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/PhpToken.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'Telerivet_API' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/telerivet.php',
        'Telerivet_APIException' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/telerivet.php',
        'Telerivet_AirtimeTransaction' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/airtimetransaction.php',
        'Telerivet_ApiCursor' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/apicursor.php',
        'Telerivet_Broadcast' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/broadcast.php',
        'Telerivet_Contact' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/contact.php',
        'Telerivet_ContactServiceState' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/contactservicestate.php',
        'Telerivet_CustomVars' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/entity.php',
        'Telerivet_DataRow' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/datarow.php',
        'Telerivet_DataTable' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/datatable.php',
        'Telerivet_Entity' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/entity.php',
        'Telerivet_Exception' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/telerivet.php',
        'Telerivet_Group' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/group.php',
        'Telerivet_IOException' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/telerivet.php',
        'Telerivet_InvalidParameterException' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/telerivet.php',
        'Telerivet_Label' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/label.php',
        'Telerivet_Message' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/message.php',
        'Telerivet_NotFoundException' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/telerivet.php',
        'Telerivet_Organization' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/organization.php',
        'Telerivet_Phone' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/phone.php',
        'Telerivet_Project' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/project.php',
        'Telerivet_RelativeScheduledMessage' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/relativescheduledmessage.php',
        'Telerivet_Route' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/route.php',
        'Telerivet_ScheduledMessage' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/scheduledmessage.php',
        'Telerivet_Service' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/service.php',
        'Telerivet_Task' => __DIR__ . '/..' . '/telerivet/telerivet-php-client/lib/task.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7deaa09936fb532beef6cbd0b38e2d43::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7deaa09936fb532beef6cbd0b38e2d43::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7deaa09936fb532beef6cbd0b38e2d43::$classMap;

        }, null, ClassLoader::class);
    }
}
