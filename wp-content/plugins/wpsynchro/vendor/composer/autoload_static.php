<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd05bbfbb2950f7e4030f8cff80428729
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPSynchro\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPSynchro\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WPSynchro\\API\\ClientSyncDatabase' => __DIR__ . '/../..' . '/includes/API/ClientSyncDatabase.php',
        'WPSynchro\\API\\DatabaseBackup' => __DIR__ . '/../..' . '/includes/API/DatabaseBackup.php',
        'WPSynchro\\API\\DownloadLog' => __DIR__ . '/../..' . '/includes/API/DownloadLog.php',
        'WPSynchro\\API\\ExecuteAction' => __DIR__ . '/../..' . '/includes/API/ExecuteAction.php',
        'WPSynchro\\API\\FileFinalize' => __DIR__ . '/../..' . '/includes/API/FileFinalize.php',
        'WPSynchro\\API\\FileTransfer' => __DIR__ . '/../..' . '/includes/API/FileTransfer.php',
        'WPSynchro\\API\\Filesystem' => __DIR__ . '/../..' . '/includes/API/Filesystem.php',
        'WPSynchro\\API\\GetFiles' => __DIR__ . '/../..' . '/includes/API/GetFiles.php',
        'WPSynchro\\API\\HealthCheck' => __DIR__ . '/../..' . '/includes/API/HealthCheck.php',
        'WPSynchro\\API\\Initiate' => __DIR__ . '/../..' . '/includes/API/Initiate.php',
        'WPSynchro\\API\\LoadAPI' => __DIR__ . '/../..' . '/includes/API/LoadAPI.php',
        'WPSynchro\\API\\MasterData' => __DIR__ . '/../..' . '/includes/API/MasterData.php',
        'WPSynchro\\API\\Migrate' => __DIR__ . '/../..' . '/includes/API/Migrate.php',
        'WPSynchro\\API\\PopulateFileList' => __DIR__ . '/../..' . '/includes/API/PopulateFileList.php',
        'WPSynchro\\API\\PopulateFileListStatus' => __DIR__ . '/../..' . '/includes/API/PopulateFileListStatus.php',
        'WPSynchro\\API\\Status' => __DIR__ . '/../..' . '/includes/API/Status.php',
        'WPSynchro\\API\\StatusFileChanges' => __DIR__ . '/../..' . '/includes/API/StatusFileChanges.php',
        'WPSynchro\\API\\VerifyMigration' => __DIR__ . '/../..' . '/includes/API/VerifyMigration.php',
        'WPSynchro\\API\\WPSynchroService' => __DIR__ . '/../..' . '/includes/API/WPSynchroService.php',
        'WPSynchro\\CLI\\WPCLICommand' => __DIR__ . '/../..' . '/includes/CLI/WPCLICommand.php',
        'WPSynchro\\CommonFunctions' => __DIR__ . '/../..' . '/includes/CommonFunctions.php',
        'WPSynchro\\Database\\DatabaseBackup' => __DIR__ . '/../..' . '/includes/Database/DatabaseBackup.php',
        'WPSynchro\\Database\\DatabaseFinalize' => __DIR__ . '/../..' . '/includes/Database/DatabaseFinalize.php',
        'WPSynchro\\Database\\DatabaseHelperFunctions' => __DIR__ . '/../..' . '/includes/Database/DatabaseHelperFunctions.php',
        'WPSynchro\\Database\\DatabaseSync' => __DIR__ . '/../..' . '/includes/Database/DatabaseSync.php',
        'WPSynchro\\Database\\Table' => __DIR__ . '/../..' . '/includes/Database/Table.php',
        'WPSynchro\\Database\\TableColumns' => __DIR__ . '/../..' . '/includes/Database/TableColumns.php',
        'WPSynchro\\Files\\FileHelperFunctions' => __DIR__ . '/../..' . '/includes/Files/FileHelperFunctions.php',
        'WPSynchro\\Files\\FilesSync' => __DIR__ . '/../..' . '/includes/Files/FilesSync.php',
        'WPSynchro\\Files\\FinalizeFiles' => __DIR__ . '/../..' . '/includes/Files/FinalizeFiles.php',
        'WPSynchro\\Files\\Location' => __DIR__ . '/../..' . '/includes/Files/Location.php',
        'WPSynchro\\Files\\PathHandler' => __DIR__ . '/../..' . '/includes/Files/PathHandler.php',
        'WPSynchro\\Files\\PopulateFileListFilterIterator' => __DIR__ . '/../..' . '/includes/Files/PopulateFileListFilterIterator.php',
        'WPSynchro\\Files\\PopulateFileListState' => __DIR__ . '/../..' . '/includes/Files/PopulateFileListState.php',
        'WPSynchro\\Files\\PopulateListHandler' => __DIR__ . '/../..' . '/includes/Files/PopulateListHandler.php',
        'WPSynchro\\Files\\Section' => __DIR__ . '/../..' . '/includes/Files/Section.php',
        'WPSynchro\\Files\\SyncList' => __DIR__ . '/../..' . '/includes/Files/SyncList.php',
        'WPSynchro\\Files\\TransferFiles' => __DIR__ . '/../..' . '/includes/Files/TransferFiles.php',
        'WPSynchro\\Files\\TransportHandler' => __DIR__ . '/../..' . '/includes/Files/TransportHandler.php',
        'WPSynchro\\Finalize\\FinalizeSync' => __DIR__ . '/../..' . '/includes/Finalize/FinalizeSync.php',
        'WPSynchro\\Initiate\\InitiateSync' => __DIR__ . '/../..' . '/includes/Initiate/InitiateSync.php',
        'WPSynchro\\Initiate\\InitiateTokenRetrieval' => __DIR__ . '/../..' . '/includes/Initiate/InitiateTokenRetrieval.php',
        'WPSynchro\\Job' => __DIR__ . '/../..' . '/includes/Job.php',
        'WPSynchro\\Licensing' => __DIR__ . '/../..' . '/includes/Licensing.php',
        'WPSynchro\\Logger\\FileLogger' => __DIR__ . '/../..' . '/includes/Logger/FileLogger.php',
        'WPSynchro\\Logger\\Logger' => __DIR__ . '/../..' . '/includes/Logger/Logger.php',
        'WPSynchro\\Logger\\LoggerTrait' => __DIR__ . '/../..' . '/includes/Logger/LoggerTrait.php',
        'WPSynchro\\Logger\\MemoryLogger' => __DIR__ . '/../..' . '/includes/Logger/MemoryLogger.php',
        'WPSynchro\\Logger\\NullLogger' => __DIR__ . '/../..' . '/includes/Logger/NullLogger.php',
        'WPSynchro\\Logger\\SyncMetadataLog' => __DIR__ . '/../..' . '/includes/Logger/SyncMetadataLog.php',
        'WPSynchro\\Masterdata\\MasterdataRetrieval' => __DIR__ . '/../..' . '/includes/Masterdata/MasterdataRetrieval.php',
        'WPSynchro\\Masterdata\\MasterdataSync' => __DIR__ . '/../..' . '/includes/Masterdata/MasterdataSync.php',
        'WPSynchro\\Migration' => __DIR__ . '/../..' . '/includes/Migration.php',
        'WPSynchro\\MigrationController' => __DIR__ . '/../..' . '/includes/MigrationController.php',
        'WPSynchro\\MigrationFactory' => __DIR__ . '/../..' . '/includes/MigrationFactory.php',
        'WPSynchro\\Pages\\AdminAddEdit' => __DIR__ . '/../..' . '/includes/Pages/AdminAddEdit.php',
        'WPSynchro\\Pages\\AdminChangelog' => __DIR__ . '/../..' . '/includes/Pages/AdminChangelog.php',
        'WPSynchro\\Pages\\AdminLicensing' => __DIR__ . '/../..' . '/includes/Pages/AdminLicensing.php',
        'WPSynchro\\Pages\\AdminLog' => __DIR__ . '/../..' . '/includes/Pages/AdminLog.php',
        'WPSynchro\\Pages\\AdminOverview' => __DIR__ . '/../..' . '/includes/Pages/AdminOverview.php',
        'WPSynchro\\Pages\\AdminRunSync' => __DIR__ . '/../..' . '/includes/Pages/AdminRunSync.php',
        'WPSynchro\\Pages\\AdminSetup' => __DIR__ . '/../..' . '/includes/Pages/AdminSetup.php',
        'WPSynchro\\Pages\\AdminSupport' => __DIR__ . '/../..' . '/includes/Pages/AdminSupport.php',
        'WPSynchro\\ServiceController' => __DIR__ . '/../..' . '/includes/ServiceController.php',
        'WPSynchro\\Status\\MigrateStatus' => __DIR__ . '/../..' . '/includes/Status/MigrateStatus.php',
        'WPSynchro\\Transport\\BasicAuth' => __DIR__ . '/../..' . '/includes/Transport/BasicAuth.php',
        'WPSynchro\\Transport\\Destination' => __DIR__ . '/../..' . '/includes/Transport/Destination.php',
        'WPSynchro\\Transport\\RemoteConnection' => __DIR__ . '/../..' . '/includes/Transport/RemoteConnection.php',
        'WPSynchro\\Transport\\RemoteTestTransport' => __DIR__ . '/../..' . '/includes/Transport/RemoteTestTransport.php',
        'WPSynchro\\Transport\\RemoteTransport' => __DIR__ . '/../..' . '/includes/Transport/RemoteTransport.php',
        'WPSynchro\\Transport\\RemoteTransportResult' => __DIR__ . '/../..' . '/includes/Transport/RemoteTransportResult.php',
        'WPSynchro\\Transport\\ReturnResult' => __DIR__ . '/../..' . '/includes/Transport/ReturnResult.php',
        'WPSynchro\\Transport\\Transfer' => __DIR__ . '/../..' . '/includes/Transport/Transfer.php',
        'WPSynchro\\Transport\\TransferAccessKey' => __DIR__ . '/../..' . '/includes/Transport/TransferAccessKey.php',
        'WPSynchro\\Transport\\TransferFile' => __DIR__ . '/../..' . '/includes/Transport/TransferFile.php',
        'WPSynchro\\Transport\\TransferToken' => __DIR__ . '/../..' . '/includes/Transport/TransferToken.php',
        'WPSynchro\\Updater\\PluginUpdater' => __DIR__ . '/../..' . '/includes/Updater/PluginUpdater.php',
        'WPSynchro\\Utilities\\Actions' => __DIR__ . '/../..' . '/includes/Utilities/Actions.php',
        'WPSynchro\\Utilities\\Actions\\Action' => __DIR__ . '/../..' . '/includes/Utilities/Actions/Action.php',
        'WPSynchro\\Utilities\\Actions\\ClearCachesOnSuccess' => __DIR__ . '/../..' . '/includes/Utilities/Actions/ClearCachesOnSuccess.php',
        'WPSynchro\\Utilities\\Actions\\ClearCurrentTransfer' => __DIR__ . '/../..' . '/includes/Utilities/Actions/ClearCurrentTransfer.php',
        'WPSynchro\\Utilities\\Actions\\ClearTransients' => __DIR__ . '/../..' . '/includes/Utilities/Actions/ClearTransients.php',
        'WPSynchro\\Utilities\\Actions\\EmailOnSyncFailure' => __DIR__ . '/../..' . '/includes/Utilities/Actions/EmailOnSyncFailure.php',
        'WPSynchro\\Utilities\\Actions\\EmailOnSyncSuccess' => __DIR__ . '/../..' . '/includes/Utilities/Actions/EmailOnSyncSuccess.php',
        'WPSynchro\\Utilities\\Activation' => __DIR__ . '/../..' . '/includes/Utilities/Activation.php',
        'WPSynchro\\Utilities\\Compatibility\\Compatibility' => __DIR__ . '/../..' . '/includes/Utilities/Compatibility/Compatibility.php',
        'WPSynchro\\Utilities\\Compatibility\\MUPluginHandler' => __DIR__ . '/../..' . '/includes/Utilities/Compatibility/MUPluginHandler.php',
        'WPSynchro\\Utilities\\Configuration\\PluginConfiguration' => __DIR__ . '/../..' . '/includes/Utilities/Configuration/PluginConfiguration.php',
        'WPSynchro\\Utilities\\DatabaseTables' => __DIR__ . '/../..' . '/includes/Utilities/DatabaseTables.php',
        'WPSynchro\\Utilities\\DebugInformation' => __DIR__ . '/../..' . '/includes/Utilities/DebugInformation.php',
        'WPSynchro\\Utilities\\ErrorHandler\\CustomPHPErrorHandler' => __DIR__ . '/../..' . '/includes/Utilities/ErrorHandler/CustomPHPErrorHandler.php',
        'WPSynchro\\Utilities\\JSData\\DeactivatePluginData' => __DIR__ . '/../..' . '/includes/Utilities/JSData/DeactivatePluginData.php',
        'WPSynchro\\Utilities\\JSData\\HealthCheckData' => __DIR__ . '/../..' . '/includes/Utilities/JSData/HealthCheckData.php',
        'WPSynchro\\Utilities\\JSData\\LoadJSData' => __DIR__ . '/../..' . '/includes/Utilities/JSData/LoadJSData.php',
        'WPSynchro\\Utilities\\JSData\\PageHeaderData' => __DIR__ . '/../..' . '/includes/Utilities/JSData/PageHeaderData.php',
        'WPSynchro\\Utilities\\JSData\\UsageReportingData' => __DIR__ . '/../..' . '/includes/Utilities/JSData/UsageReportingData.php',
        'WPSynchro\\Utilities\\SyncTimer' => __DIR__ . '/../..' . '/includes/Utilities/SyncTimer.php',
        'WPSynchro\\Utilities\\SyncTimerList' => __DIR__ . '/../..' . '/includes/Utilities/SyncTimerList.php',
        'WPSynchro\\Utilities\\Upgrade\\DatabaseUpgrade' => __DIR__ . '/../..' . '/includes/Utilities/Upgrade/DatabaseUpgrade.php',
        'WPSynchro\\Utilities\\UsageReporting' => __DIR__ . '/../..' . '/includes/Utilities/UsageReporting.php',
        'WPSynchro\\WPSynchroBootstrap' => __DIR__ . '/../..' . '/includes/WPSynchroBootstrap.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd05bbfbb2950f7e4030f8cff80428729::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd05bbfbb2950f7e4030f8cff80428729::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd05bbfbb2950f7e4030f8cff80428729::$classMap;

        }, null, ClassLoader::class);
    }
}
