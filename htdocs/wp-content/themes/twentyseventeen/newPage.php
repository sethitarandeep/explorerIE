<?php
    /**
     * Template Name: water quality and quantity
     *
     * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
     *
     * @package WordPress
     * @subpackage Twenty_Seventeen
     * @since 1.0
     * @version 1.0
     */
    
    get_header(); ?>

<div class="wrap">
<div id="primary" class="content-area">

<form method = "POST">
<input type = "TEXT" name="search" />
<input type = "SUBMIT" name="submit" value="Search" />
</form>

<main id="main" class="site-main" role="main">

<?php
    while ( have_posts() ) :
    the_post();
    
    get_template_part( 'template-parts/page/content', 'page' );
    // custom codes:
    //global $wpdb;
    //$water = $wpdb->get_results("SELECT * FROM water_quality_quantity;");
    //echo "<table>";
    //foreach($water as $water){
    //echo "<tr>";
    //echo "<td>".$water->Postcode."</td>";
    //echo "<td>".$water->Suburb."</td>";
    //echo "<td>".$water->Area."</td>";
    //echo "</tr>";
    //}
    //echo "</table>";
    $output = NULL;
    if(isset($_POST['submit'])){
        //$mysqli = NEW MySQLi("localhost","root","heaven520","ezolimo");
        //$search = $mysqli->real_escape_string($_POST['search']);
        $search = $_POST['search'];
        //$resultset = $mysqli->query("SELECT * FROM water_quality_quantity WHERE Postcode like '$search%'");
        global $wpdb;
        $center = $wpdb->get_results("SELECT * FROM carecenter WHERE postCode LIKE '$search%';");
        echo "<table>";
        foreach($center as $center){
            echo "<tr>";
            echo "<td>".$center->postCode."</td>";
           
            echo "</tr>";
        }
        echo "</table>";
        
    }
    
    
    //custom codes finished
    
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
    comments_template();
    endif;
    
    endwhile; // End of the loop.
    ?>

</main><!-- #main -->
</div><!-- #primary -->
</div><!-- .wrap -->

<?php
    get_footer();
