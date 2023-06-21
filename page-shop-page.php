<?php
/* Template Name: Shop Page */


get_header();
// die('dddedddd');
$terms = get_terms(array(
    'taxonomy'   => 'product_cat',
    'parent' => 0,
));

$photo_types = acf_get_fields('group_6353db1b2650a')[1]['choices'];
$edition_types = acf_get_fields('group_63500b39828cd')[0]['choices'];
// $phototypes = get_field('photo_type', 6675);

?>
<script>
    var photographer = localStorage.getItem('myphotographer');
    var p4d = localStorage.getItem('myp4d');
    var image_id = localStorage.getItem('cpm_image_id');
    var image_title = localStorage.getItem('cpm_image_title');
    // var image_ratio = localStorage.getItem('cpm_image_ratio');
</script>
<?php
$p4d = "<script> document.write(p4d); </script>";
$photographer = "<script> document.write(photographer); </script>";
$image_title = "<script> document.write(image_title); </script>";
// $image_ratio = "<script> document.write(image_ratio); </script>";
?>
<div class="main_wrapper">
    <div class="ratio_wrapper">
        <div class="pinned_image">
            <?php echo do_shortcode("[customimageselected]"); ?>
            <h5><b>Photographer : <?php echo $photographer; ?> </b></h5>
            <h5><b id="p4d_title">P4D : <?php echo $p4d; ?> </b></h5>
            <h5><b id="p4d_image_title">Title :<?php echo $image_title; ?></b></h5>
            <!-- <h5><b>Aspect ratio:  <?php //echo $image_ratio;  ?> </b>

            </h5> -->
            <div class="greenflag">
                <img src="https://photos4decocome112a.zapwp.com/q:i/r:1/wp:1/w:30/u:https://staging5.photos4deco.com/wp-content/uploads/green_flag.webp" data-wpc-loaded="true" alt="compatible image" title="compatible image" class="wps-ic-live-cdn theflag ic-fade-in" loading="lazy">
                The selected image can be printed without quality loss
            </div>
            <div class="redflag">
                <img src="https://photos4decocome112a.zapwp.com/q:i/r:1/wp:1/w:30/u:https://staging5.photos4deco.com/wp-content/uploads/red_flag.webp" data-wpc-loaded="true" alt="Not Compatible" title="Not Compatible" class="wps-ic-live-cdn wps-ic-loaded theflag ic-fade-in">
                Do not choose products with a red flag
            </div>
        </div>
        <div class="aspect_ratio">
            <span>Aspect Ratio</span>
            <div>
                <?php foreach ($photo_types as $key => $photo_type) :  ?>
                    <div class="ratio_type">
                        <input type="radio" class="aspect_ratio" id="<?php echo $key; ?>" name="aspect_ratio" value="<?php echo $key; ?>" onclick="select_change_function();">
                        <label for="html"><?php echo $photo_type; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="edition_ratio">
            <span>Edition</span>
            <div>
                <?php $a = 1;
                foreach ($edition_types as $key => $edition_type) :  ?>
                    <div class="edition_type">
                        <input type="radio" class="edition_ratio" id="<?php echo $a . 'cpm_p4d'; ?>" name="edition_ratio" value="<?php echo $key; ?>" onclick="select_change_function();">
                        <label for="html"><?php echo $edition_type; ?></label>
                    </div>
                <?php $a++;
                endforeach; ?>

            </div>
        </div>
    </div>
    <div class="product_category_wrapper">
        <div class="parent_category">
            <select id="parent_category" onchange="select_child_from_parent_cat();">
                <option value="">Select Product</option>
                <?php
                foreach ($terms as $key => $term) :  ?>
                        <option value="<?php echo $terms[$key]->term_id; ?>"><?php echo $terms[$key]->name; ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="child_category" id="child_category">

        </div>
    </div>
    <div class="product_wrapper">
        <div class="products" id="products">

        </div>

    </div>
</div>
<div class="btn__wrapper cpm_ajax_load_more">
    <a href="javascript:void(0);" class="btn btn__primary" id="load-more" onclick='load_more_function();' style="display: none;">Load more</a>
</div>



<?php
get_footer();
