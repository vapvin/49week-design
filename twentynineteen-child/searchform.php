<form class="elementor-search-form" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
    <input type="hidden" id="search-param" name="post_type" value="product">
    <div class="elementor-search-form__container">
        <input placeholder="Procurar um produto..." class="elementor-search-form__input" type="search" name="s" title="<?php echo __('search', 'elementor');?>" value="" required>
        <button class="elementor-search-form__submit" type="submit">
            <i class="fa fa-search" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php echo __('search', 'elementor');?></span>
        </button>
    </div>
</form>