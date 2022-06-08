<?php

/**
 * Class for handling service to get data from file system to frontend
 * Call should already be verified by permissions callback
 *
 * @since 1.2.0
 */

namespace WPSynchro\API;

use WPSynchro\Files\FileHelperFunctions;
use WPSynchro\CommonFunctions;
use WPSynchro\Transport\RemoteTransport;
use WPSynchro\Migration;
use WPSynchro\Transport\Destination;

class Filesystem extends WPSynchroService
{
    public function service()
    {
        $body = $this->getRequestBody();
        $parameters = json_decode($body);
        // Extract parameters
        if (isset($parameters->path)) {
            $path = $parameters->path;
        } else {
            http_response_code(400);
            return;
        }
        if (isset($parameters->migration)) {
            $migration = Migration::map($parameters->migration);
        } else {
            $migration = new Migration();
        }
        if (isset($parameters->url)) {
            $url = $parameters->url;
        } else {
            $url = "";
        }
        if (isset($parameters->isLocal)) {
            $is_local = $parameters->isLocal;
        } else {
            $is_local = true;
        }

        // If it is not local, call the other site on same service
        if (!$is_local) {

            $remote_request = new \stdClass();
            $remote_request->path = $path;

            $destination = new Destination(Destination::REMOTE);
            $destination->setMigration($migration);

            // Get remote transfer object
            $remotetransport = new RemoteTransport();
            $remotetransport->setDestination($destination);
            $remotetransport->init();
            $remotetransport->setUrl($url);
            $remotetransport->setDataObject($remote_request);
            $remotetransport->setSendDataAsJSON();
            $result = $remotetransport->remotePOST();

            if ($result->isSuccess()) {
                $result_body = $result->getBody();
                echo json_encode($result_body);
                return;
            }
            http_response_code(400);
            return;
        }

        $common = new CommonFunctions();

        // Paths that should NOT be syncable

        $locked_paths = [];
        $locked_paths[] = $common->fixPath(trim($common->getLogLocation(), '/'));
        $locked_paths[] = $common->fixPath(trim(WPSYNCHRO_PLUGIN_DIR, '/'));
        $locked_paths[] = $common->fixPath(ABSPATH . "wp-admin");
        $locked_paths[] = $common->fixPath(ABSPATH . "wp-includes");
        $files_in_webroot = FileHelperFunctions::getWPFilesInWebrootToExclude();
        foreach ($files_in_webroot as $filewebroot) {
            $locked_paths[] = $common->fixPath($filewebroot);
        }

        $result = new \stdClass();
        $pathdata_list = [];

        if (file_exists($path)) {
            $files = [];
            $presorteddata = array_diff(scandir($path), ['..', '.']);
            foreach ($presorteddata as $file) {
                if (is_file($file))
                    array_push($files, $file);
                else
                    array_unshift($files, $file);
            }

            foreach ($files as $file) {
                $pathdata = new PathData();
                $pathdata->absolutepath = trailingslashit($path) . $file;
                if (is_file($pathdata->absolutepath)) {
                    $pathdata->is_file = true;
                } else {
                    // is dir, check for subdirs
                    $directories = array_diff(scandir($pathdata->absolutepath), ['..', '.']);
                    if ($directories != false && count($directories) > 0) {
                        $pathdata->dir_has_content = true;
                        $pathdata->is_expanded = false;
                    }
                }
                $pathdata->basename = basename($pathdata->absolutepath);

                // Check for locked paths
                foreach ($locked_paths as $lpath) {
                    if (strpos($pathdata->absolutepath, $lpath) !== false) {
                        $pathdata->locked = true;
                        break;
                    }
                }

                $pathdata_list[] = $pathdata;
            }
        }

        $result->pathdata = $pathdata_list;

        echo json_encode($result);
        return;
    }
}

class PathData
{

    public $pathkey = "";
    public $absolutepath = "";
    public $basename = "";
    public $is_file = false;
    public $dirname = "";
    public $dir_has_content = false;
    public $children = [];
    public $locked = false;

    function __construct()
    {
        $this->pathkey = uniqid();
    }
}
