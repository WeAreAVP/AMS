<?php

/*
 * First capture all the request parameters for later use.  All parameters will be passed in, if a field is not available,
 * an empty string is passed.
 */

// Account Related Info - Start

/*
 * Contains the general account name, usually the office name.  Ex.  Re/Max - Boston Realty
 */
$account_name = $_POST["account_name"];
/*
 * Contains the general account's primary phone number.
 */
$account_phone = $_POST["account_phone"];
/*
 * Contains the genernal account's fax number, if available.
 */
$account_fax = $_POST["account_fax"];
/*
 * Contains the general account's email address.
 */
$account_email = $_POST["account_email"];
/*
 * Contains the general account's address.
 */
$account_address = $_POST["account_address"];
/*
 * Contains the general account's website address if available.
 */
$account_website = $_POST["account_website"];
/*
 * Contains the general account's ad disclaimer information if available.
 */
$account_disclaimer = $_POST["account_disclaimer"];
/*
 * Contains the general account's logo url if avaialble.
 */
$account_logo_url = $_POST["account_logo_url"];
// Account Related Info - End
// User Related Info - Start

/*
 * Contains the ad posting user's name.
 */
$user_name = $_POST["user_name"];
/*
 * Contains the ad posting user's phone number.
 */
$user_phone = $_POST["user_phone"];
/*
 * Contains the ad posting user's email.
 */
$user_email = $_POST["user_email"];
/*
 * Contains the ad posting user's profile picture url.
 */
$user_profile_url = $_POST["user_profile_url"];
/*
 * Contains the ad posting user's bio / self introduction.
 */
$user_bio = $_POST["user_bio"];
/*
 * Contains the ad posting user's personal website url.  This is used for search for other apartment this user has.
 * Generally, it is a good idea to link the ads to this url, where renters can search for the user's inventory.
 */
$user_site_url = $_POST["user_site_url"];
// User Related Info - End
// Property Ad Info - Start
/*
 * Contains the rental listing's id.  Renters can use this id to refer to the listing they are interested in.
 * It is generally a good idea to include this in a pominent location in the template.
 */
$property_id = $_POST["property_id"];
/*
 * Contains the listing's rent / month.
 */
$property_rent = $_POST["property_rent"];
/*
 * Contains the listing's number of beds.
 */
$property_beds = $_POST["property_beds"];
/*
 * Contains the listing's number of baths.
 */
$property_baths = $_POST["property_baths"];
/*
 * Contains the listing's fee info.  This is the fee the tenant needs to pay.
 */
$property_fee = $_POST["property_fee"];
/*
 * Contains the listing's available date.
 */
$property_available_date = $_POST["property_available_date"];
/*
 * Contains the utilities the listing's rent includes.
 */
$property_utilities_included = $_POST["property_utilities_included"];
/*
 * Contains the url of the primary photo for the listing, if available.
 */
$property_primary_image_url = $_POST["property_primary_image_url"];
/*
 * Contains the listing's photo urls, separated by "|".  Empty if no photo available.
 */
$property_image_urls = $_POST["property_image_urls"];
$property_img_urls = $_POST["property_img_urls"];
/*
 * Contains the youtube video url if available.
 */
$property_youtube_url = $_POST["property_youtube_url"];
/*
 * Contains the listing's list of point of interests, separated by "|".  Empty if no POI.
 */
$property_poi = $_POST["property_poi"];
/*
 * Contains the listing's list of features, separated by "|".  Empty if no feature.
 */
$property_features = $_POST["property_features"];
/*
 * Contains the listing's street number.  A listing's exact address (street number, street name, unit number) is typically
 * not display to the renters.  Please be verify if this is to be displayed.
 */
$property_street_number = $_POST["property_street_number"];
/*
 * Contains the listing's street name.  See street number.
 */
$property_street_name = $_POST["property_street_name"];
/*
 * Contains the listing's city.
 */
$property_city = $_POST["property_city"];
/*
 * Contains the listing's state.
 */
$property_state = $_POST["property_state"];
/*
 * Contains the listing's zip code, if avaialble.
 */
$property_zip = $_POST["property_zip"];
/*
 * Contains the listing's neighborhood info if avaiable.
 */
$property_neighborhood = $_POST["property_neighborhood"];
/*
 * Contians the listing's unit number if avaialble.  See street number.
 */
$property_unit = $_POST["property_unit"];
/*
 * Contains the listing's pet policy info.
 */
$property_pet = $_POST["property_pet"];
/*
 * Contains the listing's map snippet image url.  The map snippet indicates the approximate location of this listing.
 */
$property_map_url = $_POST["property_map_url"];
/*
 * Contains the listing's ad title, if avaialble.
 */
$property_ad_title = $_POST["property_ad_title"];
/*
 * Contains the listing's unit ad description, if avaialble.
 */
$property_unit_description = $_POST["property_unit_description"];
/*
 * Contains the listing's building ad description, if available.
 */
$property_building_description = $_POST["property_building_description"];
/*
 * Contains the listing's url for more information.  This url can be used to redirect the renters to get more information about
 * this listing.
 */
$property_url = $_POST["property_url"];
/*
 * Contains the inquiry form to this listing.  This url can be used to redirect the renters to ask a question about the listing.
 */
$property_inquiry_url = $property_url . "?comeFrom=ad";
// Property Ad Info - Start
// Transportation Info - Start

/*
 * Contains the listing's nearby subway stations, separated by "|".  Empty if not avaiable.
 */
$subway_stations = $_POST["subway_stations"];
/*
 * Contains the listing's nearby bus stations, separated by "|".  Empty if not available.
 */
$bus_stations = $_POST["bus_stations"];
// Transportation Info - End
// Ad Preferences - Start

/*
 * Indicates if the user wants to have his/her email displayed in the ad.
 */
$is_display_email = $_POST["is_display_email"] == "Y";
/*
 * Indicates if the user wants to have their account contact information displayed in the ad.  Sometimes the user does not
 * want company information displayed.
 */
$is_display_account_contact = $_POST["is_display_account_contact"] == "Y";
/*
 * Indicates if the user wants to have his/her phone number displayed on the ad.  Sometimes the user does not want to
 * disclose his/her number and force renters to send emails or contact online.
 */
$is_display_phone = $_POST["is_display_phone"] == "Y";
/*
 * Indicates if the user wants to display the map snippet in the ad.
 */
$is_display_map = $_POST["is_display_map"] == "Y";
/*
 * Indicates if the user wants to display the listing id in the ad.
 */
$is_display_property_id = $_POST["is_display_property_id"] == "Y";
/*
 * Indicates if the user wants to display the listing's available date.
 */
$is_display_avaialbe_date = $_POST["is_display_available_date"] == "Y";
/*
 * Indicates if the user wants to disclose the fee information in the ad.
 */
$is_display_fee = $_POST["is_display_fee"] == "Y";
/*
 * Indicates if the user wants to display the transportation information in the ad.
 */
$is_display_transportation = $_POST["is_display_transportation"] == "Y";
/*
 * Contains the user's photo preferences.  "L" indicates the user prefer to use full size photos.
 */
$user_image_preference = $_POST["user_image_preference"];
// Ad Preferences - End

$description = "";

if ( ! empty($property_unit_description))
{
	$description .= "$property_unit_description<br /><br />";
}

if ( ! empty($property_building_description))
{
	$description .= "$property_building_description";
}

/*
 * Some predefined local variables
 */
$_self = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$_parts = pathinfo($_self);
$_siteRoot = $_parts["dirname"] . "/";
$_imgsDir = $_siteRoot . "images/";

/*
 * This template specific settings only.
 */
$_logo = (empty($account_logo_url) ? "{$_imgsDir}logo.jpg" : $account_logo_url); // Aparently this is not needed for this template as this is a branded template.
$_primaryImage = (empty($property_primary_image_url) ? "{$_imgsDir}primary_photo.gif" : $property_primary_image_url);
$_faceBook = "http://www.facebook.com/sharer.php?u=" . urlencode($account_website) . "&t=" . urlencode($property_ad_title);
$_twitter = "http://twitter.com/share?url=" . urlencode($account_website) . "&text=" . urlencode($property_ad_title);


$display = "<table width='950px' border='0' cellspacing='0' cellpadding='5' bgcolor='#f3f3f3'>
      <tr>
        <td>
          <table width='98%' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
              <td>&nbsp&nbsp&nbsp<br></td>
              <td width='60%'>&nbsp</td>
              <td width='40%' align='right' valign='middle'>&nbsp</td>
            </tr> 
            <tr>
              <td>&nbsp&nbsp&nbsp</td>
              <td width='60%'><img src='$account_logo_url' width='358' height='81' /></td>
              <td width='40%' align='right' height='50px'>
                <font face='Arial, Verdana, Trebuchet MS' size='2' color='#00ACEC' valign='top' height='70'>
                <strong style='line-height:30px'>" . strtoupper($property_neighborhood) . "</strong><br>
                {$property_beds} BED | {$property_baths} BATH | \${$property_rent}
                </font>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width='98%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
              <td width='2%'>&nbsp</td>
              <td>
                <table width='100%' border='0' cellspacing='0' cellpadding='0' style='border-top:2px solid #00ACEC'>
                  <tr><td>&nbsp</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width='98%' border='0' cellspacing='0' cellpadding='10'>
            <tr>
              <td>&nbsp&nbsp&nbsp</td>
              <td width='60%' valign='top'><a href='#'><img src='{$property_primary_image_url}' width='100%' height='360' border='0'></a></td>
              <td width='40%' align='right' valign='top'><a href='#'><img src='{$property_map_url}' width='100%' height='360' border='0'></a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table width='98%' border='0' cellspacing='0' cellpadding='0' bgcolor='#ffffff'>
            <tr>
              <td  bgcolor='#f3f3f3'>&nbsp&nbsp&nbsp</td>
              <td><table width='100%' cellspacing='0' cellpadding='0' border='0' align='left'><tr>
              <td>&nbsp&nbsp&nbsp</td>
              <td width='100%' valign='top'><font face='Arial, Verdana, Trebuchet MS' size='2'>{$description} [<a href='{$property_inquiry_url}'>More Details...</a>]</font>
              </td>
            </tr>
          </table>
        </td>
        <td width='40%' valign='top' align='left'>
          <table width='100%' border='0' align='right' valign='top' cellpadding='10' cellspacing='10'>
            ";

$features = explode("|", $property_features);
$numFeatures = sizeof($features);
$cols = 3;
$cc = 0;

for ($i = 0; $i < $numFeatures; $i ++ )
{
	if ($cc == 0)
	{
		$display .= "<tr>";
	}

	$display .= "<td width='33%' valign='top'><font face='Arial, Verdana, Trebuchet MS' size='2'>" . $features[$i] . "</font></td>";
	$cc ++;

	if ($cc >= $cols)
	{
		$display .= "</tr>";
		$cc = 0;
	}
}

if ($cc > 0)
{
	$display .= "</tr>";
}

$display .= "
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>";
if ( ! empty($property_img_urls))
{
	$pimages = explode("|", $property_img_urls);
	$numImgs = sizeof($pimages);
	$cols = 1;
	$cc = 0;

	/*
	  if($numImgs > 2)
	  {
	  $numImgs = 2;
	  }
	 */

	$display .= "
<tr>
  <td>
    <table width='98%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td>
          <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
              <td></td>
              <td bgcolor='#00ACEC' background='{$_imgsDir}bdot.gif'><font face='Arial, Verdana, Trebuchet MS' size='3'><strong><img src='{$_imgsDir}bdot.gif' width='1' height='20' align='absmiddle' />Additional Pictures</strong></font></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>&nbsp<br><br></td>
      </tr>";
	for ($i = 0; $i < $numImgs; $i ++ )
	{
		if ($cc == 0)
		{
			$display .= "<tr>";
		}

		$display .= "<td align='center' colspan='2'>
                                    <a href='{$property_inquiry_url}'><img src='" . $pimages[$i] . "' width='800' border='0' /></a>
                              </td>";
		$cc ++;

		if ($cc >= $cols)
		{
			$display .= "</tr> <tr><td>&nbsp<br><br></td></tr>";
			$cc = 0;
		}
	}

	if ($cc > 0)
	{
		$display .= "</tr>";
	}
	$display .= "
    </table>
  </td>
</tr>";
}
$display .= "
<tr>
  <td>
    <table width='98%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td>&nbsp&nbsp&nbsp</td>
        <td width='60%' valign='top'>
          <table width='100%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
              <td colspan='3' bgcolor='#00ACEC' background='{$_imgsDir}bdot.gif'><font face='Arial, Verdana, Trebuchet MS' size='3'><strong><img src='{$_imgsDir}bdot.gif' width=1' height='20' align='absmiddle' />Essentials</strong></font></td>
            </tr>
            <tr>
              <td colspan='3'><img src='{$_imgsDir}' width='100%' height='1'></td>
            </tr>
            <tr>
              <td width='33%'><font face='Arial, Verdana, Trebuchet MS' size='2'>Bedrooms: {$property_beds}</font></td>
              <td width='34%'><font face='Arial, Verdana, Trebuchet MS' size='2'>Bathrooms: {$property_baths}</font></td>
              <td width='33%'><font face='Arial, Verdana, Trebuchet MS' size='2'>Rent: {$property_rent}</font></td>
            </tr>
            <tr>
              <td>
                <font face='Arial, Verdana, Trebuchet MS' size='2'>Parking: {$property_fee}</font>
              </td>
              <td>
                <font face='Arial, Verdana, Trebuchet MS' size='2'>Pets: {$property_pet}</font>
              </td>
              <td>
                <font face='Arial, Verdana, Trebuchet MS' size='2'>Available: {$property_available_date}</font>
              </td>
            </tr>
            <tr>
              <td colspan='2'><font face='Arial, Verdana, Trebuchet MS' size='2'>Includes: {$property_utilities_included}</font></td>
              <td><font face='Arial, Verdana, Trebuchet MS' size='2' color='#00ACEC'><strong>ID#: {$property_id}</strong></font></td>
            </tr>
          </table>
        </td>
        <td width='40%' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td colspan='2' bgcolor='#00ACEC' background='{$_imgsDir}'><font face='Arial, Verdana, Trebuchet MS' size='3'><strong><img src='{$_imgsDir}bdot.gif' width='1' height='20' align='absmiddle' />Schedule a Showing</strong></font></td>
          </tr>
          <tr>
            <td colspan='2' align='center'><img src='{$_imgsDir}tdot.png' width='100%' height='1'></td>
          </tr>
          <tr>
            <td colspan='2'>
              <img src='{$user_profile_url}' alt='profile image' align='left' />
              <font face='Arial, Verdana, Trebuchet MS' size='2'>
                &nbsp;&nbsp;{$user_name}<br>
                &nbsp;&nbsp;{$user_phone}<br>
                &nbsp;&nbsp;{$user_email}<br>
                <br>
                &nbsp;&nbsp;{$account_website}
              </font>
            </td>
          </tr>
          <tr>
            <td valign='top'><font face='Arial, Verdana, Trebuchet MS' size='1'>{$account_disclaimer}</font></td>
            
          </tr>
        </table>
      </td>
    </tr>
  </table>
</td>
</tr>
</table>
";


/*
 * Lastly output the url.
 */
echo($display);
?>
