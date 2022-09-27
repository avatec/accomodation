<?php
/**
 * Payments TPay class
 *
 * @package		Modules
 * @subpackage	Payments/tpay
 * @author		Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license		Property license
 *
 * <p>Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania</p>
 *
 * <p>W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.</p>
 */

 class tpay
 {
     public static $table = "tpay_payments";
     public static $Error;

     public static $url = 'https://secure.tpay.com';

     public $post, $get, $gate, $config;
     public $debug = false;

     public static function make_md5( $amount, $crc, $tr_id = null )
     {
 		global $config;

         if( is_null( $tr_id )) {
             return md5(
                 $config['tpay_merchant_id'] .
                 $amount .
                 $crc .
                 $config['tpay_secret']
             );
         } else {
             return md5(
                 $config['tpay_merchant_id'] .
                 $tr_id .
                 $amount .
                 $crc .
                 $config['tpay_secret']
             );
         }
     }

     public static function verify( $post )
     {
         global $config;

         if( $post['md5sum'] != self::make_md5( $post['tr_amount'], $post['tr_crc'], $post['tr_id'])) {
             Kernel::log( "tpay_verify.log" , "[ERROR] Błędna suma kontrolna MD5");
             return false;
         }

         if (!is_string($post['md5sum']) || strlen($post['md5sum']) !== 32) {
             Kernel::log( "tpay_verify.log" , "[ERROR] Błędny MD5");
             return false;
         }

         if( $post['id'] != $config['tpay_merchant_id'] ) {
             Kernel::log( "tpay_verify.log" , "[ERROR] Otrzymano nieprawidłowy merchant_id");
             return false;
         }

         if( $post['tr_status'] !== "TRUE" ) {
             Kernel::log( "tpay_verify.log" , "[ERROR] Otrzymano informację o błędnej płatności");
             return false;
         }

         if( $post['tr_error'] != "none" ) {
             Kernel::log( "tpay_verify.log" , "[ERROR] Otrzymano informację o błędzie: " . $post['error']);
             return false;
         }

         Kernel::log( "tpay_verify.log" , "[SUCCESS] Wszystko przebiegło prawidłowo");

         return ['result' => $post['tr_crc']];
         //return $this->update( $post['tr_id'], $post['tr_error'], $post['test_mode'], $post['tr_crc'] );
     }
 }
