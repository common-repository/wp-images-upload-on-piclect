<?php
/*
Plugin Name: WP Images upload on Piclect
Plugin URI: http://makaleci.com/wordpress-sitelerinize-resim-yukleme-ozelligi-ekleyin-shortcode-piclect.html
Description: WordPress to your site, give quick and easy image upload feature. Traffic and HDD do not need. All photos are uploaded to piclect.com services. - Shortcode with and simple.. shortcode = [up_piclect]
Version: 1.0
Author: Selcuk kilic (Kuaza)
Author URI: http://kuaza.com
License: GPLv2 or later
*/

// gelistirici icindir: hatalari gormek icin (varsa) :)
// error_reporting(E_ALL); ini_set("display_errors", 1);

if ( ! defined( 'ABSPATH' ) ) exit; 



class kuaza_piclect_upload {

public function __construct(){

global $wpdb;

	$this->table_name_kategori = $wpdb->prefix . "kuaza_piclect_upload_kategori";
	$this->table_name = $wpdb->prefix . "kuaza_piclect_upload";	


if ( is_admin() ) {
add_action('admin_menu', array($this, 'kuazaPICLECT_index__sayfa'));
}

add_action('plugins_loaded', array(&$this, 'plugins_loaded_lang'));
add_shortcode( 'up_piclect', array( $this, 'upptag_func' ) );
add_filter('query_vars',array( $this, 'kuaza_piclect_upload_plugin_add_trigger'));
add_action('template_redirect', array( $this, 'plugin_trigger_check'));


register_activation_hook(__FILE__, array(&$this, 'kuaza_pic_up_install'));
register_uninstall_hook(__FILE__, array($this, 'kuaza_pic_up_unstall'));

wp_enqueue_script('jquery');
add_action( 'wp_enqueue_scripts', array( $this, 'file_upload_js') );

}

function file_upload_js() {
	wp_enqueue_script(
		'kuaza_piclect_upload',
		plugins_url( '/style/assets/js/jquery.uploadfile.min.js' , __FILE__ )
	);
}


### Dil ozelligini aktif edelim.
function plugins_loaded_lang() {
	load_plugin_textdomain("kuaza_pic_up_lang", false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}
 

function kuazaPICLECT_index__sayfa() {
	add_menu_page(__('WordPress photos upload piclect'), __('Image upload PICLECT'), "manage_options", 'kuaza_piclect_upload', array($this, 'kuazaPICLECTadminindex'));

}

// islemleri yonlendiren fonksiyonumuz..		
function kuazaPICLECTadminindex(){
$islem = isset($_GET["islem"]) ? $_GET["islem"] : "";

switch($islem):

	case 'icerikleriguncelle':
	//kuaza_social_icerikleriguncelle();
	break;
	
	case 'ayarlariguncelle':
	$this->kuaza_piclect_upload_ayarlariguncelle();
	break;
	/* sonraki versiyonlar icin
	case 'tablosil':
	echo "tablosil ffddfdfddf";
	break;
	
	case 'sayaclariguncelle':
	echo "sayaclariguncelle dfdf";
	break;
	*/	
	default;
		$this->kuaza_PICLECT_index();
	break;
endswitch;
}


/*
 * @desc	kuaza social / index Sayfası
 * @author	selcuk kilic
*/
function kuaza_PICLECT_index(){
echo $this->kuaza_piclect_upload_menuolustur();
?>

<div>

<div style="margin-right:30px;padding-right:10px;float:left;border-right:1px solid #ccc;width:30%">
<?php _e("<h3>Plugin info</h3>","kuaza_pic_up_lang"); ?>
<?php _e("<strong>Plugin name:</strong> Free images upload plugins for piclect.com","kuaza_pic_up_lang"); ?><br /><br />
<?php _e("<strong>Plugin description:</strong> No traffic, No hdd. All free - Images upload service for wp plugins. All images upload piclect.com and your account - Shortcode with and simple..  shortcode = [up_piclect]","kuaza_pic_up_lang"); ?><br /><br />
<?php _e("<strong>Plugin version:</strong> v1.0 (first version)","kuaza_pic_up_lang"); ?><br /><br />
<?php _e("<strong>Plugin author:</strong> Selcuk kilic (kuaza)","kuaza_pic_up_lang"); ?><br /><br />
<?php _e("<strong>Plugin Widget support:</strong> no but next version yes","kuaza_pic_up_lang"); ?><br /><br />
<?php _e("<strong>Plugin support:</strong> <a href='http://makaleci.com/wordpress-sitelerinize-resim-yukleme-ozelligi-ekleyin-shortcode-piclect.html' target='_blank'>Support plugins release page (comments)</a> or this email: kuaza.ca@gmail.com","kuaza_pic_up_lang"); ?><br /><br />
<?php _e("<strong>Author social profiles:</strong>","kuaza_pic_up_lang"); ?><br />

<a href="https://www.facebook.com/kuaza.ca" target="_blank">Facebook</a>,
<a href="https://plus.google.com/u/0/+Kuaza61" target="_blank">Google</a>,
<a href="https://twitter.com/kuaza" target="_blank">Twitter</a>,
<a href="https://www.linkedin.com/profile/view?id=111819421&trk=nav_responsive_tab_profile" target="_blank">LinkedIn</a>,
<a href="http://piclect.com/kuaza" target="_blank">Piclect</a>
</div>

<div style="margin-right:30px;padding-right:10px;float:left;border-right:1px solid #ccc;width:30%">
<?php _e("<h3>Donate for support</h3>","kuaza_pic_up_lang"); ?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="RJVUX7HSHHHMG">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>
<hr />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="JR2BL8Y7QU2P8">
<input type="image" src="https://www.paypalobjects.com/tr_TR/TR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - Online ödeme yapmanın daha güvenli ve kolay yolu!">
<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>

<?php _e("<h4>Thanks for support donation..</h4>","kuaza_pic_up_lang"); ?>

<?php _e("<h3><em>Please support add my project link your website</em></h3>","kuaza_pic_up_lang"); ?>

<?php _e("<h3>My other project</h3>","kuaza_pic_up_lang"); ?>
<?php _e("<a href='http://piclect.com'>Image upload, create collections and share</a>","kuaza_pic_up_lang"); ?><hr />
<?php _e("<a href='http://makaleci.com'>Latest news and articles. (Turkish)</a>","kuaza_pic_up_lang"); ?><hr />
<?php _e("<a href='http://www.ressim.net'>Only image upload and shared. Simple and basic</a>","kuaza_pic_up_lang"); ?>
</div>

<div style="float:left;width:30%">
<?php _e("<h3>Change log</h3>","kuaza_pic_up_lang"); ?>
<code>
/* Version 1.0 */ 22/07/2014<br>
<?php _e("Release plugins","kuaza_pic_up_lang"); ?>
</code>
</div>

</div>


<?php
}

/*
* Konu icin takip tablosunda yeni alan olusturma (eger konu icin kpst istatistik tablosu olusturulmamis ise)
*/
function kuaza_piclect_kategori_olustur() {
   global $wpdb;
		
   $rows_affected = $wpdb->insert( $this->table_name_kategori, $this->icerik );
   return $rows_affected ? true : false;
}

/*
 * ayarlari duzeltme Sayfası
 * 
*/
function kuaza_piclect_upload_ayarlariguncelle(){
global $wpdb; 
echo $this->kuaza_piclect_upload_menuolustur();

if(isset($_POST["ayarlariguncelle"]) && $_POST["ayarlariguncelle"] == "evet"){

$kuaza_up_config_acik = !empty($_POST["kuaza_up_config_acik"]) ? "yes" : "no";
$kuaza_up_config_sitekey = isset($_POST["kuaza_up_config_sitekey"]) ? $_POST["kuaza_up_config_sitekey"] : "";
$kuaza_up_config_sitesecret = isset($_POST["kuaza_up_config_sitesecret"]) ? $_POST["kuaza_up_config_sitesecret"] : "";
$kuaza_up_config_cssupload = isset($_POST["kuaza_up_config_cssupload"]) ? $_POST["kuaza_up_config_cssupload"] : "";
$kuaza_up_config_birkeredeuploadsayisi = isset($_POST["kuaza_up_config_birkeredeuploadsayisi"]) ? $_POST["kuaza_up_config_birkeredeuploadsayisi"] : "20";
$kuaza_up_config_teklinktema = isset($_POST["kuaza_up_config_teklinktema"]) ? $_POST["kuaza_up_config_teklinktema"] : "";
$kuaza_up_config_toplulinktema = isset($_POST["kuaza_up_config_toplulinktema"]) ? $_POST["kuaza_up_config_toplulinktema"] : "";

// Eger upload api linki degismis ise
$kuaza_up_config_upapilink = isset($_POST["kuaza_up_config_upapilink"]) && !empty($_POST["kuaza_up_config_upapilink"]) ? $_POST["kuaza_up_config_upapilink"] : "http://piclect.com/pardus/api/v1/api-upload";

update_option( "kuaza_up_config_acik", $kuaza_up_config_acik );
update_option( "kuaza_up_config_sitekey", $kuaza_up_config_sitekey );
update_option( "kuaza_up_config_sitesecret", $kuaza_up_config_sitesecret );
update_option( "kuaza_up_config_upapilink", $kuaza_up_config_upapilink );
update_option( "kuaza_up_config_cssupload", $kuaza_up_config_cssupload );
update_option( "kuaza_up_config_birkeredeuploadsayisi", $kuaza_up_config_birkeredeuploadsayisi );
update_option( "kuaza_up_config_teklinktema", $kuaza_up_config_teklinktema );
update_option( "kuaza_up_config_toplulinktema", $kuaza_up_config_toplulinktema );

/* kategorileri guncelle baslangic */
		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_URL, "http://piclect.com/pardus/bilgial_api.php" );
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_POST, true);
		  curl_setopt($ch, CURLOPT_REFERER, get_site_url());
		  curl_setopt($ch, CURLOPT_POSTFIELDS, array("site_key" => $kuaza_up_config_sitekey, "site_secret" => $kuaza_up_config_sitesecret, "neistiyon" => "koleksiyonlar"));
		  
		  $kaynak = curl_exec($ch);

		  
		  curl_close($ch);
		 
		

if($kaynak != "" && is_array($kaynak)){

$koleksiyonlarpiclect = unserialize($kaynak);

	foreach ( $koleksiyonlarpiclect as $katcektik )
{		

$galeriduz = $wpdb->get_row( "SELECT * FROM ".$this->table_name." where ka_id='".$katcektik['ga_id']."'", ARRAY_A );

if(empty($galeriduz)){
$this->icerik = array(
"ka_id" => $katcektik['ga_id'],
"ka_name" => ($katcektik['ga_name']),
"ka_descr" => ($katcektik['ga_descr']),
"ka_create_date" => current_time('mysql')
);

$ekle = $this->kuaza_piclect_kategori_olustur();
//if($ekle){ echo $katcektik['ga_name'].": ekleme islemi basarili<br>"; }else{ echo $katcektik['ga_name'].": HATA olustu<br>"; }

}

}
}
/* kategorileri guncelle bitis */


echo "<div style='color:green'>".__('Update settings for plugins','kuaza_pic_up_lang')."</div>";
}

?>
<hr>
<?php _e("Add this short code post/page area: [up_piclect]","kuaza_pic_up_lang"); ?>

<hr>

<table class="form-table">
<form method="POST" action="">
<!--<tr valign="top">
	<th><label for="kuaza_up_config_acik"><?php _e("Enable plugin","kuaza_pic_up_lang"); ?></label></th>
	<td><input type="checkbox" id="kuaza_up_config_acik" name="kuaza_up_config_acik" <?php if(get_site_option( 'kuaza_up_config_acik' ) == 'yes'){ echo "checked='checked'"; }; ?>/></td>
<td><?php _e("Default 'yes'","kuaza_pic_up_lang"); ?></td>
</tr>-->


<tr valign="top">
	<th><label for="kuaza_up_config_sitekey"><?php _e("Api Site KEY","kuaza_pic_up_lang"); ?></label></th>
	<td><input id="kuaza_up_config_sitekey" name="kuaza_up_config_sitekey" type="text" value="<?php echo get_site_option( 'kuaza_up_config_sitekey' ); ?>" /></td>
<td><?php _e("Please visit here: http://piclect.com/developer","kuaza_pic_up_lang"); ?></td>
</tr>

<tr valign="top">
	<th><label for="kuaza_up_config_sitesecret"><?php _e("Api Site SECRET","kuaza_pic_up_lang"); ?></label></th>
	<td><input id="kuaza_up_config_sitesecret" name="kuaza_up_config_sitesecret" type="text" value="<?php echo get_site_option( 'kuaza_up_config_sitesecret' ); ?>" /></td>
<td><?php _e("Please visit here: http://piclect.com/developer","kuaza_pic_up_lang"); ?></td>
</tr>

<tr valign="top">
	<th><label for="kuaza_up_config_birkeredeuploadsayisi"><?php _e("How much one time upload files ?","kuaza_pic_up_lang"); ?></label></th>
	<td><input id="kuaza_up_config_birkeredeuploadsayisi" name="kuaza_up_config_birkeredeuploadsayisi" type="text" value="<?php echo get_site_option( 'kuaza_up_config_birkeredeuploadsayisi' ); ?>" /></td>
<td><?php _e("Default 20 images","kuaza_pic_up_lang"); ?></td>
</tr>

<tr valign="top">
	<th><label for="kuaza_up_config_teklinktema"><?php _e("Code area themes (one image)","kuaza_pic_up_lang"); ?> <?php _e("(for next version)","kuaza_pic_up_lang"); ?></label></th>
	<td><textarea id="kuaza_up_config_teklinktema" name="kuaza_up_config_teklinktema"><?php echo get_site_option( 'kuaza_up_config_teklinktema' ); ?></textarea></td>
<td><?php _e("Css for upload area","kuaza_pic_up_lang"); ?>
<hr>

{direct_original} "original size"<br />
{direct_thumb} "250px"<br />
{direct_avatar} "40px"<br />
{direct_avatar84} "84px"<br />
{direct_resize} "resize size"<br />
{direct_medium} "1024px"<br />

{title} "Title for image"<br />
{description} "Description for image"<br />

{pic_width} "480" <br />
{pic_height} "405" <br />
{pic_size} "23332" <br />
{pic_time} "2014-07-21 15:53:23" <br />
{pic_ID} "piclect image id)<br />
{pic_koleksiyon} "Piclect collection ID"<br />

</td>
</tr>

<tr valign="top">
	<th><label for="kuaza_up_config_toplulinktema"><?php _e("Bulk link area themes","kuaza_pic_up_lang"); ?> <?php _e("(for next version)","kuaza_pic_up_lang"); ?></label></th>
	<td><textarea id="kuaza_up_config_toplulinktema" name="kuaza_up_config_toplulinktema"><?php echo get_site_option( 'kuaza_up_config_toplulinktema' ); ?></textarea></td>
<td><?php _e("Disable: Coming soon (for next version)","kuaza_pic_up_lang"); ?></td>
</tr>

<tr valign="top">
	<th><label for="kuaza_up_config_cssupload"><?php _e("css for upload area","kuaza_pic_up_lang"); ?></label></th>
	<td><textarea id="kuaza_up_config_cssupload" name="kuaza_up_config_cssupload">
	<?php if(get_site_option( 'kuaza_up_config_cssupload' )) { echo get_site_option( 'kuaza_up_config_cssupload' ); }else{ ?><style>
/*
* Piclect.com wordpress API v1.0 by kuaza
*/

.ajax-file-upload-statusbar {
border: 2px solid #0ba1b5;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
padding: 5px 5px 5px 5px;
display:  inline-block;
text-align:left
}
.ajax-file-upload-filename {
width:60%; 
float:left;
margin: 0 5px 5px 10px;
color: #807579;
overflow:hidden
}
.ajax-file-upload-progress {
margin-top: -13px;
position: relative;
width: 100%;
border: 1px solid #fff;
padding: 1px;
display: block
}
.ajax-file-upload-bar {
background-color: #ccc;
width: 0;
height: 2px;
color:#FFFFFF
}
.ajax-file-upload-percent {
position: absolute;
display: inline-block;
top: 3px;
left: 48%
}
.ajax-file-upload-red {
-moz-box-shadow: inset 0 39px 0 -24px #e67a73;
-webkit-box-shadow: inset 0 39px 0 -24px #e67a73;
box-shadow: inset 0 39px 0 -24px #e67a73;
background-color: #e4685d;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
display: inline-block;
color: #fff;
font-family: arial;
font-size: 13px;
font-weight: normal;
padding: 1px 15px;
text-decoration: none;
text-shadow: 0 1px 0 #b23e35;
cursor: pointer;
vertical-align: top;
margin-right:5px;
float:right
}
.ajax-file-upload-green {
background-color: #77b55a;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
margin: 0;
padding: 0;
display: inline-block;
color: #fff;
font-family: arial;
font-size: 13px;
font-weight: normal;
padding: 4px 15px;
text-decoration: none;
cursor: pointer;
text-shadow: 0 1px 0 #5b8a3c;
vertical-align: top;
margin-right:5px
}
.ajax-file-upload {
font-family: Arial, Helvetica, sans-serif;
font-size: 16px;
font-weight: bold;
padding: 15px 20px;
cursor:pointer;	
line-height:20px;
/*height:25px;*/
margin:0 10px 10px 0;
display: inline-block;
background: #fff;
border: 1px solid #e8e8e8;
color: #888;
text-decoration: none;
border-radius: 3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
-moz-box-shadow: 0 2px 0 0 #e8e8e8;
-webkit-box-shadow: 0 2px 0 0 #e8e8e8;
box-shadow: 0 2px 0 0 #e8e8e8; 
padding: 13px 10px 6px 10px; 
color: #fff;
background: #2f8ab9;
border: none;
-moz-box-shadow: 0 2px 0 0 #13648d;
-webkit-box-shadow: 0 2px 0 0 #13648d;
box-shadow: 0 2px 0 0 #13648d; 
vertical-align:middle
}
.ajax-file-upload:hover {
background: #3396c9;
-moz-box-shadow: 0 2px 0 0 #15719f;
-webkit-box-shadow: 0 2px 0 0 #15719f;
box-shadow: 0 2px 0 0 #15719f
}
.ajax-upload-dragdrop
{
margin:0 auto;
margin-bottom: 10px;
border:2px dotted #A5A5C7;
width:60%; 
color: #DADCE3;
text-align:left;
vertical-align:middle;
padding:10px 11px 0px 10px;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px
}
.outputimageshepsi {
background-color: #F7F8E0;
margin-top:20px;
padding:10px
}
.margintop40 {
margin-top: 40px
}
.margintop20 {
margin-top: 20px
}
.itemindex { width: 84px; height: 84px;float:left; padding-left:1px; padding-bottom:1px}

.teklinkilkcevre{
margin-top:10px;
}
.linkcevre{
margin-bottom:10px;
}
.tabscevre{
}
.yuzdeyuzyap {
width: 100%;box-sizing: border-box;-webkit-box-sizing:border-box;-moz-box-sizing: border-box;
}
#tabs{font-size:90%;}
#tabs div{background:#FFFFCC;clear:both;padding:10px;min-height:200px;}
#tabs div p{line-height:150%;}
.targettab_kuaza{
margin-bottom:10px;
}
</style><?php } ?></textarea></td>
<td><?php _e("Css for upload area","kuaza_pic_up_lang"); ?></td>
</tr>

<tr>
<td>
<input id="ayarlariguncelle" name="ayarlariguncelle" type="hidden" value="evet" />
<p class="submit"><input type="submit" class="button-primary" value="<?php _e("Update settings","kuaza_pic_up_lang"); ?>" /></p>
</form>
</td>
</tr>

</table>

<?php
}

/*
 * menu olusturma
 *
*/
function kuaza_piclect_upload_menuolustur(){
?>
<a href="<?php echo get_site_url(); ?>/wp-admin/edit.php?page=kuaza_piclect_upload">
<?php _e("<h3>Plugins index</h3>","kuaza_pic_up_lang"); ?>
<a href="<?php echo get_site_url(); ?>/wp-admin/edit.php?page=kuaza_piclect_upload&islem=ayarlariguncelle">
<?php _e("<h3>Settings</h3>","kuaza_pic_up_lang"); ?>
</a>
<hr />

<?php

}

     public function upptag_func( $atts, $content="" ) {
global $wpdb;
	 $this->timestamp = time();
	 $this->session = md5('unique_salt' . $this->timestamp); 
	 $this->token = $this->session;
		ob_start(); 
		
	if(!get_site_option( 'kuaza_up_config_cssupload' )){ ?><style>
/*
* Piclect.com wordpress API v1.0 by kuaza
*/

.ajax-file-upload-statusbar {
border: 2px solid #0ba1b5;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
padding: 5px 5px 5px 5px;
display:  inline-block;
text-align:left
}
.ajax-file-upload-filename {
width:60%; 
float:left;
margin: 0 5px 5px 10px;
color: #807579;
overflow:hidden
}
.ajax-file-upload-progress {
margin-top: -13px;
position: relative;
width: 100%;
border: 1px solid #fff;
padding: 1px;
display: block
}
.ajax-file-upload-bar {
background-color: #ccc;
width: 0;
height: 2px;
color:#FFFFFF
}
.ajax-file-upload-percent {
position: absolute;
display: inline-block;
top: 3px;
left: 48%
}
.ajax-file-upload-red {
-moz-box-shadow: inset 0 39px 0 -24px #e67a73;
-webkit-box-shadow: inset 0 39px 0 -24px #e67a73;
box-shadow: inset 0 39px 0 -24px #e67a73;
background-color: #e4685d;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
display: inline-block;
color: #fff;
font-family: arial;
font-size: 13px;
font-weight: normal;
padding: 1px 15px;
text-decoration: none;
text-shadow: 0 1px 0 #b23e35;
cursor: pointer;
vertical-align: top;
margin-right:5px;
float:right
}
.ajax-file-upload-green {
background-color: #77b55a;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
margin: 0;
padding: 0;
display: inline-block;
color: #fff;
font-family: arial;
font-size: 13px;
font-weight: normal;
padding: 4px 15px;
text-decoration: none;
cursor: pointer;
text-shadow: 0 1px 0 #5b8a3c;
vertical-align: top;
margin-right:5px
}
.ajax-file-upload {
font-family: Arial, Helvetica, sans-serif;
font-size: 16px;
font-weight: bold;
padding: 15px 20px;
cursor:pointer;	
line-height:20px;
/*height:25px;*/
margin:0 10px 10px 0;
display: inline-block;
background: #fff;
border: 1px solid #e8e8e8;
color: #888;
text-decoration: none;
border-radius: 3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
-moz-box-shadow: 0 2px 0 0 #e8e8e8;
-webkit-box-shadow: 0 2px 0 0 #e8e8e8;
box-shadow: 0 2px 0 0 #e8e8e8; 
padding: 13px 10px 6px 10px; 
color: #fff;
background: #2f8ab9;
border: none;
-moz-box-shadow: 0 2px 0 0 #13648d;
-webkit-box-shadow: 0 2px 0 0 #13648d;
box-shadow: 0 2px 0 0 #13648d; 
vertical-align:middle
}
.ajax-file-upload:hover {
background: #3396c9;
-moz-box-shadow: 0 2px 0 0 #15719f;
-webkit-box-shadow: 0 2px 0 0 #15719f;
box-shadow: 0 2px 0 0 #15719f
}
.ajax-upload-dragdrop
{
margin:0 auto;
margin-bottom: 10px;
border:2px dotted #A5A5C7;
width:60%; 
color: #DADCE3;
text-align:left;
vertical-align:middle;
padding:10px 11px 0px 10px;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px
}
.outputimageshepsi {
background-color: #F7F8E0;
margin-top:20px;
padding:10px
}
.margintop40 {
margin-top: 40px
}
.margintop20 {
margin-top: 20px
}
.itemindex { width: 84px; height: 84px;float:left; padding-left:1px; padding-bottom:1px}

.teklinkilkcevre{
margin-top:10px;
}
.linkcevre{
margin-bottom:10px;
}
.tabscevre{
}
.yuzdeyuzyap {
width: 100%;box-sizing: border-box;-webkit-box-sizing:border-box;-moz-box-sizing: border-box;
}
#tabs{font-size:90%;}
#tabs div{background:#FFFFCC;clear:both;padding:10px;min-height:200px;}
#tabs div p{line-height:150%;}
.targettab_kuaza{
margin-bottom:10px;
}
</style><?php }else{ echo get_site_option( 'kuaza_up_config_cssupload' ); }
$listele_resimleri = $wpdb->get_results('SELECT * FROM '.$this->table_name_kategori, ARRAY_A);
?>

<form action="" method="post" enctype="multipart/form-data" id="UploadForm">
<input type="hidden" name="session" id="session" value="<?php echo $this->session; ?>"/>
<input type="hidden" name="timestamp" id="timestamp" value="<?php echo $this->timestamp; ?>"/>
<input type="hidden" name="token" id="token" value="<?php echo $this->token; ?>"/>
<input type="hidden" name="action" id="action" value="multiple_api"/>

<div class="" style="margin-left:auto;margin-right:auto;text-align:center;">

<div class="uploadcevre" style="margin:0 auto;text-align:center;">

<div id="mulitplefileuploader"><i class="fa fa-upload"></i> <?php _e("Select Images","kuaza_pic_up_lang"); ?></div>

</div>

<div id="extrabolumupload" class="extrabolumupload" style="display:none;width:60%;margin-left:auto;margin-right:auto;text-align:left;margin-top:20px;background:#f2f2f2;padding:10px;">
<div class="clearfix">
<strong><?php _e("Choose the collection:","kuaza_pic_up_lang"); ?></strong> <br>
<select name="kategorileri" id="kategorileri" class="yuzdeyuzyap">
<option value="116" selected="selected"><?php _e("Default collection:","kuaza_pic_up_lang"); ?></option>

<?php

if($listele_resimleri){

foreach($listele_resimleri as $kategori){
echo '<option value="'.$kategori["ka_id"].'">'.$kategori["ka_name"].'</option>';
}
}

?>

</select>
</div>
<div class="clearfix" style="margin-top:10px;">
<strong><?php _e("Resize Images","kuaza_pic_up_lang"); ?></strong> <br>
<div class="clearfix">
<select name="pic_resize" id="pic_resize" class="yuzdeyuzyap">
<option value="100"><?php _e("100x... (avatar)","kuaza_pic_up_lang"); ?></option>
<option value="150"><?php _e("150x... (thumbnail)","kuaza_pic_up_lang"); ?></option>
<option value="320"><?php _e("320x... (for websites and email)","kuaza_pic_up_lang"); ?></option>
<option value="640"><?php _e("640x... (for message boards)","kuaza_pic_up_lang"); ?></option>
<option value="800"><?php _e("800x... (15-inch monitor)","kuaza_pic_up_lang"); ?></option>
<option value="1024"><?php _e("1024x... (17-inch monitor)","kuaza_pic_up_lang"); ?></option>
<option value="1280"><?php _e("1280x... (19-inch monitor)","kuaza_pic_up_lang"); ?></option>
<option value="1600"><?php _e("1600x... (21-inch monitor)","kuaza_pic_up_lang"); ?></option>
<option value="noresize" selected><?php _e("Do not resize","kuaza_pic_up_lang"); ?></option>
</select>
</div>
</div>
<div class="" style="margin-top:10px;">
<div class="aciklamalabel"><strong><?php _e("Description","kuaza_pic_up_lang"); ?></strong> <br>
<input class="yuzdeyuzyap" type="text" name="description" id="description" value="" placeholder="<?php _e("Description for Images..","kuaza_pic_up_lang"); ?>">
</div>
<div class="etiketlabel" style="margin-top:10px;"><strong><?php _e("Tags","kuaza_pic_up_lang"); ?></strong> <br>
<input class="yuzdeyuzyap" name="tags" id="tags" type="text" placeholder="<?php _e("Car, Madonna, Tarkan","kuaza_pic_up_lang"); ?>">
</div>
</div>
<div class="" style="margin-top:10px;">
<p id="etiketeklep" style="">
<input type="hidden" name="hgorsun" value="1" id="hgorsun"/>
<label onClick="$('#hgorsun').val('1');" class="" style="">
<input type="radio" name="hgorsun" id="hgorsun1" value="1" checked>
<?php _e("Public","kuaza_pic_up_lang"); ?>
</label>
<label onClick="$('#hgorsun').val('0');" class="" style="margin-left:10px;">
<input type="radio" name="hgorsun" id="hgorsun2" value="0">
<?php _e("Private","kuaza_pic_up_lang"); ?>
</label>
<label onClick="$('#hgorsun').val('6');" class="" style="margin-left:10px;color:#c00;">
<input type="radio" name="hgorsun" id="hgorsun3" value="6">
<?php _e("<strong>+18</strong> <i>(Adult)</i>","kuaza_pic_up_lang"); ?>
</label>
</p>
<input id="uploadetbakalim" style="margin-right:60px" class="" type="button" value="<?php _e("Upload Now","kuaza_pic_up_lang"); ?>"/>
</div>
</div>
</div>
</form> 
<div class="">
 
<div id="outputimageshepsi" class="outputimageshepsi" style="display:none;"> </div>
 
<div id="uploadbitti"> </div>
</div>
<script>jQuery(document).ready(function($) {
	var maxuploadsayisi = "<?php echo get_site_option( 'kuaza_up_config_birkeredeuploadsayisi' ) ? get_site_option( 'kuaza_up_config_birkeredeuploadsayisi' ) : "20"; ?>";
	var dragDropStr="<?php _e("<span><b>Drag &amp; Drop Files</b></span>","kuaza_pic_up_lang"); ?>";
	var abortStr="<?php _e("Abort","kuaza_pic_up_lang"); ?>";
	var cancelStr="<?php _e("Cancel","kuaza_pic_up_lang"); ?>";
	var doneStr="<?php _e("Done","kuaza_pic_up_lang"); ?>";
	var multiDragErrorStr="<?php _e("Multiple File Drag &amp; Drop is not allowed.","kuaza_pic_up_lang"); ?>";
	var extErrorStr="<?php _e("is not allowed. Allowed extensions: ","kuaza_pic_up_lang"); ?>";
	var sizeErrorStr="<?php _e("is not allowed. Allowed Max size: ","kuaza_pic_up_lang"); ?>";
	var uploadErrorStr="<?php _e("Upload is not allowed","kuaza_pic_up_lang"); ?>";
	var maxFileCountErrorStr="<?php _e(" is not allowed. Maximum allowed files are:","kuaza_pic_up_lang"); ?>";
	var url = "<?php echo get_site_url(); ?>/?kuaza_piclect_upload_progress=1";
	var jsyolu = "<?php echo plugin_dir_url( __FILE__ ); ?>style/assets/js/";
	
var uploadObj = $("#mulitplefileuploader").uploadFile({
    url: url,
    dragDrop:true,
	autoSubmit:false,
	showFileCounter:true,
    fileName: "ImageFile",
    allowedTypes:"jpg,jpeg,png,gif,bmp",	
    returnType:"text",
	dragdropWidth:"95%",
	statusBarWidth:"95%",
    maxFileCount: maxuploadsayisi,//max number of files to upload
    maxFileSize: 10000000,//max filesize
    showDelete:false,
	showStatusAfterSuccess:false,
	dragDropStr: dragDropStr,
	abortStr:abortStr,
	cancelStr:cancelStr,
	doneStr:doneStr,
	multiDragErrorStr: multiDragErrorStr,
	extErrorStr:extErrorStr,
	sizeErrorStr:sizeErrorStr,
	uploadErrorStr:uploadErrorStr,

	//formData: upload_secenekleri,
	dynamicFormData: function()
		{
			return {
				'kategorileri'  : $('#kategorileri').val(),
					'timestamp' : $('#timestamp').val(), 
						'token'     : $('#token').val(),
							'session'     : $('#session').val(),
								'hgorsun' : $('#hgorsun').val(),
									'description' : $('#description').val(),
										'tags' : $('#tags').val(),
											'pic_resize' : $('#pic_resize').val(),
												'action' : $('#action').val()
													};
		},	
	
	/*onError: function(files,status,errMsg)
	{
	//
	},*/
	
	onSelect:function(files)
		{
			files[0].name;
				document.getElementById("extrabolumupload").style.display = "block";
					return true; //to allow file submission.
		},
		
	onSuccess:function(files,data,xhr)
		{
			$("#uploadbitti").fadeOut(1, function () {
				$('#uploadbitti').fadeIn('slow');	
					$("#uploadbitti").prepend(data);
			});
		},
		
	afterUploadAll: function(obj)
		{
			toplamresimyuklenen=JSON.stringify(obj.tCounter);
			
				if(toplamresimyuklenen > 1){

					$("#extrabolumupload").fadeOut(500);

						jQuery.ajax({
							url: url + "&hepsi="+$('#timestamp').val()+"&getislemcik=toplulinkal",
								async: false,
									success: function(data) {
										$(".outputimageshepsi").fadeOut(700, function () {

											$('.outputimageshepsi').fadeIn('slow');
											
											$(".outputimageshepsi").html(data);

											});

										},
						});

				}else{
				$("#extrabolumupload").fadeOut(500);
				}
				
			//$('#uploadetbakalim').button('reset');
		}
});

    $('#uploadetbakalim').click(function () {
		
		// bazen kafa calistirmak ise yarayabiliyor :)
		if(uploadObj.tCounter !== 0){
			neoluyorbakalim = (uploadObj.selectedFiles - uploadObj.tCounter);
				}else{
					neoluyorbakalim = uploadObj.selectedFiles;
						}

		if(neoluyorbakalim > 0){
					uploadObj.startUpload();
						}
    });

 
 
});
</script> 
		<?php
			return ob_get_clean();
		 }


# Trigger for progress..
function kuaza_piclect_upload_plugin_add_trigger($vars) {
    $vars[] = 'kuaza_piclect_upload_progress';
    return $vars;
}
 
 /**
 * Piclect api v1.0
 * Author: Kuaza
 * Author email: kuaza.ca@gmail.com
 * @package WP Api for piclect.com
 */
function plugin_trigger_check() {
    if(intval(get_query_var('kuaza_piclect_upload_progress')) == 1) {
	global $wpdb;
	
if(isset($_GET["getislemcik"]) && $_GET["getislemcik"] == "toplulinkal" && !empty($_GET["hepsi"]) && is_numeric($_GET["hepsi"])){

$hepsi = $_GET["hepsi"];

$listele_resimleri = $wpdb->get_results('SELECT * FROM '.$this->table_name.' where kap_hepsi = "'.$hepsi.'"', ARRAY_A);

if($listele_resimleri){
$toplamresim = count($listele_resimleri);
foreach($listele_resimleri as $resim_bilgi) {


	if($toplamresim >= '2'){
	
@$direksayfalink .= "http://piclect.com/".$resim_bilgi['kap_img_id']."
";
@$direksayfalinkdownload .= "http://piclect.com/".$resim_bilgi['kap_img_id']."/download
";	
@$direkresimlink .= $resim_bilgi['kap_buyuklink']."
";

@$direkresimlink_t .= $resim_bilgi['kap_kucuklink'].'
	
';
@$direkresimlink_r .= $resim_bilgi['kap_boyutlulink'].'
';
@$html_link_big .= "<a href='http://piclect.com/".$resim_bilgi['kap_img_id']."' target='_blank'><img src='".$resim_bilgi['kap_buyuklink']."' alt='".$resim_bilgi['kap_name']."' /></a>
	
";
@$img_link_big .= '[URL=http://piclect.com/'.$resim_bilgi['kap_img_id'].'][IMG]'.$resim_bilgi['kap_buyuklink'].'[/IMG][/URL]
	
';
@$html_link_thumb .= "<a href='http://piclect.com/".$resim_bilgi['kap_img_id']."' target='_blank'><img src='".$resim_bilgi['kap_kucuklink']."' alt='".$resim_bilgi['kap_name']."' /></a>
	
";
@$img_link_thumb .= '[URL=http://piclect.com/'.$resim_bilgi['kap_img_id'].'][IMG]'.$resim_bilgi['kap_kucuklink'].'[/IMG][/URL]
	
';
}
	}
	
	if($toplamresim >= '2'){
		?>

<div class="tabscevre">

 <div id="tabs">
  <?php _e("Bulk link area","kuaza_pic_up_lang"); ?> <select class="targettab_kuaza" id="selectOpt">
  <option value="linkin"><?php _e("Email &amp; IM","kuaza_pic_up_lang"); ?></option>
  <option value="direct"><?php _e("Direct Link","kuaza_pic_up_lang"); ?></option>
  <option value="html"><?php _e("HTML code","kuaza_pic_up_lang"); ?></option>
  <option value="img"><?php _e("IMG code","kuaza_pic_up_lang"); ?></option>
  <option value="thumbhtml"><?php _e("HTML thumb code","kuaza_pic_up_lang"); ?></option>
  <option value="thumbimg"><?php _e("IMG thumb code","kuaza_pic_up_lang"); ?></option>
  <option value="downloadphoto"><?php _e("Download photo links","kuaza_pic_up_lang"); ?></option>
  <option value="yeniboyut"><?php _e("New Resize","kuaza_pic_up_lang"); ?></option>
   </select>
<div class="tab-pane active" id="linkin"><textarea rows="10" class="yuzdeyuzyap" onclick="javascript:this.focus();this.select();">
<?php echo $direksayfalink; ?>
</textarea></div>

<div id="direct"><textarea class="yuzdeyuzyap" rows="10" onclick="javascript:this.focus();this.select();">
<?php echo $direkresimlink; ?>
</textarea></div>

<div id="html"><textarea class="yuzdeyuzyap" rows="10" onclick="javascript:this.focus();this.select();">
<?php echo $html_link_big; ?>
</textarea></div>

<div id="img"><textarea class="yuzdeyuzyap" rows="10" onclick="javascript:this.focus();this.select();">
<?php echo $img_link_big; ?>
</textarea></div>

<div id="thumbhtml"><textarea class="yuzdeyuzyap" rows="10" onclick="javascript:this.focus();this.select();">
<?php echo $html_link_thumb; ?>
</textarea></div>

<div id="thumbimg"><textarea class="yuzdeyuzyap" rows="10" onclick="javascript:this.focus();this.select();">
<?php echo $img_link_thumb; ?>
</textarea></div>

<div id="yeniboyut"><textarea class="yuzdeyuzyap" rows="10" onclick="javascript:this.focus();this.select();">
<?php echo $direkresimlink_r; ?>
</textarea></div>
<div class="tab-pane active" id="downloadphoto"><textarea rows="10" class="yuzdeyuzyap" onclick="javascript:this.focus();this.select();">
<?php echo $direksayfalinkdownload; ?>
</textarea></div>
 </div>

<script>
jQuery(document).ready(function($) {
$( ".targettab_kuaza" ).change(function() {
var myselect = document.getElementById("selectOpt");
$('#tabs div').hide();
  $('#' + myselect.options[myselect.selectedIndex].value).show();
});

$('#tabs div').hide();
$('#tabs div:first').show();

});

</script>

</div>
<?php
} 
}else{
die("Null");
}
die();
} // toplu link listeleme kismi bitis

/* 5.0 */
if(!get_site_option( 'kuaza_up_config_sitekey' ) || !get_site_option( 'kuaza_up_config_sitesecret' ))
die(__("Please enter Site KEY and Site Secret code","kuaza_pic_up_lang"));


		  // post yada dosya ekleme kismi bos ise hata mesaji verdiririz :)
		  if(empty($_POST) || empty($_FILES)){	
		  
		  die('<div class="alert">
		  '.__("Null :/","kuaza_pic_up_lang").'
		  </div>');
		  }

		  $hgorsun = (isset($_POST["hgorsun"]) && !empty($_POST["hgorsun"]) && is_numeric($_POST["hgorsun"]) ? $_POST["hgorsun"] : '0');

		  $kategorileri = (!empty($_POST["kategorileri"]) ? $_POST["kategorileri"] : (!empty($_POST["kategorileripic"]) ? $_POST["kategorileripic"] : ''));
		  $action = $_POST["action"];
		  $session = $_POST["session"];
		  $timestamp = $_POST["timestamp"];
		  $pic_resize = $_POST["pic_resize"];
		  $description = (!empty($_POST["description"]) ? $_POST["description"] : '');
		  $token = $_POST["token"];
		  $hepsi = $timestamp;
			$tags = (!empty($_POST["tags"]) ? $_POST["tags"] : '');


		 if($_FILES["ImageFile"]["tmp_name"]){

		  $resimismi = stripslashes($_FILES["ImageFile"]["name"]);
		  move_uploaded_file($_FILES["ImageFile"]["tmp_name"], plugin_dir_path( __FILE__ ).'temp/' .$resimismi) or die("error mu acaba :)/ ".$_FILES["ImageFile"]["name"]." /");

		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_URL, get_site_option( 'kuaza_up_config_upapilink' ) );
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_POST, true);
		  curl_setopt($ch, CURLOPT_REFERER, get_site_url());
		  curl_setopt($ch, CURLOPT_POSTFIELDS, array('file'=>"@".plugin_dir_path( __FILE__ )."temp/{$resimismi}", "description" => $description, "hgorsun" => $hgorsun, "pic_resize" => $pic_resize, "kategorileri" => $kategorileri, "session" => $session, "timestamp" => $timestamp, "token" => $token, "action" => $action, "site_key" => get_site_option( 'kuaza_up_config_sitekey' ), "site_secret" => get_site_option( 'kuaza_up_config_sitesecret' ), "api" => "api", "tags" => $tags));
		  $kaynakilk = curl_exec($ch);
		  
		  $kaynak = json_decode($kaynakilk, true); // json cozer array dizesine ceviririz..
		  
		  /* yuklemede hata olursa ekrana yazdiririz ve uyaririz :) */	
		  if(!is_array($kaynak)){
			die('<div class="alert">
		  Error (hata): '.var_dump($kaynak).'
		  </div>');
		  }
		  
		  curl_close($ch);
	
		  # dosyayi temp klasorunden sil sss
		  unlink(plugin_dir_path( __FILE__ ).'temp/'.$resimismi);

			$img_id = $kaynak['pic_ID'];
			$filename = addslashes($kaynak['title']);
 
			$kucuklink = $kaynak['direct_thumb'];
			$buyuklink = $kaynak['direct_original'];
			$boyutlulink = $kaynak['direct_resize'] ? $kaynak['direct_resize'] : null;
			$genislikyukseklik = $kaynak['pic_width'].' x '.$kaynak['pic_height'];
			$zaman = strtotime($kaynak['pic_time']);
			$boyut = $kaynak['pic_size'];
			$uye = (get_current_user_id() ? get_current_user_id() : '0');

			$array_bilgi_db = array(
			"kap_img_id" =>$img_id, 
			"kap_name" =>$filename, 
			"kap_descr" =>$description, 
			"kap_kucuklink" =>$kucuklink, 
			"kap_buyuklink" =>$buyuklink, 
			"kap_boyutlulink" =>$boyutlulink, 
			"kap_genislikyukseklik" =>$genislikyukseklik, 
			"kap_boyutu" =>$boyut, 
			"kap_gallery_id" =>$kategorileri, 
			"kap_time" =>$zaman, 
			"kap_tags" =>$tags, 
			"kap_uygundegil" =>$hgorsun,
			"kap_hepsi" =>$hepsi,
			"kap_uye" =>$uye

			);
			
 $resimbilgiekle = $wpdb->insert( $this->table_name, $array_bilgi_db );

		 }

		 if($resimbilgiekle){
		 
		 /* // sonraki versiyon icin tema destegi olursa eger :)
$kspt_konu_temasi_yedek = get_site_option( 'kuaza_up_config_teklinktema' );

	
	$kpst_degiskenler = $kaynak;
	
			if ( !empty($kpst_degiskenler) && is_array($kpst_degiskenler) ) :

			foreach ($kpst_degiskenler as $code => $value)
				$kspt_konu_temasi_yedek = str_replace('{{'.$code.'}}', $value, $kspt_konu_temasi_yedek);
			
			endif;
			  
	echo stripslashes($kspt_konu_temasi_yedek);
	//print_r($kpst_degiskenler);
	exit;
	*/	 
		 	 
?>

		<div class="teklinkilkcevre">
		  
		<div class="resimcevre">
		  <center><a href='http://piclect.com/<?php echo $img_id; ?>' target='_blank'>
		  <img src='<?php echo $kucuklink; ?>' border='0' alt='<?php echo $filename; ?>' class="" />
		  </a>
			<h4><?php echo $filename; ?></h4>
			</center>
		</div>
		
		<div class="teklink">	
 <div class="linkcevre">
  <label for="linkin-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("Email &amp; IM","kuaza_pic_up_lang"); ?></label>
  <input class="yuzdeyuzyap" id="linkin-<?php echo $img_id; ?>" class="" value="http://piclect.com/<?php echo $img_id; ?>" onclick="javascript:this.focus();this.select();" type="text">
   </div>
   <?php if($boyutlulink){ ?>
	 <div class="linkcevre">
	 <label for="boyutlu-link-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("Resize Direct Link","kuaza_pic_up_lang"); ?></label>
	 <input class="yuzdeyuzyap" id="boyutlu-link-<?php echo $img_id; ?>" value="<?php echo $boyutlulink; ?>" onclick="javascript:this.focus();this.select();" type="text">
	</div>
	<?php } ?>
	 <div class="linkcevre">
	 <label for="direct-link-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("Direct Link","kuaza_pic_up_lang"); ?></label>
	 <input class="yuzdeyuzyap" id="direct-link-<?php echo $img_id; ?>" value="<?php echo $buyuklink; ?>" onclick="javascript:this.focus();this.select();" type="text">
	</div>
	 <div class="linkcevre">
	 <label for="html-link-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("HTML code","kuaza_pic_up_lang"); ?></label>
	 <input class="yuzdeyuzyap" id="html-link-<?php echo $img_id; ?>" value="<a href='http://piclect.com/<?php echo $img_id; ?>' target='_blank'><img src='<?php echo $buyuklink; ?>' border='0' alt='<?php echo $filename; ?>' /></a>" onclick="javascript:this.focus();this.select();" type="text">
	</div>
	 <div class="linkcevre">
	 <label for="img-link-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("IMG code","kuaza_pic_up_lang"); ?></label>
	 <input class="yuzdeyuzyap" id="img-link-<?php echo $img_id; ?>" value="[IMG]<?php echo $buyuklink; ?>[/IMG]" onclick="javascript:this.focus();this.select();" type="text">
	</div>
	 <div class="linkcevre">
	 <label for="html-link-thumb-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("HTML thumb code","kuaza_pic_up_lang"); ?></label>
	 <input class="yuzdeyuzyap" id="html-link-thumb-<?php echo $img_id; ?>" value="<a href='http://piclect.com/<?php echo $img_id; ?>' target='_blank'><img src='<?php echo $kucuklink; ?>' border='0' alt='<?php echo $filename; ?>' /></a>" onclick="javascript:this.focus();this.select();" type="text">
	</div>
	 <div class="linkcevre">
	 <label for="img-link-thumb-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("IMG thumb code","kuaza_pic_up_lang"); ?></label>
	 <input class="yuzdeyuzyap" id="img-link-thumb-<?php echo $img_id; ?>" value="[URL=http://piclect.com/<?php echo $img_id; ?>][IMG]<?php echo $kucuklink; ?>[/IMG][/URL]" onclick="javascript:this.focus();this.select();" type="text">
	</div>
 <div class="linkcevre">
  <label for="linkindownload-<?php echo $img_id; ?>" style="text-align:right;"><?php _e("Download image","kuaza_pic_up_lang"); ?></label>
  <input class="yuzdeyuzyap" id="linkindownload-<?php echo $img_id; ?>" class="" value="http://piclect.com/<?php echo $img_id; ?>/download" onclick="javascript:this.focus();this.select();" type="text">
   </div>

	</div>
	</div>

<?php } //eklemebasarilimi if i bitis 
    exit;
    }
}		 
		 		 
		 
/*
 * Eklenti aktif edilirse tablo ve bilgileri ekleriz yada silinirse kuaza iceriklerini sildiririz.
 *
*/

public function kuaza_pic_up_install() {

	$sql = "CREATE TABLE IF NOT EXISTS `$this->table_name` (
  `kap_id` int(10) NOT NULL AUTO_INCREMENT,
  `kap_img_id` int(11) DEFAULT '0',
  `kap_kucuklink` varchar(250) DEFAULT '',
  `kap_buyuklink` varchar(250) DEFAULT '',
  `kap_name` varchar(250) DEFAULT '',
  `kap_descr` text,
  `kap_gallery_id` int(11) DEFAULT '1',
  `kap_time` int(11) DEFAULT '0',
  `kap_genislikyukseklik` varchar(50) DEFAULT '',
  `kap_boyutu` varchar(50) DEFAULT '',
  `kap_tags` text,
  `kap_uygundegil` tinyint(1) DEFAULT '1',
  `kap_begenilme` int(11) NOT NULL DEFAULT '0',
  `kap_hepsi` int(11) DEFAULT NULL,
  `kap_uye` int(11) DEFAULT '0',
  `kap_boyutlulink` varchar(250) DEFAULT NULL,
  UNIQUE KEY (`kap_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

	$sql_kategori = "CREATE TABLE IF NOT EXISTS `$this->table_name_kategori` (
  `ka_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ka_name` varchar(255) DEFAULT NULL,
  `ka_descr` text,
  `ka_enabled` tinyint(4) DEFAULT '1',
  `ka_order` varchar(255) DEFAULT NULL,
  `ka_create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`ka_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   $olustur = dbDelta( $sql );
   $olustur2 = dbDelta( $sql_kategori );
   
   $css_upload_area = "<style>
/*
* Piclect.com wordpress API v1.0 by kuaza
*/

.ajax-file-upload-statusbar {
border: 2px solid #0ba1b5;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
padding: 5px 5px 5px 5px;
display:  inline-block;
text-align:left
}
.ajax-file-upload-filename {
width:60%; 
float:left;
margin: 0 5px 5px 10px;
color: #807579;
overflow:hidden
}
.ajax-file-upload-progress {
margin-top: -13px;
position: relative;
width: 100%;
border: 1px solid #fff;
padding: 1px;
display: block
}
.ajax-file-upload-bar {
background-color: #ccc;
width: 0;
height: 2px;
color:#FFFFFF
}
.ajax-file-upload-percent {
position: absolute;
display: inline-block;
top: 3px;
left: 48%
}
.ajax-file-upload-red {
-moz-box-shadow: inset 0 39px 0 -24px #e67a73;
-webkit-box-shadow: inset 0 39px 0 -24px #e67a73;
box-shadow: inset 0 39px 0 -24px #e67a73;
background-color: #e4685d;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
display: inline-block;
color: #fff;
font-family: arial;
font-size: 13px;
font-weight: normal;
padding: 1px 15px;
text-decoration: none;
text-shadow: 0 1px 0 #b23e35;
cursor: pointer;
vertical-align: top;
margin-right:5px;
float:right
}
.ajax-file-upload-green {
background-color: #77b55a;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
margin: 0;
padding: 0;
display: inline-block;
color: #fff;
font-family: arial;
font-size: 13px;
font-weight: normal;
padding: 4px 15px;
text-decoration: none;
cursor: pointer;
text-shadow: 0 1px 0 #5b8a3c;
vertical-align: top;
margin-right:5px
}
.ajax-file-upload {
font-family: Arial, Helvetica, sans-serif;
font-size: 16px;
font-weight: bold;
padding: 15px 20px;
cursor:pointer;	
line-height:20px;
/*height:25px;*/
margin:0 10px 10px 0;
display: inline-block;
background: #fff;
border: 1px solid #e8e8e8;
color: #888;
text-decoration: none;
border-radius: 3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
-moz-box-shadow: 0 2px 0 0 #e8e8e8;
-webkit-box-shadow: 0 2px 0 0 #e8e8e8;
box-shadow: 0 2px 0 0 #e8e8e8; 
padding: 13px 10px 6px 10px; 
color: #fff;
background: #2f8ab9;
border: none;
-moz-box-shadow: 0 2px 0 0 #13648d;
-webkit-box-shadow: 0 2px 0 0 #13648d;
box-shadow: 0 2px 0 0 #13648d; 
vertical-align:middle
}
.ajax-file-upload:hover {
background: #3396c9;
-moz-box-shadow: 0 2px 0 0 #15719f;
-webkit-box-shadow: 0 2px 0 0 #15719f;
box-shadow: 0 2px 0 0 #15719f
}
.ajax-upload-dragdrop
{
margin:0 auto;
margin-bottom: 10px;
border:2px dotted #A5A5C7;
width:60%; 
color: #DADCE3;
text-align:left;
vertical-align:middle;
padding:10px 11px 0px 10px;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px
}
.outputimageshepsi {
background-color: #F7F8E0;
margin-top:20px;
padding:10px
}
.margintop40 {
margin-top: 40px
}
.margintop20 {
margin-top: 20px
}
.itemindex { width: 84px; height: 84px;float:left; padding-left:1px; padding-bottom:1px}

.teklinkilkcevre{
margin-top:10px;
}
.linkcevre{
margin-bottom:10px;
}
.tabscevre{
}
.yuzdeyuzyap {
width: 100%;box-sizing: border-box;-webkit-box-sizing:border-box;-moz-box-sizing: border-box;
}
#tabs{font-size:90%;}
#tabs div{background:#FFFFCC;clear:both;padding:10px;min-height:200px;}
#tabs div p{line-height:150%;}
.targettab_kuaza{
margin-bottom:10px;
}
</style>";
   
add_option( "kuaza_up_config_cssupload", $css_upload_area );
add_option( "kuaza_up_config_acik", "yes" );
add_option( "kuaza_up_config_sitekey", "" );
add_option( "kuaza_up_config_sitesecret", "" );
add_option( "kuaza_up_config_upapilink", "http://piclect.com/pardus/api/v1/api-upload" );
add_option( "kuaza_up_config_birkeredeuploadsayisi", "20" );

}
static function kuaza_pic_up_unstall() {
   global $wpdb;
 
   delete_option( "kuaza_up_config_cssupload" );
   delete_option( "kuaza_up_config_acik" );
   delete_option( "kuaza_up_config_sitekey" );
   delete_option( "kuaza_up_config_sitesecret" );
   delete_option( "kuaza_up_config_upapilink" );
   delete_option( "kuaza_up_config_birkeredeuploadsayisi" );

   delete_site_option( "kuaza_up_config_cssupload" );
   delete_site_option( "kuaza_up_config_acik" );
   delete_site_option( "kuaza_up_config_sitekey" );
   delete_site_option( "kuaza_up_config_sitesecret" );
   delete_site_option( "kuaza_up_config_upapilink" );
   delete_site_option( "kuaza_up_config_birkeredeuploadsayisi" );

   $wpdb->query("DROP TABLE IF EXISTS ".$this->table_name);
   $wpdb->query("DROP TABLE IF EXISTS ".$this->table_name_kategori);
   
	remove_shortcode( "up_piclect" );
	
}

}

	 $kuazaPICLECT = new kuaza_piclect_upload();
