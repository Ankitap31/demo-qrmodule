<?php
/**
 * @file
 * Contains \Drupal\product_details\Plugin\Block\QrcodeBlock.
 */
namespace Drupal\product_details\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use \Drupal\file\Entity\File;
require_once DRUPAL_ROOT.'\vendor\pendalff\phpqrcode\qrlib.php';

/**
 * Provides a 'QR code' block.
 *
 * @Block(
 *   id = "qr_block",
 *   admin_label = @Translation("Scan Here To Purchase This Product"),
 *   category = @Translation("Scan Here To Purchase This Product")
 * )
 */
class QrcodeBlock extends BlockBase {
    public function generateQrCodes($qr_text) {
      $node = \Drupal::routeMatch()->getParameter('node');
      $nid = $node->id();
      // The below code will automatically create the path for the img.
      $path = '';
      $directory = "public://Images/QrCodes/";
      file_prepare_directory($directory, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY);
      // Name of the generated image.
      $uri = $directory . $nid . '.png'; // Generates a png image.
      $path = drupal_realpath($uri);
      // Generate QR code image.
      \PHPQRCode\QRcode::png($qr_text, $path, 'L', 4, 2);
      return path;
    }
  /**
   * {@inheritdoc}
   */
  public function build() {
	  drupal_flush_all_caches();
	  $node = \Drupal::routeMatch()->getParameter('node');
		if ($node instanceof \Drupal\node\NodeInterface) {
		  // You can get nid and anything else you need from the node object.
		  $nid = $node->id();
		  $node_storage = \Drupal::entityTypeManager()->getStorage('node');
		  $node_detail = $node_storage->load($nid);
		  $node_detail->get('field_purchase_link')->uri;
		}
		$qrimage = "public://Images/QrCodes/".$nid.'.png';
		if (file_exists($qrimage) == 0) {
                   QrcodeBlock::generateQrCodes($node_detail->get('field_purchase_link')->uri);
		}

		$qrcode = file_create_url($qrimage);
    return array(
      '#type' => 'markup',
      '#markup' => "<img src='".$qrcode."'/>",
    );
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
