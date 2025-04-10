<?php
require_once plugin_dir_path(__FILE__) . '../../vendor/autoload.php';
use Google\Cloud\Vision\V1\ImageAnnotatorClient; 

function obm_ocr_page($file_path) {
    try {
        $client = new ImageAnnotatorClient([
            'credentials' => plugin_dir_path(__FILE__) . '../../credentials.json'
        ]);
        $image = file_get_contents($file_path);
        $response = $client->textDetection($image);
        $texts = $response->getTextAnnotations();
        return $texts[0]->getDescription() ?? '';
    } catch (Exception $e) {
        error_log('OCR Error: ' . $e->getMessage());
        return '';
    }
}