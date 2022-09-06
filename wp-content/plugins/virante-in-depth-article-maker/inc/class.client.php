<?php
class inDepthArticlesGenerator_Client{
	function __construct() {
	
	}
}

function generate_metadata($content){
    global $post;
//    var_dump($post);
    $post_type=get_post_type();
    $excluded_post_types=array("page");
    
    if ( is_single() && (!in_array($post_type, $excluded_post_types)) ) {
        $author_name=get_author_name();
        $comment_count=  $post->comment_count;
        
        $headline=  get_post_meta($post->ID, "idg_headline",true);
        $alternativeHeadline=  get_post_meta($post->ID, "idg_alternativeHeadline",true);
        $description=  get_post_meta($post->ID, "idg_description",true);
        
        $image="";
        if (has_post_thumbnail( $post->ID ) )
        {
            $image_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
            $image=$image_arr[0];
        }
        
        $metadata = '<span itemprop="name">'.$post->post_title.'</span>
                        by <span itemprop="author">'.$author_name.'</span>
                        <meta itemprop="headline" content="'.$headline.'"/>
                        <meta itemprop="alternativeHeadline" content="'.$alternativeHeadline.'"/>
                        <meta itemprop="image" content="'.$image.'"/>
                        <meta itemprop="description" content="'.$description.'"/>
                        <meta itemprop="datePublished" content="'.$post->post_date.'"/>
                        <meta itemprop="interactionCount" content="UserComments:'.$comment_count.'"/>
                      ';
        $content='<div itemscope itemtype="http://schema.org/Article"><span itemprop="articleBody">'.$content.'</span>';
        $metadata = $content.$metadata.'</div>';
        return $metadata;
    }
    else
        return $content;
}
add_filter('the_content', 'generate_metadata');
?>
