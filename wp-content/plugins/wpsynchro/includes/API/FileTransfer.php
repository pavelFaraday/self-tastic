<?php
namespace WPSynchro\API;

use WPSynchro\Files\TransportHandler;
use WPSynchro\Transport\ReturnResult;
use WPSynchro\Transport\Transfer;
use WPSynchro\Transport\TransferAccessKey;

/**
 * Class for handling service "filetransfer" - Receiving files
 * @since 1.0.3
 */
class FileTransfer extends WPSynchroService
{

    public function service()
    {

        // init
        global $wpsynchro_container;
        $timer = $wpsynchro_container->get("class.SyncTimerList");
        $timer->init();

        // Get transfer object, so we can get data
        $transfer = new Transfer();
        $transfer->setEncryptionKey(TransferAccessKey::getAccessKey());
        $transfer->populateFromString($this->getRequestBody());
        $data = $transfer->getDataObject();
        $files = $transfer->getFiles();

        // Handle the files and filedata, writing it to disk as needed
        $transporthandler = new TransportHandler();
        $result = $transporthandler->handleFileTransport($data, $files);

        // Return the result
        $returnresult = new ReturnResult();
        $returnresult->init();
        $returnresult->setDataObject($result);
        return $returnresult->echoDataFromServiceAndExit();
    }
}
