<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 4/2/16
 * Time: 00:32
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


?>
<dl class="variation">
    <?php foreach ( $item_data as $data ) : ?>
        <dt class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?>:</dt>
        <dd class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>">
            <?php if(isset($data['image']) && $data['image'] != ''): ?>
                <img src="<?php echo esc_url($data['image']);?>" class="img-openswatch" />
            <?php else: ?>
                <?php echo wp_kses_post( wpautop( $data['display'] ) ); ?>
            <?php endif; ?>

        </dd>
    <?php endforeach; ?>
</dl>