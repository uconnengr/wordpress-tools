<?php

/*
 * By Orlando Echevarria, School of Engineering
 * E-Mail: orlando@engr.uconn.edu
 * Property of the University of Connecticut, Copyright 2014
 * Released under the MIT License, http://www.gnu.org/licenses/gpl-2.0.html
 * 
 */

// if the function does not exist
// lets define the function

if(!function_exists("uconn_reDirectPage")){

// add functionality to the HEAD tag

/* @name uconn_reDirectPage
 * @param void
 * @version 0.5
 * @return void
 * @description allows user to redirect page to a different website or page inside a page/post using Custom Fields
 * and allows users to control their page
 * 
 */

add_action('wp_head','uconn_reDirectPage');

function uconn_reDirectPage() {

    global $post;

    // lets extract the META data information from the custom values
    // and assign them to variables
    
    $redirect = get_post_meta($post->ID, 'redirect', true);
    $url = get_post_meta($post->ID, 'redirecturl', true);

    // if it is a valid URL and response, process the META tag/redirection
    // otherwise, just put in the HEAD tag an error message that is commented
    // accepted responses are: YES, Yes, yes, Y, y, TRUE, True, true
    // URLs are checked, and must be entered in the form of http(s)://www.example.com/[dir1]/[dir2]/[filename.html]
    
    if(isValidUserResponse($redirect) == true && isValidUserURL($url) == true){
	
    print "<meta http-equiv=\"refresh\" content=\"0;url=$url\" />\n\r";
	
    } else {
	
    print "<!-- Bad META tag refresh URL: -->\n\r";
	
    }
    
    // lets get out of here and return back
    
    return ;

}

}

// add functionality to content section
// allows short code [uconn_gotourl link = "http://www.example.com"]

/*
 * @param void
 * @version 0.2
 * @return void
 * @description allows user in a page/post that redirect page/post to a different website or page/post using a shortcode
 
 usage:

 [uconn_gotonewurl url = "http://www.example.com/path1/path2/..../pathN/"]
 [uconn_gotonewurl url = "http://www.example.com/path1/path2/..../pathN/document.html"]
 [uconn_gotonewurl url = "http://www.example.com/path1/path2/..../pathN/document.php"]
 [uconn_gotonewurl url = "http://www.example.com/document.php"]
 [uconn_gotonewurl url = "http://www.example.com/document.html"]
 
 */

add_shortcode( 'uconn_gotonewurl', 'gotonewurl_shortcode' );

function gotonewurl_shortcode( $atts ) {

    $url = "";

    // extract the data and assign it to the array hash
    
    extract( shortcode_atts( array('url' => ''), $atts ) );

    // assign hash value to $url variable
    
    $url = $atts['url'];

    // is the URL a valid URL?
    // if yes, lets output the text and JavaScript to redirect the user
    // otherwise, print out a error message saying the URL is not valid
    // and don't redirect the user to that URL
    
    if(isValidUserURL($url)){
    
?>
<p>You will now be redirected momentarily to <a href="<?php print $url; ?>"><?php print $url; ?></a>. If you are not redirected within the next 10 seconds, please <a href="<?php print $url; ?>">click here</a>.</p>
<script language = "javascript">

gotoURL(); // execute function

// function that triggers web browser
// to go to new web site

function gotoURL(){
    
    location.href = "<?php print $url; ?>";
    
}
    
</script>    
<?php } else { ?>
<p>The page is trying redirect you to a URL(<?php print $url; ?>) that is not a valid URL. Please contact the website administrator.</p>
<?php }

}

/* end of additions by Orlando Echevarria */

/* utility functions */

/*
 * @param $url, represents a URL
 * @name isValidURL
 * @description the functions uses a regular expression to validate a URL that uses HTTP/HTTPS
 * @version 0.3
 * 
 */

// if the function does not exist, lets define it

if(!function_exists('isValidUserURL')){ // start if

/*
 * Purpose: To validate a URL based on a given argument $url
 * @param string $url a valid
 * @return boolean
 * @version 0.3
 * @license GPL, version 2, http://www.gnu.org/licenses/gpl-2.0.html
 * @author Orlando Echevarria, orlando@engr.uconn.edu
 * @copyright Copyright (c) 2014, University of Connecticut
 * 
 *
 */

function isValidUserURL($url = "") {

  // set status variable to null

  $status = null;
  
  // lets test the validity of the URL passed
  // if it is a valid URL set status to true
  // otherwise, lets set it to false
  
  if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url) && filter_var($url, FILTER_VALIDATE_URL)){
  
  $status = true; 
    
  } else {
  
  $status = false;
    
  }
  
  // lets return the status and get out of here
  // status has two outcomes, true or false
  
  return $status;

} // end function

} // end if

// if the function does not exist, lets define it, otherwise, just use the predefined one

if(!function_exists('isValidUserResponse')){ // start if
 
function isValidUserResponse($response = ""){ // set the response to "" if no arguments are given
 
 $status = null;
 
    // convert the responses/values to lower case
    // makes things easier
    // accepted responses are: YES, Yes, yes, Y, y, TRUE, True, true
    // if the response is a valid response
    // set the status to true
    // otherwise set the status to false
 
 if((strtolower($response) == "yes" || strtolower($response) == "true" || strtolower($response) == "y")){
  
 $status = true; 
  
 } else {
  
 $status = false;
  
 }

 // regardless of the value
 // return it and lets get out of there
 
 return $status;
 
} // end function
 
} // end if

?>