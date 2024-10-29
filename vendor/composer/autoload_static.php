<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite34ce887ac095f8b9485e305a9c67f48
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $classMap = array (
        'Bandstand_AJAX_Admin' => __DIR__ . '/../..' . '/classes/AJAX/Admin.php',
        'Bandstand_AJAX_Discography' => __DIR__ . '/../..' . '/classes/AJAX/Discography.php',
        'Bandstand_AJAX_Videos' => __DIR__ . '/../..' . '/classes/AJAX/Videos.php',
        'Bandstand_AbstractPlugin' => __DIR__ . '/../..' . '/classes/AbstractPlugin.php',
        'Bandstand_AbstractProvider' => __DIR__ . '/../..' . '/classes/AbstractProvider.php',
        'Bandstand_Compatibility' => __DIR__ . '/../..' . '/classes/Compatibility.php',
        'Bandstand_Factory_PostFactory' => __DIR__ . '/../..' . '/classes/Factory/PostFactory.php',
        'Bandstand_Image_Editor_GD' => __DIR__ . '/../..' . '/classes/Image/Editor/GD.php',
        'Bandstand_Image_Editor_Imagick' => __DIR__ . '/../..' . '/classes/Image/Editor/Imagick.php',
        'Bandstand_Image_Pixel_GD' => __DIR__ . '/../..' . '/classes/Image/Pixel/GD.php',
        'Bandstand_ModuleCollection' => __DIR__ . '/../..' . '/classes/ModuleCollection.php',
        'Bandstand_Module_AbstractModule' => __DIR__ . '/../..' . '/classes/Module/AbstractModule.php',
        'Bandstand_Module_Archives' => __DIR__ . '/../..' . '/classes/Module/Archives.php',
        'Bandstand_Module_Discography' => __DIR__ . '/../..' . '/classes/Module/Discography.php',
        'Bandstand_Module_Gigs' => __DIR__ . '/../..' . '/classes/Module/Gigs.php',
        'Bandstand_Module_Videos' => __DIR__ . '/../..' . '/classes/Module/Videos.php',
        'Bandstand_Plugin' => __DIR__ . '/../..' . '/classes/Plugin.php',
        'Bandstand_PostType_AbstractPostType' => __DIR__ . '/../..' . '/classes/PostType/AbstractPostType.php',
        'Bandstand_PostType_Archive' => __DIR__ . '/../..' . '/classes/PostType/Archive.php',
        'Bandstand_PostType_Gig' => __DIR__ . '/../..' . '/classes/PostType/Gig.php',
        'Bandstand_PostType_Playlist' => __DIR__ . '/../..' . '/classes/PostType/Playlist.php',
        'Bandstand_PostType_Record' => __DIR__ . '/../..' . '/classes/PostType/Record.php',
        'Bandstand_PostType_Track' => __DIR__ . '/../..' . '/classes/PostType/Track.php',
        'Bandstand_PostType_Venue' => __DIR__ . '/../..' . '/classes/PostType/Venue.php',
        'Bandstand_PostType_Video' => __DIR__ . '/../..' . '/classes/PostType/Video.php',
        'Bandstand_Post_AbstractPost' => __DIR__ . '/../..' . '/classes/Post/AbstractPost.php',
        'Bandstand_Post_Gig' => __DIR__ . '/../..' . '/classes/Post/Gig.php',
        'Bandstand_Post_Record' => __DIR__ . '/../..' . '/classes/Post/Record.php',
        'Bandstand_Post_Track' => __DIR__ . '/../..' . '/classes/Post/Track.php',
        'Bandstand_Post_Venue' => __DIR__ . '/../..' . '/classes/Post/Venue.php',
        'Bandstand_Post_Video' => __DIR__ . '/../..' . '/classes/Post/Video.php',
        'Bandstand_Provider_AdminAssets' => __DIR__ . '/../..' . '/classes/Provider/AdminAssets.php',
        'Bandstand_Provider_AdminHooks' => __DIR__ . '/../..' . '/classes/Provider/AdminHooks.php',
        'Bandstand_Provider_Assets' => __DIR__ . '/../..' . '/classes/Provider/Assets.php',
        'Bandstand_Provider_GeneralHooks' => __DIR__ . '/../..' . '/classes/Provider/GeneralHooks.php',
        'Bandstand_Provider_MediaHooks' => __DIR__ . '/../..' . '/classes/Provider/MediaHooks.php',
        'Bandstand_Provider_Setting_GoogleMaps' => __DIR__ . '/../..' . '/classes/Provider/Setting/GoogleMaps.php',
        'Bandstand_Provider_Setup' => __DIR__ . '/../..' . '/classes/Provider/Setup.php',
        'Bandstand_Provider_TemplateHooks' => __DIR__ . '/../..' . '/classes/Provider/TemplateHooks.php',
        'Bandstand_Provider_Widgets' => __DIR__ . '/../..' . '/classes/Provider/Widgets.php',
        'Bandstand_Query_Gigs' => __DIR__ . '/../..' . '/classes/Query/Gigs.php',
        'Bandstand_REST_AbstractPostsController' => __DIR__ . '/../..' . '/classes/REST/AbstractPostsController.php',
        'Bandstand_REST_GigsController' => __DIR__ . '/../..' . '/classes/REST/GigsController.php',
        'Bandstand_REST_RecordsController' => __DIR__ . '/../..' . '/classes/REST/RecordsController.php',
        'Bandstand_REST_TracksController' => __DIR__ . '/../..' . '/classes/REST/TracksController.php',
        'Bandstand_REST_VenuesController' => __DIR__ . '/../..' . '/classes/REST/VenuesController.php',
        'Bandstand_REST_VideosController' => __DIR__ . '/../..' . '/classes/REST/VideosController.php',
        'Bandstand_Repository_PostRepository' => __DIR__ . '/../..' . '/classes/Repository/PostRepository.php',
        'Bandstand_Screen_AbstractScreen' => __DIR__ . '/../..' . '/classes/Screen/AbstractScreen.php',
        'Bandstand_Screen_Dashboard' => __DIR__ . '/../..' . '/classes/Screen/Dashboard.php',
        'Bandstand_Screen_EditArchive' => __DIR__ . '/../..' . '/classes/Screen/EditArchive.php',
        'Bandstand_Screen_EditGig' => __DIR__ . '/../..' . '/classes/Screen/EditGig.php',
        'Bandstand_Screen_EditRecord' => __DIR__ . '/../..' . '/classes/Screen/EditRecord.php',
        'Bandstand_Screen_EditRecordArchive' => __DIR__ . '/../..' . '/classes/Screen/EditRecordArchive.php',
        'Bandstand_Screen_EditTrack' => __DIR__ . '/../..' . '/classes/Screen/EditTrack.php',
        'Bandstand_Screen_EditVenue' => __DIR__ . '/../..' . '/classes/Screen/EditVenue.php',
        'Bandstand_Screen_EditVideo' => __DIR__ . '/../..' . '/classes/Screen/EditVideo.php',
        'Bandstand_Screen_EditVideoArchive' => __DIR__ . '/../..' . '/classes/Screen/EditVideoArchive.php',
        'Bandstand_Screen_ManageGigs' => __DIR__ . '/../..' . '/classes/Screen/ManageGigs.php',
        'Bandstand_Screen_ManageRecords' => __DIR__ . '/../..' . '/classes/Screen/ManageRecords.php',
        'Bandstand_Screen_ManageTracks' => __DIR__ . '/../..' . '/classes/Screen/ManageTracks.php',
        'Bandstand_Screen_ManageVenues' => __DIR__ . '/../..' . '/classes/Screen/ManageVenues.php',
        'Bandstand_Screen_ManageVideos' => __DIR__ . '/../..' . '/classes/Screen/ManageVideos.php',
        'Bandstand_Screen_Network_Settings' => __DIR__ . '/../..' . '/classes/Screen/Network/Settings.php',
        'Bandstand_Screen_Settings' => __DIR__ . '/../..' . '/classes/Screen/Settings.php',
        'Bandstand_Taxonomy_Genre' => __DIR__ . '/../..' . '/classes/Taxonomy/Genre.php',
        'Bandstand_Taxonomy_RecordType' => __DIR__ . '/../..' . '/classes/Taxonomy/RecordType.php',
        'Bandstand_Taxonomy_VideoCategory' => __DIR__ . '/../..' . '/classes/Taxonomy/VideoCategory.php',
        'Bandstand_Template_Compatibility' => __DIR__ . '/../..' . '/classes/Template/Compatibility.php',
        'Bandstand_Template_Loader' => __DIR__ . '/../..' . '/classes/Template/Loader.php',
        'Bandstand_Template_Manager' => __DIR__ . '/../..' . '/classes/Template/Manager.php',
        'Bandstand_UpgradeManager' => __DIR__ . '/../..' . '/classes/UpgradeManager.php',
        'Bandstand_Widget_Gigs' => __DIR__ . '/../..' . '/classes/Widget/Gigs.php',
        'Bandstand_Widget_Record' => __DIR__ . '/../..' . '/classes/Widget/Record.php',
        'Bandstand_Widget_Video' => __DIR__ . '/../..' . '/classes/Widget/Video.php',
        'Composer\\Installers\\AglInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/AglInstaller.php',
        'Composer\\Installers\\AimeosInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/AimeosInstaller.php',
        'Composer\\Installers\\AnnotateCmsInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/AnnotateCmsInstaller.php',
        'Composer\\Installers\\AsgardInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/AsgardInstaller.php',
        'Composer\\Installers\\AttogramInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/AttogramInstaller.php',
        'Composer\\Installers\\BaseInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/BaseInstaller.php',
        'Composer\\Installers\\BitrixInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/BitrixInstaller.php',
        'Composer\\Installers\\BonefishInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/BonefishInstaller.php',
        'Composer\\Installers\\CakePHPInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/CakePHPInstaller.php',
        'Composer\\Installers\\ChefInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ChefInstaller.php',
        'Composer\\Installers\\ClanCatsFrameworkInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ClanCatsFrameworkInstaller.php',
        'Composer\\Installers\\CockpitInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/CockpitInstaller.php',
        'Composer\\Installers\\CodeIgniterInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/CodeIgniterInstaller.php',
        'Composer\\Installers\\Concrete5Installer' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/Concrete5Installer.php',
        'Composer\\Installers\\CraftInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/CraftInstaller.php',
        'Composer\\Installers\\CroogoInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/CroogoInstaller.php',
        'Composer\\Installers\\DecibelInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/DecibelInstaller.php',
        'Composer\\Installers\\DokuWikiInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/DokuWikiInstaller.php',
        'Composer\\Installers\\DolibarrInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/DolibarrInstaller.php',
        'Composer\\Installers\\DrupalInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/DrupalInstaller.php',
        'Composer\\Installers\\ElggInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ElggInstaller.php',
        'Composer\\Installers\\ExpressionEngineInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ExpressionEngineInstaller.php',
        'Composer\\Installers\\FuelInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/FuelInstaller.php',
        'Composer\\Installers\\FuelphpInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/FuelphpInstaller.php',
        'Composer\\Installers\\GravInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/GravInstaller.php',
        'Composer\\Installers\\HuradInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/HuradInstaller.php',
        'Composer\\Installers\\ImageCMSInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ImageCMSInstaller.php',
        'Composer\\Installers\\Installer' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/Installer.php',
        'Composer\\Installers\\JoomlaInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/JoomlaInstaller.php',
        'Composer\\Installers\\KirbyInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/KirbyInstaller.php',
        'Composer\\Installers\\KodiCMSInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/KodiCMSInstaller.php',
        'Composer\\Installers\\KohanaInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/KohanaInstaller.php',
        'Composer\\Installers\\LaravelInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/LaravelInstaller.php',
        'Composer\\Installers\\LithiumInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/LithiumInstaller.php',
        'Composer\\Installers\\MODULEWorkInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MODULEWorkInstaller.php',
        'Composer\\Installers\\MODXEvoInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MODXEvoInstaller.php',
        'Composer\\Installers\\MagentoInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MagentoInstaller.php',
        'Composer\\Installers\\MakoInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MakoInstaller.php',
        'Composer\\Installers\\MauticInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MauticInstaller.php',
        'Composer\\Installers\\MediaWikiInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MediaWikiInstaller.php',
        'Composer\\Installers\\MicroweberInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MicroweberInstaller.php',
        'Composer\\Installers\\MoodleInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/MoodleInstaller.php',
        'Composer\\Installers\\OctoberInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/OctoberInstaller.php',
        'Composer\\Installers\\OxidInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/OxidInstaller.php',
        'Composer\\Installers\\PPIInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PPIInstaller.php',
        'Composer\\Installers\\PhiftyInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PhiftyInstaller.php',
        'Composer\\Installers\\PhpBBInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PhpBBInstaller.php',
        'Composer\\Installers\\PimcoreInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PimcoreInstaller.php',
        'Composer\\Installers\\PiwikInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PiwikInstaller.php',
        'Composer\\Installers\\PlentymarketsInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PlentymarketsInstaller.php',
        'Composer\\Installers\\Plugin' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/Plugin.php',
        'Composer\\Installers\\PrestashopInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PrestashopInstaller.php',
        'Composer\\Installers\\PuppetInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/PuppetInstaller.php',
        'Composer\\Installers\\RadPHPInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/RadPHPInstaller.php',
        'Composer\\Installers\\ReIndexInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ReIndexInstaller.php',
        'Composer\\Installers\\RedaxoInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/RedaxoInstaller.php',
        'Composer\\Installers\\RoundcubeInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/RoundcubeInstaller.php',
        'Composer\\Installers\\SMFInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/SMFInstaller.php',
        'Composer\\Installers\\ShopwareInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ShopwareInstaller.php',
        'Composer\\Installers\\SilverStripeInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/SilverStripeInstaller.php',
        'Composer\\Installers\\Symfony1Installer' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/Symfony1Installer.php',
        'Composer\\Installers\\TYPO3CmsInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/TYPO3CmsInstaller.php',
        'Composer\\Installers\\TYPO3FlowInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/TYPO3FlowInstaller.php',
        'Composer\\Installers\\TheliaInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/TheliaInstaller.php',
        'Composer\\Installers\\TuskInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/TuskInstaller.php',
        'Composer\\Installers\\VanillaInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/VanillaInstaller.php',
        'Composer\\Installers\\WHMCSInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/WHMCSInstaller.php',
        'Composer\\Installers\\WolfCMSInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/WolfCMSInstaller.php',
        'Composer\\Installers\\WordPressInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/WordPressInstaller.php',
        'Composer\\Installers\\YawikInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/YawikInstaller.php',
        'Composer\\Installers\\ZendInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ZendInstaller.php',
        'Composer\\Installers\\ZikulaInstaller' => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers/ZikulaInstaller.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite34ce887ac095f8b9485e305a9c67f48::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite34ce887ac095f8b9485e305a9c67f48::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite34ce887ac095f8b9485e305a9c67f48::$classMap;

        }, null, ClassLoader::class);
    }
}
