<?php 

class UpperFooter{

    public function generate(){
        return'
        <div style="height:100px; text-align:center; display:none" id="newsletter-container">
            <h1 style="font-size:2rem;">Thank you for Subscribing !</h1>
        </div>
        <div class="upper_footer_section">
        <div class="upper_footer_section_dropshadow"></div>
        <div class="upper_footer_section_inner">
            <div class="upper_footer_section_inner_left">
                <h1> Feed Your <span style="color: tomato">Eyes</span> .</h1>
                <p>Subscribe to our newsletter and never miss the new updates.</p>
            </div>
            <form class="upper_footer_section_inner_right " id="form">
                      <input size="40"  aria-required="true" aria-invalid="false" placeholder="Your email address" value="" type="email" name="email-278" id="n_email">
                      <input style="margin-left:10px" class="wpcf7-form-control has-spinner wpcf7-submit" type="submit" value="Subscribe">
            </form>
        </div>
    </div>';
    }


}

$upper_footer = new UpperFooter();


?>